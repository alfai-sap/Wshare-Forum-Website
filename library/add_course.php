<?php
include('includes/connect.php');

if (isset($_POST['submit_course'])) {
    $course_title = mysqli_real_escape_string($con, $_POST['course_title']);
    $course_description = mysqli_real_escape_string($con, $_POST['course_description']);
    $category_id = intval($_POST['category_id']);
    $course_image = $_FILES['course_image']['name'];
    $image_tmp = $_FILES['course_image']['tmp_name'];
    $target_dir = "images/";
    $target_file = $target_dir . basename($course_image);

    if (move_uploaded_file($image_tmp, $target_file)) {
        $insert_course = "INSERT INTO courses (course_title, course_description, course_image, category_id) 
                          VALUES ('$course_title', '$course_description', '$course_image', $category_id)";
        if (mysqli_query($con, $insert_course)) {
            $course_id = mysqli_insert_id($con);

            // Insert Lessons
            $lesson_titles = $_POST['lesson_title'];
            $lesson_urls = $_POST['lesson_url'];
            for ($i = 0; $i < count($lesson_titles); $i++) {
                $lesson_title = mysqli_real_escape_string($con, $lesson_titles[$i]);
                $lesson_url = mysqli_real_escape_string($con, $lesson_urls[$i]);
                if (!empty($lesson_title) && !empty($lesson_url)) {
                    $insert_lesson = "INSERT INTO lessons (course_id, lesson_title, lesson_url) 
                                      VALUES ($course_id, '$lesson_title', '$lesson_url')";
                    mysqli_query($con, $insert_lesson);
                }
            }

            // Insert Quizzes
            $quiz_names = $_POST['quiz_name'];
            $quiz_urls = $_POST['quiz_url'];
            for ($i = 0; $i < count($quiz_names); $i++) {
                $quiz_name = mysqli_real_escape_string($con, $quiz_names[$i]);
                $quiz_url = mysqli_real_escape_string($con, $quiz_urls[$i]);
                if (!empty($quiz_name) && !empty($quiz_url)) {
                    $insert_quiz = "INSERT INTO quizzes (quiz_name, quiz_url, modified_at) 
                VALUES ('$quiz_name', '$quiz_url', NOW())";
                    mysqli_query($con, $insert_quiz);
                }
            }

            echo "<script>alert('Course added successfully!'); window.location.href='view_courses.php';</script>";
        } else {
            echo "<script>alert('Error adding course. Please try again.');</script>";
        }
    } else {
        echo "<script>alert('Failed to upload image.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Course</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f0f4f8; 
            padding-top: 60px; 
        }
        .navbar {
            background-color: #007bff; 
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); 
        }
        .navbar-brand, .nav-link {
            color: white !important; 
            font-weight: bold; 
        }
        .nav-link {
            padding: 15px 20px; 
            border-radius: 5px; 
            transition: background-color 0.3s; 
        }
        .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.1) !important; 
        }
        .container {
            max-width: 800px;
            margin: 0 auto; 
            padding: 20px;
            background-color: white; 
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); 
        }
        h2 {
            color: #007bff; 
            font-weight: bold;
        }
        .form-label {
            color: #555; 
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="admin_dashboard.php">Course Library</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" 
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="add_course.php">Add Course</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="view_courses.php">View Courses</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="manage_categories.php">Manage Categories</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        <h2 class="mb-4">Add New Course</h2>
        <form action="add_course.php" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="course_title" class="form-label">Course Title</label>
                <input type="text" class="form-control" id="course_title" name="course_title" required>
            </div>
            <div class="mb-3">
                <label for="course_description" class="form-label">Course Description</label>
                <textarea class="form-control" id="course_description" name="course_description" rows="3" required></textarea>
            </div>
            <div class="mb-3">
                <label for="course_image" class="form-label">Course Image</label>
                <input type="file" class="form-control" id="course_image" name="course_image" accept="image/*" required>
            </div>
            <div class="mb-3">
                <label for="category_id" class="form-label">Category</label>
                <select name="category_id" class="form-select" required>
                    <option value="">Select Category</option>
                    <?php
                    $category_query = "SELECT * FROM categories ORDER BY category_name ASC";
                    $category_result = mysqli_query($con, $category_query);
                    while ($category = mysqli_fetch_assoc($category_result)) {
                        echo "<option value='" . $category['id'] . "'>" . $category['category_name'] . "</option>";
                    }
                    ?>
                </select>
            </div>

            <!-- Lessons Section -->
            <h4>Lessons</h4>
            <div id="lessons">
                <div class="mb-3">
                    <label for="lesson_title" class="form-label">Lesson Title</label>
                    <input type="text" class="form-control" name="lesson_title[]" required>
                </div>
                <div class="mb-3">
                    <label for="lesson_url" class="form-label">Lesson URL</label>
                    <input type="url" class="form-control" name="lesson_url[]" required>
                </div>
            </div>
            <button type="button" class="btn btn-success mb-3" id="addLessonBtn">Add More Lessons</button>

            <!-- Quizzes Section -->
            <h4>Quizzes</h4>
            <div id="quizzes">
                <div class="mb-3">
                    <label for="quiz_name" class="form-label">Quiz Name</label>
                    <input type="text" class="form-control" name="quiz_name[]" required>
                </div>
                <div class="mb-3">
                    <label for="quiz_url" class="form-label">Quiz URL</label>
                    <input type="url" class="form-control" name="quiz_url[]" required>
                </div>
            </div>
            <button type="button" class="btn btn-success mb-3" id="addQuizBtn">Add More Quizzes</button>

            <button type="submit" class="btn btn-primary" name="submit_course">Submit Course</button>
        </form>
    </div>

    <script>
        // Add dynamic lesson input fields
        document.getElementById('addLessonBtn').addEventListener('click', function() {
            const lessonsDiv = document.getElementById('lessons');
            const lessonCount = lessonsDiv.children.length / 2 + 1;

            const newLessonTitle = document.createElement('div');
            newLessonTitle.classList.add('mb-3');
            newLessonTitle.innerHTML = `<label for="lesson_title_${lessonCount}" class="form-label">Lesson Title</label>
            <input type="text" class="form-control" name="lesson_title[]" required>`;

            const newLessonUrl = document.createElement('div');
            newLessonUrl.classList.add('mb-3');
            newLessonUrl.innerHTML = `<label for="lesson_url_${lessonCount}" class="form-label">Lesson URL</label>
            <input type="url" class="form-control" name="lesson_url[]" required>`;

            lessonsDiv.appendChild(newLessonTitle);
            lessonsDiv.appendChild(newLessonUrl);
        });

        // Add dynamic quiz input fields
        document.getElementById('addQuizBtn').addEventListener('click', function() {
            const quizzesDiv = document.getElementById('quizzes');
            const quizCount = quizzesDiv.children.length / 2 + 1;

            const newQuizName = document.createElement('div');
            newQuizName.classList.add('mb-3');
            newQuizName.innerHTML = `<label for="quiz_name_${quizCount}" class="form-label">Quiz Name</label>
            <input type="text" class="form-control" name="quiz_name[]" required>`;

            const newQuizUrl = document.createElement('div');
            newQuizUrl.classList.add('mb-3');
            newQuizUrl.innerHTML = `<label for="quiz_url_${quizCount}" class="form-label">Quiz URL</label>
            <input type="url" class="form-control" name="quiz_url[]" required>`;

            quizzesDiv.appendChild(newQuizName);
            quizzesDiv.appendChild(newQuizUrl);
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
