<?php 
session_start();
require_once 'db_connection.php';
require_once 'functions.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$userSettings = getUserSettings($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings</title>
    <link rel="stylesheet" href="./css/left-navbar.css ?v=<?php echo time(); ?>" >
    <link rel="stylesheet" href="./css/navbar.css ?v=<?php echo time(); ?>" >
    <link rel="stylesheet" href="./css/settings.css?v=<?php echo time(); ?>">
</head>
<body>
    <?php include 'navbar.php'; ?>
    
    <div class="settings-container">
        <div class="settings-section">
            <h2>Appearance</h2>
            <div class="settings-option">
                <label for="dark-mode-toggle">Dark Mode</label>
                <input type="checkbox" id="dark-mode-toggle">
            </div>
            <div class="settings-option">
                <label for="font-size">Larger Font Size</label>
                <input type="checkbox" id="font-size">
            </div>
        </div>

        <div class="settings-section">
            <h2>Language & Region</h2>
            <div class="settings-option">
                <label for="language">Language</label>
                <select id="language" class="settings-select">
                    <option value="en">English</option>
                    <option value="es">Spanish</option>
                    <option value="fr">French</option>
                </select>
            </div>
            <div class="settings-option">
                <label for="timezone">Time Zone</label>
                <select id="timezone" class="settings-select">
                    <option value="UTC">UTC</option>
                    <option value="EST">Eastern Time</option>
                    <option value="PST">Pacific Time</option>
                </select>
            </div>
        </div>

        <div class="settings-section">
            <h2>Notifications</h2>
            <div class="settings-option">
                <label for="email-notif">Email Notifications</label>
                <input type="checkbox" id="email-notif">
            </div>
            <div class="settings-option">
                <label for="push-notif">Push Notifications</label>
                <input type="checkbox" id="push-notif">
            </div>
        </div>

        <div class="settings-section">
            <h2>Privacy</h2>
            <div class="settings-option">
                <label for="profile-visibility">Make Profile Private</label>
                <input type="checkbox" id="profile-visibility">
            </div>
            <div class="settings-option">
                <label for="activity-status">Show Activity Status</label>
                <input type="checkbox" id="activity-status">
            </div>
        </div>

        <div class="settings-section">
            <h2>Account</h2>
            <div class="settings-option">
                <label for="two-factor">Two-Factor Authentication</label>
                <input type="checkbox" id="two-factor">
            </div>
            <div class="settings-option">
                <label for="login-alerts">Login Alerts</label>
                <input type="checkbox" id="login-alerts">
            </div>
        </div>

        <div class="settings-section">
            <h2>Content</h2>
            <div class="settings-option">
                <label for="image-quality">Image Quality</label>
                <select id="image-quality" class="settings-select">
                    <option value="high">High</option>
                    <option value="medium">Medium</option>
                    <option value="low">Low</option>
                </select>
            </div>
        </div>

        <div class="settings-section">
            <h2>Accessibility</h2>
            <div class="settings-option">
                <label for="screen-reader">Screen Reader Support</label>
                <input type="checkbox" id="screen-reader">
            </div>
            <div class="settings-option">
                <label for="motion-reduce">Reduce Motion</label>
                <input type="checkbox" id="motion-reduce">
            </div>
            <div class="settings-option">
                <label for="contrast">High Contrast</label>
                <input type="checkbox" id="contrast">
            </div>
        </div>

        <div class="settings-section">
            <h2>Posts & Comments</h2>
            <div class="settings-option">
                <label for="default-sort">Default Sort</label>
                <select id="default-sort" class="settings-select">
                    <option value="newest">Newest First</option>
                    <option value="oldest">Oldest First</option>
                    <option value="popular">Most Popular</option>
                </select>
            </div>
            <div class="settings-option">
                <label for="comment-threads">Comment Thread Display</label>
                <select id="comment-threads" class="settings-select">
                    <option value="all">Show All</option>
                    <option value="collapsed">Collapsed</option>
                    <option value="top">Top Comments Only</option>
                </select>
            </div>
        </div>

        <div class="settings-section">
            <h2>Data Usage</h2>
            <div class="settings-option">
                <label for="data-saver">Data Saver Mode</label>
                <input type="checkbox" id="data-saver">
            </div>
            <div class="settings-option">
                <label for="preload">Preload Media</label>
                <select id="preload" class="settings-select">
                    <option value="always">Always</option>
                    <option value="wifi">On WiFi Only</option>
                    <option value="never">Never</option>
                </select>
            </div>
        </div>

        <div class="settings-section">
            <h2>Security & Backup</h2>
            <div class="settings-option">
                <label for="backup-freq">Backup Frequency</label>
                <select id="backup-freq" class="settings-select">
                    <option value="daily">Daily</option>
                    <option value="weekly">Weekly</option>
                    <option value="monthly">Monthly</option>
                </select>
            </div>
            <div class="settings-option">
                <label for="session-timeout">Session Timeout</label>
                <select id="session-timeout" class="settings-select">
                    <option value="30">30 minutes</option>
                    <option value="60">1 hour</option>
                    <option value="120">2 hours</option>
                </select>
            </div>
        </div>

        <button class="btn-save">Save Settings</button>
    </div>

    <script>
        document.getElementById('dark-mode-toggle').addEventListener('change', function() {
            if (this.checked) {
                document.body.classList.add('dark-mode');
                localStorage.setItem('darkMode', 'enabled');
            } else {
                document.body.classList.remove('dark-mode');
                localStorage.setItem('darkMode', 'disabled');
            }
        });

        if (localStorage.getItem('darkMode') === 'enabled') {
            document.body.classList.add('dark-mode');
            document.getElementById('dark-mode-toggle').checked = true;
        }

        // Handle other settings
        const settingsToStore = [
            'font-size', 'email-notif', 'push-notif', 'profile-visibility', 
            'activity-status', 'two-factor', 'login-alerts', 'nsfw-content', 
            'autoplay', 'screen-reader', 'motion-reduce', 'contrast', 'data-saver'
        ];

        const selectOptionsToStore = [
            'language', 'timezone', 'image-quality', 'default-sort', 'comment-threads', 'preload', 
            'backup-freq', 'session-timeout'
        ];
        
        // Handle checkbox settings
        settingsToStore.forEach(setting => {
            const element = document.getElementById(setting);
            
            // Load saved settings
            if (localStorage.getItem(setting) === 'enabled') {
                element.checked = true;
                document.body.classList.add(setting + '-enabled');
            }

            // Add change listeners
            element.addEventListener('change', function() {
                if (this.checked) {
                    localStorage.setItem(setting, 'enabled');
                    document.body.classList.add(setting + '-enabled');
                } else {
                    localStorage.setItem(setting, 'disabled');
                    document.body.classList.remove(setting + '-enabled');
                }
            });
        });

        // Handle select options
        selectOptionsToStore.forEach(setting => {
            const element = document.getElementById(setting);
            
            // Load saved settings
            const savedValue = localStorage.getItem(setting);
            if (savedValue) {
                element.value = savedValue;
            }

            // Add change listeners
            element.addEventListener('change', function() {
                localStorage.setItem(setting, this.value);
            });
        });

        // Handle save button with AJAX
        document.querySelector('.btn-save').addEventListener('click', function() {
            const settings = {};
            const saveButton = this;
            
            // Disable button while saving
            saveButton.disabled = true;
            saveButton.textContent = 'Saving...';
            
            // Collect checkbox settings
            settingsToStore.forEach(setting => {
                const element = document.getElementById(setting);
                settings[setting] = element.checked ? 'enabled' : 'disabled';
            });
            
            // Collect select options
            selectOptionsToStore.forEach(setting => {
                const element = document.getElementById(setting);
                settings[setting] = element.value;
            });
            
            // Save to server
            fetch('save_settings.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'settings=' + encodeURIComponent(JSON.stringify(settings))
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show success message
                    const successMessage = document.createElement('div');
                    successMessage.className = 'alert alert-success';
                    successMessage.textContent = 'Settings saved successfully!';
                    saveButton.parentNode.insertBefore(successMessage, saveButton);
                    
                    // Remove message after 3 seconds
                    setTimeout(() => successMessage.remove(), 3000);
                } else {
                    throw new Error(data.message || 'Failed to save settings');
                }
            })
            .catch(error => {
                // Show error message
                const errorMessage = document.createElement('div');
                errorMessage.className = 'alert alert-error';
                errorMessage.textContent = error.message;
                saveButton.parentNode.insertBefore(errorMessage, saveButton);
                
                // Remove message after 3 seconds
                setTimeout(() => errorMessage.remove(), 3000);
            })
            .finally(() => {
                // Re-enable button
                saveButton.disabled = false;
                saveButton.textContent = 'Save Settings';
            });
        });

        // Load settings from server
        <?php if (!empty($userSettings)): ?>
        const serverSettings = <?php echo json_encode($userSettings); ?>;
        
        // Apply server settings
        Object.entries(serverSettings).forEach(([setting, value]) => {
            const element = document.getElementById(setting);
            if (element) {
                if (element.type === 'checkbox') {
                    element.checked = value === 'enabled';
                    if (element.checked) {
                        document.body.classList.add(setting + '-enabled');
                    }
                } else if (element.tagName === 'SELECT') {
                    element.value = value;
                }
            }
        });
        <?php endif; ?>
    </script>
</body>
</html>