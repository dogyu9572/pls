/**
 * 백오피스 세션 타이머 관리 스크립트
 */
document.addEventListener('DOMContentLoaded', function() {
    // 세션 설정 (기본값 설정)
    const sessionConfig = window.sessionConfig || { lifetime: 120 };
    const logoutUrl = window.logoutUrl || '/backoffice/logout';
    
    // 세션 관련 변수들
    const sessionTimeout = sessionConfig.lifetime * 60; // 세션 타임아웃(초 단위)
    const warningThreshold = 5 * 60; // 경고 표시 시점(초 단위, 5분 전)
    let sessionTimeLeft = document.getElementById('sessionTimeLeft');

    if (!sessionTimeLeft) {
        return; // 세션 타이머 요소가 없으면 중단
    }

    // 로그인 시점을 localStorage에서 가져오거나 새로 설정
    let loginTime = localStorage.getItem('backoffice_login_time');
    
    // 세션 만료 시간 계산을 위한 임계값 (2시간 = 120분)
    const maxSessionAge = sessionTimeout; // 120분 * 60초
    
    if (!loginTime) {
        // 처음 로그인한 경우 현재 시간으로 설정
        loginTime = new Date().getTime();
        localStorage.setItem('backoffice_login_time', loginTime);
    } else {
        // 기존 로그인 시간이 있으면 숫자로 변환
        loginTime = parseInt(loginTime);
        
        // 이전 세션이 너무 오래된 경우 (2시간 이상) 새로 시작
        const now = new Date().getTime();
        const sessionAge = (now - loginTime) / 1000; // 초 단위
        
        if (sessionAge > maxSessionAge) {
            // 세션이 너무 오래된 경우 localStorage 초기화 후 새로 시작
            localStorage.removeItem('backoffice_login_time');
            loginTime = new Date().getTime();
            localStorage.setItem('backoffice_login_time', loginTime);
        }
    }

    // 사용자 활동 감지 이벤트 목록 (mousemove 제거하여 마우스 움직임마다 갱신 방지)
    const resetEvents = ['mousedown', 'keypress', 'scroll', 'touchstart'];
    
    // 세션 연장 버튼 이벤트
    const sessionExtendBtn = document.getElementById('sessionExtendBtn');
    if (sessionExtendBtn) {
        sessionExtendBtn.addEventListener('click', function() {
            // 서버에 세션 연장 요청
            fetch('/backoffice/session/extend', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // 서버에서 반환된 새로운 로그인 시간 사용
                    const newLoginTime = data.new_login_time ? data.new_login_time * 1000 : new Date().getTime();
                    localStorage.setItem('backoffice_login_time', newLoginTime);
                    
                    // 로그인 시간 업데이트
                    loginTime = newLoginTime;
                    
                    // 경고 상태 해제
                    sessionTimeLeft.parentElement.classList.remove('text-danger');
                    
                    // 성공 메시지 표시
                    showExtendMessage('세션이 성공적으로 연장되었습니다.');
                    
                } else {
                    showExtendMessage(data.message || '세션 연장에 실패했습니다.', 'error');
                }
            })
            .catch(error => {
                console.error('세션 연장 오류:', error);
                showExtendMessage('세션 연장 중 오류가 발생했습니다.', 'error');
            });
        });
    }
    
    // 연장 성공/실패 메시지 표시
    function showExtendMessage(message, type = 'success') {
        // 기존 메시지 제거
        const existingMessage = document.querySelector('.session-extend-message');
        if (existingMessage) {
            existingMessage.remove();
        }
        
        // 새 메시지 생성
        const messageDiv = document.createElement('div');
        messageDiv.className = 'session-extend-message';
        messageDiv.textContent = message;
        
        // 타입에 따른 스타일 설정
        const backgroundColor = type === 'error' ? '#dc3545' : '#28a745';
        
        messageDiv.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: ${backgroundColor};
            color: white;
            padding: 8px 16px;
            border-radius: 4px;
            font-size: 0.85rem;
            z-index: 9999;
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
        `;
        
        document.body.appendChild(messageDiv);
        
        // 3초 후 자동 제거
        setTimeout(() => {
            messageDiv.remove();
        }, 3000);
    }

    // 세션 타이머 업데이트 함수
    function updateSessionTimer() {
        const now = new Date().getTime();
        const elapsedTime = Math.floor((now - loginTime) / 1000);
        const remainingTime = sessionTimeout - elapsedTime;

        if (remainingTime <= 0) {
            // 세션 만료 시 자동 로그아웃
            sessionTimeLeft.textContent = '(만료됨)';
            sessionTimeLeft.parentElement.classList.add('text-danger');
            
            // 세션 만료 시 localStorage 정리
            localStorage.removeItem('backoffice_login_time');
            
            // 3초 후 자동 로그아웃
            setTimeout(() => {
                window.location.href = logoutUrl;
            }, 3000);
            
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
