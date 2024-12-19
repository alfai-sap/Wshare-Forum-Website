<?php
include 'dashFunctions.php'; // Include your database functions

// Fetch the analytics data from your functions
$totalUsers = getTotalUsers();
$newUsersLastMonth = getNewUsersLastMonth();
$mostActiveUsersByPosts = getMostActiveUsersByPosts();
$mostActiveUsersByComments = getMostActiveUsersByComments();
$totalPosts = getTotalPosts();
$weeklyPosts = getPostsLastWeek();
$mostLikedPosts = getMostLikedPosts();
$totalComments = getTotalComments();
$mostCommentedPosts = getMostCommentedPosts();
$topFollowedUsers = getTopFollowedUsers();
$topFollowers = getTopFollowers();
$dailyNewUsers = getUserGrowth();
$monthlyPosts = getPostGrowth();
$topUsersByTimeSpent = getTotalTimeSpentByUsers();
$dailyNewUsers = getUserGrowth();
$monthlyPosts = getPostGrowth();
// $recentAdminActivities = getRecentAdminActivities();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analytics Dashboard</title>
    <link rel="stylesheet" href="dashboard.css?v=<?php echo time(); ?>">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-chart-matrix@1.0.0"></script>
</head>
<body>

<script>
    // Remove dark mode if enabled when accessing admin pages
    if (localStorage.getItem('darkMode') === 'enabled') {
        localStorage.setItem('darkMode', 'disabled');
        document.body.classList.remove('dark-mode');
    }
</script>

<?php include 'sidebar.php'; ?>
<h1>Wshare Analytics</h1>

<div class="container">
<!-- Analytics Summary Section -->
<div class="dashboard">
    <div class="box">
        <h2>Total Users</h2>
        <p><?php echo $totalUsers; ?></p>
    </div>
    <div class="box">
        <h2>New Users (Last Month)</h2>
        <p><?php echo $newUsersLastMonth; ?></p>
    </div>
    <div class="box">
        <h2>Total Posts</h2>
        <p><?php echo $totalPosts; ?></p>
    </div>
    <div class="box">
        <h2>Weekly Posts</h2>
        <p><?php echo $weeklyPosts; ?></p>
    </div>
    <div class="box">
        <h2>Total Comments</h2>
        <p><?php echo $totalComments; ?></p>
    </div>

    <div class="box">
        <h2>Active Users Today</h2>
        <p><?php echo getActiveUsersToday(); ?></p>
    </div>
    <div class="box">
        <h2>Posts Today</h2>
        <p><?php echo getPostsToday(); ?></p>
    </div>
    <div class="box">
        <h2>Reports Pending</h2>
        <p><?php echo getPendingReports(); ?></p>
    </div>
    <div class="box">
        <h2>System Health</h2>
        <p><?php echo getSystemHealth(); ?></p>
    </div>
    <div class="box">
        <h2>User Retention Rate</h2>
        <p><?php echo getUserRetentionRate(); ?>%</p>
    </div>
    <div class="box">
        <h2>Average Engagement Time</h2>
        <p></p><?php echo getAverageEngagementTime(); ?> minutes</p>
    </div>
</div>

<!-- Charts Section -->
<div class="charts-container">
    <div class="chart-section">
        <h2>User Growth Trend</h2>
        <canvas id="userActivityChart"></canvas>
    </div>
    <div class="chart-section">
        <h2>Post Growth Trend</h2>
        <canvas id="contentGrowthChart"></canvas>
    </div>
    <div class="chart-section">
        <h2>Heatmap of User Activity</h2>
        <canvas id="userActivityHeatmap"></canvas>
    </div>
</div>

<!-- Bento Box Style for Tables -->
<div class="table-container">
    
    <!-- Most Active Users by Posts -->
    <div class="table-box">
        <h2>Most Active Users by Posts</h2>
        <table>
            <tr><th>Username</th><th>Post Count</th></tr>
            <?php foreach ($mostActiveUsersByPosts as $user) { ?>
                <tr><td><?php echo $user['Username']; ?></td><td><?php echo $user['post_count']; ?></td></tr>
            <?php } ?>
        </table>
    </div>

    <!-- Most Active Users by Comments -->
    <div class="table-box">
        <h2>Most Active Users by Comments</h2>
        <table>
            <tr><th>Username</th><th>Comment Count</th></tr>
            <?php foreach ($mostActiveUsersByComments as $user) { ?>
                <tr><td><?php echo $user['Username']; ?></td><td><?php echo $user['comment_count']; ?></td></tr>
            <?php } ?>
        </table>
    </div>

    <!-- Most Liked Posts -->
    <div class="table-box">
        <h2>Most Liked Posts</h2>
        <table>
            <tr><th>Title</th><th>Likes</th></tr>
            <?php foreach ($mostLikedPosts as $post) { ?>
                <tr><td><?php echo $post['Title']; ?></td><td><?php echo $post['like_count']; ?></td></tr>
            <?php } ?>
        </table>
    </div>

    <!-- Most Commented Posts -->
    <div class="table-box">
        <h2>Most Commented Posts</h2>
        <table>
            <tr><th>Title</th><th>Comments</th></tr>
            <?php foreach ($mostCommentedPosts as $post) { ?>
                <tr><td><?php echo $post['Title']; ?></td><td><?php echo $post['comment_count']; ?></td></tr>
            <?php } ?>
        </table>
    </div>

    <!-- Top Followed Users -->
    <div class="table-box">
        <h2>Top Followed Users</h2>
        <table>
            <tr><th>Username</th><th>Followers</th></tr>
            <?php foreach ($topFollowedUsers as $user) { ?>
                <tr><td><?php echo $user['Username']; ?></td><td><?php echo $user['followers_count']; ?></td></tr>
            <?php } ?>
        </table>
    </div>

    <!-- Top Followers (Following the Most) -->
    <div class="table-box">
        <h2>Top Followers (Following the Most)</h2>
        <table>
            <tr><th>Username</th><th>Following Count</th></tr>
            <?php foreach ($topFollowers as $user) { ?>
                <tr><td><?php echo $user['Username']; ?></td><td><?php echo $user['following_count']; ?></td></tr>
            <?php } ?>
        </table>
    </div>

    <!-- Most Time Spent -->
    <div class="table-box">
        <h2>Most Time Spent</h2>
        <table>
            <tr>
                <th>Username</th>
                <th>Total Time Spent</th>
                <th>Time Spent Today</th>
                <th>Time Spent This Week</th>
                <th>Time Spent This Month</th>
            </tr>
            <?php foreach ($topUsersByTimeSpent as $user) { ?>
                <tr>
                    <td><?php echo $user['Username']; ?></td>
                    <td><?php echo gmdate("H:i:s", $user['total_time_spent']); ?></td>
                    <td><?php echo gmdate("H:i:s", $user['time_spent_today']); ?></td>
                    <td><?php echo gmdate("H:i:s", $user['time_spent_week']); ?></td>
                    <td><?php echo gmdate("H:i:s", $user['time_spent_month']); ?></td>
                </tr>
            <?php } ?>
        </table>
    </div>

</div>
</div>

<script>
// Chart theme colors
const chartColors = {
    primary: '#4e73df',
    success: '#1cc88a',
    info: '#36b9cc',
    warning: '#f6c23e',
    danger: '#e74a3b',
    secondary: '#858796',
    gridLines: '#eaecf4',
    text: '#5a5c69'
};

// Enhanced gradient backgrounds
const primaryGradient = document.getElementById('userActivityChart').getContext('2d').createLinearGradient(0, 0, 0, 400);
primaryGradient.addColorStop(0, 'rgba(78, 115, 223, 0.3)');
primaryGradient.addColorStop(1, 'rgba(78, 115, 223, 0)');

const successGradient = document.getElementById('contentGrowthChart').getContext('2d').createLinearGradient(0, 0, 0, 400);
successGradient.addColorStop(0, 'rgba(28, 200, 138, 0.3)');
successGradient.addColorStop(1, 'rgba(28, 200, 138, 0)');

// Chart.js global defaults
Chart.defaults.font.family = "'Nunito', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif";
Chart.defaults.font.size = 12;
Chart.defaults.plugins.tooltip.padding = 10;
Chart.defaults.plugins.tooltip.backgroundColor = 'rgba(0, 0, 0, 0.8)';
Chart.defaults.plugins.tooltip.titleColor = '#ffffff';
Chart.defaults.plugins.tooltip.bodyColor = '#ffffff';
Chart.defaults.plugins.tooltip.borderColor = 'rgba(255, 255, 255, 0.1)';
Chart.defaults.plugins.tooltip.borderWidth = 1;
Chart.defaults.plugins.tooltip.cornerRadius = 6;

// Ensure we have valid data
const userGrowthData = <?php echo json_encode($dailyNewUsers ?? []); ?>;
const postGrowthData = <?php echo json_encode($monthlyPosts ?? []); ?>;

// User Activity Chart
new Chart(
    document.getElementById('userActivityChart'),
    {
        type: 'line',
        data: {
            labels: userGrowthData.map(row => row.join_date),
            datasets: [{
                label: 'New Users',
                data: userGrowthData.map(row => row.new_users),
                borderColor: chartColors.primary,
                backgroundColor: primaryGradient,
                tension: 0.4,
                fill: true,
                pointBackgroundColor: chartColors.primary,
                pointBorderColor: '#fff',
                pointHoverRadius: 6,
                pointHoverBackgroundColor: chartColors.primary,
                pointHoverBorderColor: '#fff',
                pointHoverBorderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        padding: 20,
                        usePointStyle: true
                    }
                },
                title: {
                    display: true,
                    text: 'User Growth Trend',
                    padding: 20,
                    font: {
                        size: 16,
                        weight: 'bold'
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: chartColors.gridLines,
                        borderDash: [2, 2]
                    },
                    title: {
                        display: true,
                        text: 'Number of Users',
                        font: {
                            weight: 'bold'
                        }
                    }
                },
                x: {
                    grid: {
                        display: false
                    },
                    title: {
                        display: true,
                        text: 'Date',
                        font: {
                            weight: 'bold'
                        }
                    }
                }
            },
            interaction: {
                intersect: false,
                mode: 'index'
            },
            animation: {
                duration: 1000,
                easing: 'easeInOutQuart'
            }
        }
    }
);

// Content Growth Chart
new Chart(
    document.getElementById('contentGrowthChart'),
    {
        type: 'bar',
        data: {
            labels: postGrowthData.map(row => row.post_date),
            datasets: [{
                label: 'New Posts',
                data: postGrowthData.map(row => row.new_posts),
                backgroundColor: successGradient,
                borderColor: chartColors.success,
                borderWidth: 2,
                borderRadius: 4,
                hoverBackgroundColor: chartColors.success
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        padding: 20,
                        usePointStyle: true
                    }
                },
                title: {
                    display: true,
                    text: 'Post Growth Trend',
                    padding: 20,
                    font: {
                        size: 16,
                        weight: 'bold'
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: chartColors.gridLines,
                        borderDash: [2, 2]
                    },
                    title: {
                        display: true,
                        text: 'Number of Posts',
                        font: {
                            weight: 'bold'
                        }
                    }
                },
                x: {
                    grid: {
                        display: false
                    },
                    title: {
                        display: true,
                        text: 'Date',
                        font: {
                            weight: 'bold'
                        }
                    }
                }
            },
            animation: {
                duration: 1000,
                easing: 'easeInOutQuart'
            }
        }
    }
);

// Enhanced Heatmap
const heatmapData = <?php echo json_encode(getUserActivityHeatmapData()); ?>;

new Chart(
    document.getElementById('userActivityHeatmap'),
    {
        type: 'matrix',
        data: {
            datasets: [{
                label: 'User Activity',
                data: heatmapData.data.map((count, index) => ({
                    x: new Date(heatmapData.labels[index]).getHours(),
                    y: new Date(heatmapData.labels[index]).getDate(),
                    v: count
                })),
                backgroundColor: context => {
                    const value = context.dataset.data[context.dataIndex].v;
                    const maxValue = Math.max(...heatmapData.data);
                    const alpha = value / maxValue;
                    return `rgba(78, 115, 223, ${alpha})`;
                },
                borderColor: '#ffffff',
                borderWidth: 1,
                width: ({ chart }) => (chart.chartArea || {}).width / 24 - 1,
                height: ({ chart }) => (chart.chartArea || {}).height / 31 - 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        title: context => {
                            const hour = context[0].raw.x;
                            const day = context[0].raw.y;
                            return `Hour: ${hour}:00, Day ${day}`;
                        },
                        label: context => `Activity: ${context.raw.v}`
                    }
                }
            },
            scales: {
                x: {
                    type: 'linear',
                    position: 'bottom',
                    min: 0,
                    max: 23,
                    ticks: {
                        stepSize: 1,
                        callback: value => `${value}:00`
                    },
                    grid: {
                        display: false
                    }
                },
                y: {
                    type: 'linear',
                    min: 1,
                    max: 31,
                    ticks: {
                        stepSize: 1
                    },
                    grid: {
                        display: false
                    }
                }
            }
        }
    }
);
</script>
</body>
</html>
