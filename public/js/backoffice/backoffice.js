document.addEventListener('DOMContentLoaded', function() {
    // 모든 메뉴의 active 클래스 초기화
    document.querySelectorAll('.sidebar-menu li').forEach(function(item) {
        item.classList.remove('active');
    });

    document.querySelectorAll('.sidebar-menu .has-submenu').forEach(function(item) {
        item.classList.remove('open');
    });

    // 모바일 메뉴 토글 기능
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebar = document.querySelector('.sidebar');
    const backdrop = document.getElementById('backdrop');
    const body = document.body;

    // 화면 크기에 따라 사이드바 토글 버튼 표시 여부 설정
    function toggleSidebarButtonVisibility() {
        if (window.innerWidth <= 768) {
            sidebarToggle.style.display = 'block';
        } else {
            sidebarToggle.style.display = 'none';
        }
    }

    // 초기 로드 시 실행
    toggleSidebarButtonVisibility();
    
    // 모바일에서 사이드바 초기 상태 설정
    if (sidebar && window.innerWidth <= 768) {
        sidebar.classList.remove('active');
    }
    
    // 백드롭 초기화
    if (backdrop) {
        backdrop.style.display = 'none';
    }

    // 윈도우 크기 변경 시 실행
    window.addEventListener('resize', toggleSidebarButtonVisibility);

    if (sidebarToggle && sidebar && backdrop) {
        // FastClick 적용 (모바일 터치 딜레이 제거)
        if ('ontouchstart' in window) {
            sidebarToggle.addEventListener('touchstart', toggleSidebar, { passive: true });
            backdrop.addEventListener('touchstart', closeSidebar, { passive: true });
        } else {
            sidebarToggle.addEventListener('click', toggleSidebar);
            backdrop.addEventListener('click', closeSidebar);
        }
    }

    // 콘텐츠 영역 클릭 시 사이드바 닫기 (모바일에서만)
    const content = document.querySelector('.content');
    if (content) {
        content.addEventListener('click', function(e) {
            if (window.innerWidth <= 768 && sidebar.classList.contains('active')) {
                closeSidebar();
            }
        });
    }

    // 윈도우 리사이즈 시 모바일 메뉴 상태 관리
    window.addEventListener('resize', function() {
        if (window.innerWidth > 768 && sidebar.classList.contains('active')) {
            closeSidebar();
        }
    });

    // 사이드바 토글 함수
    function toggleSidebar(e) {
        if (e && e.type !== 'touchstart') {
            e.preventDefault();
        }
        
        const isActive = sidebar.classList.contains('active');
        
        if (isActive) {
            // 사이드바 닫기
            sidebar.classList.remove('active');
            backdrop.classList.remove('active');
            body.classList.remove('sidebar-open');
            backdrop.style.display = 'none';
        } else {
            // 사이드바 열기
            sidebar.classList.add('active');
            backdrop.classList.add('active');
            body.classList.add('sidebar-open');
            backdrop.style.display = 'block';
            // 모바일에서만 사이드바 표시
            if (window.innerWidth <= 768) {
                sidebar.style.cssText = `
                    position: fixed !important;
                    left: 0 !important;
                    width: 260px !important;
                    height: 100vh !important;
                    min-height: 200vh !important;
                    background-color: #343a40 !important;
                    z-index: 9999 !important;
                    transform: translateX(0) !important;
                    transition: transform 0.3s ease-out !important;
                `;
            }
        }
    }

    // 사이드바 닫기 함수
    function closeSidebar(e) {
        if (e && e.type !== 'touchstart') {
            e.preventDefault();
        }
        sidebar.classList.remove('active');
        backdrop.classList.remove('active');
        body.classList.remove('sidebar-open');
        backdrop.style.display = 'none';
        // 모바일에서만 사이드바 숨기기
        if (window.innerWidth <= 768) {
            sidebar.style.cssText = `
                position: fixed !important;
                left: 0 !important;
                width: 260px !important;
                height: 100vh !important;
                min-height: 200vh !important;
                background-color: #343a40 !important;
                z-index: 9999 !important;
                transform: translateX(-100%) !important;
                transition: transform 0.3s ease-in !important;
            `;
        }
    }

    // 테이블 반응형 처리 (모바일에서 테이블 헤더 라벨 추가)
    document.querySelectorAll('table.stack-on-mobile').forEach(function(table) {
        const headerTexts = [];
        const headerCells = table.querySelectorAll('thead th');

        // 헤더 텍스트 수집
        headerCells.forEach(function(th) {
            headerTexts.push(th.textContent);
        });

        // 각 행에 data-label 속성 추가
        const rows = table.querySelectorAll('tbody tr');
        rows.forEach(function(row) {
            const cells = row.querySelectorAll('td');
            cells.forEach(function(cell, i) {
                if (headerTexts[i]) {
                    cell.setAttribute('data-label', headerTexts[i]);

                    // 가상 요소 대신 JS로 라벨 추가 (더 나은 호환성)
                    const labelEl = document.createElement('span');
                    labelEl.className = 'td-label';
                    labelEl.textContent = headerTexts[i] + ': ';

                    // 기존 내용을 감싸는 wrapper 추가
                    const contentEl = document.createElement('span');
                    contentEl.className = 'td-content';

                    // 기존 내용을 wrapper로 이동
                    while (cell.firstChild) {
                        contentEl.appendChild(cell.firstChild);
                    }

                    cell.appendChild(labelEl);
                    cell.appendChild(contentEl);
                }
            });
        });
    });

    // 서브메뉴가 있는 메뉴 아이템 클릭 시 동작
    const menuItems = document.querySelectorAll('.sidebar-menu .has-submenu');

    // 모바일용 터치 이벤트 최적화
    menuItems.forEach(function(item) {
        if ('ontouchstart' in window) {
            item.addEventListener('touchend', toggleSubmenu, { passive: true });
        } else {
            item.addEventListener('click', toggleSubmenu);
        }
    });

    function toggleSubmenu(e) {
        e.preventDefault();

        const parent = this.parentElement;
        parent.classList.toggle('active');
        this.classList.toggle('open');

        const siblings = Array.from(parent.parentElement.children).filter(child => child !== parent);
        siblings.forEach(sibling => {
            sibling.classList.remove('active');
            const submenuLink = sibling.querySelector('.has-submenu');
            if(submenuLink) submenuLink.classList.remove('open');
        });
    }

    // 폼 제출 시 버튼 비활성화는 button-utils.js에서 통합 처리

    // URL에 따라 해당 메뉴 활성화
    const currentPath = window.location.pathname;

    // 2차 메뉴 먼저 확인 (더 구체적인 경로)
    let isSubmenuActive = false;
    const submenuLinks = document.querySelectorAll('.sidebar-submenu li a');

    submenuLinks.forEach(function(link) {
        const url = new URL(link.href);
        const linkPath = url.pathname;

        // 현재 경로가 링크 경로와 정확히 일치하거나, 링크 경로로 시작하는 경우
        if (currentPath === linkPath || (linkPath !== '/backoffice' && currentPath.startsWith(linkPath + '/'))) {
            // 서브메뉴 아이템 활성화
            const menuItem = link.parentElement;
            menuItem.classList.add('active');

            // 부모 메뉴 아이템 활성화
            const parentLi = menuItem.closest('.sidebar-submenu').parentElement;
            if (parentLi) {
                parentLi.classList.add('active');
                const parentLink = parentLi.querySelector('.has-submenu');
                if (parentLink) parentLink.classList.add('open');
                isSubmenuActive = true;
            }
        }
    });

    // 2차 메뉴가 활성화되지 않았다면 1차 메뉴 확인
    if (!isSubmenuActive) {
        const mainMenuLinks = document.querySelectorAll('.sidebar-menu > li > a:not(.has-submenu)');

        mainMenuLinks.forEach(function(link) {
            const url = new URL(link.href);
            const linkPath = url.pathname;

            // 현재 경로가 링크 경로와 정확히 일치하거나, 링크 경로로 시작하는 경우
            if (currentPath === linkPath || (linkPath !== '/backoffice' && currentPath.startsWith(linkPath + '/'))) {
                // 메뉴 아이템 활성화
                link.parentElement.classList.add('active');
            }
        });
    }

    // 모바일 디바이스 확인
    function isMobile() {
        return window.innerWidth <= 768;
    }

    // 테이블 가로 스크롤이 필요한지 확인하고 표시
    document.querySelectorAll('table:not(.stack-on-mobile)').forEach(function(table) {
        const wrapper = document.createElement('div');
        wrapper.className = 'table-responsive';

        // 테이블을 wrapper로 감싸기
        table.parentNode.insertBefore(wrapper, table);
        wrapper.appendChild(table);

        if (table.offsetWidth > wrapper.offsetWidth) {
            // 스크롤 힌트 추가 (선택사항)
            const scrollHint = document.createElement('div');
            scrollHint.className = 'scroll-hint';
            scrollHint.innerHTML = '<i class="fas fa-arrows-left-right"></i> 좌우로 스크롤하세요';
            wrapper.parentNode.insertBefore(scrollHint, wrapper);
        }
    });
});
