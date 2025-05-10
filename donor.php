<?php
session_start();
include 'db_connect.php';
$valid_locations = ['Badda', 'Motijheel', 'Kallyanpur', 'Mohammadpur', 'Dhanmondi', 
                   'Mirpur', 'Banani', 'Bashundhara', 'Mohakhali', 'Gulshan', 'Uttara'];
if (!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit;
}
$user_id = $_SESSION['user_id'];
$donation_data = [];
$message = "";
$donor_stmt = $conn->prepare("SELECT * FROM donor WHERE User_ID = ?");
$donor_stmt->bind_param("i", $user_id);
$donor_stmt->execute();
$donor_result = $donor_stmt->get_result();
if ($donor_result->num_rows > 0) {
    $donation_data = $donor_result->fetch_assoc();
}
$user_stmt = $conn->prepare("SELECT * FROM user WHERE User_ID = ?");
$user_stmt->bind_param("i", $user_id);
$user_stmt->execute();
$user_result = $user_stmt->get_result();
$user_data = $user_result->fetch_assoc();
$blood_group = $user_data['Blood_group'] ?? '';
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
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['bloodBankLocation'])){
    $selected_location = trim($_POST['bloodBankLocation']);
    if (!in_array($selected_location, $valid_locations)){
        $message = "<p class='error'>Invalid blood bank location selected.</p>";
    } 
    else{
        $conn->begin_transaction();
        try {
            $bank_stmt = $conn->prepare("SELECT Bank_ID FROM Blood_Bank WHERE Location = ?");
            $bank_stmt->bind_param("s", $selected_location);
            $bank_stmt->execute();
            $bank_result = $bank_stmt->get_result();
            if ($bank_result->num_rows === 0) {
                throw new Exception("Selected blood bank location is invalid.");
            }
            $bank_row = $bank_result->fetch_assoc();
            $bank_id = $bank_row['Bank_ID'];
            $can_donate = true;
            $today = new DateTime();
            if (!empty($donation_data['Last_donation_date'])){
                $last_donation_date = new DateTime($donation_data['Last_donation_date']);
                $next_eligible_date = (clone $last_donation_date)->add(new DateInterval('P3M'));
                if ($today < $next_eligible_date) {
                    $next_donation_date = $next_eligible_date->format('F j, Y');
                    throw new Exception("You cannot donate yet. Your next eligible donation date is $next_donation_date.");
                }
            }
            if (empty($blood_group) || !preg_match('/^(A|B|AB|O)[+-]$/', $blood_group)){
                throw new Exception("Invalid blood group information.");
            }
            $serial_result = $conn->query("SELECT MAX(Packet_serial_number) AS max_serial FROM Blood_Donation_Record FOR UPDATE");
            $serial_row = $serial_result->fetch_assoc();
            $new_serial = ($serial_row['max_serial'] ?? 0) + 1;
            $donation_date = $today->format('Y-m-d');
            $expiry_date = $today->modify('+42 days')->format('Y-m-d');
            $insert_stmt = $conn->prepare("INSERT INTO Blood_Donation_Record 
                (Packet_serial_number, Donation_date, Expiry_date, Blood_group, Bank_ID) 
                VALUES (?, ?, ?, ?, ?)");
            $insert_stmt->bind_param("isssi", $new_serial, $donation_date, $expiry_date, $blood_group, $bank_id);
            if (!$insert_stmt->execute()){
                throw new Exception("Error recording donation.");
            }
            $donor_check_stmt = $conn->prepare("SELECT * FROM donor WHERE User_ID = ?");
            $donor_check_stmt->bind_param("i", $user_id);
            $donor_check_stmt->execute();
            $donor_check_result = $donor_check_stmt->get_result();
            if ($donor_check_result->num_rows > 0){
                $donation_data = $donor_check_result->fetch_assoc();
                $new_times_donated = ($donation_data['Times_donated'] ?? 0) + 1;
                $regular_flag = ($new_times_donated < 5) ? 1 : 0;
                $recognized_flag = ($new_times_donated >= 5) ? 1 : 0;
                $update_stmt = $conn->prepare("UPDATE donor SET Times_donated = ?, Last_donation_date = ?, Regular_donor_flag = ?, Recognized_donor_flag = ? WHERE User_ID = ?");
                $update_stmt->bind_param("isiii", $new_times_donated, $donation_date, $regular_flag, $recognized_flag, $user_id);
                if (!$update_stmt->execute()) {
                    throw new Exception("Error updating donor record.");
                }
            } 
            else{
                $new_times_donated = 1;
                $regular_flag = 1;
                $recognized_flag = 0;
                $insert_donor_stmt = $conn->prepare("INSERT INTO donor (User_ID, Times_donated, Last_donation_date, Regular_donor_flag, Recognized_donor_flag) VALUES (?, ?, ?, ?, ?)");
                $insert_donor_stmt->bind_param("iisii", $user_id, $new_times_donated, $donation_date, $regular_flag, $recognized_flag);
                if (!$insert_donor_stmt->execute()) {
                    throw new Exception("Error inserting new donor record.");
                }
            }
            $conn->commit();
            $donor_stmt->execute();
            $donor_result = $donor_stmt->get_result();
            $donation_data = $donor_result->fetch_assoc();
            $message = "<p class='success'>Thank you! Your donation has been recorded successfully.</p>";
        } catch (Exception $e) {
            $conn->rollback();
            $message = "<p class='error'>" . htmlspecialchars($e->getMessage()) . "</p>";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Donor Portal</title>
    <link rel="stylesheet" href="style3.css">
    <style>
        .donation-status {
            margin: 20px 0;
            padding: 15px;
            border-radius: 5px;
            background-color: #f8f9fa;
        }
        .eligible {
            border-left: 5px solid #28a745;
        }
        .not-eligible {
            border-left: 5px solid #dc3545;
        }
        .donation-info {
            margin-bottom: 10px;
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
    <h2>Blood Donation Portal</h2>
    <?php echo $message; ?>

    <?php 
        $status_class = 'eligible';
        $next_eligible_msg = '<span style="color: #28a745;">You are eligible to donate</span>';

        if (!empty($donation_data['Last_donation_date'])) {
            $last_donation = new DateTime($donation_data['Last_donation_date']);
            $next_eligible = (clone $last_donation)->add(new DateInterval('P3M'));
            $today = new DateTime();

            if ($today < $next_eligible) {
                $status_class = 'not-eligible';
                $next_eligible_msg = '<span style="color: #dc3545;">' . htmlspecialchars($next_eligible->format('F j, Y')) . '</span>';
            }
        }
    ?>
    <div class="donation-status <?php echo $status_class; ?>">
        <h3>Your Donation Status</h3>
        <div class="donation-info">
            <strong>Blood Group:</strong> <?php echo htmlspecialchars($blood_group); ?>
        </div>
        <div class="donation-info">
            <strong>Total Donations:</strong> <?php echo htmlspecialchars($donation_data['Times_donated'] ?? 0); ?>
        </div>
        <?php if (!empty($donation_data['Last_donation_date'])): ?>
            <div class="donation-info">
                <strong>Last Donation:</strong> <?php echo htmlspecialchars(date('F j, Y', strtotime($donation_data['Last_donation_date']))); ?>
            </div>
            <div class="donation-info">
                <strong>Next Eligible Donation:</strong> <?php echo $next_eligible_msg; ?>
            </div>
        <?php else: ?>
            <div class="donation-info"><?php echo $next_eligible_msg; ?></div>
        <?php endif; ?>
    </div>

    <form action="" method="POST" class="donation-form">
        <div class="form-group">
            <label for="bloodBankLocation">Select Blood Bank Location:</label>
            <select id="bloodBankLocation" name="bloodBankLocation" required>
                <option value="">--Choose a location--</option>
                <?php foreach ($valid_locations as $location): ?>
                    <option value="<?php echo $location; ?>"><?php echo $location; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit" class="btn-primary">Donate Now</button>
    </form>

    <div class="donation-requests">
        <h3>Look for urgent requests</h3>
        <form action="request.php" method="GET">
            <button type="submit" class="btn-primary">Show Requests</button>
        </form>
    </div>
</div>
</body>
</html>
