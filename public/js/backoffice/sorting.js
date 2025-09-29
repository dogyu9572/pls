// 정렬 기능 JavaScript
class BoardSorting {
    constructor() {
        this.sortable = null;
        this.init();
    }

    init() {
        // 정렬 기능이 활성화된 게시판인지 확인
        if (!document.querySelector('.sortable-table')) {
            return;
        }

        this.initSortable();
        this.bindEvents();
    }

    initSortable() {
        // 리스트 형태의 정렬
        const sortableList = document.querySelector('.sortable-list');
        if (sortableList) {
            this.sortable = new Sortable(sortableList, {
                handle: '.sort-handle',
                animation: 150,
                ghostClass: 'sortable-ghost',
                chosenClass: 'sortable-chosen',
                dragClass: 'sortable-drag',
                onStart: (evt) => {
                    evt.item.classList.add('dragging');
                },
                onEnd: (evt) => {
                    evt.item.classList.remove('dragging');
                    this.updateSortOrder();
                }
            });
        }

        // 테이블 형태의 정렬
        const sortableTbody = document.querySelector('#sortable-tbody');
        if (sortableTbody) {
            this.sortable = new Sortable(sortableTbody, {
                handle: '.sort-handle',
                animation: 150,
                ghostClass: 'sortable-ghost',
                chosenClass: 'sortable-chosen',
                dragClass: 'sortable-drag',
                onStart: (evt) => {
                    evt.item.classList.add('dragging');
                },
                onEnd: (evt) => {
                    evt.item.classList.remove('dragging');
                    this.updateSortOrder();
                }
            });
        }
    }

    bindEvents() {
        // 정렬 순서 입력 필드 변경 이벤트
        document.addEventListener('change', (e) => {
            if (e.target.classList.contains('sort-input')) {
                this.updateSortOrder();
            }
        });

        // 정렬 순서 입력 필드 키보드 이벤트
        document.addEventListener('keyup', (e) => {
            if (e.target.classList.contains('sort-input') && e.key === 'Enter') {
                this.updateSortOrder();
            }
        });
    }

    updateSortOrder() {
        // 리스트 형태의 정렬
        const postItems = document.querySelectorAll('.post-item');
        // 테이블 형태의 정렬
        const tableRows = document.querySelectorAll('#sortable-tbody tr[data-post-id]');
        
        const items = postItems.length > 0 ? postItems : tableRows;
        const updates = [];

        items.forEach((item, index) => {
            const postId = item.dataset.postId;
            
            // 테이블 형태에서는 드래그 순서대로 정렬 순서 설정 (큰 숫자가 위에)
            const sortOrder = postItems.length > 0 ? 
                (item.querySelector('.sort-input') ? parseInt(item.querySelector('.sort-input').value) || 0 : index + 1) :
                (items.length - index); // 테이블에서는 역순으로 설정

            updates.push({
                post_id: postId,
                sort_order: sortOrder
            });

            // 정렬 순서 표시 업데이트 (있는 경우에만)
            const sortDisplay = item.querySelector('.sort-order-display');
            if (sortDisplay) {
                sortDisplay.textContent = sortOrder;
            }
        });

        // 서버에 정렬 순서 업데이트 요청
        this.saveSortOrder(updates);
    }

    async saveSortOrder(updates) {
        try {
            const response = await fetch('/backoffice/board-posts/update-sort-order', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ updates })
            });

            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }

            const result = await response.json();
            
            if (result.success) {
                this.showMessage('정렬 순서가 저장되었습니다.', 'success');
            } else {
                throw new Error(result.message || '정렬 순서 저장에 실패했습니다.');
            }
        } catch (error) {
            console.error('Error updating sort order:', error);
            this.showMessage('정렬 순서 저장에 실패했습니다: ' + error.message, 'error');
        }
    }

    showMessage(message, type = 'info') {
        // 기존 메시지 제거
        const existingMessage = document.querySelector('.sorting-message');
        if (existingMessage) {
            existingMessage.remove();
        }

        // 새 메시지 생성
        const messageDiv = document.createElement('div');
        messageDiv.className = `sorting-message alert alert-${type === 'success' ? 'success' : 'danger'} alert-dismissible fade show`;
        messageDiv.style.position = 'fixed';
        messageDiv.style.top = '20px';
        messageDiv.style.right = '20px';
        messageDiv.style.zIndex = '9999';
        messageDiv.innerHTML = `
            ${message}
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        `;

        document.body.appendChild(messageDiv);

        // 3초 후 자동 제거
        setTimeout(() => {
            if (messageDiv.parentNode) {
                messageDiv.remove();
            }
        }, 3000);
    }
}

// 페이지 로드 시 초기화
document.addEventListener('DOMContentLoaded', () => {
    new BoardSorting();
});

// 정렬 순서 자동 설정 함수
function autoSetSortOrder() {
    const postItems = document.querySelectorAll('.post-item');
    postItems.forEach((item, index) => {
        const sortInput = item.querySelector('.sort-input');
        if (sortInput && !sortInput.value) {
            sortInput.value = index + 1;
        }
    });
}

// 정렬 순서 초기화 함수
function resetSortOrder() {
    const postItems = document.querySelectorAll('.post-item');
    postItems.forEach((item, index) => {
        const sortInput = item.querySelector('.sort-input');
        if (sortInput) {
            sortInput.value = index + 1;
        }
    });
    new BoardSorting().updateSortOrder();
}
