<?php
ob_start(); 
session_start();
include 'db_connect.php'; 
$error = "";
if (isset($_POST['submit'])){
    $email = $_POST['email'];
    $password = $_POST['pass'];
    $query = "SELECT * FROM user WHERE Email = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if ($result && mysqli_num_rows($result) == 1){
        $user = mysqli_fetch_assoc($result);
        if (password_verify($password, $user['password'])){
            $_SESSION['email'] = $user['Email'];
            $_SESSION['user_id'] = $user['User_ID'];  
            header("Location: home.php");
            exit();  
        } 
        else{
            $error = "Incorrect password.";
        }
    } 
    else{
        $error = "User not found.";
    }
}
?>
<!doctype html>
<html>
<head>
    <title>Login - Blood Bank</title>
    <link rel="stylesheet" type="text/css" href="style.css"> 
</head>
<body>
<div class="navbar">
    <div class="navbar-left">
        <span>Blood Bank</span>
    </div>
    <div class="navbar-right">
        <a href="home.php">Home</a>
        <a href="faq.php">FAQ</a>
        <a href="contact.php">Contact us</a>
        <?php if(isset($_SESSION['user_id'])): ?>  
            <a href="logout.php">Logout</a>
        <?php else: ?>
            <a href="reg.php">Register</a>
            <a href="login.php">Login</a>
        <?php endif; ?>
    </div>
</div>
<div class="login-container">
    <h2>Log In</h2>
    <form method="POST">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" required>

        <label for="pass">Password</label>
        <input type="password" id="pass" name="pass" required>

        <input type="submit" value="Submit" name="submit">
    </form>
    <p>Don't have an account? <a href="reg.php">Sign up</a></p>
    <?php if (!empty($error)): ?>
        <div class="error-message"><?php echo $error; ?></div>
    <?php endif; ?>
</div>

</body>
</html>