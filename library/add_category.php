<?php
include('includes/connect.php');
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $category_name = trim($_POST['category_name']);
    if (!empty($category_name)) {
        $insert_category_query = "INSERT INTO categories (category_name) VALUES (?)";
        $stmt = $con->prepare($insert_category_query);
        $stmt->bind_param('s', $category_name);
        if ($stmt->execute()) {
            header("Location: admin.php?message=Category added successfully");
            exit();
        } else {
            header("Location: admin.php?error=Error adding category");
            exit();
        }
    } else {
        header("Location: admin.php?error=Category name cannot be empty");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Category</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Add New Category</h1>
        <form action="" method="POST">
            <div class="mb-3">
                <label for="category_name" class="form-label">Category Name</label>
                <input type="text" class="form-control" id="category_name" name="category_name" required>
            </div>
            <button type="submit" class="btn btn-primary">Add Category</button>
            <a href="admin.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</body>
</html>
