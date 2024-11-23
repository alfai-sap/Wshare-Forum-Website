<?php
require_once 'functions.php';

// Fetch tags from the database
$query = "SELECT TagName FROM tags ORDER BY TagName";
$result = $pdo->query($query);

$tags = [];
while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
    $tags[] = $row['TagName'];
}

// Return the tags as JSON
echo json_encode($tags);
?>
