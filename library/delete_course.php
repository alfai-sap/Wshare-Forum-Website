<?php
include('includes/connect.php');

if (isset($_GET['id'])) {
    $course_id = intval($_GET['id']);
    $course_query = "SELECT course_image FROM courses WHERE id = $course_id";
    $course_result = mysqli_query($con, $course_query);

    if (mysqli_num_rows($course_result) == 1) {
        $course = mysqli_fetch_assoc($course_result);
        $course_image = $course['course_image'];
        $delete_course = "DELETE FROM courses WHERE id = $course_id";
        if (mysqli_query($con, $delete_course)) {
            $image_path = "images/" . $course_image;
            if (file_exists($image_path)) {
                unlink($image_path);
            }
            echo "<script>alert('Course deleted successfully!'); window.location.href='view_courses.php';</script>";
        } else {
            echo "<script>alert('Error deleting course. Please try again.'); window.location.href='view_courses.php';</script>";
        }
    } else {
        echo "<script>alert('Course not found.'); window.location.href='view_courses.php';</script>";
    }
} else {
    echo "<script>window.location.href='view_courses.php';</script>";
}
?>
