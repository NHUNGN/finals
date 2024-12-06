<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

require_once 'db.php';

if (isset($_GET['id'])) {
    $post_id = $_GET['id'];

    $stmt = $conn->prepare("SELECT content.*, users.username FROM content JOIN users ON content.user_id = users.id WHERE content.id = ?");
    $stmt->execute([$post_id]);
    $post = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$post) {
        echo "Post not found.";
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment'])) {
        $comment = htmlspecialchars($_POST['comment']);
        $content_id = $post['id'];
        $user_id = $_SESSION['user_id'];

        $stmt = $conn->prepare("INSERT INTO comments (content_id, user_id, comment) VALUES (?, ?, ?)");
        $stmt->execute([$content_id, $user_id, $comment]);

        header("Location: view_post.php?id=" . $post_id);
        exit();
    }

    $stmt = $conn->prepare("SELECT comments.*, users.username FROM comments JOIN users ON comments.user_id = users.id WHERE comments.content_id = ? ORDER BY comments.created_at DESC");
    $stmt->execute([$post_id]);
    $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    echo "No post ID specified.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Post</title>
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
            <li><a href="create_content.php">Create New Blog</a></li>
            <li><a href="view_profile.php">View My Profile</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>
</header>

<main>
    <section class="view-post-section">
        <h1><?php echo htmlspecialchars($post['title']); ?></h1>
        <p><strong>By:</strong> <?php echo htmlspecialchars($post['username']); ?></p>
        <p><?php echo nl2br(htmlspecialchars($post['body'])); ?></p>

        <h2>Comments</h2>
        <?php if (count($comments) > 0): ?>
            <?php foreach ($comments as $comment): ?>
                <div class="comment-box">
                    <p><strong><?php echo htmlspecialchars($comment['username']); ?>:</strong></p>
                    <p><?php echo nl2br(htmlspecialchars($comment['comment'])); ?></p>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No comments yet. Be the first to comment!</p>
        <?php endif; ?>

        <form method="POST" action="view_post.php?id=<?php echo $post['id']; ?>">
            <textarea name="comment" placeholder="Write a comment..."></textarea>
            <button type="submit">Post Comment</button>
        </form>

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
