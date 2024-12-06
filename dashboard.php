<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

require_once 'db.php';

$stmt = $conn->prepare("SELECT content.*, users.username FROM content JOIN users ON content.user_id = users.id ORDER BY content.id DESC");
$stmt->execute();
$contents = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Your Account</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<header>
    <div class="logo">
        <a href="index.php"><img src="images/sakura.png" alt="Logo" width="150"></a>
    </div>
    <nav>
        <ul>
            <li><a href="create_content.php">Create New Blog</a></li>
            <li><a href="view_content.php">View Blog Posts</a></li>
            <li><a href="view_profile.php">View My Profile</a></li> 
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>
</header>


<main>
    <section class="dashboard-content">
        <h1>Welcome, <?php echo $_SESSION['username']; ?>!</h1>
        <h2>Recent Blog Posts</h2>
        
        <?php

        if (count($contents) > 0) {
            foreach ($contents as $content) {
                $is_user_post = ($content['user_id'] == $_SESSION['user_id']);
                echo "<div class='blog-post-box'>";
                echo "<h3><a href='view_post.php?id=" . $content['id'] . "'>" . htmlspecialchars($content['title']) . "</a></h3>";
                echo "<p>" . nl2br(htmlspecialchars($content['body'])) . "</p>";
                echo "<p><strong>Author:</strong> " . htmlspecialchars($content['username']) . "</p>";

                if ($is_user_post) {
                    echo "<a href='edit_content.php?id=" . $content['id'] . "'>Edit</a> | ";
                    echo "<a href='delete_content.php?id=" . $content['id'] . "'>Delete</a>";
                }
                
                echo "</div>";
            }
        } else {
            echo "<p>No blog posts available. <a href='create_content.php'>Create a new blog post.</a></p>";
        }
        ?>

        <div class="create-new-blog">
            <a href="create_content.php"><button>Create New Blog</button></a>
        </div>
    </section>
</main>

<footer>
    <p>&copy; 2024 BlogSite. All rights reserved.</p>
</footer>

</body>
</html>
