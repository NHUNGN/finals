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

$postStmt = $conn->prepare("SELECT * FROM content WHERE user_id = :user_id ORDER BY created_at DESC");
$postStmt->execute(['user_id' => $_SESSION['user_id']]);
$userPosts = $postStmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Profile Content</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <section class="profile-content">
        <h2>User Information</h2>
        <p><strong>Username:</strong> <?php echo htmlspecialchars($user['username']); ?></p>
        <p><strong>Full Name:</strong> <?php echo htmlspecialchars($user['name']); ?></p>
        <p><strong>Joined On:</strong> <?php echo htmlspecialchars($user['created_at']); ?></p>
    </section>

    <section class="user-blogs">
        <h2>My Blog Posts</h2>
        <?php if (!empty($userPosts)): ?>
            <?php foreach ($userPosts as $post): ?>
                <div class="blog-post">
                    <h3><?php echo htmlspecialchars($post['title']); ?></h3>
                    <p><?php echo nl2br(htmlspecialchars($post['body'])); ?></p>
                    <p><strong>Published On:</strong> <?php echo htmlspecialchars($post['created_at']); ?></p>
                    <a href="edit_content.php?id=<?php echo $post['id']; ?>" class="edit-link">Edit</a> |
                    <a href="delete_content.php?id=<?php echo $post['id']; ?>" class="delete-link" onclick="return confirm('Are you sure you want to delete this post?');">Delete</a>
                </div>
                <hr>
            <?php endforeach; ?>
        <?php else: ?>
            <p>You have not written any blog posts yet.</p>
        <?php endif; ?>
    </section>
</body>
</html>
