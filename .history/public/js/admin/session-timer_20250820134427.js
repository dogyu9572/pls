/**
 * 백오피스 세션 타이머 관리 스크립트
 */
document.addEventListener('DOMContentLoaded', function() {
    // 세션 관련 변수들
    const sessionTimeout = sessionConfig.lifetime * 60; // 세션 타임아웃(초 단위)
    const warningThreshold = 5 * 60; // 경고 표시 시점(초 단위, 5분 전)
    let timeoutStart = new Date().getTime();
    let sessionTimeLeft = document.getElementById('sessionTimeLeft');

    if (!sessionTimeLeft) {
        return; // 세션 타이머 요소가 없으면 중단
    }

    // 사용자 활동 감지 이벤트 목록 (mousemove 제거하여 마우스 움직임마다 갱신 방지)
    const resetEvents = ['mousedown', 'keypress', 'scroll', 'touchstart'];

    // 세션 타이머 업데이트 함수
    function updateSessionTimer() {
        const now = new Date().getTime();
        const elapsedTime = Math.floor((now - timeoutStart) / 1000);
        const remainingTime = sessionTimeout - elapsedTime;

        if (remainingTime <= 0) {
            // 세션 만료 시 로그아웃 페이지로 이동
            window.location.href = logoutUrl;
            return;
        }

        // 남은 시간 표시 (분:초 형식만)
        const minutes = Math.floor(remainingTime / 60);
        const seconds = remainingTime % 60;

        // 분:초 형식으로 표시 (예: 15:30)
        sessionTimeLeft.textContent = `${minutes}:${seconds.toString().padStart(2, '0')}`;

        // 경고 표시(5분 이하 남았을 때)
        if (remainingTime <= warningThreshold) {
            sessionTimeLeft.parentElement.classList.add('text-danger');
        } else {
            sessionTimeLeft.parentElement.classList.remove('text-danger');
        }
    }

    // 사용자 활동 감지 시 타이머 리셋 (로그인 시점부터 고정된 시간으로 흘러감)
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
