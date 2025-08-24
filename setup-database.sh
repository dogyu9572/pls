#!/bin/bash

# Laravel Sail 데이터베이스 설정 스크립트 (2단계: DB 설정)
echo "🗄️ 데이터베이스 설정 시작..."

# 1. MySQL 준비 상태 확인
echo "⏳ MySQL 준비 상태 확인 중..."
MAX_ATTEMPTS=30
ATTEMPT=0

while [ $ATTEMPT -lt $MAX_ATTEMPTS ]; do
    if ./vendor/bin/sail exec mysql mysqladmin ping -h localhost -u sail -ppassword > /dev/null 2>&1; then
        echo "✅ MySQL 연결 성공!"
        break
    else
        ATTEMPT=$((ATTEMPT + 1))
        echo "⏳ MySQL 준비 중... ($ATTEMPT/$MAX_ATTEMPTS)"
        sleep 2
    fi
done

if [ $ATTEMPT -eq $MAX_ATTEMPTS ]; then
    echo "❌ MySQL 연결 실패. 컨테이너를 확인해주세요."
    exit 1
fi

# 2. 애플리케이션 키 생성
echo "🔑 애플리케이션 키 생성 중..."
./vendor/bin/sail artisan key:generate

# 3. 기본 마이그레이션 실행
echo "🗄️ 기본 마이그레이션 실행 중..."
./vendor/bin/sail artisan migrate --force

# 4. 세션 테이블 확인 및 생성
echo "📋 세션 테이블 확인 중..."
if ! ./vendor/bin/sail artisan tinker --execute="Schema::hasTable('sessions')" 2>/dev/null | grep -q "true"; then
    echo "📋 세션 테이블 생성 중..."

    # 세션 테이블을 직접 생성
    ./vendor/bin/sail artisan tinker --execute="
        if (!Schema::hasTable('sessions')) {
            Schema::create('sessions', function (\$table) {
                \$table->string('id')->primary();
                \$table->foreignId('user_id')->nullable()->index();
                \$table->string('ip_address', 45)->nullable();
                \$table->text('user_agent')->nullable();
                \$table->text('payload');
                \$table->integer('last_activity')->index();
            });
            echo 'Sessions table created successfully';
        } else {
            echo 'Sessions table already exists';
        }
    "
else
    echo "✅ 세션 테이블이 이미 존재합니다."
fi

# 5. 캐시 정리 (안전하게)
echo "🧹 캐시 정리 중..."
./vendor/bin/sail artisan config:clear
./vendor/bin/sail artisan view:clear

# 캐시 테이블이 있을 때만 캐시 클리어 실행
if ./vendor/bin/sail artisan tinker --execute="Schema::hasTable('cache')" 2>/dev/null | grep -q "true"; then
    ./vendor/bin/sail artisan cache:clear
else
    echo "⚠️ 캐시 테이블이 없어서 캐시 클리어를 건너뜁니다."
fi

echo ""
echo "✅ 2단계 완료: 데이터베이스 설정 완료!"
echo "🌐 접속 URL: http://localhost"
echo "🔧 관리 명령어: ./vendor/bin/sail artisan"
echo "🗄️ 데이터베이스: $(grep DB_DATABASE .env | cut -d'=' -f2) (독립적)"
echo ""
echo "📋 다음 단계:"
echo "   ./vendor/bin/sail artisan make:controller HomeController"
echo "   ./vendor/bin/sail artisan make:model User"
echo "   ./vendor/bin/sail artisan make:auth (인증 시스템)"
