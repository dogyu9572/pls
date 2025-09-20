/**
 * 관리자 권한 설정 JavaScript
 */

document.addEventListener('DOMContentLoaded', function() {
    // 부모 메뉴 체크박스 이벤트 리스너
    const parentCheckboxes = document.querySelectorAll('.permission-item.parent-menu input[type="checkbox"]');
    
    parentCheckboxes.forEach(function(parentCheckbox) {
        parentCheckbox.addEventListener('change', function() {
            const category = this.closest('.permission-category');
            const childCheckboxes = category.querySelectorAll('.permission-item.child-menu input[type="checkbox"]');
            
            // 부모가 체크되면 모든 자식도 체크
            childCheckboxes.forEach(function(childCheckbox) {
                childCheckbox.checked = parentCheckbox.checked;
            });
        });
    });
    
    // 자식 메뉴 체크박스 이벤트 리스너
    const childCheckboxes = document.querySelectorAll('.permission-item.child-menu input[type="checkbox"]');
    
    childCheckboxes.forEach(function(childCheckbox) {
        childCheckbox.addEventListener('change', function() {
            const category = this.closest('.permission-category');
            const parentCheckbox = category.querySelector('.permission-item.parent-menu input[type="checkbox"]');
            const allChildCheckboxes = category.querySelectorAll('.permission-item.child-menu input[type="checkbox"]');
            const checkedChildCheckboxes = category.querySelectorAll('.permission-item.child-menu input[type="checkbox"]:checked');
            
            // 자식 중 하나라도 체크되면 부모도 체크
            if (checkedChildCheckboxes.length > 0) {
                parentCheckbox.checked = true;
            }
            // 모든 자식이 체크 해제되면 부모도 체크 해제
            else {
                parentCheckbox.checked = false;
            }
        });
    });
    
    // 전체 선택/해제 기능 (선택사항)
    const selectAllBtn = document.getElementById('select-all-permissions');
    const deselectAllBtn = document.getElementById('deselect-all-permissions');
    
    if (selectAllBtn) {
        selectAllBtn.addEventListener('click', function(e) {
            e.preventDefault();
            const allCheckboxes = document.querySelectorAll('.permission-item input[type="checkbox"]');
            allCheckboxes.forEach(function(checkbox) {
                checkbox.checked = true;
            });
        });
    }
    
    if (deselectAllBtn) {
        deselectAllBtn.addEventListener('click', function(e) {
            e.preventDefault();
            const allCheckboxes = document.querySelectorAll('.permission-item input[type="checkbox"]');
            allCheckboxes.forEach(function(checkbox) {
                checkbox.checked = false;
            });
        });
    }
});
