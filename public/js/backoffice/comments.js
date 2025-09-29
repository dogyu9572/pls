/**
 * 댓글 관련 JavaScript
 */
document.addEventListener('DOMContentLoaded', function() {
    // 비밀번호 폼 토글
    const passwordFormToggles = document.querySelectorAll('.show-password-form');
    if (passwordFormToggles.length > 0) {
        passwordFormToggles.forEach(function(toggle) {
            toggle.addEventListener('click', function(e) {
                e.preventDefault();
                const commentId = this.getAttribute('data-comment-id');
                const passwordForm = document.getElementById('password-form-' + commentId);

                if (passwordForm) {
                    passwordForm.classList.toggle('active');
                }
            });
        });
    }

    // 답글 폼 토글 기능
    const replyButtons = document.querySelectorAll('[data-toggle="reply-form"]');
    if (replyButtons.length > 0) {
        replyButtons.forEach(function(button) {
            button.addEventListener('click', function() {
                const targetId = this.getAttribute('data-target');
                const targetForm = document.getElementById(targetId);

                if (targetForm) {
                    targetForm.classList.toggle('active');
                }
            });
        });
    }
});
