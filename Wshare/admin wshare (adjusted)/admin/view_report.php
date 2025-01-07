<?php
require_once 'dashFunctions.php';

if (!isset($_GET['id'])) {
    header('Location: reports.php');
    exit();
}

$reportId = intval($_GET['id']);
$reportDetails = getReportedPostDetails($reportId);

if (!$reportDetails) {
    echo "Report not found.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Report</title>
    <link rel="stylesheet" href="dashboard.css?v=<?php echo time(); ?>">
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
            transition: background-color 0.3s;
        }
        .btn:hover {
            background-color: #0056b3;
        }
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
        .btn-info, .btn-success, .btn-warning, .btn-danger, .btn-secondary {
            color: white;
            border: none;
            margin: 10;
            padding: 5px 10px;
            border-radius: 4px;
            cursor: pointer;
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
    </style>
</head>
<body>
    <?php include 'sidebar.php'; ?>
    
    <div class="container">
        <h1>Report Details</h1>
        <div class="report-details">
            <p><strong>Report ID:</strong> <?php echo $reportDetails['ReportID']; ?></p>
            <p><strong>Report Type:</strong> <?php echo ucfirst($reportDetails['ReportType']); ?></p>
            <p><strong>Reporter:</strong> <?php echo htmlspecialchars($reportDetails['ReporterName']); ?></p>
            <p><strong>Reported User:</strong> <?php echo htmlspecialchars($reportDetails['ReportedUsername']); ?></p>
            <p><strong>Violation:</strong> <?php echo htmlspecialchars($reportDetails['Violation']); ?></p>
            <p><strong>Status:</strong> <?php echo ucfirst($reportDetails['Status']); ?></p>
            <p><strong>Created At:</strong> <?php echo $reportDetails['CreatedAt']; ?></p>
            <p><strong>Post Title:</strong> <?php echo htmlspecialchars($reportDetails['Title']); ?></p>
            <p><strong>Post Content:</strong> <?php echo nl2br(htmlspecialchars($reportDetails['Content'])); ?></p>
        </div>
        <?php if ($reportDetails['ReportType'] == 'post' && isset($reportDetails['PostID']) && $reportDetails['PostID']): ?>
            <h2>Post Details</h2>
            <div class="action-buttons" style="margin-bottom: 20px;">
                <a href="admin_view_post.php?id=<?php echo htmlspecialchars($reportDetails['PostID']); ?>&type=post" class="view-post-btn">
                    <i class="fas fa-eye"></i> View Full Post
                </a>
            </div>
            <div class="post-container">
        <?php endif; ?>
        <div class="evidence-section">
            <h2>Evidence</h2>
            <?php if (!empty($reportDetails['EvidencePhoto'])): ?>
                <?php
                    // Clean and encode the file path
                    $evidencePath = str_replace('\\', '/', $reportDetails['EvidencePhoto']);
                    // If the path starts with ../, adjust it relative to the current directory
                    if (strpos($evidencePath, '../') === 0) {
                        $evidencePath = dirname($_SERVER['PHP_SELF']) . '/' . $evidencePath;
                    }
                    $encodedPath = rawurlencode($evidencePath);
                ?>
                <img src="<?php echo htmlspecialchars($encodedPath); ?>" 
                     alt="Evidence Photo" 
                     style="max-width: 100%; height: auto;"
                     onerror="this.onerror=null; this.src='images/no-image.png'; this.alt='Evidence photo not available';">
            <?php else: ?>
                <p>No evidence provided.</p>
            <?php endif; ?>
        </div>
        <div class="action-buttons">
            <a href="reports.php" class="btn">Back to Reports</a>
            <button class="btn" onclick="showBanModal(<?php echo $reportDetails['ReportedUserID']; ?>)">Ban User</button>
        </div>
    </div>

    <!-- Ban Modal -->
    <div id="banModal" class="ban-modal">
        <h2>Ban User</h2>
        <form method="POST" action="manage_users.php">
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
    </script>
</body>
</html>
