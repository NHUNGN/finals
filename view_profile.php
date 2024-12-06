<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

require_once 'db.php';

$stmt = $conn->prepare("SELECT * FROM users WHERE id = :id");
$stmt->execute(['id' => $_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    die("User not found.");
}

$postStmt = $conn->prepare("SELECT * FROM content WHERE user_id = :user_id ORDER BY created_at DESC");
$postStmt->execute(['user_id' => $_SESSION['user_id']]);
$userPosts = $postStmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<header>
    <div class="logo">
        <a href="index.php"><img src="images/sakura.png" alt="Logo" width="150"></a>
    </div>
    <nav>
        <ul>
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="view_content.php">View Blog Posts</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>
</header>

<main>
    <section class="profile-frame-container">
        <h1>My Profile</h1>
        <iframe 
    src="profile_content.php" 
    frameborder="0" 
    style="width: 120%; height: 800px; border: 3px solid #555; border-radius: 10px;">
</iframe>



    </section>
</main>

<footer>
    <p>&copy; 2024 BlogSite. All rights reserved.</p>
</footer>

</body>
</html>
