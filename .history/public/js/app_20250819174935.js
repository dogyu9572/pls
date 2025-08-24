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

// 유틸리티 함수들
window.AppUtils = {
    // CSRF 토큰 가져오기
    getCsrfToken: function() {
        return document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    },
    
    // AJAX 요청 헤더 설정
    getAjaxHeaders: function() {
        return {
            'X-CSRF-TOKEN': this.getCsrfToken(),
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        };
    },
    
    // 성공 메시지 표시
    showSuccess: function(message) {
        this.showAlert(message, 'success');
    },
    
    // 에러 메시지 표시
    showError: function(message) {
        this.showAlert(message, 'danger');
    },
    
    // 경고 메시지 표시
    showWarning: function(message) {
        this.showAlert(message, 'warning');
    },
    
    // 정보 메시지 표시
    showInfo: function(message) {
        this.showAlert(message, 'info');
    },
    
    // 알림 메시지 표시
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
        const spinner = document.createElement('div');
        spinner.id = 'loading-spinner';
        spinner.className = 'loading-spinner';
        spinner.innerHTML = '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">로딩중...</span></div>';
        
        document.body.appendChild(spinner);
    },
    
    hideLoading: function() {
        const spinner = document.getElementById('loading-spinner');
        if (spinner) {
            spinner.remove();
        }
    }
};
