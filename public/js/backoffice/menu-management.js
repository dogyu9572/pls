// 메뉴 관리 관련 스크립트
document.addEventListener('DOMContentLoaded', function() {
    // 수정 및 삭제 버튼 클릭 이벤트 처리 (click 이벤트로 통일)
    document.querySelectorAll('.menu-actions a, .menu-actions button').forEach(function(element) {
        element.addEventListener('click', function(e) {
            e.stopPropagation(); // 이벤트 전파 중지
        });
    });

    // 메인 메뉴 Sortable 초기화
    initMainMenuSortable();

    // 서브메뉴 Sortable 초기화
    initSubmenuSortable();

    // 삭제 확인 모달 초기화
    initDeleteConfirmation();

    // 세션 메시지가 있는 경우 모달로 표시
    checkSessionMessage();

    // URL 자동 완성 기능 초기화
    initUrlAutoComplete();
});

// 메인 메뉴 Sortable 초기화 함수
function initMainMenuSortable() {
    const mainMenuList = document.getElementById('mainMenuList');
    if (mainMenuList) {
        new Sortable(mainMenuList, {
            animation: 300, // 애니메이션 시간 증가
            easing: "cubic-bezier(1, 0, 0, 1)", // 더 부드러운 애니메이션 효과
            handle: '.drag-handle', // 핸들만 드래그 가능
            delay: 100, // 클릭과 드래그를 구분하기 위한 지연 시간
            delayOnTouchOnly: true, // 터치 디바이스에서만 지연 적용
            touchStartThreshold: 5, // 터치 디바이스에서 드래그 시작을 위한 이동 거리
            ghostClass: 'sortable-ghost', // 드래그 중인 항목의 원래 위치 스타일
            chosenClass: 'sortable-chosen', // 선택된 항목 스타일
            dragClass: 'sortable-drag', // 드래그 중인 항목 스타일
            group: {
                name: 'menu',
                pull: true,
                put: true
            },
            fallbackTolerance: 5, // 작은 움직임 무시
            onStart: function() {
                document.body.style.cursor = 'grabbing';
            },
            onEnd: function(evt) {
                document.body.style.cursor = '';
                handleMenuMove(evt);
            }
        });
    }
}

// 서브메뉴 Sortable 초기화 함수
function initSubmenuSortable() {
    document.querySelectorAll('.submenu-list').forEach(function(submenuList) {
        new Sortable(submenuList, {
            animation: 300, // 애니메이션 시간 증가
            easing: "cubic-bezier(1, 0, 0, 1)", // 더 부드러운 애니메이션 효과
            handle: '.drag-handle', // 핸들만 드래그 가능
            delay: 100, // 클릭과 드래그를 구분하기 위한 지연 시간
            delayOnTouchOnly: true, // 터치 디바이스에서만 지연 적용
            touchStartThreshold: 5, // 터치 디바이스에서 드래그 시작을 위한 이동 거리
            ghostClass: 'sortable-ghost', // 드래그 중인 항목의 원래 위치 스타일
            chosenClass: 'sortable-chosen', // 선택된 항목 스타일
            dragClass: 'sortable-drag', // 드래그 중인 항목 스타일
            group: {
                name: 'menu',
                pull: true,
                put: true
            },
            fallbackTolerance: 5, // 작은 움직임 무시
            onStart: function() {
                document.body.style.cursor = 'grabbing';
            },
            onEnd: function(evt) {
                document.body.style.cursor = '';
                handleMenuMove(evt);
            }
        });
    });
}

// 메뉴 순서 저장 함수
function saveMenuOrder(type, parentId = null) {
    const menuOrder = [];

    if (type === 'main') {
        // 메인 메뉴 순서 수집
        document.querySelectorAll('#mainMenuList > .menu-item').forEach(function(item, index) {
            const menuId = item.dataset.id;
            if (menuId) {
                menuOrder.push({
                    id: parseInt(menuId),
                    order: index + 1
                });
            }
        });
    } else if (type === 'sub') {
        // 서브메뉴 순서 수집
        document.querySelectorAll(`.submenu-list[data-parent-id="${parentId}"] > .submenu-item`).forEach(function(item, index) {
            const menuId = item.dataset.id;
            if (menuId) {
                menuOrder.push({
                    id: parseInt(menuId),
                    order: index + 1,
                    parent_id: parseInt(parentId)
                });
            }
        });
    }

    // AJAX 요청으로 순서 저장
    fetch('/backoffice/admin-menus/update-order', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ menuOrder })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlertModal('성공', '메뉴 순서가 저장되었습니다.', 'success');
        }
    })
    .catch(error => console.error('Error:', error));
}

// 세션 메시지 확인 함수
function checkSessionMessage() {
    const successMessage = document.querySelector('.alert-success');
    if (successMessage && successMessage.textContent.trim()) {
        // 통합 모달 시스템 사용
        if (window.AppUtils && AppUtils.modal) {
            AppUtils.modal.success(successMessage.textContent.trim());
        }
        successMessage.style.display = 'none';
    }
}

// 삭제 확인 모달 초기화
function initDeleteConfirmation() {
    // 모달 요소 가져오기
    const confirmModal = document.getElementById('confirmDeleteModal');
    if (!confirmModal) return;

    const closeButtons = confirmModal.querySelectorAll('.close-modal, #cancelDeleteBtn');
    const confirmButton = document.getElementById('confirmDeleteBtn');

    // 삭제 버튼 이벤트
    document.querySelectorAll('.delete-menu-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();

            // 현재 폼과 메뉴 이름 가져오기
            const form = this.closest('form');
            const menuName = form.dataset.name;

            // 확인 메시지 설정
            document.getElementById('confirmModalMessage').textContent =
                `정말 "${menuName}" 메뉴를 삭제하시겠습니까?`;

            // 삭제 확인 버튼에 이벤트 연결
            confirmButton.onclick = function() {
                form.submit(); // 폼 제출
                closeConfirmModal();
            };

            // 모달 표시
            confirmModal.style.display = 'block';
        });
    });

    // 닫기 버튼 이벤트
    closeButtons.forEach(btn => {
        btn.addEventListener('click', closeConfirmModal);
    });

    // 모달 외부 클릭 시 닫기
    window.addEventListener('click', function(e) {
        if (e.target === confirmModal) {
            closeConfirmModal();
        }
    });

    // ESC 키로 모달 닫기
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && confirmModal.style.display === 'block') {
            closeConfirmModal();
        }
    });

    // 모달 닫기 함수
    function closeConfirmModal() {
        const confirmModal = document.getElementById('confirmDeleteModal');
        if (!confirmModal) return;

        // 현재 실행 중인 애니메이션이 있으면 취소
        confirmModal.style.animation = 'none';
        confirmModal.offsetHeight; // 강제 리플로우로 애니메이션 재설정

        // 페이드 아웃 효과
        confirmModal.style.animation = 'fadeOut 0.3s forwards';

        // 애니메이션이 완전히 끝난 후에만 display 속성 변경
        confirmModal.addEventListener('animationend', function onAnimationEnd() {
            confirmModal.style.display = 'none';
            confirmModal.style.animation = '';
            // 이벤트 리스너 제거하여 메모리 누수 방지
            confirmModal.removeEventListener('animationend', onAnimationEnd);
        }, { once: true }); // once 옵션으로 한 번만 실행
    }
}

// URL 자동 완성 기능 초기화
function initUrlAutoComplete() {
    const urlPrefixSelect = document.getElementById('url_prefix');
    const urlInput = document.getElementById('url');
    
    if (!urlPrefixSelect || !urlInput) return;
    
    // 기존 URL 값이 있으면 접두사 자동 감지 (수정 페이지에서 기존 값 활용)
    if (urlInput.value) {
        detectUrlPrefix(urlInput.value);
    }
    
    // 셀렉트박스 변경 시 URL 자동 완성
    urlPrefixSelect.addEventListener('change', function() {
        const selectedValue = this.value;
        let prefix = '';
        
        switch(selectedValue) {
            case 'admin':
                prefix = '/backoffice/';
                break;
            case 'board':
                prefix = '/backoffice/board-posts/';
                break;
            case 'external':
                prefix = 'https://';
                break;
            default:
                prefix = '';
        }
        
        // 기존 URL 값을 초기화하고 새로운 접두사만 적용
        if (prefix) {
            urlInput.value = prefix;
        } else {
            urlInput.value = '';
        }
        
        // URL 입력 필드에 포커스
        urlInput.focus();
    });
}

// URL 접두사 자동 감지 함수
function detectUrlPrefix(url) {
    const urlPrefixSelect = document.getElementById('url_prefix');
    if (!urlPrefixSelect) return;
    
    if (url.startsWith('/backoffice/board-posts/')) {
        urlPrefixSelect.value = 'board';
    } else if (url.startsWith('/backoffice/')) {
        urlPrefixSelect.value = 'admin';
    } else if (url.startsWith('http://') || url.startsWith('https://')) {
        urlPrefixSelect.value = 'external'; 
    } else {
        urlPrefixSelect.value = '';
    }
}

// 메뉴 이동 처리 함수
function handleMenuMove(evt) {
    const draggedElement = evt.item;
    const menuId = draggedElement.dataset.id;
    
    // 새로운 부모 ID 결정
    let newParentId = null;
    
    if (evt.to.id === 'mainMenuList') {
        // 메인 메뉴 리스트로 이동 (1차 메뉴)
        newParentId = null;
    } else if (evt.to.classList.contains('submenu-list')) {
        // 서브메뉴 리스트로 이동 (2차 메뉴)
        newParentId = evt.to.dataset.parentId;
    }
    
    // 기존 부모 ID 결정
    let oldParentId = null;
    if (evt.from.classList.contains('submenu-list')) {
        // 서브메뉴에서 이동
        oldParentId = evt.from.dataset.parentId;
    } else if (evt.from.id === 'mainMenuList') {
        // 메인 메뉴에서 이동
        oldParentId = null;
    }
    
    
    // 부모가 변경된 경우에만 처리
    if (newParentId !== oldParentId) {
        updateMenuParent(menuId, newParentId);
    } else {
        // 같은 레벨 내에서 순서만 변경된 경우
        if (evt.to.id === 'mainMenuList') {
            saveMenuOrder('main');
        } else if (evt.to.classList.contains('submenu-list')) {
            saveMenuOrder('sub', evt.to.dataset.parentId);
        }
    }
}

// 메뉴 부모 업데이트 함수
function updateMenuParent(menuId, newParentId) {
    fetch('/backoffice/admin-menus/update-parent', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            menu_id: menuId,
            parent_id: newParentId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // 성공 시 페이지 새로고침
            location.reload();
        } else {
            alert('메뉴 이동 중 오류가 발생했습니다: ' + (data.message || '알 수 없는 오류'));
            // 오류 시 페이지 새로고침하여 원래 상태로 복구
            location.reload();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('메뉴 이동 중 오류가 발생했습니다.');
        // 오류 시 페이지 새로고침하여 원래 상태로 복구
        location.reload();
    });
}
