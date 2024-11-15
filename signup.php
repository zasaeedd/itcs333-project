<?php
session_start();
require 'connection.php';

$error = '';
$pattern_name = "/[a-z\s]{3,20}/i";
$pattern_username = "/^[a-z][\w]{3,12}/i";
$pattern_password = "/^(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])[A-Za-z0-9_#@%\*\-]{8,24}$/";
$pattern_email = "/^20\d{7}@stu\.uob\.edu\.bh$/";

if ($_SERVER['REQUEST_METHOD'] === 'POST') 
{
    $first_name = test_input($_POST['first_name']);
    $last_name = test_input($_POST['last_name']);
    $username = test_input($_POST['username']);
    $email = test_input($_POST['email']);
    $password = test_input($_POST['password']);

    if (preg_match($pattern_name,$first_name) && preg_match($pattern_name,$last_name) && preg_match($pattern_username,$username)
     && preg_match($pattern_password,$password) && preg_match($pattern_email,$email)) 
    {
        
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        
        $sql = "INSERT INTO users (first_name, last_name, username, email, password) VALUES (:first_name, :last_name, :username, :email, :password)";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':first_name', $first_name);
        $stmt->bindParam(':last_name', $last_name);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPassword);
        
        try {
            $stmt->execute();

            $_SESSION['username'] = $username;
            header('Location: main.php');
            exit;
        } catch (PDOException $e) {
            $error="Error: ".$e->getMessage();
        }
    }
     else {
        $error = 'Please fill in all fields with valid information ';
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
    <title>signUp</title>
    <link rel="stylesheet" href="css/registraion.css">
    
</head>
<body>
    <div class="Registration">
        <form method="POST" action="">
        <h2>sign Up</h2>

        <div class="informaion-box">
        <input type="text" name="first_name" placeholder="First Name" required>
        </div>

        <div class="informaion-box">
        <input type="text" name="last_name" placeholder="Last Name" required>
        </div>

        <div class="informaion-box">
            <input type="text" name="username" placeholder="Username" required>
        
        </div>

        <div class="informaion-box">
            <input type="text" name="email" placeholder="Email" required>
        
        </div>

        <div class="informaion-box">
            <input type="password" name="password" placeholder="Password" required>
            

        </div>
        
            <button type="submit">Sign Up</button>

            <div class="register-link">
            <p>Already have an account? <a href="login.php">Login here</a></p>
            </div>
            <?php echo $error; ?>
        </form>
        
    </div>
</body>
</html>