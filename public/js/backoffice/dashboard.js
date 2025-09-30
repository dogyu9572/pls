// 백오피스 대시보드 JavaScript

// DOM 로드 완료 후 실행
document.addEventListener('DOMContentLoaded', function() {
    // 대시보드 초기화
    initDashboard();
    
    // 실시간 데이터 업데이트
    initRealTimeUpdates();
    
    // 차트 초기화 (필요시)
    initCharts();
    
    // 이벤트 리스너 등록
    initEventListeners();
});

// 대시보드 초기화
function initDashboard() {
    
    // 통계 카드 애니메이션
    animateStatCards();
    
    // 테이블 정렬 기능
    initTableSorting();
    
    // 검색 기능
    initSearchFunctionality();
}

// 통계 카드 애니메이션
function animateStatCards() {
    const statCards = document.querySelectorAll('.stat-card');
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, {
        threshold: 0.1
    });
    
    statCards.forEach(card => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = 'all 0.6s ease';
        observer.observe(card);
    });
}

// 테이블 정렬 기능
function initTableSorting() {
    const tables = document.querySelectorAll('.dashboard-table');
    
    tables.forEach(table => {
        const headers = table.querySelectorAll('th[data-sortable]');
        
        headers.forEach(header => {
            header.style.cursor = 'pointer';
            header.addEventListener('click', function() {
                sortTable(table, this);
            });
            
            // 정렬 아이콘 추가
            if (!header.querySelector('.sort-icon')) {
                const icon = document.createElement('i');
                icon.className = 'fas fa-sort sort-icon';
                icon.style.marginLeft = '0.5rem';
                icon.style.opacity = '0.5';
                header.appendChild(icon);
            }
        });
    });
}

// 테이블 정렬 함수
function sortTable(table, header) {
    const tbody = table.querySelector('tbody');
    const rows = Array.from(tbody.querySelectorAll('tr'));
    const columnIndex = Array.from(header.parentNode.children).indexOf(header);
    const isAscending = header.classList.contains('sort-asc');
    
    // 정렬 방향 토글
    header.classList.toggle('sort-asc', !isAscending);
    header.classList.toggle('sort-desc', isAscending);
    
    // 정렬 아이콘 업데이트
    const icon = header.querySelector('.sort-icon');
    if (icon) {
        icon.className = isAscending ? 'fas fa-sort-up sort-icon' : 'fas fa-sort-down sort-icon';
    }
    
    // 행 정렬
    rows.sort((a, b) => {
        const aValue = a.children[columnIndex].textContent.trim();
        const bValue = b.children[columnIndex].textContent.trim();
        
        // 숫자 정렬
        if (!isNaN(aValue) && !isNaN(bValue)) {
            return isAscending ? bValue - aValue : aValue - bValue;
        }
        
        // 문자열 정렬
        if (isAscending) {
            return bValue.localeCompare(aValue, 'ko');
        } else {
            return aValue.localeCompare(bValue, 'ko');
        }
    });
    
    // 정렬된 행을 테이블에 다시 추가
    rows.forEach(row => tbody.appendChild(row));
}

// 검색 기능
function initSearchFunctionality() {
    const searchInputs = document.querySelectorAll('.dashboard-search');
    
    searchInputs.forEach(input => {
        input.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const table = this.closest('.grid-item').querySelector('.dashboard-table');
            
            if (table) {
                filterTable(table, searchTerm);
            }
        });
    });
}

// 테이블 필터링
function filterTable(table, searchTerm) {
    const rows = table.querySelectorAll('tbody tr');
    
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        if (text.includes(searchTerm)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

// 실시간 데이터 업데이트
function initRealTimeUpdates() {
    // 30초마다 통계 데이터 업데이트
    setInterval(updateStatistics, 30000);
    
    // 1분마다 최근 활동 데이터 업데이트
    setInterval(updateRecentActivity, 60000);
}

// 통계 데이터 업데이트
function updateStatistics() {
    // AJAX로 통계 데이터 가져오기
    fetch('/backoffice/api/statistics', {
        headers: {
            'X-CSRF-TOKEN': AppUtils.getCsrfToken(),
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        updateStatCards(data);
    })
    .catch(error => {
        console.error('통계 데이터 업데이트 실패:', error);
    });
}

// 통계 카드 업데이트
function updateStatCards(data) {
    if (data.users) {
        updateStatCard('stat-users', data.users);
    }
    if (data.boards) {
        updateStatCard('stat-boards', data.boards);
    }
    if (data.posts) {
        updateStatCard('stat-posts', data.posts);
    }
    if (data.comments) {
        updateStatCard('stat-comments', data.comments);
    }
}

// 개별 통계 카드 업데이트
function updateStatCard(className, data) {
    const card = document.querySelector(`.${className}`);
    if (card) {
        const numberElement = card.querySelector('.stat-number');
        if (numberElement) {
            // 숫자 애니메이션
            animateNumber(numberElement, parseInt(numberElement.textContent), data.count);
        }
        

    }
}

// 숫자 애니메이션
function animateNumber(element, start, end) {
    const duration = 1000;
    const startTime = performance.now();
    
    function updateNumber(currentTime) {
        const elapsed = currentTime - startTime;
        const progress = Math.min(elapsed / duration, 1);
        
        const current = Math.floor(start + (end - start) * progress);
        element.textContent = current;
        
        if (progress < 1) {
            requestAnimationFrame(updateNumber);
        }
    }
    
    requestAnimationFrame(updateNumber);
}



// 최근 활동 데이터 업데이트
function updateRecentActivity() {
    // AJAX로 최근 활동 데이터 가져오기
    fetch('/backoffice/api/recent-activity', {
        headers: {
            'X-CSRF-TOKEN': AppUtils.getCsrfToken(),
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        updateActivityTables(data);
    })
    .catch(error => {
        console.error('활동 데이터 업데이트 실패:', error);
    });
}

// 활동 테이블 업데이트
function updateActivityTables(data) {
    // 최근 게시글 테이블 업데이트
    if (data.recentPosts) {
        updateRecentPostsTable(data.recentPosts);
    }
    
    // 최근 사용자 테이블 업데이트
    if (data.recentUsers) {
        updateRecentUsersTable(data.recentUsers);
    }
}

// 최근 게시글 테이블 업데이트
function updateRecentPostsTable(posts) {
    const table = document.querySelector('.grid-item:has(.dashboard-table) tbody');
    if (!table) return;
    
    // 기존 행 제거 (헤더 제외)
    const existingRows = table.querySelectorAll('tr');
    existingRows.forEach(row => row.remove());
    
    // 새 데이터로 행 생성
    posts.forEach(post => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${post.title}</td>
            <td>${post.author_name}</td>
            <td>${post.created_at}</td>
            <td>${post.board_name}</td>
            <td>
                <a href="/backoffice/posts/${post.id}/edit" class="table-action table-action-edit">
                    <i class="fas fa-edit"></i>
                </a>
            </td>
        `;
        table.appendChild(row);
    });
}

// 최근 사용자 테이블 업데이트
function updateRecentUsersTable(users) {
    const table = document.querySelector('.grid-item:has(.dashboard-table) tbody');
    if (!table) return;
    
    // 기존 행 제거 (헤더 제외)
    const existingRows = table.querySelectorAll('tr');
    existingRows.forEach(row => row.remove());
    
    // 새 데이터로 행 생성
    users.forEach(user => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${user.name}</td>
            <td>${user.email}</td>
            <td>${user.created_at}</td>
            <td><span class="table-badge badge-${user.role === 'admin' ? 'danger' : 'info'}">${user.role}</span></td>
            <td>
                <a href="/backoffice/users/${user.id}/edit" class="table-action table-action-edit">
                    <i class="fas fa-user-edit"></i>
                </a>
            </td>
        `;
        table.appendChild(row);
    });
}

// 차트 초기화
function initCharts() {
    // Chart.js가 로드되었는지 확인
    if (typeof Chart === 'undefined') {
        console.error('Chart.js가 로드되지 않았습니다.');
        return;
    }
    
    try {
        initVisitorChart();
    } catch (error) {
        console.error('차트 초기화 중 오류 발생:', error);
    }
}

// 방문객 통계 차트 초기화
function initVisitorChart() {
    const ctx = document.getElementById('visitorChart');
    if (!ctx) return;

    // API에서 실제 데이터 가져오기
    fetch('/backoffice/api/statistics', {
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        // 데이터 유효성 검사
        if (!data.daily_stats || !data.daily_stats.labels) {
            createDefaultChart(ctx);
            return;
        }
        
        if (!data.monthly_stats || !data.monthly_stats.labels) {
            createDefaultChart(ctx);
            return;
        }
        
        const chartData = {
            daily: {
                labels: data.daily_stats.labels,
                datasets: [
                    {
                        label: '일별 방문객',
                        data: data.daily_stats.data,
                        borderColor: '#667eea',
                        backgroundColor: 'rgba(102, 126, 234, 0.1)',
                        tension: 0.4,
                        fill: true,
                        pointBackgroundColor: '#667eea',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2
                    }
                ]
            },
            monthly: {
                labels: data.monthly_stats.labels,
                datasets: [
                    {
                        label: '월별 방문객',
                        data: data.monthly_stats.data,
                        borderColor: '#f093fb',
                        backgroundColor: 'rgba(240, 147, 251, 0.1)',
                        tension: 0.4,
                        fill: true,
                        pointBackgroundColor: '#f093fb',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2
                    }
                ]
            }
        };
        
        createChart(ctx, chartData);
    })
    .catch(error => {
        console.error('방문객 통계 데이터 로드 실패:', error);
        createDefaultChart(ctx);
    });
}

// 차트 생성 함수
function createChart(ctx, chartData) {
    if (!ctx) return;

    // 차트 생성 (기본은 일별)
    const chart = new Chart(ctx, {
        type: 'line',
        data: chartData.daily,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: '#f1f3f4'
                    },
                    ticks: {
                        font: {
                            size: 11
                        }
                    }
                },
                x: {
                    grid: {
                        color: '#f1f3f4'
                    },
                    ticks: {
                        font: {
                            size: 11
                        }
                    }
                }
            },
            elements: {
                point: {
                    radius: 4,
                    hoverRadius: 6
                }
            }
        }
    });

    // 타입 변경 버튼 이벤트
    const chartContainer = ctx.closest('.grid-item');
    chartContainer.querySelectorAll('.chart-type-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const type = this.dataset.type;
            
            // 활성 버튼 변경
            chartContainer.querySelectorAll('.chart-type-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            
            // 차트 데이터 업데이트
            chart.data = chartData[type];
            chart.update();
        });
    });
}

// 기본 차트 생성 함수
function createDefaultChart(ctx) {
    if (!ctx) return;
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: [],
            datasets: [{
                label: '데이터 없음',
                data: [],
                borderColor: '#ccc',
                backgroundColor: 'rgba(204, 204, 204, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: '#f1f3f4'
                    }
                },
                x: {
                    grid: {
                        color: '#f1f3f4'
                    }
                }
            }
        }
    });
}

// 이벤트 리스너 등록
function initEventListeners() {
    // 새로고침 버튼
    const refreshButtons = document.querySelectorAll('.refresh-btn');
    refreshButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            refreshDashboard();
        });
    });
    
    // 내보내기 버튼
    const exportButtons = document.querySelectorAll('.export-btn');
    exportButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            exportDashboardData();
        });
    });
}

// 대시보드 새로고침
function refreshDashboard() {
    AppUtils.showLoading();
    
    // 페이지 새로고침
    setTimeout(() => {
        window.location.reload();
    }, 500);
}

// 대시보드 데이터 내보내기
function exportDashboardData() {
    AppUtils.showLoading();
    
    // AJAX로 데이터 내보내기 요청
    fetch('/backoffice/export/dashboard', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': AppUtils.getCsrfToken(),
            'Accept': 'application/json'
        }
    })
    .then(response => response.blob())
    .then(blob => {
        // 파일 다운로드
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = `dashboard-${new Date().toISOString().split('T')[0]}.xlsx`;
        document.body.appendChild(a);
        a.click();
        window.URL.revokeObjectURL(url);
        document.body.removeChild(a);
        
        AppUtils.hideLoading();
        AppUtils.showSuccess('대시보드 데이터가 성공적으로 내보내졌습니다.');
    })
    .catch(error => {
        AppUtils.hideLoading();
        AppUtils.showError('데이터 내보내기에 실패했습니다.');
        console.error('내보내기 실패:', error);
    });
}

// 유틸리티 함수들
const DashboardUtils = {
    // 날짜 포맷팅
    formatDate: function(dateString) {
        const date = new Date(dateString);
        return date.toLocaleDateString('ko-KR', {
            year: 'numeric',
            month: '2-digit',
            day: '2-digit',
            hour: '2-digit',
            minute: '2-digit'
        });
    },
    
    // 숫자 포맷팅
    formatNumber: function(number) {
        return new Intl.NumberFormat('ko-KR').format(number);
    },
    
    // 파일 크기 포맷팅
    formatFileSize: function(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }
};

// 전역 객체에 유틸리티 추가
window.DashboardUtils = DashboardUtils;
