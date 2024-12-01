<?php
include('includes/connect.php'); 

if (isset($_GET['id'])) {
    $category_id = $_GET['id'];
    $delete_category_query = "DELETE FROM categories WHERE id = ?";
    $stmt = $con->prepare($delete_category_query);
    $stmt->bind_param('i', $category_id);

    if ($stmt->execute()) {
        header("Location: admin.php?message=Category deleted successfully");
        exit();
    } else {
        header("Location: admin.php?error=Error deleting category");
        exit();
    }
}
header("Location: admin.php?error=No category ID provided");
exit();
?>
