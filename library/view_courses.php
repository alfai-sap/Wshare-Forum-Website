<?php
include('includes/connect.php');

$courses_query = "SELECT c.id, c.course_title, c.course_description, c.course_image, cat.category_name 
                  FROM courses c 
                  JOIN categories cat ON c.category_id = cat.id 
                  ORDER BY c.id DESC";
$courses_result = mysqli_query($con, $courses_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Courses</title>
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
            max-width: 1000px;
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
        .course-card {
            margin-bottom: 20px;
            display: flex;
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
        }
        .course-image {
            width: 200px;
            height: auto;
            border-radius: 8px 0 0 8px;
        }
        .card-body {
            padding: 15px;
            flex: 1;
        }
        .quiz-list {
            margin-top: 10px;
            padding-left: 20px;
        }
        .quiz-list li {
            list-style-type: none;
            margin-bottom: 5px;
        }
        .quiz-list a {
            color: #007bff;
            text-decoration: none;
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
                        <a class="nav-link" href="add_course.php">Add Course</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="view_courses.php">View Courses</a>
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

    <div class="container mt-5">
        <h2 class="mb-4">Courses List</h2>
        <?php if (mysqli_num_rows($courses_result) > 0): ?>
            <?php while ($course = mysqli_fetch_assoc($courses_result)): ?>
                <div class="card course-card">
                    <img src="images/<?php echo $course['course_image']; ?>" class="course-image" alt="<?php echo $course['course_title']; ?>">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $course['course_title']; ?></h5>
                        <p class="card-text"><?php echo $course['course_description']; ?></p>
                        <p class="text-muted">Category: <?php echo $course['category_name']; ?></p>
                        
                        <!-- Displaying Quizzes for the Course -->
                        <div class="quiz-list">
                            <h6>Quizzes:</h6>
                            <?php
                            $course_id = $course['id'];
                            $quiz_query = "SELECT quiz_name, quiz_url FROM quizzes WHERE course_id = $course_id";
                            $quiz_result = mysqli_query($con, $quiz_query);
                            if (mysqli_num_rows($quiz_result) > 0):
                            ?>
                                <ul>
                                    <?php while ($quiz = mysqli_fetch_assoc($quiz_result)): ?>
                                        <li><a href="<?php echo $quiz['quiz_url']; ?>" target="_blank"><?php echo $quiz['quiz_name']; ?></a></li>
                                    <?php endwhile; ?>
                                </ul>
                            <?php else: ?>
                                <p>No quizzes available for this course.</p>
                            <?php endif; ?>
                        </div>
                        
                        <a href="edit_course.php?id=<?php echo $course['id']; ?>" class="btn btn-primary">Edit</a>
                        <a href="delete_course.php?id=<?php echo $course['id']; ?>" class="btn btn-danger">Delete</a>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No courses available.</p>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" 
      integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" 
      crossorigin="anonymous"></script>
</body>
</html>
