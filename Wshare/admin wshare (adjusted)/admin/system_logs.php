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
    <link rel="stylesheet" href="dashboard.css?v=<?php echo time(); ?>">
</head>
<body>
    <?php include 'sidebar.php'; ?>
    
    <div class="container">
        <h1>System Logs</h1>

        <!-- System Health Status -->
        <div class="system-health-card">
            <h2>System Status: <span class="status-<?php echo strtolower($systemHealth); ?>"><?php echo $systemHealth; ?></span></h2>
        </div>

        <!-- Filter Controls -->
        <div class="filter-section">
            <form method="GET" class="filter-form">
                <div class="filter-group">
                    <label for="days">Time Range:</label>
                    <select name="days" id="days" class="filter-select">
                        <option value="1" <?php echo $days == 1 ? 'selected' : ''; ?>>Last 24 Hours</option>
                        <option value="7" <?php echo $days == 7 ? 'selected' : ''; ?>>Last 7 Days</option>
                        <option value="30" <?php echo $days == 30 ? 'selected' : ''; ?>>Last 30 Days</option>
                    </select>
                </div>

                <div class="filter-group">
                    <label for="user_id">User ID:</label>
                    <input type="number" name="user_id" id="user_id" 
                           value="<?php echo htmlspecialchars($userID); ?>" 
                           placeholder="Enter User ID"
                           class="filter-input">
                </div>

                <div class="filter-group">
                    <label for="action_type">Action Type:</label>
                    <select name="action_type" id="action_type" class="filter-select">
                        <option value="">All Actions</option>
                        <option value="login" <?php echo $actionType == 'login' ? 'selected' : ''; ?>>Login</option>
                        <option value="post" <?php echo $actionType == 'post' ? 'selected' : ''; ?>>Post</option>
                    </select>
                </div>

                <button type="submit" class="filter-button">Apply Filters</button>
                <a href="system_logs.php" class="reset-button">Reset</a>
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

    .system-health-card {
        background: white;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        margin-bottom: 25px;
        text-align: center;
    }

    .filter-section {
        background: white;
        padding: 25px;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        margin-bottom: 25px;
    }

    .filter-form {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        align-items: flex-end;
    }

    .filter-group {
        flex: 1;
        min-width: 200px;
    }

    .filter-group label {
        display: block;
        margin-bottom: 8px;
        color: #4e73df;
        font-weight: 500;
        font-size: 0.9rem;
    }

    .filter-select,
    .filter-input {
        width: 100%;
        padding: 10px 15px;
        border: 2px solid #e1e5ee;
        border-radius: 8px;
        font-size: 0.95rem;
        color: #495057;
        transition: all 0.3s ease;
        background-color: #f8f9fa;
    }

    .filter-select {
        appearance: none;
        background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right 15px center;
        background-size: 15px;
        padding-right: 45px;
    }

    .filter-select:focus,
    .filter-input:focus {
        border-color: #4e73df;
        background-color: #fff;
        outline: none;
        box-shadow: 0 0 0 3px rgba(78, 115, 223, 0.1);
    }

    .filter-button,
    .reset-button {
        padding: 10px 20px;
        border-radius: 8px;
        font-size: 0.95rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .filter-button {
        background-color: #4e73df;
        color: white;
        border: none;
        min-width: 120px;
    }

    .filter-button:hover {
        background-color: #2e59d9;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }

    .reset-button {
        background-color: #f8f9fa;
        color: #4e73df;
        border: 2px solid #4e73df;
        text-decoration: none;
        text-align: center;
        min-width: 120px;
    }

    .reset-button:hover {
        background-color: #4e73df;
        color: white;
        text-decoration: none;
    }

    @media (max-width: 768px) {
        .filter-form {
            flex-direction: column;
            gap: 15px;
        }

        .filter-group {
            width: 100%;
        }

        .filter-button,
        .reset-button {
            width: 100%;
        }
    }
    </style>
</body>
</html>