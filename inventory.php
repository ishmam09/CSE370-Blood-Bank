<?php
session_start();
include 'db_connect.php';
$from_recipient = isset($_GET['from_recipient']) && $_GET['from_recipient'] == 1;
$logged_in = false;
if (isset($_SESSION['user_id'])){
    $logged_in = true;
} 
elseif (isset($_SESSION['email'])){
    $email = $_SESSION['email'];
    $result = mysqli_query($conn, "SELECT User_ID FROM user WHERE Email = '$email'");
    if ($result && mysqli_num_rows($result) > 0){
        $user = mysqli_fetch_assoc($result);
        $_SESSION['user_id'] = $user['User_ID'];
        $logged_in = true;
    }
}
if ($logged_in && $from_recipient && isset($_GET['receive'])){
    $packet_id = $_GET['receive'];
    $user_id = $_SESSION['user_id'];
    $blood_query = "SELECT * FROM blood_donation_record WHERE Packet_serial_number = ?";
    $stmt = $conn->prepare($blood_query);
    $stmt->bind_param("i", $packet_id);
    $stmt->execute();
    $blood = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    if ($blood){
        $check_recipient_sql = "SELECT * FROM recipient WHERE user_id = ?";
        $stmt = $conn->prepare($check_recipient_sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $recipient_result = $stmt->get_result();
        $stmt->close();
        if ($recipient_result->num_rows == 0) {
            $insert_recipient_sql = "INSERT INTO recipient (user_id, times_recieved) VALUES (?, 1)";
            $stmt = $conn->prepare($insert_recipient_sql);
            $stmt->bind_param("i", $user_id);
            if ($stmt->execute()){
                $stmt->close();
                $success = "Recipient data inserted and blood packet received!";
            } 
            else{
                $stmt->close();
                $error = "Error inserting recipient data.";
            }
        } 
        else{
            $update_recipient_sql = "UPDATE recipient SET times_recieved = times_recieved + 1 WHERE user_id = ?";
            $stmt = $conn->prepare($update_recipient_sql);
            $stmt->bind_param("i", $user_id);
            if ($stmt->execute()){
                $stmt->close();
                $success = "Recipient data updated and blood packet received!";
            } 
            else{
                $stmt->close();
                $error = "Error updating recipient data.";
            }
        }
        $delete_sql = "DELETE FROM blood_donation_record WHERE Packet_serial_number = ?";
        $stmt = $conn->prepare($delete_sql);
        $stmt->bind_param("i", $packet_id);
        if ($stmt->execute()) {
            $stmt->close();
            $success = "Blood packet {$packet_id} has been successfully received!";
        } 
        else{
            $stmt->close();
            $error = "Error deleting blood packet.";
        }
    } 
    else{
        $error = "Blood packet not found!";
    }
}
$current_date = date('M d, Y'); 
$current_date_mysql = date('Y-m-d'); 
if ($logged_in){
    $order = isset($_GET['order']) ? $_GET['order'] : 'ASC';
    $sort_by = isset($_GET['sort_by']) ? $_GET['sort_by'] : 'Packet_serial_number';
    $bank_id = isset($_GET['bank_id']) ? $_GET['bank_id'] : '';
    $blood_group = isset($_GET['blood_group']) ? $_GET['blood_group'] : '';
    $query = "SELECT bdr.*, bb.location, bb.email 
              FROM blood_donation_record bdr
              JOIN blood_bank bb ON bdr.Bank_ID = bb.Bank_ID
              WHERE bdr.Expiry_date >= ?";
    
    if (!empty($bank_id)){
        $query .= " AND bdr.Bank_ID = ?";
    }
    if (!empty($blood_group)){
        $query .= " AND bdr.Blood_group = ?";
    }
    $query .= " ORDER BY $sort_by $order";
    $stmt = $conn->prepare($query);
    if (!empty($bank_id) && !empty($blood_group)) {
        $stmt->bind_param("sis", $current_date_mysql, $bank_id, $blood_group);
    } elseif (!empty($bank_id)) {
        $stmt->bind_param("si", $current_date_mysql, $bank_id);
    } elseif (!empty($blood_group)) {
        $stmt->bind_param("ss", $current_date_mysql, $blood_group);
    } else {
        $stmt->bind_param("s", $current_date_mysql);
    }
    $stmt->execute();
    $result = $stmt->get_result();
    $locations = $conn->query("SELECT Bank_ID, location FROM blood_bank");
    $blood_groups = $conn->query("SELECT DISTINCT Blood_group FROM blood_donation_record ORDER BY Blood_group");
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Blood Inventory</title>
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
        .inventory-container{
            max-width: 1200px;
            margin: 40px auto;
            padding: 30px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.08);
        }
        .inventory-header{
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .inventory-title{
            color: rgb(53, 0, 0);
            margin-bottom: 0;
            font-size: 28px;
            position: relative;
            padding-bottom: 10px;
        }
        .inventory-title:after{
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 60px;
            height: 3px;
            background: rgba(179, 0, 0, 0.6);
        }
        .current-date{
            font-size: 16px;
            color: #666;
            font-weight: bold;
        }
        .inventory-filters{
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
            flex-wrap: wrap;
            align-items: center;
            justify-content: center;
        }
        .filter-group{
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .inventory-table{
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .inventory-table th, 
        .inventory-table td{
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        .inventory-table th{
            background-color: rgb(53, 0, 0);
            color: white;
            position: sticky;
            top: 0;
        }
        .inventory-table tr:hover{
            background-color: #f5f5f5;
        }
        .expiring-soon{
            background-color: #fff3cd;
        }
        .days-remaining{
            font-weight: bold;
        }
        .days-remaining.low{
            color: #dc3545;
        }
        .no-results{
            text-align: center;
            padding: 20px;
            color: #666;
        }
        .filter-select{
            padding: 8px 15px;
            border-radius: 4px;
            border: 1px solid #ddd;
            background: white;
            color: #333;
        }
        .filter-button{
            background: rgb(53, 0, 0);
            color: white;
            padding: 8px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background 0.3s;
        }
        .filter-button:hover{
            background: rgb(179, 0, 0);
        }
        .filter-button.active{
            background: rgb(179, 0, 0);
        }
        .login-prompt-container{
            max-width: 600px;
            margin: 40px auto;
            padding: 30px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.08);
            text-align: center;
        }
        .login-prompt-container h2{
            color: rgb(53, 0, 0);
            margin-bottom: 20px;
        }
        .login-prompt-container a{
            display: inline-block;
            background: rgb(53, 0, 0);
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
            transition: background 0.3s;
        }
        .login-prompt-container a:hover{
            background: rgb(179, 0, 0);
        }
        @media (max-width: 768px){
            .inventory-filters {
                flex-direction: column;
                align-items: stretch;
            }
            .filter-group{
                flex-direction: column;
                align-items: stretch;
                gap: 5px;
            }
            .inventory-header{
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }
        }
        .receive-btn {
            background: #28a745;
            color: white;
            padding: 5px 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background 0.3s;
        }
        .receive-btn:hover {
            background: #218838;
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
            <a href="inventory.php">Inventory</a>
            <a href="donor.php">Donate</a>
            <a href="recipient.php">Request</a>
            <?php if ($logged_in): ?>
                <a href="logout.php">Logout</a>
            <?php else: ?>
                <a href="login.php">Login</a>
            <?php endif; ?>
        </div>
    </div>
    
    <?php if (!$logged_in): ?>
        <div class="login-prompt-container">
            <h2>Login Required</h2>
            <p>To view the blood inventory, please log in to your account.</p>
            <a href="login.php">Go to Login Page</a>
        </div>
    <?php else: ?>
        <div class="inventory-container">
            <?php if (isset($success)): ?>
                <div class="success-message"><?php echo $success; ?></div>
            <?php elseif (isset($error)): ?>
                <div class="error-message"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <div class="inventory-header">
                <h1 class="inventory-title">Current Blood Inventory</h1>
                <div class="current-date">Date: <?php echo $current_date; ?></div>
            </div>        
            <div class="inventory-filters">
                <div class="filter-group">
                    <span>Sort by:</span>
                    <a href="?sort_by=Packet_serial_number&order=ASC<?= !empty($bank_id) ? '&bank_id='.$bank_id : '' ?><?= !empty($blood_group) ? '&blood_group='.$blood_group : '' ?>">
                        <button class="filter-button <?= ($sort_by == 'Packet_serial_number' && $order == 'ASC') ? 'active' : '' ?>">Serial # (ASC)</button>
                    </a>
                    <a href="?sort_by=Packet_serial_number&order=DESC<?= !empty($bank_id) ? '&bank_id='.$bank_id : '' ?><?= !empty($blood_group) ? '&blood_group='.$blood_group : '' ?>">
                        <button class="filter-button <?= ($sort_by == 'Packet_serial_number' && $order == 'DESC') ? 'active' : '' ?>">Serial # (DESC)</button>
                    </a>
                    <a href="?sort_by=Donation_date&order=ASC<?= !empty($bank_id) ? '&bank_id='.$bank_id : '' ?><?= !empty($blood_group) ? '&blood_group='.$blood_group : '' ?>">
                        <button class="filter-button <?= ($sort_by == 'Donation_date' && $order == 'ASC') ? 'active' : '' ?>">Oldest First</button>
                    </a>
                    <a href="?sort_by=Donation_date&order=DESC<?= !empty($bank_id) ? '&bank_id='.$bank_id : '' ?><?= !empty($blood_group) ? '&blood_group='.$blood_group : '' ?>">
                        <button class="filter-button <?= ($sort_by == 'Donation_date' && $order == 'DESC') ? 'active' : '' ?>">Newest First</button>
                    </a>
                </div>              
                <div class="filter-group">
                    <span>Location:</span>
                    <select class="filter-select" onchange="location = this.value ? '?bank_id='+this.value+'<?= !empty($blood_group) ? '&blood_group='.$blood_group : '' ?><?= !empty($sort_by) ? '&sort_by='.$sort_by : '' ?><?= !empty($order) ? '&order='.$order : '' ?>' : '?'">
                        <option value="">All Locations</option>
                        <?php while($loc = $locations->fetch_assoc()): ?>
                            <option value="<?= $loc['Bank_ID'] ?>" <?= $bank_id == $loc['Bank_ID'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($loc['location']) ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>             
                <div class="filter-group">
                    <span>Blood Type:</span>
                    <select class="filter-select" onchange="location = this.value ? '?blood_group='+this.value+'<?= !empty($bank_id) ? '&bank_id='.$bank_id : '' ?><?= !empty($sort_by) ? '&sort_by='.$sort_by : '' ?><?= !empty($order) ? '&order='.$order : '' ?>' : '?'">
                        <option value="">All Types</option>
                        <?php while($bg = $blood_groups->fetch_assoc()): ?>
                            <option value="<?= $bg['Blood_group'] ?>" <?= $blood_group == $bg['Blood_group'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($bg['Blood_group']) ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="filter-group">
                    <a href="inventory.php">
                        <button class="filter-button">Reset Filters</button>
                    </a>
                </div>
            </div>
            <?php if ($result->num_rows > 0): ?>
                <table class="inventory-table">
                    <thead>
                        <tr>
                            <th>Packet Serial</th>
                            <th>Blood Type</th>
                            <th>Donation Date</th>
                            <th>Expiry Date</th>
                            <th>Days Remaining</th>
                            <th>Location</th>
                            <th>Contact</th>
                            <?php if ($from_recipient): ?>
                                <th>Action</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = $result->fetch_assoc()): 
                            $expiry_date = new DateTime($row['Expiry_date']);
                            $today = new DateTime();
                            $days_remaining = $today->diff($expiry_date)->days;
                            $is_expiring_soon = $days_remaining <= 7;
                            if ($days_remaining < 0) continue;
                        ?>
                            <tr class="<?= $is_expiring_soon ? 'expiring-soon' : '' ?>">
                                <td><?= htmlspecialchars($row['Packet_serial_number']) ?></td>
                                <td><?= htmlspecialchars($row['Blood_group']) ?></td>
                                <td><?= date('M d, Y', strtotime($row['Donation_date'])) ?></td>
                                <td><?= date('M d, Y', strtotime($row['Expiry_date'])) ?></td>
                                <td>
                                    <span class="days-remaining <?= $is_expiring_soon ? 'low' : '' ?>">
                                        <?= $days_remaining ?>
                                    </span>
                                </td>
                                <td><?= htmlspecialchars($row['location']) ?></td>
                                <td><?= htmlspecialchars($row['email']) ?></td>
                                <?php if ($from_recipient): ?>
                                    <td>
                                        <a href="inventory.php?from_recipient=1&receive=<?= $row['Packet_serial_number'] ?>" 
                                           class="receive-btn" 
                                           onclick="return confirm('Are you sure you want to receive this blood packet?')">
                                            Receive
                                        </a>
                                    </td>
                                <?php endif; ?>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="no-results">
                    <p>No current blood units available matching your criteria.</p>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</body>
</html>