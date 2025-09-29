/**
 * 게시판 공통 JavaScript (범용)
 */

// 페이지 로드 시 초기화
document.addEventListener('DOMContentLoaded', function() {
    checkSessionMessage();
    initBulkActions();
});

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

// 일괄 작업 초기화
function initBulkActions() {
    const selectAllCheckbox = document.getElementById('select-all');
    const postCheckboxes = document.querySelectorAll('.post-checkbox');
    const bulkDeleteBtn = document.getElementById('bulk-delete-btn');

    if (!selectAllCheckbox || !bulkDeleteBtn) return;

    // 전체 선택/해제
    selectAllCheckbox.addEventListener('change', function() {
        postCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        updateBulkDeleteButton();
    });

    // 개별 체크박스 변경 시
    postCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            updateSelectAllCheckbox();
            updateBulkDeleteButton();
        });
    });

    // 일괄 삭제 버튼 클릭
    bulkDeleteBtn.addEventListener('click', function() {
        const selectedPosts = Array.from(postCheckboxes)
            .filter(checkbox => checkbox.checked)
            .map(checkbox => checkbox.value);

        if (selectedPosts.length === 0) {
            alert('삭제할 게시글을 선택해주세요.');
            return;
        }

        if (confirm(`선택한 ${selectedPosts.length}개의 게시글을 삭제하시겠습니까?`)) {
            bulkDeletePosts(selectedPosts);
        }
    });
}

// 전체 선택 체크박스 상태 업데이트
function updateSelectAllCheckbox() {
    const selectAllCheckbox = document.getElementById('select-all');
    const postCheckboxes = document.querySelectorAll('.post-checkbox');
    const checkedCount = Array.from(postCheckboxes).filter(checkbox => checkbox.checked).length;
    const totalCount = postCheckboxes.length;

    if (selectAllCheckbox) {
        selectAllCheckbox.checked = checkedCount === totalCount;
    }
}

// 일괄 삭제 버튼 상태 업데이트
function updateBulkDeleteButton() {
    const bulkDeleteBtn = document.getElementById('bulk-delete-btn');
    const checkedCount = Array.from(document.querySelectorAll('.post-checkbox'))
        .filter(checkbox => checkbox.checked).length;

    if (bulkDeleteBtn) {
        bulkDeleteBtn.disabled = checkedCount === 0;
        bulkDeleteBtn.textContent = `선택 삭제 (${checkedCount})`;
    }
}

// 일괄 삭제 실행
function bulkDeletePosts(postIds) {
    const formData = new FormData();
    postIds.forEach(id => formData.append('post_ids[]', id));

    // CSRF 토큰 가져오기
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    
    // 현재 게시판 타입 가져오기 (URL에서 추출)
    const boardType = getBoardTypeFromUrl();

    fetch(`/backoffice/board-posts/${boardType}/bulk-destroy`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json',
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('선택한 게시글이 삭제되었습니다.');
            location.reload();
        } else {
            alert('삭제 중 오류가 발생했습니다: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('삭제 중 오류가 발생했습니다.');
    });
}

// URL에서 게시판 타입 추출
function getBoardTypeFromUrl() {
    const pathSegments = window.location.pathname.split('/');
    const boardTypeIndex = pathSegments.indexOf('board-posts') + 1;
    return pathSegments[boardTypeIndex] || 'notice';
}

/* ===== 갤러리 스킨 전용 기능 ===== */

// 썸네일 이미지 관리
class ThumbnailManager {
    constructor() {
        this.initializeEventListeners();
    }

    initializeEventListeners() {
        // 썸네일 이미지 처리
        const thumbnailInput = document.getElementById('thumbnail');
        if (thumbnailInput) {
            thumbnailInput.addEventListener('change', this.handleThumbnailChange.bind(this));
        }
    }

    // 썸네일 이미지 변경 처리
    handleThumbnailChange(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = (e) => {
                const thumbnailPreview = document.getElementById('thumbnailPreview');
                if (thumbnailPreview) {
                    thumbnailPreview.innerHTML = `
                        <img src="${e.target.result}" alt="썸네일 미리보기" class="thumbnail-preview">
                    `;
                }
            };
            reader.readAsDataURL(file);
        }
    }

    // 기존 썸네일 제거
    removeThumbnail() {
        if (confirm('현재 썸네일을 제거하시겠습니까?')) {
            // hidden input으로 제거 표시
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'remove_thumbnail';
            hiddenInput.value = '1';
            document.querySelector('form').appendChild(hiddenInput);
            
            // 썸네일 미리보기 영역 숨기기
            const thumbnailPreview = document.getElementById('thumbnailPreview');
            if (thumbnailPreview) {
                thumbnailPreview.style.display = 'none';
            }
        }
    }
}

// 페이지 로드 시 갤러리 기능 초기화
document.addEventListener('DOMContentLoaded', function() {
    // 썸네일 매니저 초기화
    if (document.querySelector('input[name="thumbnail"]')) {
        window.thumbnailManager = new ThumbnailManager();
    }
});

// 전역 함수들 (기존 코드와의 호환성을 위해)
function removeThumbnail() {
    if (window.thumbnailManager) {
        window.thumbnailManager.removeThumbnail();
    }
}

// 기존 첨부파일 제거
function removeExistingFile(index) {
    if (confirm('이 첨부파일을 제거하시겠습니까?')) {
        const fileItem = document.querySelector(`.existing-file[data-index="${index}"]`);
        if (fileItem) {
            // 제거 표시를 위한 hidden input 추가
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'remove_attachments[]';
            hiddenInput.value = index;
            document.querySelector('form').appendChild(hiddenInput);
            
            // 화면에서 제거
            fileItem.remove();
        }
    }
}
