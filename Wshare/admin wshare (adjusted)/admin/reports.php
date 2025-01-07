<?php
include 'dashFunctions.php';

// Handle report status updates
if (isset($_POST['update_report'])) {
    updateReportStatus($_POST['report_id'], $_POST['status']);
}

if (isset($_POST['resolve_report'])) {
    updateReportStatus($_POST['report_id'], 'resolved');
}

// Get reports
$status = isset($_GET['status']) ? $_GET['status'] : null;
$postType = isset($_GET['post_type']) ? $_GET['post_type'] : null;
$reports = getReports($status, $postType);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Reports</title>
    <link rel="stylesheet" href="dashboard.css?v=<?php echo time(); ?>">
</head>
<body>
    <?php include 'sidebar.php'; ?>
    
    <div class="container">
        <h1>Manage Reports</h1>

        <!-- Filter Controls -->
        <div class="filter-controls">
            <a href="?" class="filter-btn">All</a>
            <a href="?status=pending" class="filter-btn" style="margin: 10px;">Pending</a>
            <a href="?status=reviewed" class="filter-btn" style="margin: 10px;">Reviewed</a>
            <a href="?status=resolved" class="filter-btn" style="margin: 10px;">Resolved</a>
            <a href="?post_type=general" class="filter-btn" style="margin: 10px;">General</a>
            <a href="?post_type=community" class="filter-btn" style="margin: 10px;">Community</a>
        </div>

        <!-- Reports Table -->
        <div class="table-container">
            <table>
                <tr>
                    <th>ID</th>
                    <th>Type</th>
                    <th>Reporter</th>
                    <th>Violation</th>
                    <th>Status</th>
                    <th>Post Type</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
                <?php foreach ($reports as $report): ?>
                <tr>
                    <td><?php echo $report['ReportID']; ?></td>
                    <td><?php echo ucfirst($report['ReportType']); ?></td>
                    <td><?php echo htmlspecialchars($report['Username']); ?></td>
                    <td><?php echo htmlspecialchars($report['Violation']); ?></td>
                    <td><?php echo ucfirst($report['Status']); ?></td>
                    <td><?php echo ucfirst($report['PostType']); ?></td>
                    <td><?php echo $report['CreatedAt']; ?></td>
                    <td>
                        <form method="POST" class="inline-form">
                            <input type="hidden" name="report_id" value="<?php echo $report['ReportID']; ?>">
                            <select name="status">
                                <option value="pending" <?php echo $report['Status'] == 'pending' ? 'selected' : ''; ?>>Pending</option>
                                <option value="reviewed" <?php echo $report['Status'] == 'reviewed' ? 'selected' : ''; ?>>Reviewed</option>
                                <option value="resolved" <?php echo $report['Status'] == 'resolved' ? 'selected' : ''; ?>>Resolved</option>
                            </select>
                            <button type="submit" name="update_report">Update</button>
                        </form>
                        <form method="POST" class="inline-form">
                            <input type="hidden" name="report_id" value="<?php echo $report['ReportID']; ?>">
                            <button type="submit" name="resolve_report">Resolve</button>
                        </form>
                        <button class="view-report-btn" onclick="viewReport(<?php echo $report['ReportID']; ?>)">View</button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </div>

    <script>
    function viewReport(reportId) {
        window.location.href = `view_report.php?id=${reportId}`;
    }
    </script>
</body>
</html>