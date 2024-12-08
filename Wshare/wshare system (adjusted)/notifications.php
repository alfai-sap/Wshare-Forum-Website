<?php
require_once 'functions.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$notifications = getNotifications($_SESSION['user_id']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications - WShare</title>
    <link rel="stylesheet" href="./css/notifications.css ?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="./css/navbar.css ?v=<?php echo time(); ?>" >
    <link rel="stylesheet" href="./css/left-navbar.css ?v=<?php echo time(); ?>" >
</head>
<body>
    <?php include 'navbar.php'; ?>
    
    <div class="notifications-container">
        <h1>Notifications</h1>
        
        <?php if (empty($notifications)): ?>
            <div class="no-notifications">
                <p>No notifications yet</p>
            </div>
        <?php else: ?>
            <?php foreach ($notifications as $notification): ?>
                <div class="notification-item <?php echo !$notification['Seen'] ? 'unseen' : ''; ?>" 
                     data-id="<?php echo $notification['NotificationID']; ?>">
                    <img src="<?php echo $notification['ProfilePic'] ?: 'default_pic.svg'; ?>" alt="Profile Picture">
                    <div class="notification-content">
                        <p><?php echo $notification['Content']; // Now contains HTML formatted content ?></p>
                        <span class="notification-time"><?php echo timeAgo($notification['CreatedAt']); ?></span>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <script>
        // Mark notifications as read when clicked
        document.querySelectorAll('.notification-item').forEach(item => {
            item.addEventListener('click', function() {
                const notificationId = this.dataset.id;
                fetch('mark_notification_read.php', {
                    method: 'POST',
                    body: JSON.stringify({ notification_id: notificationId }),
                    headers: {
                        'Content-Type': 'application/json'
                    }
                }).then(() => {
                    this.classList.remove('unseen');
                });
            });
        });
    </script>
</body>
</html>