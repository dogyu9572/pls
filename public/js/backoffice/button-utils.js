/**
 * 버튼 클릭 이벤트 통합 관리 유틸리티
 * 중복 클릭 방지 및 이벤트 충돌 해결
 */

class ButtonManager {
    constructor() {
        this.clickedButtons = new Set();
        this.debounceTime = 500; // 500ms 디바운스
        this.init();
    }

    init() {
        // 전역 클릭 이벤트 리스너 등록
        document.addEventListener('click', this.handleClick.bind(this), true);
        
        // 전역 폼 제출 이벤트 리스너 등록
        document.addEventListener('submit', this.handleFormSubmit.bind(this), true);
    }

    /**
     * 버튼 클릭 이벤트 통합 처리 (스마트 버전)
     */
    handleClick(event) {
        const button = event.target.closest('button, .btn, [role="button"]');
        if (!button) return;

        // 예외 처리: 특정 버튼들은 ButtonManager가 개입하지 않음
        if (this.shouldSkipButton(button)) {
            return;
        }

        // 예외 처리: 백오피스 폼 버튼들은 기본 로직만 적용
        if (this.isBackofficeFormButton(button)) {
            this.handleBackofficeFormButton(button, event);
            return;
        }

        // 예외 처리: 일반적인 UI 버튼들은 경량 처리
        if (this.isUIActionButton(button)) {
            this.handleUIActionButton(button, event);
            return;
        }

        // 기본 처리: 중요한 액션 버튼들만 강력한 중복 방지
        this.handleCriticalButton(button, event);
    }

    /**
     * ButtonManager를 완전히 건너뛸 버튼들
     */
    shouldSkipButton(button) {
        // 특정 ID의 버튼들
        const skipIds = ['sessionExtendBtn', 'sidebarToggle', 'navbar-toggler'];
        if (skipIds.includes(button.id)) {
            return true;
        }

        // 특정 클래스의 버튼들
        const skipClasses = ['btn-link', 'dropdown-toggle', 'close', 'modal-close'];
        if (skipClasses.some(cls => button.classList.contains(cls))) {
            return true;
        }

        // data-skip-button 속성이 있는 버튼들
        if (button.hasAttribute('data-skip-button')) {
            return true;
        }

        return false;
    }

    /**
     * 백오피스 폼 버튼인지 확인
     */
    isBackofficeFormButton(button) {
        const form = button.closest('form');
        if (!form) return false;

        // 백오피스 폼인지 확인
        const isBackofficeForm = form.closest('.backoffice') || 
                                 form.closest('[class*="backoffice"]') ||
                                 window.location.pathname.includes('/backoffice/');
        
        // 폼 제출 버튼인지 확인
        const isSubmitButton = button.type === 'submit' || 
                              button.classList.contains('btn-primary') ||
                              button.classList.contains('btn-success');

        return isBackofficeForm && isSubmitButton;
    }

    /**
     * 백오피스 폼 버튼 처리 (경량 버전)
     */
    handleBackofficeFormButton(button, event) {
        // 중복 제출 방지만 적용 (UI 변경 최소화)
        if (this.clickedButtons.has(button)) {
            event.preventDefault();
            event.stopPropagation();
            return;
        }

        // 짧은 디바운스만 적용 (100ms)
        if (button.dataset.lastClick) {
            const lastClick = parseInt(button.dataset.lastClick);
            const now = Date.now();
            if (now - lastClick < 100) {
                event.preventDefault();
                event.stopPropagation();
                return;
            }
        }

        // 클릭 기록
        button.dataset.lastClick = Date.now().toString();
        this.clickedButtons.add(button);

        // 100ms 후 해제
        setTimeout(() => {
            this.clickedButtons.delete(button);
        }, 100);
    }

    /**
     * UI 액션 버튼인지 확인 (드롭다운, 토글 등)
     */
    isUIActionButton(button) {
        const uiClasses = ['dropdown-toggle', 'nav-link', 'btn-outline', 'btn-secondary'];
        return uiClasses.some(cls => button.classList.contains(cls));
    }

    /**
     * UI 액션 버튼 처리 (최소 개입)
     */
    handleUIActionButton(button, event) {
        // 매우 짧은 디바운스만 적용 (50ms)
        if (button.dataset.lastClick) {
            const lastClick = parseInt(button.dataset.lastClick);
            const now = Date.now();
            if (now - lastClick < 50) {
                event.preventDefault();
                event.stopPropagation();
                return;
            }
        }

        button.dataset.lastClick = Date.now().toString();
    }

    /**
     * 중요한 액션 버튼 처리 (강력한 중복 방지)
     */
    handleCriticalButton(button, event) {
        // 이미 처리 중인 버튼인지 확인
        if (this.clickedButtons.has(button)) {
            event.preventDefault();
            event.stopPropagation();
            return;
        }

        // 디바운스 처리
        if (button.dataset.lastClick) {
            const lastClick = parseInt(button.dataset.lastClick);
            const now = Date.now();
            if (now - lastClick < this.debounceTime) {
                event.preventDefault();
                event.stopPropagation();
                return;
            }
        }

        // 버튼 상태 확인
        if (button.disabled || button.classList.contains('disabled') || button.classList.contains('submitting')) {
            event.preventDefault();
            event.stopPropagation();
            return;
        }

        // 클릭 시간 기록
        button.dataset.lastClick = Date.now().toString();

        // 처리 중 상태로 설정
        this.clickedButtons.add(button);
        this.setButtonProcessing(button, true);

        // 원래 이벤트 핸들러 실행
        setTimeout(() => {
            this.clickedButtons.delete(button);
            this.setButtonProcessing(button, false);
        }, this.debounceTime);
    }

    /**
     * 폼 제출 이벤트 처리
     */
    handleFormSubmit(event) {
        const form = event.target;
        const submitButtons = form.querySelectorAll('button[type="submit"], input[type="submit"]');
        
        submitButtons.forEach(button => {
            this.setButtonProcessing(button, true);
            
            // 폼 제출 성공/실패 후 버튼 상태 복원
            setTimeout(() => {
                this.setButtonProcessing(button, false);
            }, 3000); // 3초 후 자동 복원
        });
    }

    /**
     * 버튼 처리 상태 설정
     */
    setButtonProcessing(button, isProcessing) {
        if (isProcessing) {
            button.disabled = true;
            button.classList.add('submitting');
            
            // 원본 텍스트 저장
            if (!button.dataset.originalText) {
                button.dataset.originalText = button.textContent || button.innerHTML;
            }
            
            // 로딩 텍스트 설정
            if (button.tagName === 'BUTTON') {
                button.innerHTML = '<span class="spinner"></span> 처리 중...';
            }
        } else {
            button.disabled = false;
            button.classList.remove('submitting');
            
            // 원본 텍스트 복원
            if (button.dataset.originalText) {
                if (button.tagName === 'BUTTON') {
                    button.innerHTML = button.dataset.originalText;
                }
                delete button.dataset.originalText;
            }
        }
    }

    /**
     * 특정 버튼의 처리 상태 강제 복원
     */
    restoreButton(button) {
        this.clickedButtons.delete(button);
        this.setButtonProcessing(button, false);
    }

    /**
     * 모든 버튼의 처리 상태 복원
     */
    restoreAllButtons() {
        this.clickedButtons.clear();
        document.querySelectorAll('button.submitting, .btn.submitting').forEach(button => {
            this.setButtonProcessing(button, false);
        });
    }
}

// 전역 인스턴스 생성
window.ButtonManager = new ButtonManager();

// 페이지 언로드 시 모든 버튼 상태 복원
window.addEventListener('beforeunload', () => {
    if (window.ButtonManager) {
        window.ButtonManager.restoreAllButtons();
    }
});

// DOM 로드 완료 후 초기화
document.addEventListener('DOMContentLoaded', function() {
    // 기존 이벤트 리스너와의 충돌 방지를 위한 지연 실행
    setTimeout(() => {
        if (!window.ButtonManager) {
            window.ButtonManager = new ButtonManager();
        }
    }, 100);
});

// CSS 스피너 스타일 추가
const style = document.createElement('style');
style.textContent = `
    .spinner {
        display: inline-block;
        width: 12px;
        height: 12px;
        border: 2px solid transparent;
        border-top: 2px solid currentColor;
        border-radius: 50%;
        animation: spin 1s linear infinite;
        margin-right: 0.5rem;
    }
    
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    
    .btn.submitting {
        opacity: 0.8;
        cursor: not-allowed;
    }
`;
document.head.appendChild(style);
