/**
 * 백오피스 기본설정 관련 JavaScript
 */
document.addEventListener('DOMContentLoaded', function() {
    // 모달 관련 요소
    const alertModal = document.getElementById('alertModal');
    const modalTitle = document.getElementById('modalTitle');
    const modalMessage = document.getElementById('modalMessage');
    const closeModal = alertModal.querySelector('.close-modal');

    // 모달 표시 함수
    window.showAlertModal = function(title, message) {
        modalTitle.textContent = title;
        modalMessage.textContent = message;
        alertModal.style.display = 'block';
        
        // 2초 후 자동으로 닫기
        setTimeout(function() {
            alertModal.style.display = 'none';
        }, 2000);
    }

    // 모달 닫기
    closeModal.onclick = function() {
        alertModal.style.display = 'none';
    }

    // 모달 외부 클릭 시 닫기
    window.onclick = function(event) {
        if (event.target == alertModal) {
            alertModal.style.display = 'none';
        }
    }
    
    // ESC 키로 모달 닫기
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && alertModal.style.display === 'block') {
            alertModal.style.display = 'none';
        }
    });
});
