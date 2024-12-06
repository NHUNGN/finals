<?php
session_start();
require_once 'db.php';


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->execute(['username' => $username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['name'] = $user['name'];

        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Our Website</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<header>
    <h1>Login</h1>
    <nav>
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="register.php">Register</a></li>
        </ul>
    </nav>
</header>

<section class="login-form">
    <h2>Login</h2>

    <?php if (isset($error)) { echo "<p style='color: red;'>$error</p>"; } ?>

    <form method="POST" action="login.php">
        <label for="username">Username</label>
        <input type="text" id="username" name="username" required>

        <label for="password">Password</label>
        <input type="password" id="password" name="password" required>

        <input type="submit" value="Login">
    </form>
</section>

<footer>
    <p>&copy; 2024 Website Inc. All rights reserved.</p>
</footer>

</body>
</html>
