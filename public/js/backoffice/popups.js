// 팝업 관리 JavaScript

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

// 팝업 드래그 앤 드롭 초기화
function initPopupSortable() {
    const popupList = document.getElementById('popupList');
    if (!popupList) return;

    new Sortable(popupList, {
        handle: '.popup-drag-handle',
        animation: 150,
        ghostClass: 'sortable-ghost',
        chosenClass: 'sortable-chosen',
        dragClass: 'sortable-drag',
        onEnd: function(evt) {
            savePopupOrder();
        }
    });
}

// 팝업 순서 저장
function savePopupOrder() {
    const popupOrder = [];
    const totalItems = document.querySelectorAll('#popupList > .popup-item').length;

    // 팝업 순서 수집 (위에 있는 항목이 더 큰 숫자를 가지도록)
    document.querySelectorAll('#popupList > .popup-item').forEach(function(item, index) {
        const popupId = item.dataset.id;
        if (popupId) {
            const order = totalItems - index;  // 역순으로 계산
            popupOrder.push({
                id: parseInt(popupId),
                order: order
            });
        }
    });

    // AJAX 요청으로 순서 저장
    fetch('/backoffice/popups/update-order', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ popupOrder })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // 성공 메시지 표시
            showSuccessMessage('팝업 순서가 저장되었습니다.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showErrorMessage('순서 저장 중 오류가 발생했습니다.');
    });
}

// 게시기간 토글 기능
function togglePeriodFields() {
    const periodFields = document.getElementById('period_fields');
    const radioButtons = document.querySelectorAll('input[name="use_period"]');
    
    if (periodFields && radioButtons.length > 0) {
        // 초기 상태 설정
        const checkedRadio = document.querySelector('input[name="use_period"]:checked');
        if (checkedRadio && checkedRadio.value === '1') {
            periodFields.style.display = 'block';
        } else {
            periodFields.style.display = 'none';
        }
        
        radioButtons.forEach(radio => {
            radio.addEventListener('change', function() {
                if (this.value === '1') {
                    periodFields.style.display = 'block';
                } else {
                    periodFields.style.display = 'none';
                }
            });
        });
    }
}

// 팝업타입에 따른 섹션 토글
function togglePopupTypeSections() {
    const popupTypeRadios = document.querySelectorAll('input[name="popup_type"]');
    const imageSection = document.getElementById('popup_image_section');
    const htmlSection = document.getElementById('popup_html_section');
    
    if (popupTypeRadios.length > 0) {
        popupTypeRadios.forEach(radio => {
            radio.addEventListener('change', function() {
                if (this.value === 'image') {
                    if (imageSection) imageSection.style.display = 'block';
                    if (htmlSection) htmlSection.style.display = 'none';
                } else if (this.value === 'html') {
                    if (imageSection) imageSection.style.display = 'none';
                    if (htmlSection) htmlSection.style.display = 'block';
                }
            });
        });
    }
}

// 이미지 미리보기 기능
function initImagePreview() {
    const fileInput = document.getElementById('popup_image');
    const preview = document.getElementById('popupImagePreview');
    
    if (fileInput && preview) {
        fileInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.innerHTML = `
                        <img src="${e.target.result}" alt="미리보기" class="thumbnail-preview">
                        <button type="button" class="btn btn-sm btn-outline-danger mt-2" onclick="removeImagePreview()">
                            <i class="fas fa-trash"></i> 이미지 제거
                        </button>
                    `;
                };
                reader.readAsDataURL(file);
            }
        });
    }
}

// 이미지 미리보기 제거
function removeImagePreview() {
    const fileInput = document.getElementById('popup_image');
    const preview = document.getElementById('popupImagePreview');
    const removeInput = document.getElementById('remove_popup_image');
    
    if (fileInput) fileInput.value = '';
    if (preview) preview.innerHTML = '';
    if (removeInput) removeInput.value = '1';
}

// 드래그 앤 드롭 파일 업로드
function initDragAndDrop() {
    const fileInputs = document.querySelectorAll('.board-file-input');
    
    fileInputs.forEach(input => {
        const wrapper = input.closest('.board-file-input-wrapper');
        
        if (wrapper) {
            wrapper.addEventListener('dragover', function(e) {
                e.preventDefault();
                this.classList.add('dragover');
            });
            
            wrapper.addEventListener('dragleave', function(e) {
                e.preventDefault();
                this.classList.remove('dragover');
            });
            
            wrapper.addEventListener('drop', function(e) {
                e.preventDefault();
                this.classList.remove('dragover');
                
                const files = e.dataTransfer.files;
                if (files.length > 0) {
                    input.files = files;
                    input.dispatchEvent(new Event('change'));
                }
            });
        }
    });
}

// 성공 메시지 표시
function showSuccessMessage(message) {
    // 기존 알림 제거
    const existingAlert = document.querySelector('.alert-success');
    if (existingAlert) {
        existingAlert.remove();
    }
    
    // 새 알림 생성
    const alert = document.createElement('div');
    alert.className = 'alert alert-success board-hidden-alert';
    alert.textContent = message;
    
    // 페이지 상단에 추가
    const container = document.querySelector('.board-container');
    if (container) {
        container.insertBefore(alert, container.firstChild);
        
        // 3초 후 자동 제거
        setTimeout(() => {
            alert.remove();
        }, 3000);
    }
}

// 에러 메시지 표시
function showErrorMessage(message) {
    // 기존 알림 제거
    const existingAlert = document.querySelector('.alert-danger');
    if (existingAlert) {
        existingAlert.remove();
    }
    
    // 새 알림 생성
    const alert = document.createElement('div');
    alert.className = 'alert alert-danger board-hidden-alert';
    alert.textContent = message;
    
    // 페이지 상단에 추가
    const container = document.querySelector('.board-container');
    if (container) {
        container.insertBefore(alert, container.firstChild);
        
        // 5초 후 자동 제거
        setTimeout(() => {
            alert.remove();
        }, 5000);
    }
}

// Summernote 에디터 초기화
function initSummernote() {
    if (typeof $.fn.summernote !== 'undefined') {
        $('.summernote-editor').summernote({
            height: 300,
            lang: 'ko-KR',
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'italic', 'strikethrough', 'clear']],
                ['fontname', ['fontname']],
                ['color', ['color', 'forecolor', 'backcolor']],
                ['fontsize', ['fontsize']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['height', ['height']],
                ['insert', ['link', 'picture', 'table']],
                ['view', ['fullscreen', 'codeview']]
            ],
            fontNames: ['맑은 고딕', '굴림체', '바탕체', 'Arial', 'Times New Roman', 'Courier New', 'Verdana'],
            fontSizes: ['8', '9', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23', '24', '25', '26', '27', '28', '29', '30', '31', '32', '33', '34', '35', '36', '37', '38', '39', '40', '41', '42', '43', '44', '45', '46', '47', '48', '49', '50', '51', '52', '53', '54', '55', '56', '57', '58', '59', '60', '61', '62', '63', '64', '65', '66', '67', '68', '69', '70', '71', '72'],
            callbacks: {
                onImageUpload: function(files) {
                    for (let i = 0; i < files.length; i++) {
                        uploadImage(files[i], this);
                    }
                }
            }
        });
        
    } else {
        console.error('Summernote가 로드되지 않았습니다!');
    }
}

// 이미지 업로드 함수
function uploadImage(file, editor) {
    const formData = new FormData();
    formData.append('image', file);
    formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
    
    fetch('/backoffice/upload-image', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(result => {
        if (result.uploaded) {
            $(editor).summernote('insertImage', result.url);
        } else {
            alert('이미지 업로드에 실패했습니다.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('이미지 업로드 중 오류가 발생했습니다.');
    });
}

// 날짜 인풋 클릭 이벤트 추가
function initDateInputs() {
    const dateInputs = document.querySelectorAll('input[type="date"]');
    
    dateInputs.forEach(input => {
        // 인풋 전체 영역 클릭 시 달력 열기
        input.addEventListener('click', function() {
            this.showPicker && this.showPicker();
        });
        
        // 포커스 시에도 달력 열기
        input.addEventListener('focus', function() {
            this.showPicker && this.showPicker();
        });
    });
}

// DOM 로드 완료 후 초기화
document.addEventListener('DOMContentLoaded', function() {
    // Sortable.js 라이브러리 동적 로드
    loadSortableLibrary().then(() => {
        // 팝업 드래그 앤 드롭 초기화 (팝업 리스트 페이지용)
        initPopupSortable();
    });
    
    // 게시기간 토글 기능
    togglePeriodFields();
    
    // 팝업타입에 따른 섹션 토글
    togglePopupTypeSections();
    
    // 이미지 미리보기 기능
    initImagePreview();
    
    // 드래그 앤 드롭 파일 업로드
    initDragAndDrop();
    
    // 날짜 인풋 클릭 이벤트
    initDateInputs();
    
    // jQuery 로드 확인 후 Summernote 초기화
    if (typeof $ !== 'undefined') {
        initSummernote();
    } else {
        // jQuery가 로드되지 않은 경우 대기
        const checkJQuery = setInterval(() => {
            if (typeof $ !== 'undefined') {
                clearInterval(checkJQuery);
                initSummernote();
            }
        }, 100);
    }
});
