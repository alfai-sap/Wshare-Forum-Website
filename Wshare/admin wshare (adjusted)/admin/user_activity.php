<?php
include 'dashFunctions.php';

// Fetch user activities 
$activities = getUserActivities(50); // Last 50 activities
$activityStats = getActivityStats();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Activity Logs</title>
    <link rel="stylesheet" href="dashboard.css">
</head>
<body>
    <?php include 'sidebar.php'; ?>
    
    <div class="container">
        <h1>User Activity Logs</h1>

        <!-- Statistics Cards -->
        <div class="dashboard">
            <div class="box">
                <h2>Total Activities</h2>
                <p><?php echo $activityStats['total_activities']; ?></p>
            </div>
            <div class="box">
                <h2>Activities (24h)</h2>
                <p><?php echo $activityStats['activities_24h']; ?></p>
            </div>
            <div class="box">
                <h2>Activities (7d)</h2>
                <p><?php echo $activityStats['activities_7d']; ?></p>
            </div>
        </div>

        <!-- Activity Logs Table -->
        <div class="table-container">
            <table>
                <tr>
                    <th>Time</th>
                    <th>User</th>
                    <th>Activity Type</th>
                    <th>Description</th>
                    <th>IP Address</th>
                </tr>
                <?php foreach ($activities as $activity): ?>
                <tr>
                    <td><?php echo $activity['CreatedAt']; ?></td>
                    <td><?php echo htmlspecialchars($activity['Username']); ?></td>
                    <td><?php echo htmlspecialchars($activity['ActivityType']); ?></td>
                    <td><?php echo htmlspecialchars($activity['Description']); ?></td>
                    <td><?php echo htmlspecialchars($activity['IPAddress']); ?></td>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </div>
</body>
</html>