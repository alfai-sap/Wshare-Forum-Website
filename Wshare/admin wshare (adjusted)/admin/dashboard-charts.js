// Add new file: admin/js/dashboard-charts.js
document.addEventListener('DOMContentLoaded', function() {
    // User Activity Chart
    const userActivityCtx = document.getElementById('userActivityChart').getContext('2d');
    new Chart(userActivityCtx, {
        type: 'line',
        data: {
            labels: chartLabels,
            datasets: [{
                label: 'User Activity',
                data: userActivityData,
                borderColor: '#007bff',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });

    // Content Growth Chart
    const contentGrowthCtx = document.getElementById('contentGrowthChart').getContext('2d');
    new Chart(contentGrowthCtx, {
        type: 'bar',
        data: {
            labels: chartLabels,
            datasets: [{
                label: 'Posts',
                data: postsData,
                backgroundColor: '#28a745'
            }, {
                label: 'Comments',
                data: commentsData,
                backgroundColor: '#ffc107'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });
});