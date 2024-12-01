<?php
include('includes/connect.php');
if (isset($_GET['id'])) {
    $course_id = intval($_GET['id']);
    $course_query = "SELECT * FROM courses WHERE id = $course_id";
    $course_result = mysqli_query($con, $course_query);

    if (mysqli_num_rows($course_result) == 1) {
        $course = mysqli_fetch_assoc($course_result);
        $course_title = $course['course_title'];
        $course_description = $course['course_description'];
        $course_image = $course['course_image'];
        $category_id = $course['category_id'];
    } else {
        echo "<script>alert('Course not found.'); window.location.href='view_courses.php';</script>";
        exit();
    }
} else {
    echo "<script>window.location.href='view_courses.php';</script>";
    exit();
}

if (isset($_POST['update_course'])) {
    $new_course_title = mysqli_real_escape_string($con, $_POST['course_title']);
    $new_course_description = mysqli_real_escape_string($con, $_POST['course_description']);
    $new_category_id = intval($_POST['category_id']);
    if ($_FILES['course_image']['name']) {
        $new_course_image = $_FILES['course_image']['name'];
        $image_tmp = $_FILES['course_image']['tmp_name'];
        $target_dir = "images/";
        $target_file = $target_dir . basename($new_course_image);
        if (move_uploaded_file($image_tmp, $target_file)) {
            if (file_exists($target_dir . $course_image)) {
                unlink($target_dir . $course_image);
            }
            $course_image = $new_course_image;
        } else {
            echo "<script>alert('Failed to upload image.');</script>";
        }
    }
    $update_course = "UPDATE courses SET 
                        course_title = '$new_course_title',
                        course_description = '$new_course_description',
                        course_image = '$course_image',
                        category_id = $new_category_id
                     WHERE id = $course_id";
    if (mysqli_query($con, $update_course)) {
        echo "<script>alert('Course updated successfully!'); window.location.href='view_courses.php';</script>";
    } else {
        echo "<script>alert('Error updating course. Please try again.');</script>";
    }
}

if (isset($_POST['update_quizzes'])) {
    $quiz_names = $_POST['quiz_name'];
    $quiz_urls = $_POST['quiz_url'];

    // Update quizzes for the course
    foreach ($quiz_names as $key => $quiz_name) {
        $quiz_name = mysqli_real_escape_string($con, $quiz_name);
        $quiz_url = mysqli_real_escape_string($con, $quiz_urls[$key]);
        if (!empty($quiz_name) && !empty($quiz_url)) {
            $quiz_query = "INSERT INTO quizzes (course_id, quiz_name, quiz_url) VALUES ($course_id, '$quiz_name', '$quiz_url')";
            mysqli_query($con, $quiz_query);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Course</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .container { max-width: 800px; }
        .current-image { width: 150px; height: auto; margin-bottom: 10px; }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
      <div class="container-fluid">
        <a class="navbar-brand" href="admin_dashboard.php">Course Library</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" 
          aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav ms-auto">
            <li class="nav-item"><a class="nav-link" href="add_course.php">Add Course</a></li>
            <li class="nav-item"><a class="nav-link" href="view_courses.php">View Courses</a></li>
            <li class="nav-item"><a class="nav-link" href="manage_categories.php">Manage Categories</a></li>
            <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
          </ul>
        </div>
      </div>
    </nav>
    <div class="container mt-5 pt-5">
        <h2 class="mb-4">Edit Course</h2>
        <form action="edit_course.php?id=<?php echo $course_id; ?>" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="course_title" class="form-label">Course Title</label>
                <input type="text" class="form-control" id="course_title" name="course_title" value="<?php echo htmlspecialchars($course_title); ?>" required>
            </div>
            <div class="mb-3">
                <label for="course_description" class="form-label">Course Description</label>
                <textarea class="form-control" id="course_description" name="course_description" rows="3" required><?php echo htmlspecialchars($course_description); ?></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Current Course Image</label><br>
                <img src="images/<?php echo htmlspecialchars($course_image); ?>" alt="<?php echo htmlspecialchars($course_title); ?>" class="current-image">
            </div>
            <div class="mb-3">
                <label for="course_image" class="form-label">Change Course Image</label>
                <input type="file" class="form-control" id="course_image" name="course_image" accept="image/*">
                <small class="form-text text-muted">Leave blank to keep the current image.</small>
            </div>
            <div class="mb-3">
                <label for="category_id" class="form-label">Category</label>
                <select name="category_id" class="form-select" required>
                    <option value="">Select Category</option>
                    <?php
                    $category_query = "SELECT * FROM categories ORDER BY category_name ASC";
                    $category_result = mysqli_query($con, $category_query);
                    while ($category = mysqli_fetch_assoc($category_result)) {
                        $selected = ($category['id'] == $category_id) ? 'selected' : '';
                        echo "<option value='" . $category['id'] . "' $selected>" . $category['category_name'] . "</option>";
                    }
                    ?>
                </select>
            </div>

            <h4>Lessons</h4>
            <div id="lessons">
                <?php
                $lessons_query = "SELECT * FROM lessons WHERE course_id = $course_id";
                $lessons_result = mysqli_query($con, $lessons_query);
                while ($lesson = mysqli_fetch_assoc($lessons_result)) {
                    $lesson_title = htmlspecialchars($lesson['lesson_title']);
                    $lesson_url = htmlspecialchars($lesson['lesson_url']);
                    echo "
                    <div class='mb-3'>
                        <label class='form-label'>Lesson Title</label>
                        <input type='text' class='form-control' name='lesson_title[]' value='$lesson_title' required>
                    </div>
                    <div class='mb-3'>
                        <label class='form-label'>Lesson URL</label>
                        <input type='url' class='form-control' name='lesson_url[]' value='$lesson_url' required>
                    </div>
                    <hr>";
                }
                ?>
            </div>
            <button type="button" class="btn btn-success mb-3" id="addLessonBtn">Add More Lessons</button>

            <h4>Quizzes</h4>
            <div id="quizzes">
                <?php
                $quizzes_query = "SELECT * FROM quizzes WHERE course_id = $course_id";
                $quizzes_result = mysqli_query($con, $quizzes_query);
                while ($quiz = mysqli_fetch_assoc($quizzes_result)) {
                    $quiz_name = htmlspecialchars($quiz['quiz_name']);
                    $quiz_url = htmlspecialchars($quiz['quiz_url']);
                    echo "
                    <div class='mb-3'>
                        <label class='form-label'>Quiz Name</label>
                        <input type='text' class='form-control' name='quiz_name[]' value='$quiz_name' required>
                    </div>
                    <div class='mb-3'>
                        <label class='form-label'>Quiz URL</label>
                        <input type='url' class='form-control' name='quiz_url[]' value='$quiz_url' required>
                    </div>
                    <hr>";
                }
                ?>
            </div>
            <button type="button" class="btn btn-success mb-3" id="addQuizBtn">Add More Quizzes</button>

            <button type="submit" class="btn btn-primary" name="update_course">Update Course</button>
            <button type="submit" class="btn btn-secondary" name="update_quizzes">Update Quizzes</button>
        </form>
    </div>

    <script>
        document.getElementById('addLessonBtn').addEventListener('click', function() {
            const lessonsDiv = document.getElementById('lessons');
            const lessonHTML = `
                <div class='mb-3'>
                    <label class='form-label'>Lesson Title</label>
                    <input type='text' class='form-control' name='lesson_title[]' required>
                </div>
                <div class='mb-3'>
                    <label class='form-label'>Lesson URL</label>
                    <input type='url' class='form-control' name='lesson_url[]' required>
                </div><hr>
            `;
            lessonsDiv.insertAdjacentHTML('beforeend', lessonHTML);
        });

        document.getElementById('addQuizBtn').addEventListener('click', function() {
            const quizzesDiv = document.getElementById('quizzes');
            const quizHTML = `
                <div class='mb-3'>
                    <label class='form-label'>Quiz Name</label>
                    <input type='text' class='form-control' name='quiz_name[]' required>
                </div>
                <div class='mb-3'>
                    <label class='form-label'>Quiz URL</label>
                    <input type='url' class='form-control' name='quiz_url[]' required>
                </div><hr>
            `;
            quizzesDiv.insertAdjacentHTML('beforeend', quizHTML);
        });
    </script>
</body>
</html>
