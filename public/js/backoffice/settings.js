/**
 * 백오피스 기본설정 관련 JavaScript
 */
document.addEventListener('DOMContentLoaded', function() {
    // 세션 메시지가 있으면 자동으로 모달 표시
    const successMessage = document.querySelector('.alert-success');
    if (successMessage && successMessage.textContent.trim()) {
        if (window.AppUtils && AppUtils.modal) {
            AppUtils.modal.success(successMessage.textContent.trim());
        }
        successMessage.style.display = 'none';
    }
});
