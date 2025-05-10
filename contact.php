<?php
session_start();
include 'db_connect.php';
?>
<!doctype html>
<html>
<head>
    <title>Contact Us</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
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
            position: sticky;
            top: 0;
            z-index: 1000;
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
            transition: color 0.3s;
        }
        .navbar-right a:hover{
            color: #ffcccc;
        }
        .team-container{
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
        }
        .team-title{
            text-align: center;
            color: rgb(53, 0, 0);
            margin-bottom: 40px;
            font-size: 36px;
            position: relative;
            padding-bottom: 15px;
        }
        .team-title:after{
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 4px;
            background: rgba(179, 0, 0, 0.6);
        }
        .member-card{
            display: flex;
            background: white;
            border-radius: 8px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            margin-bottom: 30px;
            overflow: hidden;
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .member-card:hover{
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }
        .member-photo{
            width: 250px;
            height: 250px;
            object-fit: cover;
            border-right: 1px solid #eee;
        }
        .member-info{
            padding: 30px;
            flex: 1;
        }
        .member-name{
            color: rgb(53, 0, 0);
            margin-top: 0;
            font-size: 24px;
            position: relative;
            padding-bottom: 10px;
        }
        .member-name:after{
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 60px;
            height: 3px;
            background: rgba(179, 0, 0, 0.6);
        }
        .member-bio{
            line-height: 1.7;
            color: #333;
            margin-bottom: 20px;
        }
        .social-links{
            display: flex;
            gap: 15px;
            margin-top: 20px;
        }
        .social-link{
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: rgb(53, 0, 0);
            color: white;
            font-size: 18px;
            transition: all 0.3s;
            text-decoration: none;
        }
        .social-link:hover{
            background: rgb(179, 0, 0);
            transform: scale(1.1);
        }
        @media (max-width: 768px){
            .member-card{
                flex-direction: column;
            }
            .member-photo{
                width: 100%;
                height: 300px;
                border-right: none;
                border-bottom: 1px solid #eee;
            }
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
<div class="team-container">
    <h1 class="team-title">Our Team</h1>
    <div class="member-card">
        <img src="images\raisa.jpg" alt="Fairouj Labiba Raisa" class="member-photo">
        <div class="member-info">
            <h2 class="member-name">Fairouj Labiba Raisa</h2>
            <p class="member-bio">
                I am Fairouj Labiba Raisa. I'm a passionate web developer who likes to solve problems and make the best use of time. Follow me for more.
            </p>
            <div class="social-links">
                <a href="https://www.facebook.com/fairouj.raisa" class="social-link" target="_blank">
                    <i class="fab fa-facebook-f"></i>
                </a>
                <a href="https://www.instagram.com/dejaa.vuuu_?igsh=MWZsMW44OHc4MnBhbA==" class="social-link" target="_blank">
                    <i class="fab fa-instagram"></i>
                </a>
                <a href="https://linkedin.com" class="social-link" target="_blank">
                    <i class="fab fa-linkedin-in"></i>
                </a>
            </div>
        </div>
    </div>
    <div class="member-card">
        <img src="images\rahin.jpg" alt="Fatin Anjum Rahin" class="member-photo">
        <div class="member-info">
            <h2 class="member-name">Fatin Anjum Rahin</h2>
            <p class="member-bio">
                I am Fatin Anjum, a student at BRAC University with a passion for web development and design. I have skills in Python, 
                MySQL, PHP, HTML, CSS, and Blender, and I am eager to build dynamic, user-friendly websites and creative 3D content. 
            </p>
            <div class="social-links">
                <a href="https://www.facebook.com/fa.rahin.144" class="social-link" target="_blank">
                    <i class="fab fa-facebook-f"></i>
                </a>
                <a href="https://www.instagram.com/__.raaahiiin?igsh=ZTNsdTd1emN1anJ1" class="social-link" target="_blank">
                    <i class="fab fa-instagram"></i>
                </a>
                <a href="https://linkedin.com" class="social-link" target="_blank">
                    <i class="fab fa-linkedin-in"></i>
                </a>
            </div>
        </div>
    </div>
    <div class="member-card">
        <img src="images\ishmam.jpg" alt="Syed Azmain Ishmam" class="member-photo">
        <div class="member-info">
            <h2 class="member-name">Syed Azmain Ishmam</h2>
            <p class="member-bio">
                My name is Syed Azmain Ishmam. I like coding and solving problems. Huge gamer and Madrid Fan. 
            </p>
            <div class="social-links">
                <a href="https://www.facebook.com/profile.php?id=100041124923310" class="social-link" target="_blank">
                    <i class="fab fa-facebook-f"></i>
                </a>
                <a href="https://www.instagram.com/_._raiiin_._/profilecard/?igsh=OHh6eGx5d3pxNHY5" class="social-link" target="_blank">
                    <i class="fab fa-instagram"></i>
                </a>
                <a href="https://linkedin.com" class="social-link" target="_blank">
                    <i class="fab fa-linkedin-in"></i>
                </a>
            </div>
        </div>
    </div>
</div>
</body>
</html>