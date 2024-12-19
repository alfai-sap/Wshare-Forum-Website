<?php
require_once '../admin wshare (adjusted)/admin/dashFunctions.php';
require_once 'functions.php';
session_start();

if (!isset($_GET['id'])) {
    header('Location: community_page.php');
    exit();
}

$reportId = intval($_GET['id']);
$reportDetails = getReportedPostDetails($reportId);

if (!$reportDetails) {
    echo "Report not found.";
    exit();
}

$settings = getAllAdminSettings();
$reporteduserID = getUserIdByUsername($reportDetails['ReportedUsername']);

// Get the reported user's ID from the report details
$reportedUserId = null;
if ($reportDetails && $reportDetails['ReportedUsername']) {
    $reportedUserId = getUserIdByUsername($reportDetails['ReportedUsername']);
}

// Get community ID from the report details
$query = "SELECT cp.CommunityID 
          FROM community_posts cp 
          WHERE cp.PostID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $reportDetails['TargetID']);
$stmt->execute();
$result = $stmt->get_result();
$communityData = $result->fetch_assoc();
$communityID = $communityData['CommunityID'] ?? null;

if (!$communityID) {
    // Try to get community ID directly from the report if it exists
    $communityID = $reportDetails['CommunityID'] ?? null;
}

// Store the previous page URL in session if it's from community_page.php
if (isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], 'community_page.php') !== false) {
    $_SESSION['previous_community_page'] = $_SERVER['HTTP_REFERER'];
}

// Handle ban submission
if (isset($_POST['ban_user'])) {
    if (isset($reportedUserId) && filter_var($reportedUserId, FILTER_VALIDATE_INT)) {
        $userID = $reportedUserId;
        $reason = $_POST['ban_reason'];
        $duration = $_POST['ban_duration'];

        if (!$communityID) {
            $error = "Invalid community ID";
        } else if (isset($_SESSION['user_id']) && filter_var($_SESSION['user_id'], FILTER_VALIDATE_INT)) {
            $adminID = $_SESSION['user_id'];

            if (banUserFromCommunity($userID, $adminID, $communityID, $reason, $duration)) {
                $success = "User has been banned from this community successfully.";
            } else {
                $error = "Failed to ban user from community.";
            }
        } else {
            $error = "Invalid or missing admin ID.";
        }
    }
}

// Handle resolve submission
if (isset($_POST['resolve_report'])) {
    $reportId = intval($_POST['report_id']);
    if (updateReportStatus($reportId, 'resolved')) {
        $success = "Report has been resolved successfully.";
    } else {
        $error = "Failed to resolve the report.";
    }
}

// ...existing code...
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Community Report</title>
    <link rel="stylesheet" href="dashboard.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="./css/left-navbar.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="./css/navbar.css?v=<?php echo time(); ?>">
    <style>
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .report-details {
            margin-bottom: 20px;
        }
        .report-details p {
            margin: 10px 0;
        }
        .report-details strong {
            display: inline-block;
            width: 150px;
        }
        .evidence-section {
            margin-top: 20px;
        }
        .evidence-section img {
            max-width: 100%;
            height: auto;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-top: 10px;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            background-color: #f8f9fa;
        }

        .report-details strong {
            display: inline-block;
            width: 160px;
            color: #2c3e50;
            font-weight: 600;
        }

        .evidence-section {
            margin-top: 30px;
            background: #f8f9fa;
            padding: 25px;
            border-radius: 8px;
            border: 1px solid #e9ecef;
        }

        .evidence-section h2 {
            color: #2c3e50;
            margin-bottom: 20px;
            font-size: 1.5em;
        }

        .evidence-section img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .action-buttons {
            margin: 30px 0;
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
            justify-content: flex-start;
            align-items: center;
        }

        .btn {
            display: inline-block;
            padding: 12px 25px;
            font-weight: 500;
            text-decoration: none;
            border-radius: 6px;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            font-size: 1rem;
            margin: 5px;
        }

        .btn-primary {
            background-color: #3498db;
            color: white;
        }

        .btn-primary:hover {
            background-color: #2980b9;
            transform: translateY(-2px);
        }

        .btn-warning {
            background-color: #e74c3c;
            color: white;
        }

        .btn-warning:hover {
            background-color: #c0392b;
            transform: translateY(-2px);
        }

        .btn-success {
            background-color: #2ecc71;
            color: white;
        }

        .btn-success:hover {
            background-color: #27ae60;
            transform: translateY(-2px);
        }

        .btn-secondary {
            background-color: #95a5a6;
            color: white;
        }

        .btn-secondary:hover {
            background-color: #7f8c8d;
            transform: translateY(-2px);
        }

        .btn-back {
            background-color: #95a5a6;
            color: white;
        }

        .btn-back:hover {
            background-color: #7f8c8d;
        }

        .btn-ban {
            background-color: #e74c3c;
            color: white;
        }

        .btn-ban:hover {
            background-color: #c0392b;
        }

        /* Enhanced Modal Styling */
        .ban-modal {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            height: auto;
            min-height: 400px;
            width: 600px;
            background: #ffffff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.15);
            z-index: 1000;
        }

        .ban-modal h2 {
            color: #2c3e50;
            margin-bottom: 25px;
            padding-bottom: 10px;
            border-bottom: 2px solid #eee;
        }

        .ban-modal .form-group {
            margin-bottom: 20px;
        }

        .ban-modal label {
            color: #2c3e50;
            font-weight: 500;
            margin-bottom: 8px;
        }

        .ban-modal select,
        .ban-modal textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            transition: border-color 0.3s;
        }

        .ban-modal select:focus,
        .ban-modal textarea:focus {
            border-color: #3498db;
            outline: none;
        }

        .ban-modal textarea {
            min-height: 120px;
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

        /* Add these modal button styles */
        .btn-danger {
            background-color: #dc3545;
            color: white;
        }

        .btn-danger:hover {
            background-color: #c82333;
            transform: translateY(-2px);
        }

        .ban-modal .btn {
            margin: 10px 10px 10px 0;
            display: inline-block;
            min-width: 120px;
        }

        .ban-modal form {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>
    
    <div class="container">
        <h1>Report Details</h1>
        <div class="report-details">
            <p><strong>Report ID:</strong> <?php echo $reportDetails['ReportID']; ?></p>
            <p><strong>Report Type:</strong> <?php echo ucfirst($reportDetails['ReportType']); ?></p>
            <p><strong>Reporter:</strong> <?php echo htmlspecialchars($reportDetails['ReporterName']); ?></p>
            <p><strong>Reported User:</strong> <?php echo htmlspecialchars($reportDetails['ReportedUsername']); ?></p>
            <p><strong>Violation:</strong> <?php echo htmlspecialchars($reportDetails['Violation']); ?></p>
            <p><strong>Status:</strong> <?php echo ucfirst($reportDetails['Status']); ?></p>
            <p><strong>Created At:</strong> <?php echo calculateTimeAgo($reportDetails['CreatedAt']); ?></p>
            <p><strong>Post Title:</strong> <?php echo htmlspecialchars($reportDetails['Title']); ?></p>
            <p><strong>Post Content:</strong> <?php echo nl2br(htmlspecialchars($reportDetails['Content'])); ?></p>
        </div>
        <div class="evidence-section">
            <h2>Evidence</h2>
            <?php if (!empty($reportDetails['EvidencePhoto'])): ?>
                <img src="<?php echo htmlspecialchars($reportDetails['EvidencePhoto']); ?>" alt="Evidence Photo">
            <?php else: ?>
                <p>No evidence provided.</p>
            <?php endif; ?>
        </div>
        <div class="action-buttons">
            <?php if (isset($_SESSION['previous_community_page'])): ?>
                <a href="<?php echo $_SESSION['previous_community_page']; ?>" class="btn btn-back">Back to Community</a>
            <?php else: ?>
                <a href="community_page.php?community_id=<?php echo $communityID; ?>" class="btn btn-back">Back to Community</a>
            <?php endif; ?>
            <?php if ($reportedUserId): ?>
                <button class="btn btn-ban" onclick="showBanModal(<?php echo $reportedUserId; ?>)">Ban User</button>
            <?php endif; ?>
            <form method="POST" style="display: inline;">
                <input type="hidden" name="report_id" value="<?php echo $reportId; ?>">
                <button type="submit" name="resolve_report" class="btn btn-success">Resolve Report</button>
            </form>
        </div>
    </div>

    <!-- Ban Modal -->
    <div id="modalBackdrop" class="modal-backdrop"></div>
    <div id="banModal" class="ban-modal">
        <h2>Ban User: <?php echo htmlspecialchars($reportDetails['ReportedUsername']); ?></h2>
        <form method="POST">
            <input type="hidden" name="user_id" id="banUserId" value="<?php echo $reportedUserId; ?>">
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
            <div class="modal-buttons">
                <button type="submit" name="ban_user" class="btn btn-danger">Ban User</button>
                <button type="button" onclick="hideBanModal()" class="btn btn-secondary">Cancel</button>
            </div>
        </form>
    </div>

    <script>
    function showBanModal(userId) {
        document.getElementById('banUserId').value = userId;
        document.getElementById('banModal').style.display = 'block';
        document.getElementById('modalBackdrop').style.display = 'block';
        document.body.style.overflow = 'hidden'; // Prevent scrolling when modal is open
    }

    function hideBanModal() {
        document.getElementById('banModal').style.display = 'none';
        document.getElementById('modalBackdrop').style.display = 'none';
        document.body.style.overflow = 'auto'; // Restore scrolling when modal is closed
    }
    </script>
</html>
</body>
</html>