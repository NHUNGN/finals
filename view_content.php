<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

require_once 'db.php';

$stmt = $conn->prepare("SELECT * FROM content WHERE user_id = :user_id");
$stmt->execute(['user_id' => $_SESSION['user_id']]);
$contents = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Blog Posts</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<header>
    <h1>Your Blog Posts</h1>
    <nav>
        <ul>
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="view_profile.php?user_id=<?php echo $_SESSION['user_id']; ?>">My Profile</a></li> 
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>
</header>

<main>
    <section class="blog-posts">
        <h2>My Blog Content</h2>
        
        <?php
        foreach ($contents as $content) {
            echo "<h3>" . $content['title'] . "</h3>";
            echo "<p>" . $content['body'] . "</p>";
            echo "<a href='edit_content.php?id=" . $content['id'] . "'>Edit</a> | ";
            echo "<a href='delete_content.php?id=" . $content['id'] . "'>Delete</a>";
            echo "<hr>"; 
        }
        ?>

        <div class="back-to-dashboard">
            <a href="dashboard.php"><button>Back to Dashboard</button></a>
        </div>
    </section>
</main>

<footer>
    <p>&copy; 2024 BlogSite. All rights reserved.</p>
</footer>

</body>
</html>
