#!/bin/bash

# Laravel Sail 프로젝트 설정 스크립트 (1단계: 프로젝트 설정)
PROJECT_NAME="backoffice"

echo "🚀 Laravel Sail 프로젝트 설정 중: $PROJECT_NAME"

# 1. 현재 디렉토리 확인
if [ ! -f "composer.json" ] || [ ! -f "docker-compose.yml" ]; then
    echo "❌ 현재 디렉토리가 Laravel 프로젝트가 아닙니다."
    echo "프로젝트 루트 디렉토리에서 실행해주세요."
    exit 1
fi

# 2. .env 파일 설정
echo "⚙️ 환경 설정 중..."
if [ ! -f ".env" ]; then
    echo "📄 .env 파일 생성 중..."
    cp .env.example .env
fi

# .env 파일 업데이트
sed -i "s/APP_NAME=Laravel/APP_NAME=$PROJECT_NAME/" .env
sed -i "s/DB_DATABASE=laravel/DB_DATABASE=$PROJECT_NAME/" .env

# 3. Docker 볼륨 이름 고유화 (데이터베이스 분리) - GitHub clone 후에는 건너뛰기
if [ ! -d ".git" ] || [ -z "$(git remote -v 2>/dev/null)" ]; then
    echo "🔧 Docker 볼륨 고유화 중..."
    sed -i "s/sail-mysql/${PROJECT_NAME}-mysql/g" docker-compose.yml
    sed -i "s/sail-redis/${PROJECT_NAME}-redis/g" docker-compose.yml
else
    echo "✅ GitHub 프로젝트 감지: Docker 볼륨 설정 건너뛰기"
fi

# 5. 권한 설정
echo "🔐 권한 설정 중..."
sudo chown -R $USER:$USER storage
sudo chown -R $USER:$USER bootstrap/cache
sudo chown -R $USER:$USER database/migrations
sudo chmod -R 775 storage
sudo chmod -R 775 bootstrap/cache
sudo chmod -R 775 database/migrations


# 4. Git 초기화 (기존 .git이 없을 때만)
if [ ! -d ".git" ]; then
    echo "📝 Git 초기화 중..."
    git init
    git add .
    git commit -m "Initial commit: $PROJECT_NAME"
else
    echo "✅ Git 저장소가 이미 초기화되어 있습니다."
fi

# 5. Composer 의존성 설치
echo "📦 Composer 의존성 설치 중..."
composer install --no-interaction --prefer-dist --optimize-autoloader

# 6. Docker 컨테이너 시작
echo "🐳 Docker 컨테이너 시작 중..."
./vendor/bin/sail up -d

echo ""
echo "✅ 1단계 완료: 프로젝트 설정 및 Docker 시작"
echo "📁 프로젝트 위치: $(pwd)"
echo "🌐 접속 URL: http://localhost"
echo ""
echo "📋 다음 단계:"
echo "   ./setup-database.sh"
echo ""
echo "⚠️  MySQL이 완전히 준비될 때까지 1-2분 대기 후 데이터베이스 설정을 실행하세요."
