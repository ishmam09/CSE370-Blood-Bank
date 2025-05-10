<?php
session_start();
include 'db_connect.php';
?>
<!doctype html>
<html>
<head>
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
            text-decoration: underline;
        }
        .hero{
            background: url('photo-1638272467190-4ff6f773315c.avif') center/cover no-repeat;
            color: white;
            text-align: center;
            padding: 120px 20px;
            position: relative;
        }
        .hero::after{
            content: '';
            position: absolute;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: rgba(179, 0, 0, 0.6);
        }
        .hero-content{
            position: relative;
            z-index: 2;
            max-width: 800px;
            margin: 0 auto;
        }
        .hero h1{
            font-size: 40px;
            margin-bottom: 20px;
        }
        .hero p{
            font-size: 18px;
            margin-bottom: 30px;
        }
        .hero-buttons{
            display: flex;
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap;
        }
        .hero-buttons a{
            background: white;
            color: rgb(53, 0, 0);
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
            transition: background 0.3s, color 0.3s;
        }
        .hero-buttons a.primary{
            background: rgb(53, 0, 0);
            color: white;
        }
        .hero-buttons a:hover{
            transform: translateY(-2px);
            opacity: 0.9;
        }
        .join-donor-btn{
            background-color: rgba(179, 0, 0, 0.6);
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .join-donor-btn:hover{
            background-color: rgba(179, 0, 0, 0.8);
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
    .stats-container{
        display: flex;
        justify-content: space-around;
        flex-wrap: wrap;
        margin-top: 40px;
        text-align: center;
        background: rgba(179, 0, 0, 0.05);
        padding: 20px;
        border-radius: 8px;
    }
    .stat-item{
        padding: 15px;
        min-width: 150px;
    }
    .stat-number{
        font-size: 32px;
        color: rgb(53, 0, 0);
        font-weight: bold;
    }
    @media (max-width: 768px){
        .content-section {
            padding: 20px;
            margin: 20px 15px;
        }
        .stats-container{
            flex-direction: column;
            gap: 20px;
        }
    }
    .qr-links-section{
        display: flex;
        justify-content: space-between;
        align-items: center;
        max-width: 1200px;
        margin: 40px auto;
        padding: 30px;
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 15px rgba(0,0,0,0.08);
    }
    .qr-code{
        flex: 1;
        text-align: center;
        padding: 20px;
    }
    .important-links{
        flex: 2;
        padding: 20px;
    }
    .link-buttons{
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 15px;
    }
    .link-button{
        background: rgb(53, 0, 0);
        color: white;
        padding: 12px 20px;
        text-decoration: none;
        border-radius: 6px;
        font-weight: bold;
        text-align: center;
        transition: all 0.3s ease;
    }
    .link-button:hover{
        background: rgb(179, 0, 0);
        transform: translateY(-2px);
    }
    @media (max-width: 768px){
        .qr-links-section {
            flex-direction: column;
        }
        .link-buttons{
            grid-template-columns: 1fr;
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
            <a href="editprofile.php">Profile Management</a>
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
<div class="hero">
    <div class="hero-content">
        <h1>Welcome to Blood Bank </h1>
        <p>Blood Bank is a place where you can easily get blood donors without any hassle.</p>
        <div class="hero-buttons">
            <a href="donor.php" class="primary">Donate Blood</a>
            <a href="recipient.php" class="primary">Search for Donors</a>
        </div>
    </div>
</div>
<div class="content-section">
    <h2>What is Blood Bank?</h2>
    <p>
        <strong>Blood Bank is an automated blood service that connects blood searchers with voluntary donors 
        in a moment through the website. We always a free and serve you all at any moment.</strong>
    </p>
</div>
<div class="content-section">
    <h2>Why Blood Bank?</h2>
    <ul class="benefits-list">
        <li><strong>100% Automated</strong></li>
        <li><strong>Available 24×7</strong></li>
        <li><strong>Always Free</strong></li>
        <li><strong>All data are secured</strong></li>
    </ul>
</div>
<div class="content-section">
    <h2>Take a look at our Inventory</h2>
    <p>
        <strong>We have a huge collection of blood in out inventory. Check out.</strong>
    </p>
    <div class="hero-buttons">
        <a href="inventory.php" class="primary">Inventory</a>
    </div>   
</div>
<div class="qr-links-section">
    <div class="qr-code">
        <img src="Untitled 1.png" alt="Scan QR Code" width="150">
        <p>Scan to visit our mobile site</p>
    </div>
    <div class="important-links">
        <h3>Important Links:</h3>
        <div class="link-buttons">
            <a href="contact.php" class="link-button">Contact Us</a>
            <a href="donor.php" class="link-button">Donate Blood</a>
            <a href="recipient.php" class="link-button">Recieve blood</a>
            <a href="faq.php" class="link-button">FAQ</a>
            <a href="upcoming.php" class="link-button">Upcoming Events</a>
            <a href="inventory.php" class="link-button">Inventory</a>
        </div>
    </div>
</div>
</body>
</html>
