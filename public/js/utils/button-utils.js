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
     * 버튼 클릭 이벤트 통합 처리
     */
    handleClick(event) {
        const button = event.target.closest('button, .btn, [role="button"]');
        if (!button) return;

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
