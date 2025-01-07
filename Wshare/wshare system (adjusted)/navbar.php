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
        <!-- Profile Section -->
        <div class="profile-section">
            <li>
                <a href="user_profile.php">
                    <div class="profile-card">
                        <?php
                        $username = $_SESSION['username'];
                        $user = getUserByUsername($username);
                        ?>
                        <div class="profile-image">
                            <?php if (!empty($user['ProfilePic'])): ?>
                                <img class="login_user_pic" src="<?php echo $user['ProfilePic']; ?>">
                            <?php else: ?>
                                <img class="login_user_pic" src="default_pic.svg">
                            <?php endif; ?>
                        </div>
                        <h3 class="username-nav">Welcome, <b><?php echo $_SESSION['username']; ?></b>!</h3>
                    </div>
                </a>
            </li>
        </div>

        <!-- Main Navigation -->
        <div class="nav-section">
            <li><a href="index.php">
                <div class="left-nav">
                    <img class="icons" src="homepage.svg">
                    <p class="label_nav">Home</p>
                </div>
            </a></li>

            <li><a href="networkfeed.php">
                <div class="left-nav">
                    <img class="icons" src="networkfeed.svg">
                    <p class="label_nav">Feed</p>
                </div>
            </a></li>

            <li><a href="Communities.php">
                <div class="left-nav">
                    <img class="icons" src="chats2.svg">
                    <p class="label_nav">Communities</p>
                </div>
            </a></li>

            <li><a href="notifications.php">
                <div class="left-nav">
                    <img class="icons" src="notif.svg">
                    <p class="label_nav">Notifications</p>
                    <?php if ($notificationCount = getUnreadNotificationCount($_SESSION['user_id'])): ?>
                        <span class="notification-badge"><?php echo $notificationCount; ?></span>
                    <?php endif; ?>
                </div>
            </a></li>
        </div>

        <!-- Network Section -->
        <div class="nav-section">
            <div class="section-title">Network</div>
            <li><a href="network.php">
                <div class="left-nav">
                    <img class="icons" src="twopeople.svg">
                    <p class="label_nav">Your Network</p>
                </div>
            </a></li>

            <li><a href="search_users.php">
                <div class="left-nav">
                    <img class="icons" src="searchpeople.svg">
                    <p class="label_nav">Find Learners</p>
                </div>
            </a></li>
        </div>

        <!-- Resources Section -->
        <div class="nav-section">
            <div class="section-title">Resources</div>
            <li><a href="course_library.php">
                <div class="left-nav">
                    <img class="icons" src="library.svg">
                    <p class="label_nav">Course Library</p>
                </div>
            </a></li>

            <li><a href="bookmarks.php">
                <div class="left-nav">
                    <img class="icons" src="bookmark_filled.svg">
                    <p class="label_nav">Bookmarks</p>
                </div>
            </a></li>
        </div>

        <!-- Settings Section -->
        <div class="nav-section">
            <?php if (isset($currentUser['IsAdmin']) && $currentUser['IsAdmin'] == 1): ?>
                <li><a href="../admin wshare (adjusted)/admin/dashboard.php">
                    <div class="left-nav admin-nav">
                        <img class="icons" src="admin-dashboard.svg">
                        <p class="label_nav">Admin Dashboard</p>
                    </div>
                </a></li>
            <?php endif; ?>

            <li><a href="settings.php">
                <div class="left-nav">
                    <img class="icons" src="settings.svg">
                    <p class="label_nav">Settings</p>
                </div>
            </a></li>

            <li><a href="logout.php">
                <div class="left-nav">
                    <img class="icons" src="logout.svg">
                    <p class="label_nav">Logout</p>
                </div>
            </a></li>
        </div>
    </ul>