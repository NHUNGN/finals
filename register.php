<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require_once 'db.php';
    
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $name = trim($_POST['name']);
    
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->execute(['username' => $username]);
    $existingUser = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($existingUser) {
        $error = "Username already exists. Please choose a different one.";
    } else {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        $stmt = $conn->prepare("INSERT INTO users (username, password, name) VALUES (:username, :password, :name)");
        $stmt->execute(['username' => $username, 'password' => $hashedPassword, 'name' => $name]);
        
        header("Location: login.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Our Website</title>
    <link rel="stylesheet" href="styles.css">
    <script>
        function validateForm() {
            var username = document.getElementById('username').value;
            var password = document.getElementById('password').value;
            var name = document.getElementById('name').value;
            var errorMessage = "";

            if (username.trim() === "" || password.trim() === "" || name.trim() === "") {
                errorMessage = "All fields are required.";
            }

            if (errorMessage) {
                alert(errorMessage);
                return false;
            }

            return true;
        }
    </script>
</head>
<body>
    <header>
        <a href="index.php" class="logo">
            <img src="images/sakura.png" alt="Website Logo" style="height: 50px; margin-right: 15px;">
        </a>
        <h1>Create an Account</h1>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="login.php">Login</a></li>
            </ul>
        </nav>
    </header>

    <section class="register-form">
        <h2>Register</h2>
        
        <?php if (isset($error)) { echo "<p style='color: red;'>$error</p>"; } ?>

        <form method="POST" action="register.php" onsubmit="return validateForm()">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>

            <label for="name">Full Name</label>
            <input type="text" id="name" name="name" required>

            <input type="submit" value="Register">
        </form>
    </section>

    <footer>
        <p>&copy; 2024 Website Inc. All rights reserved.</p>
    </footer>
</body>
</html>
