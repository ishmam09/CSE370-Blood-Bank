<?php
session_start();
include 'db_connect.php';
if (!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}
$comment = $blood_group = $location = $time = "";
$error = "";
$success = "";
if ($_SERVER["REQUEST_METHOD"] == "POST"){
    $comment = mysqli_real_escape_string($conn, trim($_POST['Comment']));
    $blood_group = mysqli_real_escape_string($conn, trim($_POST['Blood-Group']));
    $location = mysqli_real_escape_string($conn, trim($_POST['Location']));
    $time = mysqli_real_escape_string($conn, trim($_POST['Time']));
    $user_id = $_SESSION['user_id'];
    $valid_blood_groups = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];
    if (!in_array(strtoupper($blood_group), $valid_blood_groups)) {
        $error = "Please enter a valid blood group (e.g., A+, B-, AB+, etc.)";
    }
    elseif (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $time)) {
        $error = "Please enter date in YYYY-MM-DD format";
    }
    else{
        $result = $conn->query("SELECT COUNT(*) AS total FROM Request");
        $row = $result->fetch_assoc();
        if ($row['total'] == 0) {
            $conn->query("ALTER TABLE Request AUTO_INCREMENT = 1");
        }

        $sql = "INSERT INTO Request (User_ID, Blood_group, Location, Time, Comment) 
                VALUES (?, ?, ?, ?, ?)";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("issss", $user_id, $blood_group, $location, $time, $comment);
        
        if ($stmt->execute()){
            $success = "Your blood request (ID: " . $stmt->insert_id . ") has been submitted successfully!";
            $comment = $blood_group = $location = $time = "";
        } else {
            $error = "Error submitting request: " . $conn->error;
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Recipient Portal | Blood Bank</title>
    <link rel="stylesheet" href="style2.css">
</head>
<body>
    <div class="navbar">
        <div class="navbar-left">
            <span>Blood Bank</span>
        </div>
        <div class="navbar-right">
            <a href="home.php">Home</a>
            <a href="inventory.php?from_recipient=1">Inventory</a>
            <a href="faq.php">FAQ</a>
            <a href="contact.php">Contact Us</a>
            <a href="logout.php">Logout</a>
        </div>
    </div>

    <div class="content-section">
        <h2>Recipient Portal</h2>
        
        <?php if (!empty($error)): ?>
            <div class="error-message"><?php echo $error; ?></div>
        <?php elseif (!empty($success)): ?>
            <div class="success-message"><?php echo $success; ?></div>
        <?php endif; ?>
        
        <div class="portal-options">
            <div class="portal-option">
                <h3>1. Look into our inventory</h3>
                <div class="button-group">
                    <a href="inventory.php?from_recipient=1" class="link-button">View Inventory</a>
                    <a href="searchbar.php" class="link-button">Search Blood Bank</a>
                </div>
            </div>
            
            <div class="portal-option">
                <h3>2. Blood Not Available? Post a request here</h3>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" class="request-form">
                    <label for="Comment">Leave a comment here</label>
                    <input type="text" id="Comment" name="Comment" value="<?php echo htmlspecialchars($comment); ?>" required>
                    
                    <label for="Blood-Group">Required blood-group:</label>
                    <input type="text" id="Blood-Group" name="Blood-Group" value="<?php echo htmlspecialchars($blood_group); ?>" 
                           placeholder="e.g., A+, B-, O+" required>
                    
                    <label for="Location">Where to deliver blood?</label>
                    <input type="text" id="Location" name="Location" value="<?php echo htmlspecialchars($location); ?>" required>
                    
                    <label for="Time">Required date:</label>
                    <input type="text" id="Time" name="Time" value="<?php echo htmlspecialchars($time); ?>" 
                           placeholder="YYYY-MM-DD" required>
                    
                    <button type="submit" class="submit-btn">Post Request</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>