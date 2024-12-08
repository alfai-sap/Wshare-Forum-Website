<?php
include 'dashFunctions.php';

// Get filter, search, and sort parameters
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';
$sort = isset($_GET['sort']) ? $_GET['sort'] : '';

// Fetch communities based on search/filter/sort
if (!empty($searchTerm)) {
    $communities = searchCommunities($searchTerm);
} else {
    $communities = $filter === 'all' ? getAllCommunities() : filterCommunities($filter);
}

if (!empty($sort)) {
    usort($communities, function($a, $b) use ($sort) {
        return strcmp($a[$sort], $b[$sort]);
    });
}

// Get analytics data
$totalCommunities = getTotalCommunities();
$totalMembers = getTotalCommunityMembers();
$activeCommunities = getActiveCommunities();
$inactiveCommunities = getInactiveCommunities();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Communities</title>
    <link rel="stylesheet" href="dashboard.css?v=<?php echo time(); ?>">
</head>
<body>
    <?php include 'sidebar.php'; ?>
    <div class="container">
        <h1>Manage Communities</h1>

        <!-- Analytics Summary Section -->
        <div class="dashboard">
            <div class="box">
                <h2>Total Communities</h2>
                <p><?php echo $totalCommunities; ?></p>
            </div>
            <div class="box">
                <h2>Total Members</h2>
                <p><?php echo $totalMembers; ?></p>
            </div>
            <div class="box">
                <h2>Active Communities</h2>
                <p><?php echo $activeCommunities; ?></p>
            </div>
            <div class="box">
                <h2>Inactive Communities</h2>
                <p><?php echo $inactiveCommunities; ?></p>
            </div>
        </div>

        <!-- Search and Filter Controls -->
        <div class="controls-container">
            <div class="search-box">
                <form method="GET" class="search-form">
                    <input type="text" name="search" placeholder="Search communities..." 
                           value="<?php echo htmlspecialchars($searchTerm); ?>">
                    <button type="submit">Search</button>
                </form>
            </div>

            <div class="filter-box">
                <form method="GET" class="filter-form">
                    <select name="filter" onchange="this.form.submit()">
                        <option value="all" <?php echo $filter === 'all' ? 'selected' : ''; ?>>All Communities</option>
                        <option value="active" <?php echo $filter === 'active' ? 'selected' : ''; ?>>Active</option>
                        <option value="inactive" <?php echo $filter === 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                        <option value="public" <?php echo $filter === 'public' ? 'selected' : ''; ?>>Public</option>
                        <option value="private" <?php echo $filter === 'private' ? 'selected' : ''; ?>>Private</option>
                        <option value="most_members" <?php echo $filter === 'most_members' ? 'selected' : ''; ?>>Most Members</option>
                    </select>
                </form>
            </div>

            <div class="sort-box">
                <form method="GET" class="sort-form">
                    <select name="sort" onchange="this.form.submit()">
                        <option value="" <?php echo $sort === '' ? 'selected' : ''; ?>>Sort By</option>
                        <option value="Title" <?php echo $sort === 'Title' ? 'selected' : ''; ?>>Title</option>
                        <option value="member_count" <?php echo $sort === 'member_count' ? 'selected' : ''; ?>>Members</option>
                        <option value="Visibility" <?php echo $sort === 'Visibility' ? 'selected' : ''; ?>>Visibility</option>
                    </select>
                </form>
            </div>
        </div>

        <!-- Communities Table -->
        <div class="table-container">
            <h2>All Communities</h2>
            <table>
                <tr>
                    <th>Community ID</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Visibility</th>
                    <th>Members</th>
                    <th>Admins</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
                <?php foreach ($communities as $community): ?>
                <tr>
                    <td><?php echo $community['CommunityID']; ?></td>
                    <td><?php echo htmlspecialchars($community['Title']); ?></td>
                    <td><?php echo htmlspecialchars($community['Description']); ?></td>
                    <td><?php echo htmlspecialchars($community['Visibility']); ?></td>
                    <td><?php echo $community['member_count']; ?></td>
                    <td><?php echo implode(', ', $community['admins']); ?></td>
                    <td><?php echo $community['is_active'] ? 'Active' : 'Inactive'; ?></td>
                    <td>
                        <button onclick="viewCommunity(<?php echo $community['CommunityID']; ?>)">View</button>
                        <button onclick="deleteCommunity(<?php echo $community['CommunityID']; ?>)">Delete</button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </div>

    <style>
    .controls-container {
        display: flex;
        gap: 20px;
        margin-bottom: 20px;
        padding: 15px;
        background: #f8f9fa;
        border-radius: 5px;
    }
    .search-form input[type="text"] {
        padding: 8px;
        width: 300px;
        border: 1px solid #ddd;
        border-radius: 4px;
    }
    .filter-form select {
        padding: 8px;
        border: 1px solid #ddd;
        border-radius: 4px;
        background: white;
    }
    .sort-form select {
        padding: 8px;
        border: 1px solid #ddd;
        border-radius: 4px;
        background: white;
    }
    .search-form button {
        padding: 8px 15px;
        background: #007bff;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }
    .search-form button:hover {
        background: #0056b3;
    }
    </style>

    <script>
    function viewCommunity(communityId) {
        window.location.href = `view_community.php?id=${communityId}`;
    }

    function deleteCommunity(communityId) {
        if (confirm('Are you sure you want to delete this community?')) {
            // Add your delete logic here
            window.location.href = `delete_community.php?id=${communityId}`;
        }
    }
    </script>
</body>
</html>