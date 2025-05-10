<?php
session_start();
include 'db_connect.php';
if (!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit;
}
if (empty($_SESSION['csrf_token'])){
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$user_id = $_SESSION['user_id'];
$user_data = [];
$message = '';
function getDonationData($conn, $user_id){
    if (isset($_SESSION['donation_data'])){
        return $_SESSION['donation_data'];
    }
    $stmt = $conn->prepare("SELECT * FROM donor WHERE User_ID = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->num_rows > 0 ? $result->fetch_assoc() : [];
}
$user_stmt = $conn->prepare("SELECT * FROM user WHERE User_ID = ?");
$user_stmt->bind_param("i", $user_id);
$user_stmt->execute();
$user_result = $user_stmt->get_result();
if ($user_result->num_rows === 0) {
    $message = "<p class='error'>User not found.</p>";
} 
else{
    $user_data = $user_result->fetch_assoc();
}
$donation_data = getDonationData($conn, $user_id);
$receiving_count = 0;
$receiving_stmt = $conn->prepare("SELECT SUM(times_recieved) as total FROM recipient WHERE User_ID = ?");
$receiving_stmt->bind_param("i", $user_id);
$receiving_stmt->execute();
$receiving_result = $receiving_stmt->get_result();

if ($receiving_result->num_rows > 0) {
    $row = $receiving_result->fetch_assoc();
    $receiving_count = $row['total'] ?? 0;
}
if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    if (isset($_POST['request_certificate'])) {
        if ($donation_data['Recognized_donor_flag'] == 1){
            $message = "<p class='success'>Your certificate will be sent to your email shortly.</p>";
        }
    } 
    elseif (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])){
        $message = "<p class='error'>Security token mismatch. Please try again.</p>";
    } 
    else{
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        $phone = preg_replace('/[^0-9+]/', '', $_POST['phone']);
        $address = htmlspecialchars($_POST['address']);
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)){
            $message = "<p class='error'>Please enter a valid email address.</p>";
        } 
        else{
            $update_sql = "UPDATE user SET Email=?, Phone_number=?, Address=? WHERE User_ID=?";
            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->bind_param("sssi", $email, $phone, $address, $user_id);
            if ($update_stmt->execute()) {
                $message = "<p class='success'>Profile updated successfully!</p>";
                $user_stmt->execute();
                $user_result = $user_stmt->get_result();
                $user_data = $user_result->fetch_assoc();
            } else {
                $message = "<p class='error'>Error updating profile: " . htmlspecialchars($conn->error) . "</p>";
            }
        }
    }
    $donation_data = getDonationData($conn, $user_id);
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Profile Management</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <div class="navbar">
        <div class="navbar-left">
            <span>Blood Bank</span>
        </div>
        <div class="navbar-right">
            <a href="home.php" class="nav-button home">Home</a>
            <a href="faq.php" class="nav-button faq">FAQ</a>
            <a href="contact.php" class="nav-button contact">Contact Us</a>
            <a href="logout.php" class="nav-button logout">Logout</a>
        </div>    
    </div>
    <div class="profile-content">
        <div class="profile-section">
            <div class="edit-container">
                <h2>Edit Profile</h2>
                <?php echo $message; ?>  
                <form method="POST" action="">
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                    <div class="form-group">
                        <label for="fname">First Name</label>
                        <input type="text" id="fname" name="fname" value="<?php echo htmlspecialchars($user_data['First_Name'] ?? ''); ?>" readonly>          
                    </div>
                    <div class="form-group">
                        <label for="lname">Last Name</label>
                        <input type="text" id="lname" name="lname" value="<?php echo htmlspecialchars($user_data['Last_Name'] ?? ''); ?>" readonly>
                    </div>
                    <div class="form-group">
                        <label for="gender">Gender</label>
                        <input type="text" id="gender" name="gender" value="<?php echo htmlspecialchars($user_data['Gender'] ?? ''); ?>" readonly>
                    </div>
                    <div class="form-group">
                        <label for="email">Email*</label>
                        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user_data['Email'] ?? ''); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone No*</label>
                        <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($user_data['Phone_number'] ?? ''); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="address">Address*</label>
                        <input type="text" id="address" name="address" value="<?php echo htmlspecialchars($user_data['Address'] ?? ''); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="bdate">Birthdate</label>
                        <input type="text" id="bdate" name="bdate" value="<?php echo htmlspecialchars($user_data['Birth_date'] ?? ''); ?>" readonly>
                    </div>
                    <div class="form-group">
                        <label for="bg">Blood Group</label>
                        <input type="text" id="bg" name="bg" value="<?php echo htmlspecialchars($user_data['Blood_group'] ?? ''); ?>" readonly>
                    </div>
                    <div class="form-actions">
                        <input type="submit" class="btn-primary" value="Update" name="submit">
                    </div>
                </form>
            </div>
            <div class="insights-container">
                <h2>Donation Insights</h2>
                <div class="insight-item">
                    <span class="insight-label">Times Donated:</span>
                    <span class="insight-value"><?php echo $donation_data['Times_donated'] ?? 0; ?></span>
                </div>
                <div class="insight-item">
                    <span class="insight-label">Last Donation:</span>
                    <span class="insight-value">
                        <?php 
                        if (!empty($donation_data['Last_donation_date'])) {
                            echo date('F j, Y', strtotime($donation_data['Last_donation_date']));
                        } else {
                            echo 'Never';
                        }
                        ?>
                    </span>
                </div>
                <div class="insight-item">
                    <span class="insight-label">Donor Status:</span>
                    <span class="insight-value">
                        <?php 
                        if (($donation_data['Recognized_donor_flag'] ?? 0) == 1) {
                            echo "Recognized Donor";
                        } elseif (($donation_data['Regular_donor_flag'] ?? 0) == 1) {
                            echo "Regular Donor";
                        } else {
                            echo "New Donor";
                        }
                        ?>
                    </span>
                </div>
                <form method="POST" class="certificate-form">
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                    <button type="submit" class="btn-certificate" name="request_certificate" 
                        <?php echo (($donation_data['Recognized_donor_flag'] ?? 0) == 0) ? 'disabled' : ''; ?>>
                        Request Certificate
                    </button>
                    <?php if (($donation_data['Recognized_donor_flag'] ?? 0) == 0): ?>
                        <p class="info-text">You need to be a recognized donor to request a certificate</p>
                    <?php endif; ?>
                </form>
            </div>
            <div class="insights-container">
                <h2>Receiving Insights</h2>
                <div class="insight-item">
                    <span class="insight-label">Times Received:</span>
                    <span class="insight-value"><?php echo $receiving_count; ?></span>
                </div>
            </div>
        </div>
    </div>
    <script>
        setTimeout(function() {
            window.location.reload();
        }, 30000);
    </script>
</body>
</html>