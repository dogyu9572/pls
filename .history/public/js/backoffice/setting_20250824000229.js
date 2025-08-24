// 설정 페이지 JavaScript

// 파일 업로드 관련 함수들
function handleFileUpload(fileInput, uploadAreaId, selectedId, fileNameId) {
    const file = fileInput.files[0];
    if (!file) return;

    const uploadArea = document.getElementById(uploadAreaId);
    const selected = document.getElementById(selectedId);
    const fileName = document.getElementById(fileNameId);

    if (uploadArea && selected && fileName) {
        // 파일명 표시
        fileName.textContent = file.name;
        
        // 업로드 영역 숨기기
        uploadArea.style.display = 'none';
        
        // 선택된 파일 표시
        selected.classList.add('show');
        
        // 이미지 파일인 경우 미리보기 생성
        if (file.type.startsWith('image/')) {
            createImagePreview(file);
        }
    }
}

// 이미지 미리보기 생성
function createImagePreview(file) {
    const reader = new FileReader();
    reader.onload = function(e) {
        const previewContainer = document.querySelector('.file-preview') || createPreviewContainer();
        const previewContent = previewContainer.querySelector('.file-preview-content');
        
        previewContent.innerHTML = `
            <img src="${e.target.result}" alt="파일 미리보기" class="file-preview-image">
        `;
        
        // 기존 미리보기 제거
        const existingPreview = document.querySelector('.file-preview');
        if (existingPreview && existingPreview !== previewContainer) {
            existingPreview.remove();
        }
        
        // 새 미리보기 추가
        if (!existingPreview) {
            document.querySelector('.file-upload-container').appendChild(previewContainer);
        }
    };
    reader.readAsDataURL(file);
}

// 미리보기 컨테이너 생성
function createPreviewContainer() {
    const container = document.createElement('div');
    container.className = 'file-preview';
    container.innerHTML = `
        <div class="file-preview-header">
            <span class="file-preview-title">새 파일 미리보기</span>
            <button type="button" class="file-preview-remove" onclick="removeNewFilePreview()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="file-preview-content">
        </div>
    `;
    return container;
}

// 선택된 파일 제거
function removeSelectedFile(inputName) {
    const fileInput = document.getElementById(inputName);
    const uploadArea = document.getElementById(inputName + 'UploadArea');
    const selected = document.getElementById(inputName + 'Selected');
    
    if (fileInput && uploadArea && selected) {
        fileInput.value = '';
        uploadArea.style.display = 'block';
        selected.classList.remove('show');
    }
}

// 새 파일 미리보기 제거
function removeNewFilePreview() {
    const previewContainer = document.querySelector('.file-preview');
    if (previewContainer) {
        previewContainer.remove();
    }
}

// 로고 파일 삭제
function removeLogo() {
    if (confirm('로고를 삭제하시겠습니까?')) {
        addHiddenInput('remove_logo', '1');
    }
}

// 파비콘 파일 삭제
function removeFavicon() {
    if (confirm('파비콘을 삭제하시겠습니까?')) {
        addHiddenInput('remove_favicon', '1');
    }
}

// 숨겨진 입력 필드 추가
function addHiddenInput(name, value) {
    const form = document.querySelector('form');
    const input = document.createElement('input');
    input.type = 'hidden';
    input.name = name;
    input.value = value;
    form.appendChild(input);
    
    // 폼 제출
    form.submit();
}

// 페이지 로드 시 초기화
document.addEventListener('DOMContentLoaded', function() {
    // 로고 파일 업로드 이벤트
    const logoInput = document.getElementById('logo');
    if (logoInput) {
        logoInput.addEventListener('change', function() {
            handleFileUpload(this, 'logoUploadArea', 'logoSelected', 'logoFileName');
        });
    }
    
    // 파비콘 파일 업로드 이벤트
    const faviconInput = document.getElementById('favicon');
    if (faviconInput) {
        faviconInput.addEventListener('change', function() {
            handleFileUpload(this, 'faviconUploadArea', 'faviconSelected', 'faviconFileName');
        });
    }
    
    // 드래그 앤 드롭 이벤트
    setupDragAndDrop();
});

// 드래그 앤 드롭 설정
function setupDragAndDrop() {
    const uploadAreas = document.querySelectorAll('.file-upload-area');
    
    uploadAreas.forEach(area => {
        area.addEventListener('dragover', function(e) {
            e.preventDefault();
            this.classList.add('drag-over');
        });
        
        area.addEventListener('dragleave', function(e) {
            e.preventDefault();
            this.classList.remove('drag-over');
        });
        
        area.addEventListener('drop', function(e) {
            e.preventDefault();
            this.classList.remove('drag-over');
            
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                const fileInput = this.querySelector('input[type="file"]');
                if (fileInput) {
                    fileInput.files = files;
                    fileInput.dispatchEvent(new Event('change'));
                }
            }
        });
    });
}
