<?php
include 'dashFunctions.php';

// Handle settings updates
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($_POST['settings'] as $name => $value) {
        updateSetting($name, $value);
    }
    $success = true;
}

$settings = getAllAdminSettings();
$systemStatus = getSystemStatus();
$dbStats = getDatabaseStats();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Settings</title>
    <link rel="stylesheet" href="dashboard.css?v=<?php echo time(); ?>">
</head>
<body>
    <?php include 'sidebar.php'; ?>
    
    <!-- General Settings Section -->
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
        </div>
    </div>

    <!-- Database Statistics Section -->
    <div class="container">
        <h1>Database Overview</h1>
        
        <!-- Summary Cards -->
        <div class="db-cards">
            <div class="db-card total-size">
                <h3>Total Database Size</h3>
                <div class="card-value"><?php echo round($dbStats['total_size'], 2); ?> MB</div>
            </div>
            <div class="db-card table-count">
                <h3>Total Tables</h3>
                <div class="card-value"><?php echo count($dbStats['tables']); ?></div>
            </div>
        </div>

        <!-- User Tables Section -->
        <section class="table-section">
            <div class="section-header">
                <h2>User Data Tables</h2>
                <p class="section-description">Tables related to user accounts, profiles, and authentication</p>
            </div>
            <div class="table-card">
                <div class="card-header">
                    <div class="header-info">
                        <span class="table-count"><?php echo count(array_filter($dbStats['tables'], function($table) {
                            return strpos($table['table_name'], 'user') !== false || 
                                   strpos($table['table_name'], 'profile') !== false || 
                                   strpos($table['table_name'], 'account') !== false;
                        })); ?> tables</span>
                    </div>
                </div>
                <table>
                    <tr>
                        <th>Table Name</th>
                        <th>Size (MB)</th>
                        <th>Rows</th>
                        <th>Status</th>
                    </tr>
                    <?php foreach ($dbStats['tables'] as $table): ?>
                        <?php if (strpos($table['table_name'], 'user') !== false || 
                                 strpos($table['table_name'], 'profile') !== false || 
                                 strpos($table['table_name'], 'account') !== false): ?>
                            <tr>
                                <td><?php echo $table['table_name']; ?></td>
                                <td><?php echo round($table['size_mb'], 3); ?></td>
                                <td><?php echo number_format($table['rows']); ?></td>
                                <td><span class="status-indicator <?php echo $table['size_mb'] > 5 ? 'warning' : 'good'; ?>">
                                    <?php echo $table['size_mb'] > 5 ? 'Large' : 'Optimal'; ?></span></td>
                            </tr>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </table>
            </div>
        </section>

        <!-- Content Tables Section -->
        <section class="table-section">
            <div class="section-header">
                <h2>Content Tables</h2>
                <p class="section-description">Tables storing posts, comments, and media content</p>
            </div>
            <div class="table-card">
                <div class="card-header">
                    <div class="header-info">
                        <span class="table-count"><?php echo count(array_filter($dbStats['tables'], function($table) {
                            return strpos($table['table_name'], 'post') !== false || 
                                   strpos($table['table_name'], 'comment') !== false || 
                                   strpos($table['table_name'], 'media') !== false;
                        })); ?> tables</span>
                    </div>
                </div>
                <table>
                    <tr>
                        <th>Table Name</th>
                        <th>Size (MB)</th>
                        <th>Rows</th>
                        <th>Status</th>
                    </tr>
                    <?php foreach ($dbStats['tables'] as $table): ?>
                        <?php if (strpos($table['table_name'], 'post') !== false || 
                                 strpos($table['table_name'], 'comment') !== false || 
                                 strpos($table['table_name'], 'media') !== false): ?>
                            <tr>
                                <td><?php echo $table['table_name']; ?></td>
                                <td><?php echo round($table['size_mb'], 3); ?></td>
                                <td><?php echo number_format($table['rows']); ?></td>
                                <td><span class="status-indicator <?php echo $table['size_mb'] > 10 ? 'warning' : 'good'; ?>">
                                    <?php echo $table['size_mb'] > 10 ? 'Large' : 'Optimal'; ?></span></td>
                            </tr>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </table>
            </div>
        </section>

        <!-- System Tables Section -->
        <section class="table-section">
            <div class="section-header">
                <h2>System Tables</h2>
                <p class="section-description">System configuration, logs, and settings tables</p>
            </div>
            <div class="table-card">
                <div class="card-header">
                    <div class="header-info">
                        <span class="table-count"><?php echo count(array_filter($dbStats['tables'], function($table) {
                            return strpos($table['table_name'], 'log') !== false || 
                                   strpos($table['table_name'], 'setting') !== false || 
                                   strpos($table['table_name'], 'config') !== false;
                        })); ?> tables</span>
                    </div>
                </div>
                <table>
                    <tr>
                        <th>Table Name</th>
                        <th>Size (MB)</th>
                        <th>Rows</th>
                        <th>Status</th>
                    </tr>
                    <?php foreach ($dbStats['tables'] as $table): ?>
                        <?php if (strpos($table['table_name'], 'log') !== false || 
                                 strpos($table['table_name'], 'setting') !== false || 
                                 strpos($table['table_name'], 'config') !== false): ?>
                            <tr>
                                <td><?php echo $table['table_name']; ?></td>
                                <td><?php echo round($table['size_mb'], 3); ?></td>
                                <td><?php echo number_format($table['rows']); ?></td>
                                <td><span class="status-indicator <?php echo $table['size_mb'] > 2 ? 'warning' : 'good'; ?>">
                                    <?php echo $table['size_mb'] > 2 ? 'Large' : 'Optimal'; ?></span></td>
                            </tr>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </table>
            </div>
        </section>
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
    .database-overview {
        margin-top: 40px;
    }
    .db-cards {
        display: flex;
        gap: 20px;
        margin-bottom: 20px;
    }
    .db-card {
        background: white;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        flex: 1;
        text-align: center;
    }
    .db-card h3 {
        margin-bottom: 10px;
    }
    .card-value {
        font-size: 1.5em;
        font-weight: bold;
    }
    .table-groups {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 20px;
    }
    .table-card {
        background: white;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
    }
    .table-count {
        background: #f8f9fa;
        padding: 5px 10px;
        border-radius: 12px;
        font-size: 0.9em;
    }
    .table-section {
        margin-bottom: 40px;
    }
    .section-header {
        margin-bottom: 20px;
    }
    .section-description {
        color: #6c757d;
        font-size: 0.9em;
    }
    </style>
</body>
</html>