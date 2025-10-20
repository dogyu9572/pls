#!/bin/bash

# 사용법: sudo ./setup-ftp-account.sh [FTP계정명] [프로젝트경로]
# 예시: sudo ./setup-ftp-account.sh ftpuser /var/www/project

# 파라미터 확인
if [ $# -lt 2 ]; then
    echo "사용법: sudo $0 [FTP계정명] [프로젝트경로]"
    echo "예시: sudo $0 ftpuser /var/www/project"
    exit 1
fi

FTP_USER=$1
PROJECT_PATH=$2

echo "=== FTP 계정 설정 시작 ==="
echo "계정명: $FTP_USER"
echo "프로젝트 경로: $PROJECT_PATH"
echo ""

# 1. 사용자 계정 존재 여부 확인
if id "$FTP_USER" &>/dev/null; then
    echo "⚠️  사용자 '$FTP_USER'가 이미 존재합니다."
    read -p "기존 계정을 사용하시겠습니까? (y/n): " -n 1 -r
    echo
    if [[ ! $REPLY =~ ^[Yy]$ ]]; then
        echo "❌ 스크립트를 종료합니다."
        exit 1
    fi
else
    # 2. FTP 사용자 계정 생성
    echo "📝 FTP 계정 생성 중..."
    useradd -M -s /bin/bash $FTP_USER
    
    # 3. 비밀번호 설정
    echo "🔐 비밀번호를 설정하세요:"
    passwd $FTP_USER
    
    if [ $? -eq 0 ]; then
        echo "✅ FTP 계정 생성 완료"
    else
        echo "❌ 계정 생성 실패"
        exit 1
    fi
fi

# 4. 프로젝트 디렉토리 존재 확인
if [ ! -d "$PROJECT_PATH" ]; then
    echo "❌ 프로젝트 경로가 존재하지 않습니다: $PROJECT_PATH"
    exit 1
fi

cd $PROJECT_PATH
echo ""
echo "=== 권한 설정 시작 ==="

# 5. FTP 수정 가능 디렉토리 권한 설정
echo "📁 public 디렉토리 권한 설정..."
chown -R $FTP_USER:www-data public
chmod -R 775 public

echo "📁 resources 디렉토리 권한 설정..."
chown -R $FTP_USER:www-data resources
chmod -R 775 resources

echo "📁 app/Http/Controllers 디렉토리 권한 설정..."
chown -R $FTP_USER:www-data app/Http/Controllers
chmod -R 775 app/Http/Controllers

echo "📁 routes 디렉토리 권한 설정..."
chown -R $FTP_USER:www-data routes
chmod -R 775 routes

# 6. 라라벨 캐시 디렉토리 (www-data 소유)
echo "📁 storage 디렉토리 권한 설정..."
chown -R www-data:www-data storage
chmod -R 775 storage

echo "📁 bootstrap/cache 디렉토리 권한 설정..."
chown -R www-data:www-data bootstrap/cache
chmod -R 775 bootstrap/cache

echo ""
echo "=== 설정 완료 ==="
echo ""
echo "📊 권한 확인:"
ls -la | grep -E "public|resources|app|routes|storage|bootstrap"

echo ""
echo "✅ FTP 계정 설정 완료!"
echo ""
echo "📌 접속 정보:"
echo "   - FTP 계정: $FTP_USER"
echo "   - 프로젝트 경로: $PROJECT_PATH"
echo "   - 수정 가능 디렉토리: public, resources, app/Http/Controllers, routes"
echo ""
echo "🔧 vsftpd 설정이 필요한 경우:"
echo "   sudo nano /etc/vsftpd.conf"
echo "   sudo systemctl restart vsftpd"