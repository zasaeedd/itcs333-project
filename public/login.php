<?php
session_start();
require_once '../config/connect.php';
$db = connect();

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = test_input($_POST['username']);
    $password = test_input($_POST['password']);

    // Query Users table
    $stmt = $db->prepare("SELECT * FROM Users WHERE Username = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Validate credentials
    if ($user && password_verify($password, $user['Password'])) {
        $_SESSION['username'] = $user['Username'];
        header('Location: main.php');
        exit;
    } else {
        $error = 'Invalid username or password';
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
