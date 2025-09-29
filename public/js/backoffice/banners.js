/**
 * 배너 관리 페이지 JavaScript
 */

// Sortable.js 라이브러리 동적 로드
function loadSortableLibrary() {
    return new Promise((resolve, reject) => {
        // 이미 로드되어 있는지 확인
        if (typeof Sortable !== 'undefined') {
            resolve();
            return;
        }
        
        // 스크립트 태그 생성
        const script = document.createElement('script');
        script.src = 'https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js';
        script.onload = () => resolve();
        script.onerror = () => reject(new Error('Sortable.js 로드 실패'));
        
        // head에 추가
        document.head.appendChild(script);
    });
}
document.addEventListener('DOMContentLoaded', function() {
    // Sortable.js 라이브러리 동적 로드
    loadSortableLibrary().then(() => {
        // 배너 드래그 앤 드롭 초기화 (배너 리스트 페이지용)
        initBannerSortable();
    });
    
    // 게시기간 토글 기능
    const usePeriodCheckbox = document.getElementById('use_period');
    const periodFields = document.getElementById('period_fields');

    function togglePeriodFields() {
        if (usePeriodCheckbox && periodFields) {
            if (usePeriodCheckbox.checked) {
                periodFields.style.display = 'block';
            } else {
                periodFields.style.display = 'none';
            }
        }
    }

    if (usePeriodCheckbox) {
        usePeriodCheckbox.addEventListener('change', togglePeriodFields);
        togglePeriodFields(); // 초기 상태 설정
    }

    // 이미지 미리보기 기능
    function setupImagePreview(inputId, previewId) {
        const input = document.getElementById(inputId);
        const preview = document.getElementById(previewId);
        const wrapper = input?.closest('.board-file-input-wrapper');

        if (input && preview && wrapper) {
            // 파일 선택 시
            input.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    showImagePreview(file, preview, inputId, previewId);
                }
            });

            // 드래그 앤 드롭 이벤트
            wrapper.addEventListener('dragover', function(e) {
                e.preventDefault();
                wrapper.classList.add('dragover');
            });

            wrapper.addEventListener('dragleave', function(e) {
                e.preventDefault();
                wrapper.classList.remove('dragover');
            });

            wrapper.addEventListener('drop', function(e) {
                e.preventDefault();
                wrapper.classList.remove('dragover');
                
                const files = e.dataTransfer.files;
                if (files.length > 0) {
                    const file = files[0];
                    if (file.type.startsWith('image/')) {
                        input.files = files;
                        showImagePreview(file, preview, inputId, previewId);
                    } else {
                        alert('이미지 파일만 업로드 가능합니다.');
                    }
                }
            });
        }
    }

    // 이미지 미리보기 표시
    function showImagePreview(file, preview, inputId, previewId) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.innerHTML = `
                <div class="board-file-preview-item">
                    <img src="${e.target.result}" alt="미리보기" class="board-file-preview-img">
                    <div class="board-file-preview-info">
                        <span class="board-file-preview-name">${file.name}</span>
                        <span class="board-file-preview-size">${(file.size / 1024 / 1024).toFixed(2)} MB</span>
                    </div>
                    <button type="button" class="board-file-preview-remove" onclick="removeImagePreview('${inputId}', '${previewId}')">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `;
        };
        reader.readAsDataURL(file);
    }

    // 데스크톱 이미지 미리보기 설정
    setupImagePreview('desktop_image', 'desktopImagePreview');
    
    // 모바일 이미지 미리보기 설정
    setupImagePreview('mobile_image', 'mobileImagePreview');
});

// 이미지 미리보기 제거 함수 (전역 함수)
function removeImagePreview(inputId, previewId) {
    const input = document.getElementById(inputId);
    const preview = document.getElementById(previewId);
    
    if (input) input.value = '';
    if (preview) preview.innerHTML = '';
    
    // 서버에 이미지 제거 요청을 위한 숨겨진 필드 설정
    if (inputId === 'desktop_image') {
        const removeField = document.getElementById('remove_desktop_image');
        if (removeField) removeField.value = '1';
    } else if (inputId === 'mobile_image') {
        const removeField = document.getElementById('remove_mobile_image');
        if (removeField) removeField.value = '1';
    }
}

// 배너 드래그 앤 드롭 초기화 (배너 리스트 페이지용)
function initBannerSortable() {
    const bannerList = document.getElementById('bannerList');
    if (bannerList && typeof Sortable !== 'undefined') {
        new Sortable(bannerList, {
            animation: 300,
            easing: "cubic-bezier(1, 0, 0, 1)",
            handle: '.banner-drag-handle',
            delay: 100,
            delayOnTouchOnly: true,
            touchStartThreshold: 5,
            ghostClass: 'sortable-ghost',
            chosenClass: 'sortable-chosen',
            dragClass: 'sortable-drag',
            fallbackTolerance: 5,
            onStart: function() {
                document.body.style.cursor = 'grabbing';
            },
            onEnd: function() {
                document.body.style.cursor = '';
                saveBannerOrder();
            }
        });
    }
}

// 배너 순서 저장 함수
function saveBannerOrder() {
    const bannerOrder = [];
    const totalItems = document.querySelectorAll('#bannerList > .banner-item').length;

    // 배너 순서 수집 (위에 있는 항목이 더 큰 숫자를 가지도록)
    document.querySelectorAll('#bannerList > .banner-item').forEach(function(item, index) {
        const bannerId = item.dataset.id;
        if (bannerId) {
            const order = totalItems - index;  // 역순으로 계산
            bannerOrder.push({
                id: parseInt(bannerId),
                order: order
            });
        }
    });

    // AJAX 요청으로 순서 저장
    fetch('/backoffice/banners/update-order', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ bannerOrder })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // 성공 메시지 표시
            showSuccessMessage('배너 순서가 저장되었습니다.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showErrorMessage('순서 저장 중 오류가 발생했습니다.');
    });
}

// 성공 메시지 표시
function showSuccessMessage(message) {
    const alertDiv = document.createElement('div');
    alertDiv.className = 'alert alert-success board-hidden-alert';
    alertDiv.textContent = message;
    
    const container = document.querySelector('.board-container');
    container.insertBefore(alertDiv, container.firstChild);
    
    setTimeout(() => {
        alertDiv.remove();
    }, 3000);
}

// 에러 메시지 표시
function showErrorMessage(message) {
    const alertDiv = document.createElement('div');
    alertDiv.className = 'alert alert-danger board-hidden-alert';
    alertDiv.textContent = message;
    
    const container = document.querySelector('.board-container');
    container.insertBefore(alertDiv, container.firstChild);
    
    setTimeout(() => {
        alertDiv.remove();
    }, 3000);
}
