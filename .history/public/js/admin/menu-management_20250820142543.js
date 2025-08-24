// 메뉴 관리 관련 스크립트
document.addEventListener('DOMContentLoaded', function() {
    // 수정 및 삭제 버튼 클릭 이벤트 처리
    document.querySelectorAll('.menu-actions a, .menu-actions button').forEach(function(element) {
        element.addEventListener('mousedown', function(e) {
            e.stopPropagation(); // 이벤트 전파 중지
        });
    });

    // 메인 메뉴 Sortable 초기화
    initMainMenuSortable();

    // 서브메뉴 Sortable 초기화
    initSubmenuSortable();

    // 알림 모달 초기화
    initAlertModal();

    // 삭제 확인 모달 초기화
    initDeleteConfirmation();

    // 더 이상 세션 메시지 확인이 필요하지 않음 (Blade에서 직접 호출)
    // checkSessionMessage();
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
            filter: '.submenu-list', // 서브메뉴 리스트는 드래그에서 제외
            fallbackTolerance: 5, // 작은 움직임 무시
            onStart: function() {
                document.body.style.cursor = 'grabbing';
            },
            onEnd: function(evt) {
                document.body.style.cursor = '';
                saveMenuOrder('main');
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
                name: 'submenu',
                pull: false,
                put: false
            },
            fallbackTolerance: 5, // 작은 움직임 무시
            onStart: function() {
                document.body.style.cursor = 'grabbing';
            },
            onEnd: function(evt) {
                document.body.style.cursor = '';
                const parentId = submenuList.dataset.parentId;
                saveMenuOrder('sub', parentId);
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
    fetch('/backoffice/menus/update-order', {
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

// 알림 모달 초기화
function initAlertModal() {
    const modal = document.getElementById('alertModal');
    if (!modal) return;

    const closeBtn = modal.querySelector('.close-modal');
    closeBtn.addEventListener('click', function() {
        closeModal();
    });

    // 모달 외부 클릭 시 닫기
    window.addEventListener('click', function(e) {
        if (e.target === modal) {
            closeModal();
        }
    });

    // ESC 키로 모달 닫기
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && modal.style.display === 'block') {
            closeModal();
        }
    });
}

// 모달 열기 함수 (전역으로 노출)
window.showAlertModal = function(title, message, type = 'success') {
    const modal = document.getElementById('alertModal');
    if (!modal) return;

    const modalHeader = document.getElementById('modalHeader');
    const modalTitle = document.getElementById('modalTitle');
    const modalMessage = document.getElementById('modalMessage');

    // 모든 클래스 제거하고 타입에 맞는 클래스 추가
    modalHeader.className = 'modal-header';
    modalHeader.classList.add(type);

    modalTitle.textContent = title;
    modalMessage.textContent = message;

    modal.style.display = 'block';

    // 자동으로 닫히도록 설정 (3초)
    setTimeout(function() {
        closeModal();
    }, 3000);
}

// 모달 닫기 함수
function closeModal() {
    const modal = document.getElementById('alertModal');
    if (!modal) return;

    // 현재 실행 중인 애니메이션이 있으면 취소
    modal.style.animation = 'none';
    modal.offsetHeight; // 강제 리플로우로 애니메이션 재설정

    // 페이드 아웃 효과
    modal.style.animation = 'fadeOut 0.3s forwards';

    // 애니메이션이 완전히 끝난 후에만 display 속성 변경
    modal.addEventListener('animationend', function onAnimationEnd() {
        modal.style.display = 'none';
        modal.style.animation = '';
        // 이벤트 리스너 제거하여 메모리 누수 방지
        modal.removeEventListener('animationend', onAnimationEnd);
    }, { once: true }); // once 옵션으로 한 번만 실행
}

// 세션 메시지 확인 함수 (더 이상 사용하지 않음)
function checkSessionMessage() {
    // 이제 Blade 템플릿에서 직접 모달을 호출하므로 여기서는 아무것도 하지 않음
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
