<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    require_once 'db.php';
    
    $stmt = $conn->prepare("DELETE FROM content WHERE id = :id");
    $stmt->execute(['id' => $_GET['id']]);
    
    header("Location: dashboard.php");
    exit();
}
?>
