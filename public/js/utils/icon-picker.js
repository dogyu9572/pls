// Font Awesome 아이콘 선택기
document.addEventListener('DOMContentLoaded', function() {
    const iconInput = document.getElementById('icon');
    const iconPreview = document.getElementById('icon-preview');
    const iconPickerContainer = document.getElementById('icon-picker-container');
    const iconPickerSearch = document.getElementById('icon-search');
    const iconPickerList = document.getElementById('icon-list');
    const showPickerBtn = document.getElementById('show-icon-picker');

    // Font Awesome 아이콘 모음 (자주 사용하는 일반적인 아이콘들)
    const commonIcons = [
        'fa-home', 'fa-user', 'fa-users', 'fa-cog', 'fa-gear',
        'fa-wrench', 'fa-dashboard', 'fa-tachometer-alt', 'fa-chart-bar',
        'fa-chart-line', 'fa-envelope', 'fa-bell', 'fa-calendar',
        'fa-file', 'fa-folder', 'fa-image', 'fa-video', 'fa-music',
        'fa-cart-shopping', 'fa-money-bill', 'fa-credit-card', 'fa-tag',
        'fa-bookmark', 'fa-star', 'fa-heart', 'fa-check', 'fa-times',
        'fa-download', 'fa-upload', 'fa-sync', 'fa-redo', 'fa-undo',
        'fa-edit', 'fa-pencil', 'fa-trash', 'fa-plus', 'fa-minus',
        'fa-search', 'fa-eye', 'fa-eye-slash', 'fa-lock', 'fa-unlock',
        'fa-sign-in-alt', 'fa-sign-out-alt', 'fa-power-off', 'fa-cogs',
        'fa-list', 'fa-table', 'fa-th', 'fa-th-large', 'fa-comment',
        'fa-comments', 'fa-paper-plane', 'fa-reply', 'fa-share',
        'fa-thumbs-up', 'fa-thumbs-down', 'fa-question', 'fa-info',
        'fa-exclamation', 'fa-warning', 'fa-bell', 'fa-rss', 'fa-globe',
        'fa-link', 'fa-unlink', 'fa-external-link', 'fa-phone',
        'fa-mobile', 'fa-tablet', 'fa-laptop', 'fa-desktop', 'fa-server',
        'fa-database', 'fa-cloud', 'fa-wifi', 'fa-bluetooth', 'fa-map',
        'fa-map-marker', 'fa-location-dot', 'fa-compass', 'fa-address-book',
        'fa-address-card', 'fa-id-badge', 'fa-id-card', 'fa-clipboard'
    ];

    // 아이콘 스타일 접두사 (solid)
    const iconStylePrefix = 'fas';

    // 초기 아이콘 미리보기 업데이트
    function updateIconPreview() {
        if (iconInput && iconPreview) {
            if (iconInput.value) {
                const iconClass = iconInput.value.startsWith('fa-') ? iconInput.value : `fa-${iconInput.value}`;
                iconPreview.innerHTML = `<i class="${iconStylePrefix} ${iconClass}"></i>`;
                iconPreview.style.display = 'flex';
            } else {
                iconPreview.style.display = 'none';
            }
        }
    }

    // 아이콘 선택기 초기화
    function initializeIconPicker() {
        if (!iconPickerList) return;

        iconPickerList.innerHTML = '';
        commonIcons.forEach(icon => {
            const iconElement = document.createElement('div');
            iconElement.className = 'icon-item';
            iconElement.innerHTML = `<i class="${iconStylePrefix} ${icon}"></i>`;
            iconElement.dataset.icon = icon;

            iconElement.addEventListener('click', function() {
                iconInput.value = this.dataset.icon;
                updateIconPreview();
                iconPickerContainer.style.display = 'none';
            });

            iconPickerList.appendChild(iconElement);
        });
    }

    // 아이콘 필터링
    function filterIcons(query) {
        if (!iconPickerList) return;

        const filter = query.toLowerCase();
        const iconItems = iconPickerList.getElementsByClassName('icon-item');

        for (let i = 0; i < iconItems.length; i++) {
            const iconName = iconItems[i].dataset.icon.toLowerCase();
            if (iconName.includes(filter)) {
                iconItems[i].style.display = '';
            } else {
                iconItems[i].style.display = 'none';
            }
        }
    }

    // 이벤트 리스너 설정
    if (showPickerBtn) {
        showPickerBtn.addEventListener('click', function(e) {
            e.preventDefault();
            if (iconPickerContainer.style.display === 'block') {
                iconPickerContainer.style.display = 'none';
            } else {
                iconPickerContainer.style.display = 'block';
                initializeIconPicker();
            }
        });
    }

    if (iconPickerSearch) {
        iconPickerSearch.addEventListener('input', function() {
            filterIcons(this.value);
        });
    }

    // 아이콘 입력 필드 변경 시 미리보기 업데이트
    if (iconInput) {
        iconInput.addEventListener('input', updateIconPreview);
        // 초기 로드 시 미리보기 업데이트
        updateIconPreview();
    }

    // 외부 클릭 시 선택기 닫기
    document.addEventListener('click', function(e) {
        if (iconPickerContainer &&
            !iconPickerContainer.contains(e.target) &&
            e.target !== showPickerBtn) {
            iconPickerContainer.style.display = 'none';
        }
    });

    // 빠른 초기화 시도 - 페이지 로드 즉시 미리보기 업데이트
    updateIconPreview();
});
