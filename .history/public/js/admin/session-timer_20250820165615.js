/**
 * 백오피스 세션 타이머 관리 스크립트
 */
document.addEventListener('DOMContentLoaded', function() {
    // 세션 관련 변수들
    const sessionTimeout = sessionConfig.lifetime * 60; // 세션 타임아웃(초 단위)
    const warningThreshold = 5 * 60; // 경고 표시 시점(초 단위, 5분 전)
    let sessionTimeLeft = document.getElementById('sessionTimeLeft');

    if (!sessionTimeLeft) {
        return; // 세션 타이머 요소가 없으면 중단
    }

    // 로그인 시점을 localStorage에서 가져오거나 새로 설정
    let loginTime = null;
    
    // 서버 세션에서 로그인 시간 확인 (우선)
    if (typeof sessionStorage !== 'undefined' && sessionStorage.getItem('login_time')) {
        loginTime = parseInt(sessionStorage.getItem('login_time'));
    }
    
    // 세션에 없으면 localStorage에서 가져오기
    if (!loginTime) {
        loginTime = localStorage.getItem('backoffice_login_time');
        if (loginTime) {
            loginTime = parseInt(loginTime);
        }
    }
    
    // 둘 다 없으면 현재 시간으로 설정
    if (!loginTime) {
        loginTime = new Date().getTime();
        localStorage.setItem('backoffice_login_time', loginTime);
    }
    
    // 세션에도 저장
    if (typeof sessionStorage !== 'undefined') {
        sessionStorage.setItem('login_time', loginTime);
    }

    // 사용자 활동 감지 이벤트 목록 (mousemove 제거하여 마우스 움직임마다 갱신 방지)
    const resetEvents = ['mousedown', 'keypress', 'scroll', 'touchstart'];

    // 세션 타이머 업데이트 함수
    function updateSessionTimer() {
        const now = new Date().getTime();
        const elapsedTime = Math.floor((now - loginTime) / 1000);
        const remainingTime = sessionTimeout - elapsedTime;

        if (remainingTime <= 0) {
            // 세션 만료 시 localStorage 정리하고 로그아웃 페이지로 이동
            localStorage.removeItem('backoffice_login_time');
            window.location.href = logoutUrl;
            return;
        }

        // 남은 시간 표시 (분:초 형식만)
        const minutes = Math.floor(remainingTime / 60);
        const seconds = remainingTime % 60;

        // 분 초 형식으로 표시 (예: 180분 30초)
        sessionTimeLeft.textContent = `(${minutes}분 ${seconds}초)`;

        // 경고 표시(5분 이하 남았을 때)
        if (remainingTime <= warningThreshold) {
            sessionTimeLeft.parentElement.classList.add('text-danger');
        } else {
            sessionTimeLeft.parentElement.classList.remove('text-danger');
        }
    }

    // 사용자 활동 감지 시 경고 상태만 해제 (타이머는 리셋하지 않음)
    resetEvents.forEach(function(event) {
        window.addEventListener(event, function() {
            // 타이머 리셋하지 않고 경고 상태만 해제
            sessionTimeLeft.parentElement.classList.remove('text-danger');
        });
    });

    // 초기 타이머 업데이트
    updateSessionTimer();

    // 1초마다 타이머 업데이트
    setInterval(updateSessionTimer, 1000);
});
