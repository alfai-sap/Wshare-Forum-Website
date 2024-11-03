
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
                    <p class="label_nav">Collaboration Hubs</p>
                </div>

            </a>
        </li>

        <li>
            <a href="search_users.php">

                <div class="left-nav">
                    <img class="icons" src="searchpeople.svg">
                    <p class="label_nav">Find connections</p>
                </div>

            </a>
        </li>

        <li>
            <a href="networkfeed.php">

                <div class="left-nav">
                    <img class="icons" src="networkfeed.svg">
                    <p class="label_nav">Posts from Connections</p>
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
            <a href="logout.php">

                <div class="left-nav">
                    <img class="icons" src="logout.svg">
                    <p class="label_nav">Logout</p>
                </div>

            </a>
        </li>
    </ul>