<?php
include 'dashFunctions.php';

// Handle settings updates
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($_POST['settings'] as $name => $value) {
        updateSetting($name, $value);
    }
    $success = true;
}

$settings = getAllSettings();
$systemStatus = getSystemStatus();
$dbStats = getDatabaseStats();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Settings</title>
    <link rel="stylesheet" href="dashboard.css">
</head>
<body>
    <?php include 'sidebar.php'; ?>
    
    <div class="container">
        <h1>System Settings</h1>

        <?php if (isset($success)): ?>
            <div class="alert alert-success">Settings updated successfully!</div>
        <?php endif; ?>

        <div class="settings-grid">
            <!-- General Settings -->
            <div class="settings-section">
                <h2>General Settings</h2>
                <form method="POST">
                    <div class="setting-group">
                        <label>Site Name</label>
                        <input type="text" name="settings[site_name]" value="<?php echo $settings['site_name'] ?? 'WShare'; ?>">
                    </div>
                    
                    <div class="setting-group">
                        <label>User Registration</label>
                        <select name="settings[allow_registration]">
                            <option value="1" <?php echo ($settings['allow_registration'] ?? '1') == '1' ? 'selected' : ''; ?>>Enabled</option>
                            <option value="0" <?php echo ($settings['allow_registration'] ?? '1') == '0' ? 'selected' : ''; ?>>Disabled</option>
                        </select>
                    </div>

                    <div class="setting-group">
                        <label>Maintenance Mode</label>
                        <select name="settings[maintenance_mode]">
                            <option value="0" <?php echo ($settings['maintenance_mode'] ?? '0') == '0' ? 'selected' : ''; ?>>Off</option>
                            <option value="1" <?php echo ($settings['maintenance_mode'] ?? '0') == '1' ? 'selected' : ''; ?>>On</option>
                        </select>
                    </div>

                    <button type="submit">Save Settings</button>
                </form>
            </div>

            <!-- System Status -->
            <div class="settings-section">
                <h2>System Status</h2>
                <div class="status-grid">
                    <div class="status-item">
                        <h3>Database</h3>
                        <p class="status-<?php echo $systemStatus['database'] === 'Connected' ? 'good' : 'bad'; ?>">
                            <?php echo $systemStatus['database']; ?>
                        </p>
                    </div>
                    <div class="status-item">
                        <h3>Server</h3>
                        <p><?php echo $systemStatus['server']; ?></p>
                    </div>
                    <div class="status-item">
                        <h3>PHP Version</h3>
                        <p><?php echo $systemStatus['php_version']; ?></p>
                    </div>
                    <div class="status-item">
                        <h3>Memory Usage</h3>
                        <p><?php echo round($systemStatus['memory_usage'] / 1024 / 1024, 2); ?> MB</p>
                    </div>
                </div>
            </div>

            <!-- Database Stats -->
            <div class="settings-section">
                <h2>Database Statistics</h2>
                <p>Total Size: <?php echo round($dbStats['total_size'], 2); ?> MB</p>
                <table>
                    <tr>
                        <th>Table</th>
                        <th>Size (MB)</th>
                    </tr>
                    <?php foreach ($dbStats['tables'] as $table): ?>
                    <tr>
                        <td><?php echo $table['table_name']; ?></td>
                        <td><?php echo $table['size_mb']; ?></td>
                    </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        </div>
    </div>

    <style>
    .settings-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 20px;
    }
    .settings-section {
        background: white;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .setting-group {
        margin-bottom: 15px;
    }
    .setting-group label {
        display: block;
        margin-bottom: 5px;
    }
    .status-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 15px;
    }
    .status-good { color: #28a745; }
    .status-bad { color: #dc3545; }
    </style>
</body>
</html>