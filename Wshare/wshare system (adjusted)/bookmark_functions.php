<?php
require_once 'functions.php';

function addBookmark($userID, $postID, $label = null, $notes = null) {
    global $conn;
    
    // Check if bookmark already exists
    $checkStmt = $conn->prepare("SELECT BookmarkID FROM bookmarks WHERE UserID = ? AND PostID = ?");
    $checkStmt->bind_param("ii", $userID, $postID);
    $checkStmt->execute();
    $result = $checkStmt->get_result();
    
    if ($result->num_rows > 0) {
        return false; // Already bookmarked
    }
    
    // Check if the post exists in either posts or community_posts table
    $postExistsStmt = $conn->prepare("SELECT PostID FROM posts WHERE PostID = ? UNION SELECT PostID FROM community_posts WHERE PostID = ?");
    $postExistsStmt->bind_param("ii", $postID, $postID);
    $postExistsStmt->execute();
    $postExistsResult = $postExistsStmt->get_result();
    
    if ($postExistsResult->num_rows === 0) {
        return false; // Post does not exist
    }
    
    // Insert the bookmark
    $stmt = $conn->prepare("INSERT INTO bookmarks (UserID, PostID, Label, Notes) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiss", $userID, $postID, $label, $notes);
    return $stmt->execute();
}

function removeBookmark($userID, $postID) {
    global $conn;
    $stmt = $conn->prepare("DELETE FROM bookmarks WHERE UserID = ? AND PostID = ?");
    $stmt->bind_param("ii", $userID, $postID);
    return $stmt->execute();
}

function isBookmarked($userID, $postID) {
    global $conn;
    $stmt = $conn->prepare("SELECT BookmarkID FROM bookmarks WHERE UserID = ? AND PostID = ?");
    $stmt->bind_param("ii", $userID, $postID);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->num_rows > 0;
}

function getUserBookmarks($userID, $label = null, $searchQuery = '', $searchFilter = 'all') {
    global $conn;
    
    $query = "SELECT b.*, p.Title, p.Content, p.CreatedAt, u.Username, 
              CASE 
                WHEN cp.PostID IS NOT NULL THEN 'community'
                ELSE 'general'
              END as PostType
              FROM bookmarks b
              LEFT JOIN posts p ON b.PostID = p.PostID
              LEFT JOIN community_posts cp ON b.PostID = cp.PostID
              LEFT JOIN users u ON COALESCE(p.UserID, cp.UserID) = u.UserID
              WHERE b.UserID = ?";
    
    $params = array($userID);
    $types = "i";
    
    if ($label) {
        $query .= " AND b.Label = ?";
        $params[] = $label;
        $types .= "s";
    }

    if ($searchQuery) {
        $searchTerm = "%{$searchQuery}%";
        switch ($searchFilter) {
            case 'title':
                $query .= " AND p.Title LIKE ?";
                break;
            case 'label':
                $query .= " AND b.Label LIKE ?";
                break;
            case 'notes':
                $query .= " AND b.Notes LIKE ?";
                break;
            default: // 'all'
                $query .= " AND (p.Title LIKE ? OR b.Label LIKE ? OR b.Notes LIKE ?)";
                $params = array_merge($params, array($searchTerm, $searchTerm, $searchTerm));
                $types .= "sss";
                break;
        }
        if ($searchFilter !== 'all') {
            $params[] = $searchTerm;
            $types .= "s";
        }
    }
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

function getBookmarkLabels($userID) {
    global $conn;
    $stmt = $conn->prepare("SELECT DISTINCT Label FROM bookmarks WHERE UserID = ? AND Label IS NOT NULL");
    $stmt->bind_param("i", $userID);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

function updateBookmarkLabel($bookmarkID, $userID, $newLabel) {
    global $conn;
    $stmt = $conn->prepare("UPDATE bookmarks SET Label = ? WHERE BookmarkID = ? AND UserID = ?");
    $stmt->bind_param("sii", $newLabel, $bookmarkID, $userID);
    return $stmt->execute();
}

function updateBookmarkNotes($bookmarkID, $userID, $notes) {
    global $conn;
    $stmt = $conn->prepare("UPDATE bookmarks SET Notes = ? WHERE BookmarkID = ? AND UserID = ?");
    $stmt->bind_param("sii", $notes, $bookmarkID, $userID);
    return $stmt->execute();
}

function createBookmarkLabel($userID, $label) {
    global $conn;
    $stmt = $conn->prepare("INSERT INTO bookmarks (UserID, Label) VALUES (?, ?)");
    $stmt->bind_param("is", $userID, $label);
    return $stmt->execute();
}

function addCommunityBookmark($userID, $postID, $label = null, $notes = null) {
    global $conn;
    
    // Check if bookmark already exists
    $checkStmt = $conn->prepare("SELECT BookmarkID FROM community_bookmarks WHERE UserID = ? AND PostID = ?");
    $checkStmt->bind_param("ii", $userID, $postID);
    $checkStmt->execute();
    $result = $checkStmt->get_result();
    
    if ($result->num_rows > 0) {
        return false; // Already bookmarked
    }
    
    // Insert the bookmark
    $stmt = $conn->prepare("INSERT INTO community_bookmarks (UserID, PostID, Label, Notes) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiss", $userID, $postID, $label, $notes);
    return $stmt->execute();
}

function removeCommunityBookmark($userID, $postID) {
    global $conn;
    $stmt = $conn->prepare("DELETE FROM community_bookmarks WHERE UserID = ? AND PostID = ?");
    $stmt->bind_param("ii", $userID, $postID);
    return $stmt->execute();
}

function isCommunityBookmarked($userID, $postID) {
    global $conn;
    $stmt = $conn->prepare("SELECT BookmarkID FROM community_bookmarks WHERE UserID = ? AND PostID = ?");
    $stmt->bind_param("ii", $userID, $postID);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->num_rows > 0;
}

function getUserCommunityBookmarks($userID, $label = null, $searchQuery = '', $searchFilter = 'all') {
    global $conn;
    
    $query = "SELECT b.*, p.Title, p.Content, p.CreatedAt, u.Username 
              FROM community_bookmarks b
              LEFT JOIN community_posts p ON b.PostID = p.PostID
              LEFT JOIN users u ON p.UserID = u.UserID
              WHERE b.UserID = ?";
    
    $params = array($userID);
    $types = "i";
    
    if ($label) {
        $query .= " AND b.Label = ?";
        $params[] = $label;
        $types .= "s";
    }

    if ($searchQuery) {
        $searchTerm = "%{$searchQuery}%";
        switch ($searchFilter) {
            case 'title':
                $query .= " AND p.Title LIKE ?";
                break;
            case 'label':
                $query .= " AND b.Label LIKE ?";
                break;
            case 'notes':
                $query .= " AND b.Notes LIKE ?";
                break;
            default: // 'all'
                $query .= " AND (p.Title LIKE ? OR b.Label LIKE ? OR b.Notes LIKE ?)";
                $params = array_merge($params, array($searchTerm, $searchTerm, $searchTerm));
                $types .= "sss";
                break;
        }
        if ($searchFilter !== 'all') {
            $params[] = $searchTerm;
            $types .= "s";
        }
    }
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

function getCommunityBookmarkLabels($userID) {
    global $conn;
    $stmt = $conn->prepare("SELECT DISTINCT Label FROM community_bookmarks WHERE UserID = ? AND Label IS NOT NULL");
    $stmt->bind_param("i", $userID);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

function updateCommunityBookmarkLabel($bookmarkID, $userID, $newLabel) {
    global $conn;
    $stmt = $conn->prepare("UPDATE community_bookmarks SET Label = ? WHERE BookmarkID = ? AND UserID = ?");
    $stmt->bind_param("sii", $newLabel, $bookmarkID, $userID);
    return $stmt->execute();
}

function updateCommunityBookmarkNotes($bookmarkID, $userID, $notes) {
    global $conn;
    $stmt = $conn->prepare("UPDATE community_bookmarks SET Notes = ? WHERE BookmarkID = ? AND UserID = ?");
    $stmt->bind_param("sii", $notes, $bookmarkID, $userID);
    return $stmt->execute();
}

function createCommunityBookmarkLabel($userID, $label) {
    global $conn;
    $stmt = $conn->prepare("INSERT INTO community_bookmarks (UserID, Label) VALUES (?, ?)");
    $stmt->bind_param("is", $userID, $label);
    return $stmt->execute();
}
?>