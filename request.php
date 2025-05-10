<?php
session_start();
include 'db_connect.php';
if (!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit;
}
$user_id = $_SESSION['user_id'];
$user_query = "SELECT * FROM user WHERE User_ID = $user_id";
$user_result = $conn->query($user_query);
$user = $user_result->fetch_assoc();
$donor_query = "SELECT * FROM donor WHERE User_ID = $user_id";
$donor_result = $conn->query($donor_query);
$donor = $donor_result->fetch_assoc();
$requests = [];
$query = "SELECT r.*, u.First_Name, u.Last_Name 
          FROM Request r 
          JOIN user u ON r.User_ID = u.User_ID
          ORDER BY r.Time DESC";
$result = $conn->query($query);
if ($result && $result->num_rows > 0){
    while ($row = $result->fetch_assoc()){
        $requests[] = $row;
    }
} 
else{
    $message = "No requests found.";
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['request_id'])){
    $request_id = intval($_POST['request_id']);
    $request_query = "SELECT * FROM Request WHERE Request_ID = $request_id";
    $request_result = $conn->query($request_query);
    if ($request_result && $request_result->num_rows > 0) {
        $request = $request_result->fetch_assoc();
        if ($user['Blood_group'] !== $request['Blood_group']) {
            $message = "Your blood group does not match the requested blood group.";
            echo "<script>alert('$message');</script>";
        } 
        else{
            $current_date = new DateTime();
            $eligible = false;
            if ($donor && $donor['Last_donation_date'] != null){
                $last_donation_date = new DateTime($donor['Last_donation_date']);
                $interval = $last_donation_date->diff($current_date);

                if ($interval->m >= 3 || $interval->y >= 1) {
                    $eligible = true;
                }
            } 
            else{
                $eligible = true;
            }
            if ($eligible){
                $current_date_str = $current_date->format('Y-m-d');
                $donor_check_query = "SELECT * FROM donor WHERE User_ID = $user_id";
                $donor_check_result = $conn->query($donor_check_query);
                if ($donor_check_result->num_rows > 0) {
                    $current_times_donated = $donor['Times_donated'] ?? 0;
                    $new_times_donated = $current_times_donated + 1;
                    $regular_donor_flag = ($new_times_donated < 5) ? 1 : 0;
                    $recognized_donor_flag = ($new_times_donated >= 5) ? 1 : 0;
                    $update_donor_query = "UPDATE donor 
                                           SET Last_donation_date = '$current_date_str', Times_donated = $new_times_donated,
                                               Regular_donor_flag = $regular_donor_flag, Recognized_donor_flag = $recognized_donor_flag
                                           WHERE User_ID = $user_id";
                    $conn->query($update_donor_query);
                } 
                else{
                    $insert_donor_query = "INSERT INTO donor (User_ID, Last_donation_date, Times_donated, Regular_donor_flag, Recognized_donor_flag) 
                                           VALUES ($user_id, '$current_date_str', 1, 1, 0)";
                    $conn->query($insert_donor_query);
                }
                $delete_request_query = "DELETE FROM Request WHERE Request_ID = $request_id";
                $conn->query($delete_request_query);
                $message = "Blood donation successful! Thank you for donating.";
                echo "<script>alert('$message');</script>";
                echo "<script>window.location.href='request.php';</script>";
                exit;
            } 
            else{
                $message = "You are not eligible to donate. You must wait at least 3 months from your last donation.";
                echo "<script>alert('$message');</script>";
            }
        }
    } 
    else{
        $message = "Invalid request.";
        echo "<script>alert('$message');</script>";
    }
}
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
        .content-section{
            max-width: 1200px;
            margin: 40px auto;
            padding: 30px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.08);
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
        .request-list{
            margin-top: 40px;
            display: flex;
            flex-direction: column;
            gap: 20px;
        }
        .request-item{
            display: flex;
            justify-content: space-between;
            background: rgba(179, 0, 0, 0.05);
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        .request-info{
            flex: 1;
        }
        .request-buttons{
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .request-buttons button{
            background-color: rgba(179, 0, 0, 0.6);
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .request-buttons button:hover{
            background-color: rgba(179, 0, 0, 0.8);
        }
        .error-message{
            color: red;
            font-weight: bold;
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
        <a href="editprofile.php">Profile Management</a>
        <a href="faq.php">FAQ</a>
        <a href="contact.php">Contact Us</a>
        <a href="logout.php">Logout</a>
    </div>
</div>
<div class="content-section">
    <h2>Requests for Blood Donations</h2>
    <?php if (isset($message)) echo "<p class='error-message'>$message</p>"; ?>
    <div class="request-list">
        <?php if (count($requests) > 0): ?>
            <?php foreach ($requests as $request): ?>
                <div class="request-item">
                    <div class="request-info">
                        <h3><?php echo htmlspecialchars($request['First_Name'] . ' ' . $request['Last_Name']); ?></h3>
                        <p><strong>Blood Group:</strong> <?php echo htmlspecialchars($request['Blood_group']); ?></p>
                        <p><strong>Location:</strong> <?php echo htmlspecialchars($request['Location']); ?></p>
                        <p><strong>Date Required:</strong> <?php echo htmlspecialchars($request['Time']); ?></p>
                    </div>
                    <div class="request-buttons">
                        <form action="request.php" method="POST">
                            <input type="hidden" name="request_id" value="<?php echo $request['Request_ID']; ?>">
                            <button type="submit">Donate</button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No donation requests available at the moment.</p>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
