<?php
session_start();
require 'connection.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = test_input($_POST['username']);
    $password = test_input($_POST['password']);

    $stmt = $db->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
    $_SESSION['username'] = $user['username'];
        header('Location: main.php');
        exit;
    } else {
        $error='invalid username or password';
    }
}

function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="css/registraion.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
    <div class="Registration">
        <form method="POST" action="">
        <h2>Login</h2>
        <div class="informaion-box">
            <input type="text" name="username" placeholder="Username" required>
            <i class='bx bxs-user'></i> 
        </div>

        <div class="informaion-box">
            <input type="password" name="password" placeholder="Password" required>
            <i class='bx bxs-lock-alt' ></i>

        </div>
            <button type="submit">Login</button>

            <div class="register-link">
            <p>Don't have an account? <a href="signup.php">Sign up here</a></p>
            </div>
            <?php echo $error; ?>
        </form>
        
    </div>
</body>
</html>
