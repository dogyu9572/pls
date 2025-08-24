/**
 * 갤러리 스킨 전용 JavaScript
 */

// 갤러리 이미지 관리
class GalleryManager {
    constructor() {
        this.initializeEventListeners();
    }

    initializeEventListeners() {
        // 썸나일 이미지 처리
        const thumbnailInput = document.getElementById('thumbnail');
        if (thumbnailInput) {
            thumbnailInput.addEventListener('change', this.handleThumbnailChange.bind(this));
        }

        // 갤러리 이미지 처리
        const imagesInput = document.getElementById('images');
        if (imagesInput) {
            imagesInput.addEventListener('change', this.handleGalleryImagesChange.bind(this));
        }

        // 드래그 앤 드롭
        this.initializeDragAndDrop();
    }

    // 썸나일 이미지 변경 처리
    handleThumbnailChange(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = (e) => {
                const thumbnailImage = document.getElementById('thumbnailImage');
                const thumbnailPreview = document.getElementById('thumbnailPreview');
                
                if (thumbnailImage && thumbnailPreview) {
                    thumbnailImage.src = e.target.result;
                    thumbnailPreview.style.display = 'block';
                }
            };
            reader.readAsDataURL(file);
        }
    }

    // 갤러리 이미지 변경 처리
    handleGalleryImagesChange(e) {
        const files = Array.from(e.target.files);
        const preview = document.getElementById('galleryPreview');
        
        if (preview) {
            preview.innerHTML = '';

            files.forEach((file, index) => {
                const reader = new FileReader();
                reader.onload = (e) => {
                    const imageItem = document.createElement('div');
                    imageItem.className = 'image-item';
                    imageItem.innerHTML = `
                        <img src="${e.target.result}" alt="새 갤러리 이미지">
                        <button type="button" class="image-remove" onclick="galleryManager.removeNewGalleryImage(${index})">
                            <i class="fas fa-times"></i>
                        </button>
                    `;
                    preview.appendChild(imageItem);
                };
                reader.readAsDataURL(file);
            });
        }
    }

    // 새 갤러리 이미지 제거
    removeNewGalleryImage(index) {
        const input = document.getElementById('images');
        if (input) {
            const dt = new DataTransfer();
            const { files } = input;

            for (let i = 0; i < files.length; i++) {
                if (i !== index) {
                    dt.items.add(files[i]);
                }
            }

            input.files = dt.files;
            // 미리보기 다시 로드
            const event = new Event('change');
            input.dispatchEvent(event);
        }
    }

    // 기존 썸나일 제거
    removeThumbnail() {
        if (confirm('현재 썸나일을 제거하시겠습니까?')) {
            // hidden input으로 제거 표시
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'remove_thumbnail';
            hiddenInput.value = '1';
            document.querySelector('form').appendChild(hiddenInput);
            
            // 기존 썸나일 영역 숨기기
            const existingImages = document.querySelector('.existing-images');
            if (existingImages) {
                existingImages.style.display = 'none';
            }
        }
    }

    // 기존 갤러리 이미지 제거
    removeExistingImage(index) {
        if (confirm('이 이미지를 제거하시겠습니까?')) {
            // 해당 이미지 항목 제거
            const imageItem = event.target.closest('.existing-image-item');
            if (imageItem) {
                imageItem.remove();
            }
            
            // hidden input으로 제거 표시
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'remove_existing_images[]';
            hiddenInput.value = index;
            document.querySelector('form').appendChild(hiddenInput);
        }
    }

    // 드래그 앤 드롭 초기화
    initializeDragAndDrop() {
        ['thumbnailUploadArea', 'galleryUploadArea'].forEach(id => {
            const area = document.getElementById(id);
            if (area) {
                const input = area.querySelector('input[type="file"]');
                if (input) {
                    this.setupDragAndDrop(area, input, id);
                }
            }
        });
    }

    // 드래그 앤 드롭 설정
    setupDragAndDrop(area, input, areaId) {
        area.addEventListener('dragover', (e) => {
            e.preventDefault();
            area.classList.add('dragover');
        });

        area.addEventListener('dragleave', () => {
            area.classList.remove('dragover');
        });

        area.addEventListener('drop', (e) => {
            e.preventDefault();
            area.classList.remove('dragover');
            
            if (areaId === 'thumbnailUploadArea') {
                input.files = e.dataTransfer.files;
                input.dispatchEvent(new Event('change'));
            } else {
                const dt = new DataTransfer();
                dt.items.add(...e.dataTransfer.files);
                input.files = dt.files;
                input.dispatchEvent(new Event('change'));
            }
        });
    }
}

// 갤러리 쇼케이스 관리
class GalleryShowcase {
    constructor() {
        this.initializeEventListeners();
    }

    initializeEventListeners() {
        // ESC 키로 라이트박스 닫기
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                this.closeLightbox();
            }
        });
    }

    // 메인 이미지 변경
    changeMainImage(thumbnail, imagePath, index) {
        const mainImage = document.getElementById('mainImage');
        if (mainImage) {
            mainImage.src = imagePath;
        }
        
        // 썸나일 활성화 상태 변경
        document.querySelectorAll('.gallery-thumbnail').forEach(thumb => {
            thumb.classList.remove('active');
        });
        thumbnail.classList.add('active');
    }

    // 라이트박스 열기
    openLightbox() {
        const mainImage = document.getElementById('mainImage');
        if (mainImage && mainImage.src) {
            const lightboxImage = document.getElementById('lightboxImage');
            const lightbox = document.getElementById('lightbox');
            
            if (lightboxImage && lightbox) {
                lightboxImage.src = mainImage.src;
                lightbox.style.display = 'block';
                document.body.style.overflow = 'hidden';
            }
        }
    }

    // 라이트박스 닫기
    closeLightbox() {
        const lightbox = document.getElementById('lightbox');
        if (lightbox) {
            lightbox.style.display = 'none';
            document.body.style.overflow = 'auto';
        }
    }
}

// 폼 제출 시 이미지 데이터 구조화
class GalleryFormHandler {
    constructor() {
        this.initializeFormHandler();
    }

    initializeFormHandler() {
        const form = document.querySelector('form');
        if (form) {
            form.addEventListener('submit', this.handleFormSubmit.bind(this));
        }
    }

    handleFormSubmit(e) {
        const imagesInput = document.getElementById('images');
        if (imagesInput && imagesInput.files.length > 0) {
            // 이미지 파일들을 FormData에 추가
            const files = Array.from(imagesInput.files);
            files.forEach((file, index) => {
                const formData = new FormData();
                formData.append(`gallery_images[${index}]`, file);
            });
        }
    }
}

// 페이지 로드 시 초기화
document.addEventListener('DOMContentLoaded', function() {
    // 갤러리 매니저 초기화
    if (document.querySelector('.gallery-container, .gallery-item')) {
        window.galleryManager = new GalleryManager();
    }

    // 갤러리 쇼케이스 초기화
    if (document.querySelector('.gallery-showcase')) {
        window.galleryShowcase = new GalleryShowcase();
    }

    // 폼 핸들러 초기화
    if (document.querySelector('form')) {
        window.galleryFormHandler = new GalleryFormHandler();
    }
});

// 전역 함수들 (기존 코드와의 호환성을 위해)
function changeMainImage(thumbnail, imagePath, index) {
    if (window.galleryShowcase) {
        window.galleryShowcase.changeMainImage(thumbnail, imagePath, index);
    }
}

function openLightbox() {
    if (window.galleryShowcase) {
        window.galleryShowcase.openLightbox();
    }
}

function closeLightbox() {
    if (window.galleryShowcase) {
        window.galleryShowcase.closeLightbox();
    }
}

function removeGalleryImage(index) {
    if (window.galleryManager) {
        window.galleryManager.removeNewGalleryImage(index);
    }
}

function removeThumbnail() {
    if (window.galleryManager) {
        window.galleryManager.removeThumbnail();
    }
}

function removeExistingImage(index) {
    if (window.galleryManager) {
        window.galleryManager.removeExistingImage(index);
    }
}
