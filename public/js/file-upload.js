/**
 * 파일 업로드 입력 필드의 파일명을 표시하는 스크립트
 */
document.addEventListener('DOMContentLoaded', function() {
    const fileInputs = document.querySelectorAll('.custom-file-input');
    Array.from(fileInputs).forEach(input => {
        input.addEventListener('change', function(e) {
            const fileName = e.target.files[0]?.name || '파일 선택...';
            const label = e.target.nextElementSibling;
            label.textContent = fileName;
        });
    });
});
