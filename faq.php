<?php
session_start();
include 'db_connect.php';
$logged_in = false;
$user_id = null;
if (isset($_SESSION['user_id'])){
    $user_id = $_SESSION['user_id'];
    $logged_in = true;
} 
elseif (isset($_SESSION['email'])){
    $email = $_SESSION['email'];
    $result = mysqli_query($conn, "SELECT User_ID FROM user WHERE Email = '$email'");
    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        $user_id = $user['User_ID'];
        $_SESSION['user_id'] = $user_id; 
        $logged_in = true;
    }
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['new_question'])){
    if (!$logged_in) {
        header("Location: login.php");
        exit();
    }
    $question = trim($_POST['new_question']);
    if (!empty($question) && $user_id) {
        $check_user = mysqli_query($conn, "SELECT User_ID FROM user WHERE User_ID = $user_id");
        if (mysqli_num_rows($check_user) > 0) {
            $result = mysqli_query($conn, "SELECT MAX(Serial_number) AS max_serial FROM faq");
            $row = mysqli_fetch_assoc($result);
            $next_serial = ($row['max_serial'] !== null) ? $row['max_serial'] + 1 : 1;
            $stmt = mysqli_prepare($conn, "INSERT INTO faq (Serial_number, Question, User_ID) VALUES (?, ?, ?)");
            mysqli_stmt_bind_param($stmt, "isi", $next_serial, $question, $user_id);
            if (!mysqli_stmt_execute($stmt)) {
                die("Database error: " . mysqli_error($conn));
            }
            header("Location: faq.php");
            exit();
        } else {
            die("Error: User account not found");
        }
    }
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['answer_text']) && isset($_POST['faq_id'])){
    $answer = trim($_POST['answer_text']);
    $faq_id = intval($_POST['faq_id']);
    if (!empty($answer)){
        $stmt = mysqli_prepare($conn, "UPDATE faq SET Answer = ? WHERE Serial_number = ?");
        mysqli_stmt_bind_param($stmt, "si", $answer, $faq_id);
        mysqli_stmt_execute($stmt);
        header("Location: faq.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Frequently Asked Questions</title>
    <style>
        body{
            font-family: Arial, sans-serif;
            background: #f1f1f1;
            margin: 0;
            padding: 0;
        }
        .navbar{
            background-color: rgb(53, 0, 0);
            color: white;
            display: flex;
            justify-content: space-between;
            padding: 15px 30px;
            align-items: center;
        }
        .navbar-left span{
            font-size: 20px;
            font-weight: bold;
        }
        .navbar-right a{
            color: white;
            text-decoration: none;
            margin-left: 20px;
            font-size: 16px;
        }
        .navbar-right a:hover{
            text-decoration: underline;
        }
        .container{
            max-width: 800px;
            margin: 40px auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        .page-title{
            text-align: center;
            color: rgb(53, 0, 0);
            margin-top: 20px;
        }
        .faq-item{
            margin-bottom: 25px;
            border-bottom: 1px solid #eee;
            padding-bottom: 15px;
        }
        .question{
            font-weight: bold;
            color: #333;
            margin-bottom: 5px;
        }
        .answer{
            color: #555;
        }
        .submit-form, .answer-form{
            margin-top: 30px;
            padding: 20px;
            background: #f9f9f9;
            border-radius: 8px;
        }
        textarea{
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-top: 5px;
        }
        input[type="submit"]{
            background: rgb(53, 0, 0);
            color: white;
            border: none;
            padding: 10px 15px;
            margin-top: 10px;
            border-radius: 4px;
            cursor: pointer;
        }
        input[type="submit"]:hover{
            background: rgb(70, 0, 0);
        }
        .login-prompt{
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>
<div class="navbar">
    <div class="navbar-left">
        <span>Blood Bank</span>
    </div>
    <div class="navbar-right">
        <a href="home.php">Home</a>
        <a href="faq.php">FAQ</a>
        <a href="contact.php">Contact Us</a>
        <?php if($logged_in): ?>
            <a href="logout.php">Logout</a>
        <?php else: ?>
            <a href="login.php">Login</a>
        <?php endif; ?>
    </div>
</div>
<h1 class="page-title">Frequently Asked Questions</h1>
<div class="container">
    <?php
    $result = mysqli_query($conn, "SELECT * FROM faq ORDER BY Serial_number ASC");
    $serial_number = 1;
    while ($row = mysqli_fetch_assoc($result)): ?>
        <div class="faq-item">
            <div class="question">Q<?= $serial_number ?>: <?= htmlspecialchars($row['Question']) ?></div>
            <?php if(!empty($row['Answer'])): ?>
                <div class="answer">A:   <?= htmlspecialchars($row['Answer']) ?></div>
            <?php else: ?>
                <form method="POST" class="answer-form">
                    <input type="hidden" name="faq_id" value="<?= $row['Serial_number'] ?>">
                    <textarea name="answer_text" placeholder="Enter your answer..." required></textarea>
                    <input type="submit" value="Submit Answer">
                </form>
            <?php endif; ?>
        </div>
        <?php $serial_number++;?>
    <?php endwhile; ?>
    <div class="submit-form">
        <h2>Ask a New Question</h2>
        <?php if($logged_in): ?>
            <form method="POST">
                <textarea name="new_question" placeholder="Type your question here..." required></textarea>
                <input type="submit" value="Post Question">
            </form>
        <?php else: ?>
            <p class="login-prompt">Please <a href="login.php">login</a> to ask a question.</p>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
