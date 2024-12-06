<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require_once 'db.php';
    
    $title = $_POST['title'];
    $body = $_POST['body'];
    $user_id = $_SESSION['user_id'];

    $stmt = $conn->prepare("INSERT INTO content (title, body, user_id) VALUES (:title, :body, :user_id)");
    $stmt->execute(['title' => $title, 'body' => $body, 'user_id' => $user_id]);

    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Blog Post</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<header>
    <h1>Create a Blog Post</h1>
    <nav>
        <ul>
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>
</header>

<main>
    <section class="create-blog-form">
        <h2>Write Your Blog</h2>
        
        <form method="POST" action="create_content.php">
            <label for="title">Blog Title:</label>
            <input type="text" name="title" id="title" required>

            <label for="body">Blog Body:</label>
            <textarea name="body" id="body" rows="10" required></textarea>

            <input type="submit" value="Publish Blog">
        </form>
    </section>
</main>

<footer>
    <p>&copy; 2024 BlogSite. All rights reserved.</p>
</footer>

</body>
</html>
