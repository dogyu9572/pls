// 메인 레이아웃 JavaScript

// DOM 로드 완료 후 실행
document.addEventListener('DOMContentLoaded', function() {
    // 네비게이션 토글 기능
    initNavigation();
    
    // 드롭다운 메뉴 기능
    initDropdowns();
    
    // 스크롤 이벤트 처리
    initScrollEffects();
    
    // 로그아웃 폼 처리
    initLogoutForm();
    
    // 프로필 드롭다운 개선
    initProfileDropdown();
});

// 네비게이션 초기화
function initNavigation() {
    const navbarToggler = document.querySelector('.navbar-toggler');
    const navbarCollapse = document.querySelector('.navbar-collapse');
    
    if (navbarToggler && navbarCollapse) {
        navbarToggler.addEventListener('click', function() {
            navbarCollapse.classList.toggle('show');
        });
        
        // 모바일에서 메뉴 클릭 시 자동으로 닫기
        const navLinks = navbarCollapse.querySelectorAll('.nav-link');
        navLinks.forEach(link => {
            link.addEventListener('click', function() {
                if (window.innerWidth < 992) {
                    navbarCollapse.classList.remove('show');
                }
            });
        });
    }
}

// 드롭다운 메뉴 초기화
function initDropdowns() {
    const dropdownToggles = document.querySelectorAll('[data-bs-toggle="dropdown"]');
    
    dropdownToggles.forEach(toggle => {
        toggle.addEventListener('click', function(e) {
            e.preventDefault();
            
            const dropdownMenu = this.nextElementSibling;
            if (dropdownMenu && dropdownMenu.classList.contains('dropdown-menu')) {
                dropdownMenu.classList.toggle('show');
            }
        });
    });
    
    // 드롭다운 외부 클릭 시 닫기
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.dropdown')) {
            const openDropdowns = document.querySelectorAll('.dropdown-menu.show');
            openDropdowns.forEach(dropdown => {
                dropdown.classList.remove('show');
            });
        }
    });
}

// 스크롤 이벤트 처리
function initScrollEffects() {
    let lastScrollTop = 0;
    const header = document.querySelector('.main-header');
    
    if (header) {
        window.addEventListener('scroll', function() {
            const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
            
            if (scrollTop > lastScrollTop && scrollTop > 100) {
                // 아래로 스크롤
                header.style.transform = 'translateY(-100%)';
            } else {
                // 위로 스크롤
                header.style.transform = 'translateY(0)';
            }
            
            lastScrollTop = scrollTop;
        });
    }
}

// 로그아웃 폼 처리
function initLogoutForm() {
    const logoutForm = document.getElementById('logout-form');
    const logoutLink = document.querySelector('a[href*="logout"]');
    
    if (logoutForm && logoutLink) {
        logoutLink.addEventListener('click', function(e) {
            e.preventDefault();
            
            if (confirm('정말 로그아웃 하시겠습니까?')) {
                logoutForm.submit();
            }
        });
    }
}

// 프로필 드롭다운 초기화 (hover 영역 개선)
function initProfileDropdown() {
    const userDropdown = document.querySelector('.user-dropdown');
    const dropdownContent = document.querySelector('.dropdown-content');
    
    if (userDropdown && dropdownContent) {
        let hideTimeout;
        let isVisible = false;
        
        // 마우스가 드롭다운 영역에 들어올 때
        userDropdown.addEventListener('mouseenter', function() {
            clearTimeout(hideTimeout);
            dropdownContent.style.display = 'block';
            isVisible = true;
        });
        
        // 마우스가 드롭다운 영역을 벗어날 때
        userDropdown.addEventListener('mouseleave', function() {
            hideTimeout = setTimeout(function() {
                dropdownContent.style.display = 'none';
                isVisible = false;
            }, 300); // 300ms 지연
        });
        
        // 드롭다운 내용 영역에 마우스가 들어올 때
        dropdownContent.addEventListener('mouseenter', function() {
            clearTimeout(hideTimeout);
            isVisible = true;
        });
        
        // 드롭다운 내용 영역을 벗어날 때
        dropdownContent.addEventListener('mouseleave', function() {
            hideTimeout = setTimeout(function() {
                dropdownContent.style.display = 'none';
                isVisible = false;
            }, 300); // 300ms 지연
        });
    }
}

// 유틸리티 함수들
window.AppUtils = {
    // CSRF 토큰 가져오기
    getCsrfToken: function() {
        return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    },
    
    // AJAX 요청 헤더 설정
    getAjaxHeaders: function() {
        return {
            'X-CSRF-TOKEN': this.getCsrfToken(),
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        };
    },
    
    // 통합 모달 관리 시스템
    modal: {
        // 모달 요소들
        elements: {
            modal: null,
            title: null,
            message: null,
            header: null
        },
        
        // 모달 초기화
        init: function() {
            this.elements.modal = document.getElementById('alertModal');
            if (!this.elements.modal) return;
            
            this.elements.title = document.getElementById('modalTitle');
            this.elements.message = document.getElementById('modalMessage');
            this.elements.header = document.getElementById('modalHeader');
            
            this.bindEvents();
        },
        
        // 이벤트 바인딩
        bindEvents: function() {
            const closeBtn = this.elements.modal?.querySelector('.close-modal');
            if (closeBtn) {
                closeBtn.onclick = () => this.hide();
            }
            
            // 모달 외부 클릭 시 닫기
            window.addEventListener('click', (e) => {
                if (e.target === this.elements.modal) {
                    this.hide();
                }
            });
            
            // ESC 키로 모달 닫기
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && this.elements.modal?.style.display === 'block') {
                    this.hide();
                }
            });
        },
        
        // 모달 표시
        show: function(title, message, type = 'success', autoHide = true, duration = 2000) {
            if (!this.elements.modal) return;
            
            // 헤더 클래스 설정
            if (this.elements.header) {
                this.elements.header.className = 'modal-header';
                this.elements.header.classList.add(type);
            }
            
            // 내용 설정
            if (this.elements.title) this.elements.title.textContent = title;
            if (this.elements.message) this.elements.message.textContent = message;
            
            // 모달 표시
            this.elements.modal.style.display = 'block';
            
            // 자동 숨김 설정
            if (autoHide) {
                setTimeout(() => this.hide(), duration);
            }
        },
        
        // 모달 숨김
        hide: function() {
            if (!this.elements.modal) return;
            
            // 현재 실행 중인 애니메이션이 있으면 취소
            this.elements.modal.style.animation = 'none';
            this.elements.modal.offsetHeight; // 강제 리플로우로 애니메이션 재설정
            
            // 페이드 아웃 효과
            this.elements.modal.style.animation = 'fadeOut 0.3s forwards';
            
            // 애니메이션이 완전히 끝난 후에만 display 속성 변경
            this.elements.modal.addEventListener('animationend', function onAnimationEnd() {
                this.elements.modal.style.display = 'none';
                this.elements.modal.style.animation = '';
                // 이벤트 리스너 제거하여 메모리 누수 방지
                this.elements.modal.removeEventListener('animationend', onAnimationEnd);
            }.bind(this), { once: true }); // once 옵션으로 한 번만 실행
        },
        
        // 성공 모달
        success: function(message, title = '성공') {
            this.show(title, message, 'success', true, 1000);
        },
        
        // 에러 모달
        error: function(message, title = '오류') {
            this.show(title, message, 'error', true, 1000);
        },
        
        // 경고 모달
        warning: function(message, title = '경고') {
            this.show(title, message, 'warning', true, 1000);
        },
        
        // 정보 모달
        info: function(message, title = '알림') {
            this.show(title, message, 'info', true, 1000);
        }
    },
    
    // 성공 메시지 표시 (기존 함수 유지)
    showSuccess: function(message) {
        this.showAlert(message, 'success');
    },
    
    // 에러 메시지 표시 (기존 함수 유지)
    showError: function(message) {
        this.showAlert(message, 'danger');
    },
    
    // 경고 메시지 표시 (기존 함수 유지)
    showWarning: function(message) {
        this.showAlert(message, 'warning');
    },
    
    // 정보 메시지 표시 (기존 함수 유지)
    showInfo: function(message) {
        this.showAlert(message, 'info');
    },
    
    // 알림 메시지 표시 (기존 함수 유지)
    showAlert: function(message, type = 'info') {
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
        alertDiv.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        // 페이지 상단에 알림 추가
        const container = document.querySelector('.container');
        if (container) {
            container.insertBefore(alertDiv, container.firstChild);
            
            // 5초 후 자동으로 사라지기
            setTimeout(() => {
                if (alertDiv.parentNode) {
                    alertDiv.remove();
                }
            }, 5000);
        }
    },
    
    // 로딩 스피너 표시/숨김
    showLoading: function() {
        // 로딩 스피너 표시 로직
    },
    
    hideLoading: function() {
        // 로딩 스피너 숨김 로직
    }
};

// 페이지 로드 시 모달 시스템 초기화
document.addEventListener('DOMContentLoaded', function() {
    AppUtils.modal.init();
    
    // 세션 메시지가 있으면 자동으로 모달 표시
    const successMessage = document.querySelector('.alert-success');
    if (successMessage && successMessage.textContent.trim()) {
        AppUtils.modal.success(successMessage.textContent.trim());
        successMessage.style.display = 'none';
    }
});
