<?php
include 'dashFunctions.php';

$days = isset($_GET['days']) ? (int)$_GET['days'] : 7;
$userID = isset($_GET['user_id']) ? (int)$_GET['user_id'] : null;
$actionType = isset($_GET['action_type']) ? $_GET['action_type'] : null;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$logsPerPage = 20;
$offset = ($page - 1) * $logsPerPage;

$logs = getSystemLogs($days, $userID, $actionType, $logsPerPage, $offset);
$totalLogs = countSystemLogs($days, $userID, $actionType);
$totalPages = ceil($totalLogs / $logsPerPage);
$systemHealth = getSystemHealth();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Logs</title>
    <link rel="stylesheet" href="dashboard.css">
</head>
<body>
    <?php include 'sidebar.php'; ?>
    
    <div class="container">
        <h1>System Logs</h1>

        <!-- System Health Status -->
        <div class="system-health-status">
            <h2>System Status: <span class="status-<?php echo strtolower($systemHealth); ?>"><?php echo $systemHealth; ?></span></h2>
        </div>

        <!-- Filter Controls -->
        <div class="filter-controls">
            <form method="GET">
                <label for="days">Days:</label>
                <select name="days" id="days" onchange="this.form.submit()">
                    <option value="1" <?php echo $days == 1 ? 'selected' : ''; ?>>Last 24 Hours</option>
                    <option value="7" <?php echo $days == 7 ? 'selected' : ''; ?>>Last 7 Days</option>
                    <option value="30" <?php echo $days == 30 ? 'selected' : ''; ?>>Last 30 Days</option>
                </select>

                <label for="user_id">User:</label>
                <input type="number" name="user_id" id="user_id" value="<?php echo htmlspecialchars($userID); ?>" placeholder="User ID">

                <label for="action_type">Action:</label>
                <select name="action_type" id="action_type">
                    <option value="">All</option>
                    <option value="login" <?php echo $actionType == 'login' ? 'selected' : ''; ?>>Login</option>
                    <option value="post" <?php echo $actionType == 'post' ? 'selected' : ''; ?>>Post</option>
                    <!-- Add more action types as needed -->
                </select>

                <button type="submit">Filter</button>
            </form>
        </div>

        <!-- System Logs Table -->
        <div class="table-container">
            <table>
                <tr>
                    <th>Time</th>
                    <th>Action</th>
                    <th>Username</th>
                    <th>User ID</th>
                    <th>Details</th>
                </tr>
                <?php foreach ($logs as $log): ?>
                <tr>
                    <td><?php echo $log['timestamp']; ?></td>
                    <td><?php echo ucfirst($log['action']); ?></td>
                    <td><?php echo htmlspecialchars($log['Username']); ?></td>
                    <td><?php echo $log['UserID']; ?></td>
                    <td><?php echo htmlspecialchars($log['details'] ?? ''); ?></td>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>

        <!-- Pagination -->
        <div class="pagination">
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a href="?days=<?php echo $days; ?>&user_id=<?php echo htmlspecialchars($userID); ?>&action_type=<?php echo htmlspecialchars($actionType); ?>&page=<?php echo $i; ?>" class="<?php echo $i == $page ? 'active' : ''; ?>"><?php echo $i; ?></a>
            <?php endfor; ?>
        </div>
    </div>

    <style>
    .status-good { color: #28a745; }
    .status-warning { color: #ffc107; }
    .status-critical { color: #dc3545; }
    .filter-controls {
        margin-bottom: 20px;
    }
    .filter-controls label {
        margin-right: 10px;
    }
    .filter-controls select, .filter-controls input {
        margin-right: 20px;
    }
    .pagination {
        margin-top: 20px;
        text-align: center;
    }
    .pagination a {
        margin: 0 5px;
        padding: 5px 10px;
        background-color: #007bff;
        color: #fff;
        text-decoration: none;
        border-radius: 4px;
    }
    .pagination a.active {
        background-color: #0056b3;
    }
    </style>
</body>
</html>