/**
 * 게시글 작성/수정 페이지 JavaScript (범용)
 */

// CSRF 토큰을 전역 변수로 설정
window.csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

// Summernote 에디터 초기화
function initSummernote() {
    if (typeof $.fn.summernote !== 'undefined') {
        $('#content').summernote({
            height: 400,
            lang: 'ko-KR',                
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'italic', 'strikethrough', 'clear']],
                ['fontname', ['fontname']],
                ['color', ['color', 'forecolor', 'backcolor']],
                ['fontsize', ['fontsize']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['height', ['height']],
                ['insert', ['link', 'picture', 'video', 'table']],
                ['view', ['fullscreen', 'codeview']],
                ['help', ['help']]
            ],
            fontNames: ['맑은 고딕', '굴림체', '바탕체', 'Arial', 'Times New Roman', 'Courier New', 'Verdana'],
            fontSizes: ['8', '9', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23', '24', '25', '26', '27', '28', '29', '30', '31', '32', '33', '34', '35', '36', '37', '38', '39', '40', '41', '42', '43', '44', '45', '46', '47', '48', '49', '50', '51', '52', '53', '54', '55', '56', '57', '58', '59', '60', '61', '62', '63', '64', '65', '66', '67', '68', '69', '70', '71', '72'],
            callbacks: {
                onImageUpload: function(files) {
                    for (let i = 0; i < files.length; i++) {
                        uploadImage(files[i], this);
                    }
                },
                onInit: function() {
                    // Summernote 초기화 완료
                }
            }
        });
        
        // 드롭다운 이벤트 강제 활성화
        setTimeout(function() {
            $('.note-btn-group .dropdown-toggle').off('click').on('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                $(this).dropdown('toggle');
            });
        }, 500);
        
    } else {
        console.error('Summernote가 로드되지 않았습니다!');
        // 폴백: 일반 textarea로 표시
        $('#content').show().attr('style', 'height: 400px; resize: vertical;');
    }
}

// 커스텀 필드 에디터 초기화
function initCustomFieldEditors() {
    if (typeof $.fn.summernote !== 'undefined') {
        $('.summernote-editor').each(function() {
            $(this).summernote({
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
        });
        
    }
}

// 이미지 업로드 함수
function uploadImage(file, editor) {
    const formData = new FormData();
    formData.append('image', file);
    formData.append('_token', window.csrfToken);
    
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


// URL을 embed 코드로 변환
function convertToEmbedCode(url) {
    let videoId = '';
    let embedUrl = '';
    
    // YouTube URL 처리 - 다양한 형식 지원
    if (url.includes('youtube.com/watch')) {
        const match = url.match(/[?&]v=([^&]+)/);
        if (match) {
            videoId = match[1];
            embedUrl = `https://www.youtube.com/embed/${videoId}`;
        }
    } else if (url.includes('youtu.be/')) {
        const match = url.match(/youtu\.be\/([^?&]+)/);
        if (match) {
            videoId = match[1];
            embedUrl = `https://www.youtube.com/embed/${videoId}`;
        }
    } else if (url.includes('youtube.com/embed/')) {
        // 이미 embed URL인 경우
        const match = url.match(/youtube\.com\/embed\/([^?&]+)/);
        if (match) {
            videoId = match[1];
            embedUrl = url; // 그대로 사용
        }
    }
    // Vimeo URL 처리
    else if (url.includes('vimeo.com/')) {
        const match = url.match(/vimeo\.com\/(\d+)/);
        if (match) {
            videoId = match[1];
            embedUrl = `https://player.vimeo.com/video/${videoId}`;
        }
    }
    
    if (embedUrl) {
        return `<div class="video-container" style="position: relative; padding-bottom: 56.25%; height: 0; overflow: hidden; margin: 20px 0;">
            <iframe src="${embedUrl}" 
                    style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;" 
                    frameborder="0" 
                    allowfullscreen>
            </iframe>
        </div>`;
    }
    
    return null;
}

// 첨부파일 관리 클래스
class FileManager {
    constructor() {
        this.fileInput = document.getElementById('attachments');
        this.filePreview = document.getElementById('filePreview');
        this.fileUpload = document.querySelector('.board-file-upload');
        this.maxFiles = 5;
        this.maxFileSize = 10 * 1024 * 1024; // 10MB
        
        this.init();
    }
    
    init() {
        // 파일 선택 이벤트
        this.fileInput.addEventListener('change', (e) => {
            const files = Array.from(e.target.files);
            if (files.length > 0) {
                this.replaceAllFiles(files);
            }
        });
        
        // 드래그 앤 드롭 이벤트
        this.fileUpload.addEventListener('dragover', (e) => {
            e.preventDefault();
            this.fileUpload.classList.add('board-file-drag-over');
        });
        
        this.fileUpload.addEventListener('dragleave', (e) => {
            e.preventDefault();
            this.fileUpload.classList.remove('board-file-drag-over');
        });
        
        this.fileUpload.addEventListener('drop', (e) => {
            e.preventDefault();
            this.fileUpload.classList.remove('board-file-drag-over');
            const files = Array.from(e.dataTransfer.files);
            this.handleFiles(files);
        });
    }
    
    // 파일 교체 함수 (기존 파일을 모두 지우고 새 파일로 교체)
    replaceAllFiles(files) {
        // 파일 개수 제한 체크
        if (files.length > this.maxFiles) {
            alert(`최대 ${this.maxFiles}개까지만 선택할 수 있습니다.`);
            this.fileInput.value = '';
            return;
        }
        
        // 파일 크기 체크
        const oversizedFiles = files.filter(file => file.size > this.maxFileSize);
        if (oversizedFiles.length > 0) {
            alert('10MB 이상인 파일이 있습니다. 10MB 이하의 파일만 선택해주세요.');
            this.fileInput.value = '';
            return;
        }
        
        // FileList를 DataTransfer로 변환
        const dt = new DataTransfer();
        files.forEach(file => dt.items.add(file));
        this.fileInput.files = dt.files;
        
        // 미리보기 생성
        this.updateFilePreview();
    }
    
    // 파일 처리 함수 (드래그 앤 드롭용 - 기존 파일에 추가)
    handleFiles(files) {
        // 파일 개수 제한 체크
        if (files.length > this.maxFiles) {
            alert(`최대 ${this.maxFiles}개까지만 선택할 수 있습니다.`);
            return;
        }
        
        // 파일 크기 체크
        const oversizedFiles = files.filter(file => file.size > this.maxFileSize);
        if (oversizedFiles.length > 0) {
            alert('10MB 이상인 파일이 있습니다. 10MB 이하의 파일만 선택해주세요.');
            return;
        }
        
        // 중복 파일 체크 (파일명 기준)
        const existingFiles = Array.from(this.fileInput.files);
        const newFiles = files.filter(newFile => 
            !existingFiles.some(existingFile => 
                existingFile.name === newFile.name && 
                existingFile.size === newFile.size
            )
        );
        
        if (newFiles.length === 0) {
            alert('이미 추가된 파일입니다.');
            return;
        }
        
        // 기존 파일과 새 파일 합치기
        const allFiles = [...existingFiles, ...newFiles];
        
        if (allFiles.length > this.maxFiles) {
            alert(`최대 ${this.maxFiles}개까지만 선택할 수 있습니다.`);
            return;
        }
        
        // FileList를 DataTransfer로 변환
        const dt = new DataTransfer();
        allFiles.forEach(file => dt.items.add(file));
        this.fileInput.files = dt.files;
        
        // 미리보기 생성
        this.updateFilePreview();
    }
    
    // 파일 미리보기 업데이트
    updateFilePreview() {
        const files = Array.from(this.fileInput.files);
        this.filePreview.innerHTML = '';
        
        files.forEach((file, index) => {
            const fileItem = document.createElement('div');
            fileItem.className = 'board-file-item';
            fileItem.innerHTML = `
                <div class="board-file-info">
                    <i class="fas fa-file"></i>
                    <span class="board-file-name">${file.name}</span>
                    <span class="board-file-size">(${(file.size / 1024 / 1024).toFixed(2)}MB)</span>
                </div>
                <button type="button" class="board-file-remove" onclick="fileManager.removeFile(${index})">
                    <i class="fas fa-times"></i>
                </button>
            `;
            this.filePreview.appendChild(fileItem);
        });
    }
    
    // 파일 제거 함수
    removeFile(index) {
        const dt = new DataTransfer();
        const files = Array.from(this.fileInput.files);
        
        files.splice(index, 1);
        files.forEach(file => dt.items.add(file));
        
        this.fileInput.files = dt.files;
        
        // 미리보기 업데이트
        this.updateFilePreview();
    }
    
    // 기존 첨부파일 제거 함수
    removeExistingFile(index) {
        const existingFiles = document.querySelectorAll('.board-attachment-list .existing-file');
        const targetFile = existingFiles[index];
        const hiddenInput = targetFile.querySelector('input[name="existing_attachments[]"]');
        
        if (hiddenInput) {
            hiddenInput.remove(); // 숨겨진 입력 필드 제거
            targetFile.remove(); // 화면에서 제거
        }
    }
}

// 모달창 내 Insert Video 버튼 기능 교체
$(document).ready(function() {
    // 모달이 나타날 때마다 체크하여 Insert Video 버튼 기능 교체
    setInterval(function() {
        const insertBtn = $('.note-video-btn[value="Insert Video"]');
        if (insertBtn.length > 0 && !insertBtn.data('custom-attached')) {
            insertBtn.data('custom-attached', true);
            insertBtn.off('click').on('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                // 모달창의 URL 입력값 가져오기
                const videoUrl = $('.note-video-url').val();
                
                if (videoUrl && videoUrl.trim() !== '') {
                    const embedCode = convertToEmbedCode(videoUrl.trim());
                    
                    if (embedCode) {
                        // HTML 문자열을 DOM 요소로 변환
                        const tempDiv = document.createElement('div');
                        tempDiv.innerHTML = embedCode;
                        const videoElement = tempDiv.firstElementChild;
                        
                        $('#content').summernote('insertNode', videoElement);
                        
                        // 모달창 닫기
                        $('.modal').modal('hide');
                    } else {
                        alert('지원하지 않는 비디오 URL입니다. YouTube 또는 Vimeo URL을 입력해주세요.');
                    }
                } else {
                    alert('비디오 URL을 입력해주세요.');
                }
                
                return false;
            });
        }
    }, 500);
});

// 페이지 로드 시 초기화
document.addEventListener('DOMContentLoaded', function() {
    // jQuery 로드 확인 후 Summernote 초기화
    if (typeof $ !== 'undefined') {
        initSummernote();
        initCustomFieldEditors(); // 커스텀 필드 에디터도 초기화
    } else {
        // jQuery가 로드되지 않은 경우 대기
        const checkJQuery = setInterval(() => {
            if (typeof $ !== 'undefined') {
                clearInterval(checkJQuery);
                initSummernote();
                initCustomFieldEditors(); // 커스텀 필드 에디터도 초기화
            }
        }, 100);
    }
    
    // 파일 관리자 초기화
    window.fileManager = new FileManager();
    
    // 기존 첨부파일 제거 함수를 전역으로 사용할 수 있도록 설정
    window.removeExistingFile = function(index) {
        window.fileManager.removeExistingFile(index);
    };
});

