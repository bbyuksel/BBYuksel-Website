<?php
// Clean input data
function clean_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Format date for display
function format_date($date) {
    return date('F j, Y', strtotime($date));
}

// Check if user is logged in
function is_logged_in() {
    return isset($_SESSION['user_id']);
}

// Redirect to a URL
function redirect($url) {
    header('Location: ' . $url);
    exit;
}

// Get profile data
function get_profile($pdo) {
    $stmt = $pdo->prepare("SELECT * FROM profile WHERE id = 1");
    $stmt->execute();
    return $stmt->fetch();
}

// Get news items
function get_news($pdo, $limit = 0) {
    $sql = "SELECT * FROM news ORDER BY date DESC";
    if ($limit > 0) {
        $sql .= " LIMIT " . $limit;
    }
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll();
}

// Get publications
function get_publications($pdo) {
    $stmt = $pdo->prepare("SELECT * FROM publications ORDER BY year DESC, id DESC");
    $stmt->execute();
    return $stmt->fetchAll();
}

// Get projects
function get_projects($pdo) {
    $stmt = $pdo->prepare("SELECT * FROM projects ORDER BY start_date DESC");
    $stmt->execute();
    return $stmt->fetchAll();
}

// Upload file
function upload_file($file, $allowed_types = ['image/jpeg', 'image/png']) {
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return false;
    }
    
    // Check file type
    if (!in_array($file['type'], $allowed_types)) {
        return false;
    }
    
    // Create unique filename
    $file_extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $unique_filename = uniqid() . '_' . basename($file['name']);
    
    // Make sure uploads directory exists
    $upload_dir = dirname(__DIR__) . '/uploads/';
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }
    
    // Move file
    $destination = $upload_dir . $unique_filename;
    
    if (move_uploaded_file($file['tmp_name'], $destination)) {
        return $unique_filename;
    }
    
    return false;
}

// Debug fonksiyonu ekleyelim
function debug_login($username, $password) {
    global $pdo;
    
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();
    
    if ($user) {
        echo "User found in database<br>";
        echo "Stored hash: " . $user['password'] . "<br>";
        echo "Password verify result: " . (password_verify($password, $user['password']) ? 'true' : 'false') . "<br>";
    } else {
        echo "User not found in database<br>";
    }
}
?>
