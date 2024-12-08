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
// Format data for charts
const userGrowthData = <?php echo json_encode(array_map(function($item) {
    return [
        'date' => $item['join_date'],
        'count' => (int)$item['new_users']
    ];
}, $dailyNewUsers)); ?>;

const postGrowthData = <?php echo json_encode(array_map(function($item) {
    return [
        'date' => $item['post_date'],
        'count' => (int)$item['new_posts']
    ];
}, $monthlyPosts)); ?>;

// User Activity Chart
const userActivityChart = new Chart(
    document.getElementById('userActivityChart'),
    {
        type: 'line',
        data: {
            labels: userGrowthData.map(row => row.date),
            datasets: [{
                label: 'New Users',
                data: userGrowthData.map(row => row.count),
                borderColor: '#007bff',
                tension: 0.1,
                fill: false
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                title: {
                    display: true,
                    text: 'User Growth Trend'
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    }
);

// Content Growth Chart
const contentGrowthChart = new Chart(
    document.getElementById('contentGrowthChart'),
    {
        type: 'bar',
        data: {
            labels: postGrowthData.map(row => row.date),
            datasets: [{
                label: 'New Posts',
                data: postGrowthData.map(row => row.count),
                backgroundColor: '#28a745',
                borderColor: '#28a745',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                title: {
                    display: true,
                    text: 'Post Growth Trend'
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    }
);

const heatmapData = <?php echo json_encode(getUserActivityHeatmapData()); ?>;

const userActivityHeatmap = new Chart(
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
                    const alpha = value / Math.max(...heatmapData.data);
                    return `rgba(255, 99, 132, ${alpha})`;
                },
                borderColor: 'rgba(255, 99, 132, 1)',
                borderWidth: 1,
                width: context => context.chart.chartArea.width / 24,
                height: context => context.chart.chartArea.height / 31
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                title: {
                    display: true,
                    text: 'Heatmap of User Activity'
                },
                tooltip: {
                    callbacks: {
                        title: context => `Hour: ${context[0].raw.x}, Day: ${context[0].raw.y}`,
                        label: context => `Activity Count: ${context.raw.v}`
                    }
                }
            },
            scales: {
                x: {
                    type: 'linear',
                    position: 'bottom',
                    ticks: {
                        stepSize: 1,
                        callback: value => `${value}:00`
                    }
                },
                y: {
                    type: 'linear',
                    ticks: {
                        stepSize: 1,
                        callback: value => `Day ${value}`
                    }
                }
            }
        }
    }
);
</script>
</body>
</html>
