<?php
include('includes/connect.php');

if (isset($_GET['id'])) {
    $course_id = intval($_GET['id']);
    $course_query = "SELECT * FROM courses WHERE id = $course_id";
    $course_result = mysqli_query($con, $course_query);

    if (mysqli_num_rows($course_result) > 0) {
        $course = mysqli_fetch_assoc($course_result);
        $course_title = htmlspecialchars($course['course_title']);
        $course_description = htmlspecialchars($course['course_description']);
        $course_image = htmlspecialchars($course['course_image']);
        
        // Query for lessons
        $lessons_query = "SELECT * FROM lessons WHERE course_id = $course_id";
        $lessons_result = mysqli_query($con, $lessons_query);

        // Query for quizzes
        $quizzes_query = "SELECT * FROM quizzes WHERE course_id = $course_id";
        $quizzes_result = mysqli_query($con, $quizzes_query);
    } else {
        echo "<p>Course not found.</p>";
        exit();
    }
} else {
    echo "<p>No course ID provided.</p>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $course_title; ?> - Course Details</title>
    <link href="style.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .fixed-size-image {
            width: 100%; /* Full width within the column */
            height: auto; /* Maintain aspect ratio */
            max-width: 450px; /* Limit the maximum width */
            object-fit: cover; /* Ensures the image fits within the defined dimensions */
            border: 15px;
            border-radius: 15px;
        }
        .logo-icon {
            font-size: 2.5rem;
            color: blue;
            margin-left: 10px; /* Move slightly to the left */
        }
    </style>
</head>
<body>
<div class="sidebar">
    <!-- Logo -->
    <div class="logo-section">
    </div>
    <!-- Menu Items -->
    <ul class="menu">
        <li>
            <a href="#" class="menu-item">
                <i class="bi bi-list"></i>
                <span>Menu</span>
            </a>
        </li>
        <li>
            <a href="index.php" class="menu-item">
                <i class="bi bi-x-diamond-fill"></i>
                <span>Back</span>
            </a>
        </li>
        <li>
            <a href="#" class="menu-item">
                <i class="bi bi-house-door"></i>
                <span>WShare</span>
            </a>
        </li>
        <li>
            <a href="#" class="menu-item">
                <i class="bi bi-gear"></i>
                <span>Settings</span>
            </a>
        </li>
        <li>
            <a href="#" class="menu-item">
                <i class="bi bi-person-circle"></i>
                <span>Logout</span>
            </a>
        </li>
    </ul>
</div>

<h1 class="library-header">
    <i class="bi bi-bookmarks-fill gradient-icon"></i>
    Wshare Library
</h1>

<div class="container mt-5">
    <div class="row">
        <!-- Image Column with Lessons and Quizzes -->
        <div class="col-md-4">
            <img src="./images/<?php echo $course_image; ?>" class="fixed-size-image img-fluid" alt="<?php echo $course_title; ?>">
            <h6 class="mt-4">Lessons & Exercises:</h6>
            <ul>
                <?php
                if (mysqli_num_rows($lessons_result) > 0) {
                    while ($lesson = mysqli_fetch_assoc($lessons_result)) {
                        $lesson_title = htmlspecialchars($lesson['lesson_title']);
                        $lesson_url = htmlspecialchars($lesson['lesson_url']);
                        echo "<li><a href='$lesson_url' target='_blank'>$lesson_title</a></li>";
                    }
                } else {
                    echo "<li>No lessons available for this course.</li>";
                }
                ?>
            </ul>

            <h6 class="mt-4">Quizzes:</h6>
            <ul>
                <?php
                if (mysqli_num_rows($quizzes_result) > 0) {
                    while ($quiz = mysqli_fetch_assoc($quizzes_result)) {
                        $quiz_name = htmlspecialchars($quiz['quiz_name']);
                        $quiz_url = htmlspecialchars($quiz['quiz_url']);
                        echo "<li><a href='$quiz_url' target='_blank'>$quiz_name</a></li>";
                    }
                } else {
                    echo "<li>No quizzes available for this course.</li>";
                }
                ?>
            </ul>
        </div>

        <!-- Content Column -->
        <div class="col-md-8">
            <h1><?php echo $course_title; ?></h1>
            <p><?php echo $course_description; ?></p>
        </div>
    </div>
</div>

</body>
</html>
