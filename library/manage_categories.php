<?php
include('includes/connect.php');

if (isset($_POST['add_category'])) {
    $category_name = mysqli_real_escape_string($con, $_POST['category_name']);
    $insert_category = "INSERT INTO categories (category_name) VALUES ('$category_name')";
    if (mysqli_query($con, $insert_category)) {
        echo "<script>alert('Category added successfully!'); window.location.href='manage_categories.php';</script>";
    } else {
        echo "<script>alert('Error adding category. It might already exist.');</script>";
    }
}
if (isset($_GET['delete_id'])) {
    $category_id = intval($_GET['delete_id']);
    $check_courses = "SELECT COUNT(*) as course_count FROM courses WHERE category_id = $category_id";
    $check_result = mysqli_query($con, $check_courses);
    $course_count = mysqli_fetch_assoc($check_result)['course_count'];

if ($course_count > 0) {
        echo "<script>alert('Cannot delete category. There are courses associated with this category.'); window.location.href='manage_categories.php';</script>";
    } else {
        $delete_category = "DELETE FROM categories WHERE id = $category_id";
        if (mysqli_query($con, $delete_category)) {
            echo "<script>alert('Category deleted successfully!'); window.location.href='manage_categories.php';</script>";
        } else {
            echo "<script>alert('Error deleting category.'); window.location.href='manage_categories.php';</script>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Categories</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
body {
    background-color: #f9f9f9;
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
    margin-top: 20px;
}
h2 {
    color: #007bff;
    font-weight: bold;
}
.form-label {
    color: #555;
}
.table {
    border-collapse: collapse;
    width: 100%;
}
.table th, .table td {
    padding: 12px;
    text-align: center;
}
.table th {
    background-color: #007bbd;
    color: #ffffff;
}
.table-hover tbody tr:hover {
    background-color: #f1f1f1;
}
.btn-danger {
    background-color: #dc3545;
    border-color: #dc3545;
}
.btn-danger:hover {
    background-color: #c82333;
    border-color: #bd2130;
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
                        <a class="nav-link" href="view_courses.php">View Courses</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="manage_categories.php">Manage Categories</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container mt-5 pt-5">
        <h2 class="mb-4">Manage Categories</h2>
        <div class="mb-4">
            <h4>Add New Category</h4>
            <form action="manage_categories.php" method="post" class="row g-3">
                <div class="col-md-8">
                    <input type="text" class="form-control" name="category_name" placeholder="Category Name" required>
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary" name="add_category">Add Category</button>
                </div>
            </form>
        </div>
        <h4>Existing Categories</h4>
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>Course ID</th>
                    <th>Category Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $category_query = "SELECT * FROM categories ORDER BY id DESC";
                $category_result = mysqli_query($con, $category_query);

                if (mysqli_num_rows($category_result) > 0) {
                    $course_id = 1;
                    while ($category = mysqli_fetch_assoc($category_result)) {
                        $category_id = $category['id'];
                        $category_name = htmlspecialchars($category['category_name']);
                        echo "
                        <tr>
                            <td>{$course_id}</td>
                            <td>{$category_name}</td>
                            <td>
                                <a href='manage_categories.php?delete_id={$category_id}' class='btn btn-sm btn-danger' onclick=\"return confirm('Are you sure you want to delete this category?');\">Delete</a>
                            </td>
                        </tr>";
                        $course_id++;
                    }
                } else {
                    echo "<tr><td colspan='3' class='text-center'>No categories found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" 
      integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" 
      crossorigin="anonymous"></script>
</body>
</html>
