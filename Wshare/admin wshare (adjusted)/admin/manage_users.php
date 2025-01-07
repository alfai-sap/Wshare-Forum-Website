<?php
include 'dashFunctions.php';
session_start();

// Check if admin is logged in
checkAdminSession();

// Store admin ID in a variable for use throughout the page
$adminID = $_SESSION['admin_id'];

// Handle CRUD operations
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_user'])) {
        addUserDash($_POST['username'], $_POST['email'], $_POST['password']);
    } elseif (isset($_POST['update_user'])) {
        updateUserDash($_POST['user_id'], $_POST['username'], $_POST['email']);
    } elseif (isset($_POST['delete_user'])) {
        deleteUserDash($_POST['user_id']);
    }
}

// Handle ban submission
if (isset($_POST['ban_user'])) {
    $userID = $_POST['user_id'];
    $reason = $_POST['ban_reason'];
    $duration = $_POST['ban_duration'];
    
    // Use the stored admin ID
    if (banUser($userID, $adminID, $reason, $duration)) {
        $success = "User has been banned successfully.";
    } else {
        $error = "Failed to ban user.";
    }
}

// Handle unban
if (isset($_POST['unban_user'])) {
    $userID = $_POST['user_id'];
    if (unbanUser($userID)) {
        $success = "User has been unbanned successfully.";
        // Stay on the same page after unbanning
        header("Location: ".$_SERVER['PHP_SELF']);
        exit();
    } else {
        $error = "Failed to unban user.";
    }
}

// Fetch all users for display
$users = getAllUsersDash();

// Handle search query
$searchTerm = '';
if (isset($_POST['search'])) {
    $searchTerm = $_POST['search'];
}

// Fetch all users for display or filter based on search term
$users = getUsersBySearch($searchTerm);

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

if (isset($_GET['action']) && $_GET['action'] === 'ban' && isset($_GET['user_id'])) {
    $userId = intval($_GET['user_id']);
    $adminId = $_SESSION['admin_id'];
    $reason = "Violation of community guidelines"; // You can customize this or get it from a form
    $duration = 30; // Ban duration in days

    if (banUser($userId, $adminId, $reason, $duration)) {
        echo "User has been banned successfully.";
    } else {
        echo "Failed to ban the user.";
    }
}

$settings = getAllAdminSettings();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <link rel="stylesheet" href="dashboard.css?v=<?php echo time(); ?>">
    <style>
        .ban-modal {
            height: 500px;
            width: 800px;
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            z-index: 1000;
        }
        .modal-backdrop {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 999;
        }
        .ban-modal h2 {
            margin-bottom: 20px;
            color: #333;
        }
        .ban-modal .form-group {
            margin-bottom: 15px;
        }
        .ban-modal label {
            display: block;
            margin-bottom: 5px;
            color: #555;
        }
        .ban-modal select,
        .ban-modal textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .ban-modal textarea {
            resize: vertical;
            height: 100px;
        }
        .ban-modal button {
            margin-top: 10px;
        }
        .ban-history-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .ban-history-table th,
        .ban-history-table td {
            padding: 8px;
            border: 1px solid #ddd;
            text-align: left;
        }

        .ban-history-table th {
            background-color: #007bff;
        }

        .active-ban {
            color: #dc3545;
            font-weight: bold;
        }

        .expired-ban {
            color: #6c757d;
        }

        .btn-info, .btn-success, .btn-warning, .btn-danger, .btn-secondary {
            color: white;
            border: none;
            margin: 5px; /* reduced from 10 */
            padding: 4px 8px; /* reduced from 5px 10px */
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.9rem; /* added to reduce text size */
        }

        .btn-info {
            background-color: #17a2b8;
        }

        .btn-info:hover {
            background-color: #138496;
        }

        .btn-success {
            background-color: #28a745;
        }

        .btn-success:hover {
            background-color: #218838;
        }

        .btn-warning {
            background-color: #dc3545; /* Change to red */
            color: white; /* Change text color to white */
        }

        .btn-warning:hover {
            background-color: #c82333; /* Darker red on hover */
        }

        .btn-danger {
            background-color: #dc3545;
        }

        .btn-danger:hover {
            background-color: #c82333;
        }

        .btn-secondary {
            background-color: #6c757d;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
        }

        .user-button {
            padding: 4px 8px; /* reduced padding */
            min-width: 60px; /* reduced from default */
            font-size: 0.9rem;
            height: 28px; /* reduced height */
        }

        .action-button-group {
            gap: 4px; /* reduced gap between buttons */
        }

        .inline-form input[type="text"],
        .inline-form input[type="email"] {
            width: 140px; /* reduced from 180px */
            padding: 4px 8px;
            font-size: 0.85rem;
        }

        .inline-form button {
            margin-right: 5px;
        }

        .user-actions form {
            display: flex;
            flex-direction: column;
            gap: 4px;
            align-items: flex-start;
            width: 100%;
        }

        .user-actions input[type="text"],
        .user-actions input[type="email"] {
            width: 100%;
            padding: 4px 8px;
            margin-bottom: 4px;
            font-size: 0.85rem;
            border: 2px solid #e1e5ee;
            border-radius: 6px;
        }

        .action-button-group {
            margin-top: 4px;
            display: flex;
            gap: 4px;
        }

        .col-actions {
            min-width: 200px; /* Ensure enough space for the column layout */
        }
    </style>
</head>
<body>

<?php include 'sidebar.php'; ?>

<h1>Manage Users</h1>

<div class="container">

    <!-- Add User Form -->
    <div class="form-box">
        <h2>Add New User</h2>
        <form method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" name="add_user">Add User</button>
        </form>
    </div>

    <!-- Search Form -->
    <div class="search-box">
        <form method="POST">
            <input type="text" name="search" placeholder="Search users by username or email" value="<?php echo $searchTerm; ?>">
            <button type="submit">Search</button>
        </form>
    </div>


    <!-- Users Table -->
    <div class="table-container">
        <h2>All Users</h2>
        <table class="user-table">
            <tr>
                <th class="col-id">UserID</th>
                <th class="col-username">Username</th>
                <th class="col-email">Email</th>
                <th class="col-date">Date Joined</th>
                <th class="col-status">Status</th>
                <th class="col-actions">Actions</th>
                <th class="col-history">History</th>
                <th class="col-manage">Manage</th>
            </tr>
            <?php foreach ($users as $user) { 
                $banInfo = isUserBanned($user['UserID']);
                $banHistory = getUserBanHistory($user['UserID']);
            ?>
            <tr>
                <td><?php echo $user['UserID']; ?></td>
                <td><?php echo $user['Username']; ?></td>
                <td><?php echo $user['Email']; ?></td>
                <td><?php echo calculateTimeAgo($user['JoinedAt']); ?></td>
                <td>
                    <?php if ($banInfo): ?>
                        <span class="badge badge-danger">Banned until <?php echo date('Y-m-d', strtotime($banInfo['BanEnd'])); ?></span>
                    <?php else: ?>
                        <span class="badge badge-success">Active</span>
                    <?php endif; ?>
                </td>
                <td>
                    <div class="user-actions">
                        <form method="POST" class="inline-form">
                            <input type="text" name="username" value="<?php echo $user['Username']; ?>" required>
                            <input type="email" name="email" value="<?php echo $user['Email']; ?>" required>
                            <input type="hidden" name="user_id" value="<?php echo $user['UserID']; ?>">
                            <div class="action-button-group">
                                <button type="submit" name="update_user" class="user-button btn-update">Update</button>
                            </div>
                        </form>
                    </div>
                </td>
                <td>
                    <button onclick="showBanHistory(<?php echo htmlspecialchars(json_encode($banHistory)); ?>)" 
                            class="user-button btn-history">View (<?php echo count($banHistory); ?>)</button>
                </td>
                <td>
                    <div class="action-button-group">
                        <?php if ($banInfo): ?>
                            <form method="POST" onsubmit="return confirm('Are you sure you want to unban this user?');">
                                <input type="hidden" name="user_id" value="<?php echo $user['UserID']; ?>">
                                <button type="submit" name="unban_user" class="user-button btn-update">Unban</button>
                            </form>
                        <?php else: ?>
                            <button onclick="showBanModal(<?php echo $user['UserID']; ?>)" 
                                    class="user-button btn-ban">Ban</button>
                        <?php endif; ?>
                        <form method="POST" onsubmit="return confirm('Are you sure you want to delete this user?');">
                            <input type="hidden" name="user_id" value="<?php echo $user['UserID']; ?>">
                            <button type="submit" name="delete_user" class="user-button btn-delete">Delete</button>
                        </form>
                    </div>
                </td>
            </tr>
            <?php } ?>
        </table>
    </div>
</div>

<!-- Ban Modal -->
<div id="banModal" class="ban-modal">
    <h2>Ban User</h2>
    <form method="POST">
        <input type="hidden" name="user_id" id="banUserId">
        <div class="form-group">
            <label for="ban_duration">Ban Duration (days):</label>
            <select name="ban_duration" id="ban_duration" required>
                <option value="1">1 day</option>
                <option value="3">3 days</option>
                <option value="7">1 week</option>
                <option value="30">1 month</option>
                <option value="90">3 months</option>
                <option value="365">1 year</option>
                <option value="36500">Permanent</option>
            </select>
        </div>
        <div class="form-group">
            <label for="ban_reason">Reason:</label>
            <textarea name="ban_reason" id="ban_reason" required></textarea>
        </div>
        <button type="submit" name="ban_user" class="btn btn-danger">Ban User</button>
        <button type="button" onclick="hideBanModal()" class="btn btn-secondary">Cancel</button>
    </form>
</div>
<div id="modalBackdrop" class="modal-backdrop"></div>

<!-- Add Ban History Modal -->
<div id="banHistoryModal" class="ban-modal">
    <h2>Ban History</h2>
    <div id="banHistoryContent"></div>
    <button type="button" onclick="hideBanHistoryModal()" class="btn btn-secondary">Close</button>
</div>

<script>
function showBanModal(userId) {
    document.getElementById('banUserId').value = userId;
    document.getElementById('banModal').style.display = 'block';
    document.getElementById('modalBackdrop').style.display = 'block';
}

function hideBanModal() {
    document.getElementById('banModal').style.display = 'none';
    document.getElementById('modalBackdrop').style.display = 'none';
}

function showBanHistory(history) {
    const modal = document.getElementById('banHistoryModal');
    const content = document.getElementById('banHistoryContent');
    
    let html = '<table class="ban-history-table">';
    html += '<tr><th>Date</th><th>Duration</th><th>Reason</th><th>Banned By</th><th>Status</th></tr>';
    
    history.forEach(ban => {
        const banStart = new Date(ban.BanStart).toLocaleDateString();
        const banEnd = new Date(ban.BanEnd).toLocaleDateString();
        const isActive = ban.IsActive == 1 ? 'Active' : 'Expired';
        const statusClass = ban.IsActive == 1 ? 'active-ban' : 'expired-ban';
        
        html += `<tr>
            <td>${banStart}</td>
            <td>${banStart} to ${banEnd}</td>
            <td>${ban.BanReason}</td>
            <td>${ban.BannedByUsername}</td>
            <td class="${statusClass}">${isActive}</td>
        </tr>`;
    });
    
    html += '</table>';
    content.innerHTML = html;
    modal.style.display = 'block';
    document.getElementById('modalBackdrop').style.display = 'block';
}

function hideBanHistoryModal() {
    document.getElementById('banHistoryModal').style.display = 'none';
    document.getElementById('modalBackdrop').style.display = 'none';
}
</script>

</body>
</html>
