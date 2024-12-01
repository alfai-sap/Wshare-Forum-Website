<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Course Library</title>
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
.navbar-brand {
color: white !important;
font-weight: bold;
font-size: 1.5rem;
}
.nav-link {
color: white !important;
padding: 15px 20px;
border-radius: 5px;
transition: background-color 0.3s;
}
.nav-link:hover {
background-color: rgba(255, 255, 255, 0.1) !important;
}
.container {
margin-top: 40px;
padding: 20px;
 border-radius: 8px;
background-color: white;
box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}
h1 {
color: #007bff;
font-weight: bold;
}
p {
 color: #555;
}
</style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Course Library</a>
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
        <h1 class="text-center">Hello Library Admin</h1>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" 
      integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" 
      crossorigin="anonymous"></script>
</body>
</html>
