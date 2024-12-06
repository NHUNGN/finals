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
    $id = $_POST['id'];
    
    $stmt = $conn->prepare("UPDATE content SET title = :title, body = :body WHERE id = :id");
    $stmt->execute(['title' => $title, 'body' => $body, 'id' => $id]);
    
    header("Location: view_content.php");
    exit();
}

if (isset($_GET['id'])) {
    require_once 'db.php';
    
    $stmt = $conn->prepare("SELECT * FROM content WHERE id = :id");
    $stmt->execute(['id' => $_GET['id']]);
    $content = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<form method="POST" action="edit_content.php">
    Title: <input type="text" name="title" value="<?php echo $content['title']; ?>" required>
    Body: <textarea name="body" required><?php echo $content['body']; ?></textarea>
    <input type="hidden" name="id" value="<?php echo $content['id']; ?>">
    <input type="submit" value="Update">
</form>
