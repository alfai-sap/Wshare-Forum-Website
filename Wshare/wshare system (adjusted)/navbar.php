<!-- Logo Navigation Bar -->
    <ul class="logo-navbar">

        <li><button id="logo-nav" class="logo-nav"><img class="toggle-icon" src="menu.svg"></button></li>

        <li>
            <a href="index.php">
                <div class="logo-nav">
                    <p class="logo-label-nav">Wshare</p>
                </div>
            </a>
        </li>

    </ul>

    <!-- Left Navigation Bar -->
    <ul class="left-navbar" id="left-navbar">
        <br><br><br><br><br>
        <li>
            <a href="user_profile.php">

                <div class="left-nav">
                    <?php
                    // Get user information from the session variable after login
                    $username = $_SESSION['username'];
                    $user = getUserByUsername($username);

                    if (!empty($user['ProfilePic'])):
                    ?>
                        <img class="login_user_pic" src="<?php echo $user['ProfilePic']; ?>">
                    <?php else: ?>
                        <img class="login_user_pic" src="default_pic.svg">
                    <?php endif; ?>

                    <h3 class="username-nav">Welcome, <b><?php echo $_SESSION['username']; ?></b>!</h3>
                </div>
            </a>

        </li>

        <hr />
        <li>
            <a href="index.php">

                <div class="left-nav">
                    <img class="icons" src="homepage.svg">
                    <p class="label_nav">Home</p>
                </div>

            </a>
        </li>

        <li>
            <a href="Communities.php">

                <div class="left-nav">
                    <img class="icons" src="chats2.svg">
                    <p class="label_nav">Community Hubs</p>
                </div>

            </a>
        </li>

        <li>
            <a href="search_users.php">

                <div class="left-nav">
                    <img class="icons" src="searchpeople.svg">
                    <p class="label_nav">Search user</p>
                </div>

            </a>
        </li>

        <li>
            <a href="networkfeed.php">

                <div class="left-nav">
                    <img class="icons" src="networkfeed.svg">
                    <p class="label_nav">Posts from Network</p>
                </div>

            </a>
        </li>

        <li>
            <a href="network.php">

                <div class="left-nav">
                    <img class="icons" src="twopeople.svg">
                    <p class="label_nav">Your Network</p>
                </div>

            </a>
        </li>

        

        <li>
            <a href="#">

                <div class="left-nav">
                    <img class="icons" src="library.svg">
                    <p class="label_nav">Course Library</p>
                </div>

            </a>
        </li>

        <hr />

        <li>
            <a href="notifications.php">
                <div class="left-nav">
                    <img class="icons" src="notif.svg">
                    <p class="label_nav">Notifications</p>
                    <?php if ($notificationCount = getUnreadNotificationCount($_SESSION['user_id'])): ?>
                        <span class="notification-badge"><?php echo $notificationCount; ?></span>
                    <?php endif; ?>
                </div>
            </a>
        </li>

        <li>
            <a href="#">

                <div class="left-nav">
                    <img class="icons" src="review.svg">
                    <p class="label_nav">Feedback</p>
                </div>

            </a>
        </li>

        <li>
            <a href="settings.php">

                <div class="left-nav">
                    <img class="icons" src="settings.svg">
                    <p class="label_nav">Settings</p>
                </div>

            </a>
        </li>
        
        <?php
        // Check if user is admin
        $currentUser = getUserByUsername($_SESSION['username']);
        if (isset($currentUser['IsAdmin']) && $currentUser['IsAdmin'] == 1): ?>
            <li>
                <a href="../admin wshare (adjusted)/admin/dashboard.php">  <!-- Updated path -->
                    <div class="left-nav">
                        <img class="icons" src="admin-dashboard.svg">
                        <p class="label_nav">Admin Dashboard</p>
                    </div>
                </a>
            </li>
        <?php endif; ?>

        <li>
            <a href="logout.php">

                <div class="left-nav">
                    <img class="icons" src="logout.svg">
                    <p class="label_nav">Logout</p>
                </div>

            </a>
        </li>
    </ul>