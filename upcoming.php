<?php
session_start();
include 'db_connect.php';
?>
<!doctype html>
<html>
<head>
    <title>Upcoming Events</title>
</head>
<style>
    body{
            font-family: Arial, sans-serif;
            background: #f1f1f1;
            margin: 0;
            padding: 0;  
        }
        .navbar{
            background-color:rgb(53, 0, 0);
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
            text-decoration: underline:
        }
        .content-section{
        max-width: 1200px;
        margin: 40px auto;
        padding: 30px;
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 15px rgba(0,0,0,0.08);
        }
        .content-section h2{
            color: rgb(53, 0, 0);
            margin-top: 0;
            font-size: 28px;
            position: relative;
            padding-bottom: 10px;
        }
        .content-section h2:after{
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 60px;
            height: 3px;
            background: rgba(179, 0, 0, 0.6);
        }
        .content-section p{
            line-height: 1.7;
            color: #333;
            margin-bottom: 15px;
        }
        .content-section h3{
            color: rgb(53, 0, 0);
            margin-top: 25px;
            font-size: 22px;
        }
        .content-section ul{
            padding-left: 20px;
            line-height: 1.7;
        }
        .content-section li{
            margin-bottom: 8px;
        }
</style>
<body> 
<div class="navbar">
    <div class="navbar-left">
        <span>Blood Bank</span>
    </div>
    <div class="navbar-right">
        <a href="home.php">Home</a>
        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="editprofile.php">Edit Profile</a>
        <?php endif; ?>
        <a href="faq.php">FAQ</a>
        <a href="contact.php">Contact Us</a>
        <?php if (isset($_SESSION['email'])): ?>
            <a href="logout.php">Logout</a>
        <?php else: ?>
            <a href="login.php">Login</a>
        <?php endif; ?>
    </div>
</div>
<div class="content-section">
    <h2>Upcoming Events</h2>
    <p>
        <strong>Currently there are no upcoming events. Please keep an eye on our website for further news.</strong>
    </p>
</div>      
</body>