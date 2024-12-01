<?php
include('includes/connect.php');
$search = isset($_GET['search']) ? $_GET['search'] : '';
$category_filter = isset($_GET['category']) ? $_GET['category'] : '';
function truncate_description($description, $word_limit = 50) {
    $words = explode(' ', $description);
    return implode(' ', array_slice($words, 0, $word_limit)) . (count($words) > $word_limit ? '...' : '');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Course Library</title>
    <link href="style.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .course-card {
            height: 500px;
        }

        .card-body {
            height: 200px; 
            overflow: hidden; 
        }
        .card-img-top {
            width: 100%; 
            height: 250px;
            object-fit: cover;
        }
        .logo-icon {
            margin-left: 20px;
            color: blue;
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
            <a href="#search-area" class="menu-item">
                <i class="bi bi-search"></i>
                <span>Search</span>
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
    <h3>Available Courses</h3>
    <form method="get" class="mb-4">
        <div class="row">
            <div id="search-area" class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Search for courses..." value="<?php echo htmlspecialchars($search); ?>">
            </div>

            <div class="col-md-4">
                <select name="category" class="form-control">
                    <option value="">All Categories</option>
                    <?php
                    $category_query = "SELECT * FROM categories";
                    $category_result = mysqli_query($con, $category_query);
                    while ($category = mysqli_fetch_assoc($category_result)) {
                        $selected = ($category_filter == $category['id']) ? 'selected' : '';
                        echo "<option value='" . $category['id'] . "' $selected>" . htmlspecialchars($category['category_name']) . "</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-primary">Search</button>
            </div>
        </div>
    </form>

    <div class="row">
        <?php
        $query = "SELECT * FROM courses WHERE 1";
        if ($search != '') {
            $query .= " AND (course_title LIKE '%$search%' OR course_description LIKE '%$search%')";
        }
        if ($category_filter != '') {
            $query .= " AND category_id = $category_filter";
        }

        $result = mysqli_query($con, $query);

        if (mysqli_num_rows($result) > 0) {
            while ($course = mysqli_fetch_assoc($result)) {
                $course_id = $course['id'];
                $course_title = htmlspecialchars($course['course_title']);
                $course_description = truncate_description(htmlspecialchars($course['course_description']));
                $course_image = htmlspecialchars($course['course_image']);

                echo "
                <div class='col-md-4'>
                    <div class='card mb-4 course-card'>
                        <a href='course_details.php?id=$course_id'>
                            <img src='./images/$course_image' class='card-img-top' alt='$course_title'>
                        </a>
                        <div class='card-body'>
                            <h5 class='card-title'>
                                <a href='course_details.php?id=$course_id' class='text-decoration-none'>$course_title</a>
                            </h5>
                            <p class='card-text'>$course_description</p>
                        </div>
                    </div>
                </div>";
            }
        } else {
            echo "<p>No courses found.</p>";
        }
        ?>
    </div>
</div>

<script>
  document.querySelector('a[href="#search-area"]').addEventListener('click', function () {
    // Delay to ensure scroll is complete before focusing
    setTimeout(function () {
      const searchInput = document.querySelector('input[name="search"]');
      searchInput.focus(); // Focus the search input
    }, 200);
  });
</script>

</body>
</html>
