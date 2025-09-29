# 🚀 Laravel Sail 프로젝트 설정 가이드

## 📋 현재 상태
- ✅ Laravel Sail 완전 설정
- ✅ MySQL, Redis 포함
- ✅ 권한 문제 해결
- ✅ 데이터베이스 연결 설정
- ✅ **안정성 개선**: MySQL 대기 로직, 마이그레이션 재시도

## 🚀 프로젝트 설정 (2단계)

### 1단계: 프로젝트 설정 및 Docker 시작
```bash
# 프로젝트 루트 디렉토리에서
./setup-project.sh
```

### 2단계: 데이터베이스 설정
```bash
# MySQL이 준비될 때까지 1-2분 대기 후 실행
./setup-database.sh
```

**자동 처리되는 개선사항:**
- 시더 실행으로 기본 데이터 생성
- 관리자 계정 자동 생성

## 📁 수동 설정 방법
```bash
# 1. .env 파일 설정
cp .env.example .env
sed -i "s/APP_NAME=Laravel/APP_NAME=backoffice/" .env
sed -i "s/DB_DATABASE=laravel/DB_DATABASE=backoffice/" .env

# 2. 권한 설정
sudo chown -R $USER:$USER storage bootstrap/cache database/migrations
sudo chmod -R 775 storage bootstrap/cache database/migrations

# 3. 실행
./vendor/bin/sail up -d
./vendor/bin/sail artisan key:generate
./vendor/bin/sail artisan migrate
```

## ✅ 자동 처리되는 항목들

### 1단계: 프로젝트 설정
- 환경 설정 (.env)
- Docker 볼륨 고유화
- 권한 설정
- Git 초기화 (필요시)
- Docker 컨테이너 시작

### 2단계: 데이터베이스 설정
- **MySQL 준비 상태 확인** (최대 60초)
- 애플리케이션 키 생성
- 세션 테이블 마이그레이션 자동 생성
- 데이터베이스 마이그레이션
- 캐시 정리

### 안정성 기능
- **MySQL 연결 상태 실시간 확인**
- **세션 테이블 자동 생성**
- **마이그레이션 실패 시 자동 재시도**

## 🔧 주요 명령어
```bash
# 컨테이너 관리
./vendor/bin/sail up -d          # 시작
./vendor/bin/sail down           # 중지
./vendor/bin/sail restart        # 재시작

# Laravel 명령어
./vendor/bin/sail artisan migrate
./vendor/bin/sail artisan make:controller HomeController
./vendor/bin/sail artisan make:model User

# 쉘 접속
./vendor/bin/sail shell
```

## 🛠️ 설정 스크립트
```bash
# 프로젝트 설정
./setup-project.sh

# 데이터베이스 설정
./setup-database.sh
```

## 🌐 접속 정보
- **애플리케이션**: http://localhost
- **데이터베이스**: localhost:3306
- **Redis**: localhost:6379
- **Mailpit**: http://localhost:8025
- **Meilisearch**: http://localhost:7700

## 🚨 문제 해결

### MySQL 연결 오류
```bash
# 컨테이너 상태 확인
./vendor/bin/sail ps

# MySQL 수동 연결 테스트
./vendor/bin/sail exec mysql mysqladmin ping -h localhost -u sail -ppassword

# 설정 스크립트 사용
./setup-database.sh
```

### 마이그레이션 오류
```bash
# 수동 마이그레이션
./vendor/bin/sail artisan migrate --force

# 설정 스크립트 사용
./setup-database.sh
``` 


# 🚀 Laravel 12 서버 환경 요구사항

## 📋 현재 프로젝트 정보
- **Laravel 12** (PHP 8.4 필요)
- **MySQL 8.0** (데이터베이스)
- **Nginx** (웹서버)
- **설치 방식**: 네이티브 설치 (Docker 사용 안함)

## 🔧 서버에 설치할 소프트웨어

### 1. PHP 8.4 + 필수 확장
- php8.4
- php8.4-fpm
- php8.4-mysql
- php8.4-xml
- php8.4-mbstring
- php8.4-curl
- php8.4-zip
- php8.4-bcmath
- php8.4-intl
- php8.4-gd

### 2. 웹서버
- Nginx

### 3. 데이터베이스
- MySQL 8.0

### 4. 패키지 관리자
- Composer

### 로컬 (WSL + Docker)

### 서버 (네이티브 설치)
