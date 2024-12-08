<?php
include 'dashFunctions.php'; // Include your database functions

// Handle backup request
if (isset($_POST['backup'])) {
    $backupFile = 'wshare_db_backup_' . date('Y-m-d_H-i-s') . '.sql';
    $command = "mysqldump --user=root --password= --host=localhost wshare_db_new > $backupFile";
    system($command, $output);
    if ($output === 0) {
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . basename($backupFile));
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($backupFile));
        readfile($backupFile);
        unlink($backupFile); // Delete the file after download
        exit;
    } else {
        $backupSuccess = false;
    }
}

// Handle restore request
if (isset($_POST['restore'])) {
    $restoreFile = $_FILES['restore_file']['tmp_name'];
    $command = "mysql --user=root --password= --host=localhost wshare_db_new < $restoreFile";
    system($command, $output);
    $restoreSuccess = $output === 0;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Backup and Restore</title>
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
        .backup-restore-section {
            margin-bottom: 20px;
        }
        .backup-restore-section h2 {
            margin-bottom: 10px;
            color: #333;
        }
        .backup-restore-section form {
            margin-bottom: 20px;
        }
        .backup-restore-section button {
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .backup-restore-section button:hover {
            background-color: #0056b3;
        }
        .alert {
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        .alert-success {
            background-color: #d4edda;
            color: #155724;
        }
        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
        }
    </style>
</head>
<body>
    <?php include 'sidebar.php'; ?>
    <div class="container">
        <h1>Backup and Restore</h1>

        <?php if (isset($backupSuccess) && !$backupSuccess): ?>
            <div class="alert alert-danger">
                Backup failed.
            </div>
        <?php endif; ?>

        <?php if (isset($restoreSuccess)): ?>
            <div class="alert <?php echo $restoreSuccess ? 'alert-success' : 'alert-danger'; ?>">
                <?php echo $restoreSuccess ? 'Restore completed successfully!' : 'Restore failed.'; ?>
            </div>
        <?php endif; ?>

        <div class="backup-restore-section">
            <h2>Backup Database</h2>
            <form method="POST">
                <button type="submit" name="backup">Backup Now</button>
            </form>
        </div>

        <div class="backup-restore-section">
            <h2>Restore Database</h2>
            <form method="POST" enctype="multipart/form-data">
                <input type="file" name="restore_file" required>
                <button type="submit" name="restore">Restore Now</button>
            </form>
        </div>
    </div>
</body>
</html>