<?php
require_once 'db_connection.php';

function logUserLogin($userID) {
    global $conn;
    $query = "INSERT INTO user_sessions (UserID, LoginTime) VALUES (?, NOW())";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $userID);
    mysqli_stmt_execute($stmt);
}

// Log user logout time
function logUserLogout($userID) {
    global $conn;
    $query = "UPDATE user_sessions SET LogoutTime = NOW() WHERE UserID = ? AND LogoutTime IS NULL ORDER BY LoginTime DESC LIMIT 1";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $userID);
    if (mysqli_stmt_execute($stmt)) {
        echo "Logout time recorded successfully!";
    } else {
        echo "Error logging out: " . mysqli_error($conn);
    }
}

//para sa login
function createUser($username, $email, $password) {
    global $conn;

    //argon2id hash ang gagamitin for its reliable security
    $options = [
        'memory_cost' => PASSWORD_ARGON2_DEFAULT_MEMORY_COST,
        'time_cost' => PASSWORD_ARGON2_DEFAULT_TIME_COST,
        'threads' => PASSWORD_ARGON2_DEFAULT_THREADS
    ];

    $hashed_password = password_hash($password, PASSWORD_ARGON2ID, $options);

    //$hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO Users (Username, Email, Password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $username, $email, $hashed_password);
    return $stmt->execute();
}

//also a function to get user's username
function getUserByUsername($username) {
    global $conn;
    $sql = "SELECT u.*, up.Fullname, up.Bio, up.ProfilePic, u.IsAdmin, u.JoinedAt 
            FROM Users u 
            LEFT JOIN userprofiles up ON u.UserID = up.UserID 
            WHERE Username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}


//sa login mag verify ng password
function verifyUser($username, $password) {

    $user = getUserByUsername($username);

    if ($user && password_verify($password, $user['Password'])) {
        return $user;
    }

    return false;
}
//check username uniqueness
function isUsernameUnique($username) {
    global $conn;
    $sql = "SELECT COUNT(*) FROM Users WHERE Username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();
    return $count == 0;
}

function isEmailUnique($email) {
    global $conn;
    $sql = "SELECT COUNT(*) FROM Users WHERE Email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();
    return $count == 0;
}


function getCommentById($commentId) {
    global $conn;  // Assuming you have a global database connection

    // First, try to get the comment from the main comments table
    $query = "SELECT c.*, u.Username 
              FROM comments c 
              JOIN users u ON c.UserID = u.UserID 
              WHERE c.CommentID = ?";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $commentId);
    $stmt->execute();
    
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    }
    
    // If not found in main comments, check community comments
    $query = "SELECT cc.*, u.Username 
              FROM community_comments cc 
              JOIN users u ON cc.UserID = u.UserID 
              WHERE cc.CommentID = ?";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $commentId);
    $stmt->execute();
    
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    }
    
    return null;
}

function getUserById($userId) {
    global $conn;  // Assuming you have a global database connection

    // Fetch user details with associated profile information
    $query = "SELECT u.*, up.Fullname, up.Bio, up.ProfilePic 
              FROM users u 
              LEFT JOIN userprofiles up ON u.UserID = up.UserID 
              WHERE u.UserID = ?";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        // Remove sensitive information like password
        unset($user['Password']);
        
        return $user;
    }
    
    return null;
}

//search posts
function searchPosts($search) {
    global $conn; // database connection

    // search query
    $search = mysqli_real_escape_string($conn, $search); // Escape the search query to prevent SQL injection
    $sql = "SELECT Posts.*, Users.Username 
            FROM Posts 
            LEFT JOIN Users ON Posts.UserID = Users.UserID 
            WHERE Posts.Title LIKE '%$search%' OR Posts.Content LIKE '%$search%'";

    // Execute the SQL query
    $result = $conn->query($sql);

    // Check if any rows were returned
    if ($result->num_rows > 0) {
        // Fetch the rows and store them in an array
        $posts = [];

        while ($row = $result->fetch_assoc()) {
            $posts[] = $row;
        }

        return $posts;

    } else {
        // No posts found matching the search query
        return [];
    }
}

function getPostsByTags($tags) {
    global $conn;
    $query = "SELECT p.* FROM posts p
              INNER JOIN post_tags pt ON p.PostID = pt.PostID
              INNER JOIN tags t ON pt.TagID = t.TagID
              WHERE t.TagName IN ('$tags')
              GROUP BY p.PostID";
    $result = $conn->query($query);
    return $result;
}


//without image
function createPost($title, $content, $username, $thumbnail) {
    global $conn;

    // Prepare SQL to insert post into database
    $sql = "INSERT INTO posts (Title, Content, UserID, PhotoPath) VALUES (?, ?, (SELECT UserID FROM users WHERE Username = ?), ?)";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ssss", $title, $content, $username, $thumbnail);
        if ($stmt->execute()) {
            return true;
        } else {
            // Log the error for debugging
            error_log("Error executing query: " . $stmt->error);
            return false;
        }
    } else {
        // Log the error for debugging
        error_log("Error preparing query: " . $conn->error);
        return false;
    }
}



//automatic to siya para i-display ang mga posts sa homepage
function getRecentPosts() {
    global $conn; 
    $sql = "SELECT Posts.*, Users.Username FROM Posts JOIN Users ON Posts.UserID = Users.UserID ORDER BY CreatedAt DESC LIMIT 25"; //nakalimit lang sa 25, from latest to oldest. pwede pa i-increase para marami pang post na makita 
    $result = $conn->query($sql);
    return ($result->num_rows > 0) ? $result->fetch_all(MYSQLI_ASSOC) : array();
}

function searchAndFilterPosts($search, $tag) {
    global $conn;
    $query = "SELECT p.*, u.Username FROM posts p JOIN users u ON p.UserID = u.UserID
              LEFT JOIN post_tags pt ON p.PostID = pt.PostID
              LEFT JOIN tags t ON pt.TagID = t.TagID
              WHERE 1=1";

    if (!empty($search)) {
        $query .= " AND (p.Title LIKE ? OR p.Content LIKE ?)";
    }

    if (!empty($tag)) {
        $query .= " AND t.TagName = ?";
    }

    $stmt = $conn->prepare($query);

    if (!empty($search) && !empty($tag)) {
        $searchParam = '%' . $search . '%';
        $stmt->bind_param('sss', $searchParam, $searchParam, $tag);
    } elseif (!empty($search)) {
        $searchParam = '%' . $search . '%';
        $stmt->bind_param('ss', $searchParam, $searchParam);
    } elseif (!empty($tag)) {
        $stmt->bind_param('s', $tag);
    }

    $stmt->execute();
    $result = $stmt->get_result();
    $posts = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    return $posts;
}

//for getting the comm id
function getCommentsByPostId($post_id) {
    global $conn;
    $sql = "SELECT ProfilePic, Comments.*, Users.Username FROM Comments JOIN Users ON Comments.UserID = Users.UserID JOIN userprofiles ON Comments.UserID = userprofiles.UserID WHERE PostID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $post_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return ($result->num_rows > 0) ? $result->fetch_all(MYSQLI_ASSOC) : array();
}

function getCommunityCommentsByPostId($post_id) {
    global $conn;
    $sql = "SELECT ProfilePic, Community_comments.*, Users.Username FROM Community_comments JOIN Users ON community_comments.UserID = Users.UserID JOIN userprofiles ON Community_comments.UserID = userprofiles.UserID WHERE PostID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $post_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return ($result->num_rows > 0) ? $result->fetch_all(MYSQLI_ASSOC) : array();
}

//comment function
function createComment($user_id, $post_id, $content) {
    global $conn;
    $sql = "INSERT INTO Comments (PostID, UserID, Content) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iis", $post_id, $user_id, $content);
    return $stmt->execute();
}


function createCommunityComment($post_id, $user_id, $content) {
    global $conn;
    $sql = "INSERT INTO community_comments (PostID, UserID, Content) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iis", $post_id, $user_id, $content);
    return $stmt->execute();
}
//kunin yung username ng user through id
function getUserIdByUsername($username) {
    global $conn; 

    $sql = "SELECT UserID FROM Users WHERE Username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        return $user['UserID'];
    } else {
        return false;
    }
}

function getUserProfilePic($userId) {
    global $conn;
    $sql = "SELECT ProfilePic FROM userprofiles WHERE UserID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    return !empty($row['ProfilePic']) ? $row['ProfilePic'] : 'default_pic.svg';
}

//post ng user (to be displayed in the user's profile)
function getUserPosts($username) {
    global $conn; 

    $sql = "SELECT * FROM Posts WHERE UserID = (SELECT UserID FROM Users WHERE Username = ?) ORDER BY PostID DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    return $result->fetch_all(MYSQLI_ASSOC);
}

//for updating ng profile pic
function updateUserProfilePicture($username, $profile_picture_path) {
    global $conn; 

    $sql = "UPDATE UserProfiles SET ProfilePic = ? WHERE UserID = (SELECT UserID FROM Users WHERE Username = ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $profile_picture_path, $username);
    $stmt->execute();

    return $stmt->affected_rows > 0;
}

function updateOrInsertUserBio($userID, $bio) {
    global $conn;

    // Check if the user already has a profile entry
    $checkIfExists = "SELECT UserID FROM UserProfiles WHERE UserID = ?";
    $stmt = $conn->prepare($checkIfExists);
    $stmt->bind_param("i", $userID);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // If user profile exists, update the bio
        $sql = "UPDATE UserProfiles SET Bio = ? WHERE UserID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $bio, $userID);
        $stmt->execute();
    } else {
        // If user profile doesn't exist, insert a new entry
        $sql = "INSERT INTO UserProfiles (UserID, Bio) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("is", $userID, $bio);
        $stmt->execute();
    }

    $stmt->close();
}

function InsertUserBioOnfirstLogin($userID) {
    global $conn;

    $uID = $userID;
    // Check if the user already has a profile entry
    $checkIfExists = "SELECT UserID FROM UserProfiles WHERE UserID = ?";
    $stmt = $conn->prepare($checkIfExists);
    $stmt->bind_param("i", $uID);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows < 1) {
        // If user profile doesn't exist, insert a new entry
        $sql = "INSERT INTO UserProfiles (UserID, Bio) VALUES (:uID, 'bio not set');";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("is", $uID, $bio);
        $stmt->execute();
    }
    $stmt->close();
}

//for modifying user bio
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Check if userID is set and is a valid integer
    if(isset($_POST['userID']) && filter_var($_POST['userID'], FILTER_VALIDATE_INT)) {
        // Get user ID and bio from the form
        $userID = $_POST['userID'];
        $bio = $_POST['bio'];

        // Call the function to update or insert user's bio
        updateOrInsertUserBio($userID, $bio);

        // Redirect back to profile page
        header('Location: edit_profile.php');
        exit;
    } else {
        // Handle invalid or missing userID
        echo "Invalid or missing userID";
        // You can also redirect the user to an error page or display a message
    }
}//end of update user bio function

//di rin nagamit, para to sa users to put some desciption in their profile. since we don't have yet a feature for add friend, this will not be really necessary
function getbioByUsername($username) {
    global $db;

    // Prepare and execute SQL query
    $query = "SELECT u.Username, u.Email, up.Bio, up.ProfilePic 
              FROM Users u 
              LEFT JOIN UserProfiles up ON u.UserID = up.UserID 
              WHERE u.Username = :username";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':username', $username);
    $stmt->execute();

    // Fetch user data
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    return $user;
}


//magkuha ng profile pic
function getUserProfileById($userID) {
    global $conn;

    try {
        // Prepare and execute the SQL query
        $statement = $conn->prepare("SELECT * FROM UserProfiles WHERE UserID = ?");
        $statement->bind_param("i", $userID);
        $statement->execute();

        
        $result = $statement->get_result();
        $profile = $result->fetch_assoc();

        return $profile;

    } catch (Exception $e) {

        echo "Error: " . $e->getMessage();
        return false;
    }
}



// Function to edit a post
function editPost($postID, $title, $content, $userID) {
    global $conn;

    // Sanitize input para secure using escape method to avoid sql injection
    $postID = mysqli_real_escape_string($conn, $postID);
    $title = mysqli_real_escape_string($conn, $title);
    $content = mysqli_real_escape_string($conn, $content);
    $userID = mysqli_real_escape_string($conn, $userID);

    // Validate user's authorization to edit post
    $query = "SELECT UserID FROM Posts WHERE PostID = '$postID' LIMIT 1";

    $result = mysqli_query($conn, $query);

    //hanapin ang post associated sa user id
    if (!$result || mysqli_num_rows($result) !== 1) {

        return false; // Post not found or error occurred
    }

    //i-collect lahat ng post
    $row = mysqli_fetch_assoc($result);

    //i-check if yung mag eedit ba ng post is mismong author
    if ($row['UserID'] != $userID) {

        return false; // User is not authorized to edit this post
    }

    // Update post in the database gamit ang sql query
    $query = "UPDATE Posts SET Title = '$title', Content = '$content' WHERE PostID = '$postID'";

    $result = mysqli_query($conn, $query);

    if (!$result) {

        return false; // Error occurred while updating post
    }

    return true; // Post updated successfully
}


// Function to get post by ID
function getPostById($post_id) {
    global $conn;
    $sql = "SELECT p.*, u.Username FROM Posts p INNER JOIN Users u ON p.UserID = u.UserID WHERE PostID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $post_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

// Function to update a post
function updatePost($postId, $title, $content, $photoPath = null) {
    global $conn;
    $title = mysqli_real_escape_string($conn, $title);
    $content = mysqli_real_escape_string($conn, $content);
    if ($photoPath) {
        $sql = "UPDATE Posts SET Title = ?, Content = ?, PhotoPath = ? WHERE PostID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $title, $content, $photoPath, $postId);
    } else {
        $sql = "UPDATE Posts SET Title = ?, Content = ? WHERE PostID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $title, $content, $postId);
    }
    return $stmt->execute();
}

// Function to get tags by post ID
function getTagsByPostId($postId) {
    global $conn;
    $sql = "SELECT t.TagName FROM tags t INNER JOIN post_tags pt ON t.TagID = pt.TagID WHERE pt.PostID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $postId);
    $stmt->execute();
    $result = $stmt->get_result();
    $tags = [];
    while ($row = $result->fetch_assoc()) {
        $tags[] = $row['TagName'];
    }
    return $tags;
}

// Function to update post tags
function updatePostTags($postId, $selectedTags) {
    global $conn;
    // Delete existing tags
    $sql = "DELETE FROM post_tags WHERE PostID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $postId);
    $stmt->execute();

    // Insert new tags
    if (!empty($selectedTags)) {
        $tagsArray = explode(',', $selectedTags);
        $stmt = $conn->prepare("INSERT INTO post_tags (PostID, TagID) VALUES (?, (SELECT TagID FROM tags WHERE TagName = ?))");
        foreach ($tagsArray as $tagName) {
            $stmt->bind_param("is", $postId, $tagName);
            $stmt->execute();
        }
        $stmt->close();
    }
}

//verification of owner-post
function userOwnsPost($username, $postId) {
    global $conn;
    
    // Query the database to check if the user with the given username owns the post with the given postId
    $query = "SELECT p.UserID
              FROM Posts p
              INNER JOIN UserProfiles up ON p.UserID = up.UserID
              INNER JOIN Users u ON up.UserID = u.UserID
              WHERE p.PostID = ? AND u.Username = ?";
    
    // Prepare the statement
    $statement = $conn->prepare($query);
    
    // Bind parameters
    $statement->bind_param('is', $postId, $username);
    
    // Execute the statement
    $statement->execute();
    
    // Bind the result variables
    $statement->bind_result($userID);
    
    // Fetch the result
    $statement->fetch();
    
    // Check if the user owns the post
    if ($userID) {
        // User owns the post
        return true;
    } else {
        // User does not own the post
        return false;
    }
}

//para mag delete ng post (along with the comments,likes, and replies)
function deletePost($postID, $userID) {
    global $conn;

    // Sanitize input
    $postID = mysqli_real_escape_string($conn, $postID);
    $userID = mysqli_real_escape_string($conn, $userID);

    // Validate user's authorization to delete post
    $query = "SELECT UserID FROM Posts WHERE PostID = '$postID' LIMIT 1";
    $result = mysqli_query($conn, $query);

    if (!$result || mysqli_num_rows($result) !== 1) {
        return false; // Post not found or error occurred
    }

    $row = mysqli_fetch_assoc($result);

    if ($row['UserID'] != $userID) {
        return false; // User is not authorized to delete this post if di siya ang author.
    }

    // Delete likes associated with the post first
    $query = "DELETE FROM Likes WHERE PostID = '$postID'";
    $result = mysqli_query($conn, $query);

    if (!$result) {
        return false; // Error occurred while deleting likes
    }

    // Delete comments associated with the post
    $query = "DELETE FROM Comments WHERE PostID = '$postID'";
    $result = mysqli_query($conn, $query);

    if (!$result) {
        return false; // Error occurred while deleting comments
    }

    // Then delete the post from the database
    $query = "DELETE FROM Posts WHERE PostID = '$postID'";
    $result = mysqli_query($conn, $query);

    if (!$result) {
        return false; // Error occurred while deleting post
    }

    return true; // Post deleted successfully
}


function getCommunityPostById($post_id) {
    global $conn; 

    
    $sql = "SELECT * FROM community_posts p INNER JOIN Users u ON p.UserID = u.UserID WHERE PostID = ?";

   
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $post_id);

   
    $stmt->execute();

   
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {

        // Fetch the post details
        $post = $result->fetch_assoc();

        return $post;

    } else {

        return null;
    }
}


// Update username in the database
function updateUsername($oldUsername, $newUsername) {
    global $conn;
    $stmt = $conn->prepare("UPDATE Users SET Username = ? WHERE Username = ?");
    $stmt->bind_param("ss", $newUsername, $oldUsername);
    $stmt->execute();
    $stmt->close();
}

// Update email in the database
function updateEmail($username, $newEmail) {
    global $conn;
    $stmt = $conn->prepare("UPDATE Users SET Email = ? WHERE Username = ?");
    $stmt->bind_param("ss", $newEmail, $username);
    $stmt->execute();
    $stmt->close();
}

// Verify password
function verifyPassword($username, $password) {
    global $conn;
    $stmt = $conn->prepare("SELECT Password FROM Users WHERE Username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows == 1) {
        $stmt->bind_result($hashedPassword);
        $stmt->fetch();
        if (password_verify($password, $hashedPassword)) {
            $stmt->close();
            return true;
        }
    }
    $stmt->close();
    return false;
}


// Function to retrieve user data by username
function getUserBio($username) {
    global $conn;

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT Users.UserID, Users.Username, Users.Email, COALESCE(UserProfiles.Bio, 'Bio not set') AS Bio FROM Users LEFT JOIN UserProfiles ON Users.UserID = UserProfiles.UserID WHERE Users.Username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();
    $conn->close();

    if ($user && isset($user['Bio'])) {
        return $user;
    } else {
        return ['UserID' => null, 'Username' => $username, 'Email' => '', 'Bio' => 'Bio not set']; // Set email and bio to "Bio not set"
    }
}

// Fetch posts from followed users
function getPostsFromFollowedUsers($user_id, $conn) {
    $sql = "SELECT 
                posts.PostID, 
                up.ProfilePic, 
                posts.UserID, 
                posts.Title, 
                posts.Content, 
                posts.CreatedAt, 
                posts.UpdatedAt, 
                users.Username 
            FROM posts
            JOIN users ON posts.UserID = users.UserID
            JOIN userprofiles up ON users.UserID = up.UserID
            JOIN follows ON posts.UserID = follows.FollowingID
            WHERE follows.FollowerID = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    return $stmt->get_result();
}


// Fetch posts from users who are following the current user
function getPostsFromFollowers($user_id, $conn) {
    $sql = "SELECT 
                posts.PostID, 
                up.ProfilePic, 
                posts.UserID, 
                posts.Title, 
                posts.Content, 
                posts.CreatedAt, 
                posts.UpdatedAt, 
                users.Username 
            FROM posts
            JOIN users ON posts.UserID = users.UserID
            JOIN userprofiles up ON users.UserID = up.UserID
            JOIN follows ON posts.UserID = follows.FollowerID
            WHERE follows.FollowingID = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    return $stmt->get_result();
}


function getPostsSortedByTime() {
    global $conn;

    $sql = "SELECT * FROM Posts p INNER join Users u ON p.UserID = u.UserID ORDER BY CreatedAt DESC";
    $result = $conn->query($sql);

    $posts = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $posts[] = $row;
        }
    }

    return $posts;
}

// Function to get posts sorted by date
function getPostsSortedByDate() {
    global $conn; // Access the global connection variable

    $sql = "SELECT * FROM Posts p INNER JOIN Users u ON u.UserID = p.UserID ORDER BY CreatedAt ASC";
    $result = $conn->query($sql);

    $posts = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $posts[] = $row;
        }
    }

    return $posts;
}

// Function to get posts sorted by number of comments (popularity)
function getPostsSortedByComments() {
    global $conn;

    $sql = "SELECT Username, Posts.*, COUNT(Comments.CommentID) AS comment_count 
            FROM Posts 
            LEFT JOIN Comments ON Posts.PostID = Comments.PostID
            LEFT JOIN Users ON Posts.UserID = Users.UserID
            GROUP BY Posts.PostID 
            ORDER BY comment_count DESC";
    $result = $conn->query($sql);

    $posts = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $posts[] = $row;
        }
    }

    return $posts;
}

//posts sorted by highest bpts
function getPostsSortedByBPTS() {
    global $conn;

    // Use LEFT JOIN for Likes to include posts even if they have no likes
    $sql = "SELECT Username, Posts.*, COUNT(Likes.LikeID) AS like_count 
            FROM Posts 
            LEFT JOIN Likes ON Posts.PostID = Likes.PostID
            JOIN Users ON Posts.UserID = Users.UserID
            GROUP BY Posts.PostID
            ORDER BY like_count DESC";

    $result = $conn->query($sql);

    $posts = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $posts[] = $row;
        }
    }

    return $posts;
}

function getFilteredPosts($sort = '', $timeframe = '', $tag = '') {
    global $conn;
    
    $sql = "SELECT DISTINCT p.*, u.Username, 
            COUNT(DISTINCT l.LikeID) as like_count,
            COUNT(DISTINCT c.CommentID) as comment_count
            FROM Posts p
            LEFT JOIN Users u ON p.UserID = u.UserID
            LEFT JOIN Likes l ON p.PostID = l.PostID
            LEFT JOIN Comments c ON p.PostID = c.PostID";

    // Add tag filtering if specified
    if (!empty($tag)) {
        $sql .= " LEFT JOIN post_tags pt ON p.PostID = pt.PostID
                  LEFT JOIN tags t ON pt.TagID = t.TagID
                  WHERE t.TagName = ?";
    }

    // Add timeframe filtering
    $timeframeClause = "";
    switch ($timeframe) {
        case 'today':
            $timeframeClause = " AND DATE(p.CreatedAt) = CURDATE()";
            break;
        case 'week':
            $timeframeClause = " AND p.CreatedAt >= DATE_SUB(NOW(), INTERVAL 1 WEEK)";
            break;
        case 'month':
            $timeframeClause = " AND p.CreatedAt >= DATE_SUB(NOW(), INTERVAL 1 MONTH)";
            break;
        case 'year':
            $timeframeClause = " AND p.CreatedAt >= DATE_SUB(NOW(), INTERVAL 1 YEAR)";
            break;
    }
    
    if (!empty($timeframeClause)) {
        $sql .= empty($tag) ? " WHERE 1=1" . $timeframeClause : $timeframeClause;
    }

    $sql .= " GROUP BY p.PostID";

    // Add sorting
    switch ($sort) {
        case 'time':
            $sql .= " ORDER BY p.CreatedAt DESC";
            break;
        case 'date':
            $sql .= " ORDER BY p.CreatedAt ASC";
            break;
        case 'comments':
            $sql .= " ORDER BY comment_count DESC";
            break;
        case 'Bpts':
            $sql .= " ORDER BY like_count DESC";
            break;
        case 'popular':
            $sql .= " ORDER BY (like_count + comment_count) DESC";
            break;
        case 'trending':
            $sql .= " AND p.CreatedAt >= DATE_SUB(NOW(), INTERVAL 1 WEEK)
                     ORDER BY (like_count + comment_count) DESC";
            break;
        default:
            $sql .= " ORDER BY p.CreatedAt DESC";
    }

    $stmt = $conn->prepare($sql);
    if (!empty($tag)) {
        $stmt->bind_param("s", $tag);
    }
    $stmt->execute();
    $result = $stmt->get_result();

    return $result->fetch_all(MYSQLI_ASSOC);
}

function getPostEngagementScore($postId) {
    global $conn;
    
    $sql = "SELECT 
            (COUNT(DISTINCT l.LikeID) * 1.5 + COUNT(DISTINCT c.CommentID)) as engagement_score
            FROM Posts p
            LEFT JOIN Likes l ON p.PostID = l.PostID
            LEFT JOIN Comments c ON p.PostID = c.PostID
            WHERE p.PostID = ?
            GROUP BY p.PostID";
            
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $postId);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    
    return $row['engagement_score'] ?? 0;
}

// Function to handle like/unlike actions
function toggleLike($postID, $userID) {
    global $conn;
    
    // Check if the user already liked the post
    $query = "SELECT * FROM Likes WHERE PostID = ? AND UserID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $postID, $userID);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Unlike the post
        $query = "DELETE FROM Likes WHERE PostID = ? AND UserID = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ii", $postID, $userID);
        $stmt->execute();
        return getLikeCount($postID);
    } else {
        // Like the post
        $query = "INSERT INTO Likes (PostID, UserID) VALUES (?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ii", $postID, $userID);
        $stmt->execute();

        // Get post owner information and post details
        $postQuery = "SELECT p.UserID, p.Title, u.Username 
                     FROM Posts p 
                     JOIN Users u ON p.UserID = u.UserID 
                     WHERE p.PostID = ?";
        $stmt = $conn->prepare($postQuery);
        $stmt->bind_param("i", $postID);
        $stmt->execute();
        $postResult = $stmt->get_result();
        $postData = $postResult->fetch_assoc();

        // Get liker's username
        $likerQuery = "SELECT Username FROM Users WHERE UserID = ?";
        $stmt = $conn->prepare($likerQuery);
        $stmt->bind_param("i", $userID);
        $stmt->execute();
        $likerResult = $stmt->get_result();
        $likerData = $likerResult->fetch_assoc();

        // Create notification for post owner
        if ($postData['UserID'] != $userID) { // Don't notify if user likes their own post
            $postTitle = strlen($postData['Title']) > 30 ? 
                        substr($postData['Title'], 0, 30) . '...' : 
                        $postData['Title'];
            
            $content = sprintf(
                '%s liked your post "%s"',
                htmlspecialchars($likerData['Username']),
                htmlspecialchars($postTitle)
            );
            
            createNotification($postData['UserID'], $userID, 'like', $content, $postID);
        }

        return getLikeCount($postID);
    }
}

// Optional: Add more notification types for different activities
function createCommentNotification($postID, $commentUserID, $comment) {
    global $conn;
    
    // Get post owner and post details
    $postQuery = "SELECT p.UserID, p.Title, u.Username 
                 FROM Posts p 
                 JOIN Users u ON p.UserID = u.UserID 
                 WHERE p.PostID = ?";
    $stmt = $conn->prepare($postQuery);
    $stmt->bind_param("i", $postID);
    $stmt->execute();
    $postData = $stmt->get_result()->fetch_assoc();
    
    // Get commenter's username
    $commenterQuery = "SELECT Username FROM Users WHERE UserID = ?";
    $stmt = $conn->prepare($commenterQuery);
    $stmt->bind_param("i", $commentUserID);
    $stmt->execute();
    $commenterData = $stmt->get_result()->fetch_assoc();
    
    if ($postData['UserID'] != $commentUserID) {
        $postTitle = strlen($postData['Title']) > 30 ? 
                    substr($postData['Title'], 0, 30) . '...' : 
                    $postData['Title'];
        
        $commentPreview = strlen($comment) > 50 ? 
                         substr($comment, 0, 50) . '...' : 
                         $comment;
        
        $content = sprintf(
            '<strong>%s</strong> commented on your post "<a href="view_post.php?id=%d">%s</a>": "%s"',
            htmlspecialchars($commenterData['Username']),
            $postID,
            htmlspecialchars($postTitle),
            htmlspecialchars($commentPreview)
        );
        
        createNotification($postData['UserID'], $commentUserID, 'comment', $content, $postID);
    }
}
//Number of likes
function getLikeCount($postID) {
    global $conn;

    $query = "SELECT COUNT(*) AS LikeCount FROM Likes WHERE PostID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $postID);
    $stmt->execute();
    $result = $stmt->get_result();

    // Fetch the result
    $row = $result->fetch_assoc();

    return $row['LikeCount'];
}

function getCommunityLikeCount($postID) {
    // Access the global database connection
    global $conn;

    // Prepare and execute the query to count likes for the given post
    $query = "SELECT COUNT(*) AS likeCount FROM community_likes WHERE PostID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $postID);
    $stmt->execute();
    $result = $stmt->get_result();
    
    // Fetch the like count
    $row = $result->fetch_assoc();
    $likeCount = $row['likeCount'];

    // Close the statement
    $stmt->close();

    return $likeCount;
}

function getCommunityCountComment($postID) {
    // Access the global database connection
    global $conn;

    // Prepare and execute the query to count comments for the given post
    $query = "SELECT COUNT(*) AS commentCount FROM community_comments WHERE PostID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $postID);
    $stmt->execute();
    $result = $stmt->get_result();
    
    // Fetch the comment count
    $row = $result->fetch_assoc();
    $commentCount = $row['commentCount'];

    // Close the statement
    $stmt->close();

    return $commentCount;
}


//comment replies
function getRepliesByCommentId($commentId) {
    global $conn;

    $sql = "SELECT Replies.*, Users.Username, UserProfiles.ProfilePic 
            FROM Replies 
            JOIN Users ON Replies.UserID = Users.UserID 
            JOIN UserProfiles ON Replies.UserID = UserProfiles.UserID 
            WHERE Replies.CommentID = ? 
            ORDER BY Replies.CreatedAt ASC";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("i", $commentId);
    $stmt->execute();
    $result = $stmt->get_result();

    $replies = [];
    while ($row = $result->fetch_assoc()) {
        $replies[] = $row;
    }

    $stmt->close();

    return $replies;
}

function getCommunityRepliesByCommentId($commentId) {
    global $conn;

    $sql = "SELECT Community_replies.*, Users.Username, UserProfiles.ProfilePic 
            FROM Community_replies 
            JOIN Users ON Community_replies.UserID = Users.UserID 
            JOIN UserProfiles ON Community_replies.UserID = UserProfiles.UserID 
            WHERE Community_replies.CommentID = ? 
            ORDER BY Community_replies.CreatedAt ASC";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("i", $commentId);
    $stmt->execute();
    $result = $stmt->get_result();

    $replies = [];
    while ($row = $result->fetch_assoc()) {
        $replies[] = $row;
    }

    $stmt->close();

    return $replies;
}

//uploading a reply to a comment
function insertReply($comment_id, $user_id, $content) {
    global $conn;

    // Prepare and execute the SQL query
    $sql = "INSERT INTO Replies (CommentID, UserID, Content) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iis", $comment_id, $user_id, $content);
    $result = $stmt->execute();

    // Return true if the query was successful, otherwise false
    return $result;
}

function insertCommunityReply($comment_id, $user_id, $content) {
    global $conn;

    // Prepare and execute the SQL query
    $sql = "INSERT INTO Community_replies (CommentID, UserID, Content) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iis", $comment_id, $user_id, $content);
    $result = $stmt->execute();

    // Return true if the query was successful, otherwise false
    return $result;
}

// Function to count the number of comments for a given post ID
function countComments($postId) {
    global $conn;

    $sql = "SELECT COUNT(*) AS commentCount FROM Comments WHERE PostID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $postId);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();

    return $row['commentCount'];
}

// Function to count the number of replies for a given comment ID
function countReplies($commentId) {
    global $conn;

    $sql = "SELECT COUNT(*) AS replyCount FROM Replies WHERE CommentID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $commentId);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();

    return $row['replyCount'];
}


//search users
function searchUsers($query) {
    global $conn;
    
    // Sanitize the input query
    $query = $conn->real_escape_string($query);

    // Prepare the SQL statement
    $sql = "SELECT Users.UserID, Users.Username, Users.Email, UserProfiles.ProfilePic 
            FROM Users 
            LEFT JOIN UserProfiles ON Users.UserID = UserProfiles.UserID 
            WHERE Users.Username LIKE '%$query%' OR Users.Email LIKE '%$query%'";

    // Execute the query
    $result = $conn->query($sql);

    if ($result === false) {
        die("Query failed: " . $conn->error);
    }

    // Fetch results
    return $result->fetch_all(MYSQLI_ASSOC);
}

//Functions para sa following feature
// Follow a user
function followUser($followerID, $followingID) {
    global $conn;
    $stmt = $conn->prepare("INSERT INTO follows (FollowerID, FollowingID) VALUES (?, ?)");
    if ($stmt) {
        $stmt->bind_param("ii", $followerID, $followingID);
        $stmt->execute();
        $stmt->close();
        return true;
    } else {
        // Handle error
        return false;
    }
}

// Unfollow a user
function unfollowUser($followerID, $followingID) {
    global $conn;
    $stmt = $conn->prepare("DELETE FROM follows WHERE FollowerID = ? AND FollowingID = ?");
    if ($stmt) {
        $stmt->bind_param("ii", $followerID, $followingID);
        $stmt->execute();
        $stmt->close();
        return true;
    } else {
        // Handle error
        return false;
    }
}

// Check if the user is following another user
function isFollowing($followerID, $followingID) {
    global $conn;
    $stmt = $conn->prepare("SELECT COUNT(*) FROM follows WHERE FollowerID = ? AND FollowingID = ?");
    if ($stmt) {
        $stmt->bind_param("ii", $followerID, $followingID);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();
        return $count > 0;
    } else {
        // Handle error
        return false;
    }
}

// Get followers of the logged-in user
function getFollowers($userID) {
    global $conn;
    $stmt = $conn->prepare("SELECT Users.UserID, Users.Username, Users.Email, UserProfiles.ProfilePic 
                            FROM follows 
                            INNER JOIN Users ON follows.FollowerID = Users.UserID
                            LEFT JOIN UserProfiles ON Users.UserID = UserProfiles.UserID
                            WHERE follows.FollowingID = ?");
    if ($stmt) {
        $stmt->bind_param("i", $userID);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    } else {
        return [];
    }
}

// Get users followed by the logged-in user
function getFollowing($userID) {
    global $conn;
    $stmt = $conn->prepare("SELECT Users.UserID, Users.Username, Users.Email, UserProfiles.ProfilePic 
                            FROM follows 
                            INNER JOIN Users ON follows.FollowingID = Users.UserID
                            LEFT JOIN UserProfiles ON Users.UserID = UserProfiles.UserID
                            WHERE follows.FollowerID = ?");
    if ($stmt) {
        $stmt->bind_param("i", $userID);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    } else {
        return [];
    }
}

// Search followers or followed users
function searchFollowUsers($userID, $query, $type) {
    global $conn;
    $query = '%' . $conn->real_escape_string($query) . '%';
    
    if ($type === 'followers') {
        $stmt = $conn->prepare("SELECT Users.UserID, Users.Username, Users.Email, UserProfiles.ProfilePic 
                                FROM follows 
                                INNER JOIN Users ON follows.FollowerID = Users.UserID
                                LEFT JOIN UserProfiles ON Users.UserID = UserProfiles.UserID
                                WHERE follows.FollowingID = ? AND (Users.Username LIKE ? OR Users.Email LIKE ?)");
    } else {
        $stmt = $conn->prepare("SELECT Users.UserID, Users.Username, Users.Email, UserProfiles.ProfilePic 
                                FROM follows 
                                INNER JOIN Users ON follows.FollowingID = Users.UserID
                                LEFT JOIN UserProfiles ON Users.UserID = UserProfiles.UserID
                                WHERE follows.FollowerID = ? AND (Users.Username LIKE ? OR Users.Email LIKE ?)");
    }

    if ($stmt) {
        $stmt->bind_param("iss", $userID, $query, $query);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    } else {
        return [];
    }
}

//posts from mutuals
function getFollowedUsers($userID) {
    global $conn;
    $sql = "SELECT u.UserID, u.Username 
            FROM Follows f
            JOIN Users u ON f.FollowerID = u.UserID
            WHERE f.FollowID = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userID);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $followedUsers = [];
    while ($row = $result->fetch_assoc()) {
        $followedUsers[] = $row;
    }
    
    $stmt->close();
    return $followedUsers;
}
function getPostsFromUsers($userIDs) {
    global $conn;
    
    if (empty($userIDs)) {
        return [];
    }
    
    // Build placeholders for the query
    $placeholders = implode(',', array_fill(0, count($userIDs), '?'));
    
    $sql = "SELECT p.PostID, p.UserID, p.Title, p.Content, p.CreatedAt, p.updatedAt, u.Username 
            FROM Posts p
            JOIN Users u ON p.UserID = u.UserID
            WHERE p.UserID IN ($placeholders)
            ORDER BY p.CreatedAt DESC";
    
    $stmt = $conn->prepare($sql);
    
    // Dynamically bind parameters
    $types = str_repeat('i', count($userIDs));
    $stmt->bind_param($types, ...$userIDs);
    
    $stmt->execute();
    $result = $stmt->get_result();
    
    $posts = [];
    while ($row = $result->fetch_assoc()) {
        $posts[] = $row;
    }
    
    $stmt->close();
    return $posts;
}

//
//FUNCTIONS FOR USER ANALYTICS//
//
function trackPostView($postID) {
    global $conn;
    $stmt = $conn->prepare("INSERT INTO post_activity (PostID, ActivityType) VALUES (?, 'view')");
    $stmt->bind_param("i", $postID);
    $stmt->execute();
    $stmt->close();
    $conn->close();
}

function trackPostLike($postID, $userID) {
    global $conn;
    $stmt = $conn->prepare("INSERT INTO likes (PostID, UserID, LikedAt) VALUES (?, ?, NOW())");
    $stmt->bind_param("ii", $postID, $userID);
    $stmt->execute();
    $stmt->close();
    $conn->close();
    
    // Also track the like in post_activity
    $stmt = $conn->prepare("INSERT INTO post_activity (PostID, ActivityType) VALUES (?, 'like')");
    $stmt->bind_param("i", $postID);
    $stmt->execute();
    $stmt->close();
}

function trackUserLogin($userID) {
    global $conn;
    $stmt = $conn->prepare("INSERT INTO user_activity (UserID, ActivityType) VALUES (?, 'login')");
    $stmt->bind_param("i", $userID);
    $stmt->execute();
    $stmt->close();
    $conn->close();
}

function trackPostComment($postID, $userID) {
    global $conn;
    $stmt = $conn->prepare("INSERT INTO post_activity (PostID, ActivityType) VALUES (?, 'comment')");
    $stmt->bind_param("i", $postID);
    $stmt->execute();
    $stmt->close();
    
    // Also track the comment in comments table
    $stmt = $conn->prepare("INSERT INTO comments (PostID, UserID, Content, CreatedAt) VALUES (?, ?, 'Comment content here', NOW())");
    $stmt->bind_param("ii", $postID, $userID);
    $stmt->execute();
    $stmt->close();
    $conn->close();
}

function trackPostReply($commentID, $userID) {
    global $conn;
    $stmt = $conn->prepare("INSERT INTO post_activity (PostID, ActivityType) VALUES ((SELECT PostID FROM comments WHERE CommentID = ?), 'reply')");
    $stmt->bind_param("i", $commentID);
    $stmt->execute();
    $stmt->close();
    
    // Also track the reply in replies table
    $stmt = $conn->prepare("INSERT INTO replies (CommentID, UserID, Content, CreatedAt) VALUES (?, ?, 'Reply content here', NOW())");
    $stmt->bind_param("ii", $commentID, $userID);
    $stmt->execute();
    $stmt->close();
    $conn->close();
}


//SIDEBAR FUNCTIONS
// Query to get top users (most likes collected)
function getTopUsers() {
    global $conn;
    $sql = "SELECT u.Username, COUNT(l.LikeID) AS total_likes, up.ProfilePic
            FROM users u
            LEFT JOIN likes l ON u.UserID = l.UserID JOIN userprofiles up ON up.UserID = l.UserID
            GROUP BY u.UserID
            ORDER BY total_likes DESC
            LIMIT 5"; // Get the top 5 users
    $result = mysqli_query($conn, $sql);

    // Fetch and return the data as an associative array
    $topUsers = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $topUsers[] = $row;
    }

    return $topUsers;
}

function getTopUsersByTotalLikes() {
    global $conn;
    $sql = "SELECT u.Username, COUNT(l.LikeID) AS total_likes, up.ProfilePic
            FROM users u
            JOIN userprofiles up ON u.UserID = up.UserID
            JOIN posts p ON up.UserID = p.UserID
            JOIN likes l ON p.PostID = l.PostID
            GROUP BY u.UserID
            ORDER BY total_likes DESC
            LIMIT 10"; // Get the top 5 users by total accumulated likes
    $result = mysqli_query($conn, $sql);

    // Fetch and return the data as an associative array
    $topUsers = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $topUsers[] = $row;
    }

    return $topUsers;
}

function getTopDailyPosts() {
    global $conn;
    $query = "SELECT 
                p.PostID, 
                p.Title, 
                p.PhotoPath, 
                p.Content, 
                u.Username, 
                up.ProfilePic,
                COUNT(DISTINCT l.LikeID) AS TotalLikes, 
                COUNT(DISTINCT c.CommentID) AS TotalComments,
                (COUNT(DISTINCT l.LikeID) + COUNT(DISTINCT c.CommentID)) AS Engagement
            FROM 
                posts p
            JOIN likes l ON l.PostID = p.PostID
            JOIN comments c ON c.PostID = p.PostID
            JOIN users u ON u.UserID = p.UserID
            JOIN userprofiles up ON p.UserID = up.UserID    
            WHERE 
                DATE(p.CreatedAt) = CURDATE()
            GROUP BY 
                p.PostID
            ORDER BY 
                Engagement DESC
            LIMIT 5;
            ";
    $result = mysqli_query($conn, $query);
    if (!$result) {
        die('Query Failed: ' . mysqli_error($conn));
    }
    $topDailyPosts = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $topDailyPosts[] = $row;
    }
    return $topDailyPosts;
}


// Query to get top communities (most number of members)
function getTopCommunities() {
    global $conn;
    $sql = "SELECT c.Title, COUNT(cm.UserID) AS member_count
            FROM communities c
            LEFT JOIN community_members cm ON c.CommunityID = cm.CommunityID
            GROUP BY c.CommunityID
            ORDER BY member_count DESC
            LIMIT 10"; // Get the top 5 communities
    $result = mysqli_query($conn, $sql);

    // Fetch and return the data as an associative array
    $topCommunities = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $topCommunities[] = $row;
    }

    return $topCommunities;
}

// Query to get notifications
function getNotifications($userID) {
    global $conn;
    $sql = "SELECT n.*, u.Username, up.ProfilePic 
            FROM notifications n 
            JOIN users u ON n.UserID = u.UserID 
            LEFT JOIN userprofiles up ON u.UserID = up.UserID 
            WHERE n.RecipientID = ? 
            ORDER BY n.CreatedAt DESC 
            LIMIT 20";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userID);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}

// Create a new notification - Fixed version
function createNotification($recipientID, $userID, $type, $content, $referenceID) {
    global $conn;
    
    // Debug log
    error_log("Creating notification: recipientID=$recipientID, userID=$userID, type=$type, content=$content, referenceID=$referenceID");
    
    // Make sure content is a string and not empty
    if (empty($content)) {
        error_log("Empty notification content");
        return false;
    }

    $sql = "INSERT INTO notifications (RecipientID, UserID, Type, Content, ReferenceID, Seen) 
            VALUES (?, ?, ?, ?, ?, 0)";
    
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        error_log("Prepare failed: " . $conn->error);
        return false;
    }
    
    $stmt->bind_param("iisis", $recipientID, $userID, $type, $content, $referenceID);
    $result = $stmt->execute();
    
    if (!$result) {
        error_log("Execute failed: " . $stmt->error);
        return false;
    }
    
    return true;
}

// Mark notification as read
function markNotificationAsRead($notificationID) {
    global $conn;
    $sql = "UPDATE notifications SET Seen = 1 WHERE NotificationID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $notificationID);
    return $stmt->execute();
}

// Get unread notification count
function getUnreadNotificationCount($userID) {
    global $conn;
    $sql = "SELECT COUNT(*) as count FROM notifications WHERE RecipientID = ? AND Seen = 0";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userID);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    return $row['count'];
}


// HELPER FUNCTIONS
//
function timeAgo($timestamp) {
    $time = strtotime($timestamp);
    $diff = time() - $time;

    if ($diff < 60) {
        // Less than 1 minute ago
        return ($diff <= 1) ? 'just now' : $diff . ' seconds ago';
    } elseif ($diff < 3600) {
        // Less than 1 hour ago
        $minutes = floor($diff / 60);
        return ($minutes == 1) ? '1 min ago' : $minutes . ' mins ago';
    } elseif ($diff < 86400) {
        // Less than 1 day ago
        $hours = floor($diff / 3600);
        return ($hours == 1) ? '1 hr ago' : $hours . ' hrs ago';
    } elseif ($diff < 604800) {
        // Less than 1 week ago
        $days = floor($diff / 86400);
        return ($days == 1) ? '1d ago' : $days . ' days ago';
    } elseif ($diff < 2419200) {
        // Less than 1 month ago (4 weeks approximation)
        $weeks = floor($diff / 604800);
        return ($weeks == 1) ? '1 week ago' : $weeks . ' weeks ago';
    } elseif ($diff < 29030400) {
        // Less than 1 year ago (12 months approximation)
        $months = floor($diff / 2419200);
        return ($months == 1) ? '1 month ago' : $months . ' months ago';
    } else {
        // More than 1 year ago
        $years = floor($diff / 29030400);
        return ($years == 1) ? '1yr ago' : $years . ' years ago';
    }
}

function formatParagraph($text, $maxLength = 300, $maxWords = 300) {
    // Trim leading and trailing spaces
    $cleanText = trim($text);

    // Replace multiple spaces with a single space
    $cleanText = preg_replace('/\s+/', ' ', $cleanText);

    // Truncate the text to the specified number of words
    $words = explode(' ', $cleanText); // Split the text into words
    if (count($words) > $maxWords) {
        $cleanText = implode(' ', array_slice($words, 0, $maxWords)) . '...'; // Take only the first $maxWords
    }

    // Or, truncate the text based on a character limit (if the word limit isn't enough)
    if (strlen($cleanText) > $maxLength) {
        $cleanText = substr($cleanText, 0, $maxLength) . '...'; // Truncate at $maxLength
    }

    // Sanitize the text to prevent HTML injection
    $cleanText = htmlspecialchars($cleanText, ENT_QUOTES, 'UTF-8');

    // Convert newlines to <br> tags
    return nl2br($cleanText);
}

function verifyAdminCredentials($username, $password) {
    global $conn;
    
    // Check if the user exists and is an admin
    $sql = "SELECT UserID, Password, IsAdmin FROM Users WHERE Username = ? AND IsAdmin = 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['Password'])) {
            return $user['UserID'];
        }
    }
    return false;
}

function getAllSettings() {
    global $conn;
    $query = "SELECT * FROM admin_settings";
    $result = mysqli_query($conn, $query);
    $settings = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $settings[$row['SettingName']] = $row['SettingValue'];
    }
    return $settings;
}

function hasUserLikedPost($postID, $userID) {
    global $conn;
    $query = "SELECT COUNT(*) as count FROM Likes WHERE PostID = ? AND UserID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $postID, $userID);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    return $row['count'] > 0;
}

function getLikeDate($postID, $userID) {
    global $conn;
    $query = "SELECT LikedAt FROM Likes WHERE PostID = ? AND UserID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $postID, $userID);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    return $row['LikedAt'];
}

function getUserSettings($userId) {
    global $conn;
    $settings = array();
    
    try {
        // Create the table if it doesn't exist
        $createTable = "CREATE TABLE IF NOT EXISTS user_settings (
            id INT PRIMARY KEY AUTO_INCREMENT,
            user_id INT NOT NULL,
            setting_name VARCHAR(50) NOT NULL,
            setting_value TEXT,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            UNIQUE KEY unique_user_setting (user_id, setting_name),
            FOREIGN KEY (user_id) REFERENCES users(UserID)
        )";
        $conn->query($createTable);
        
        // Now query the settings
        $query = "SELECT setting_name, setting_value FROM user_settings WHERE user_id = ?";
        $stmt = $conn->prepare($query);
        if ($stmt) {
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            $result = $stmt->get_result();
            
            while ($row = $result->fetch_assoc()) {
                $settings[$row['setting_name']] = $row['setting_value'];
            }
            
            $stmt->close();
        }
    } catch (Exception $e) {
        error_log("Error getting user settings: " . $e->getMessage());
    }
    
    return $settings;
}

function updateUserSetting($userId, $settingName, $settingValue) {
    global $conn;
    
    try {
        $query = "INSERT INTO user_settings (user_id, setting_name, setting_value) 
                  VALUES (?, ?, ?) 
                  ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)";
                  
        $stmt = $conn->prepare($query);
        if ($stmt) {
            $stmt->bind_param("iss", $userId, $settingName, $settingValue);
            $success = $stmt->execute();
            $stmt->close();
            return $success;
        }
    } catch (Exception $e) {
        error_log("Error updating user setting: " . $e->getMessage());
    }
    return false;
}

// Make sure this is the last closing brace in the file
?>
