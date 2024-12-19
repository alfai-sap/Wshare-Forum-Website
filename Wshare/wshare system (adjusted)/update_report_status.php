
<?php
require_once 'functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $reportId = intval($_POST['report_id']);
    $status = $_POST['status'];

    if (updateReportStatus($reportId, $status)) {
        header('Location: community_page.php?community_id=' . intval($_POST['community_id']));
    } else {
        echo "Failed to update report status.";
    }
}
?>