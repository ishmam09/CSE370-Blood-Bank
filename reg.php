<?php
include 'db_connect.php';
function generateNextUserId($conn){
    $query = "SELECT MAX(User_ID) as max_id FROM user";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    if ($row['max_id'] === null){
        return 100;
    }
    return $row['max_id'] + 1;
}
$nextUserId = generateNextUserId($conn);
if (isset($_POST['submit'])){
    $user = $nextUserId;
    $fname = mysqli_real_escape_string($conn, $_POST['fname']);
    $lname = mysqli_real_escape_string($conn, $_POST['lname']);
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $bdate = mysqli_real_escape_string($conn, $_POST['bdate']);
    $bg = mysqli_real_escape_string($conn, $_POST['bg']);
    $pass = password_hash($_POST['pass'], PASSWORD_DEFAULT); 
    $check_query = "SELECT * FROM user WHERE Email='$email'";
    $result = mysqli_query($conn, $check_query);
    if (mysqli_num_rows($result) > 0) {
        echo "<script>alert('Email already exists. Please use a different one.');</script>";
    } 
    else{
        $sql = "INSERT INTO user (User_ID, First_Name, Last_Name, Gender, Email, Phone_number, Address, Birth_date, Blood_group, password)
                VALUES ('$user', '$fname', '$lname', '$gender', '$email', '$phone', '$address', '$bdate', '$bg', '$pass')";

        if (mysqli_query($conn, $sql)) {
            echo "<script>alert('Registration successful! Your User ID is: $user'); window.location.href='login.php';</script>";
        } 
        else{
            echo "<script>alert('Error occurred: " . mysqli_error($conn) . "');</script>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign Up - Blood Bank</title>
    <link rel="stylesheet" type="text/css" href="style.css"> 
    <style>
        input[readonly]{
            background-color: #f0f0f0;
            cursor: not-allowed;
        }
    </style>
</head>
<body>
<div class="navbar">
    <div class="navbar-left">
        <span>Blood Bank</span>
    </div>
    <div class="navbar-right">
        <a href="home.php"> Home </a>
        <a href="faq.php"> FAQ </a>
        <a href="contact.php"> Contact us </a>
        <a href="login.php"> Login </a>
    </div>    
</div> 
<div class="login-container">
    <h2>Sign Up</h2>
    <form name="form" method="POST">
        <label for="user">User ID</label>
        <input type="text" id="user" name="user" value="<?php echo $nextUserId; ?>" readonly>
        <label for="fname">First Name</label>
        <input type="text" id="fname" name="fname" required>
        <label for="lname">Last Name</label>
        <input type="text" id="lname" name="lname" required>
        <label for="gender">Gender</label>
        <select id="gender" name="gender" required>
            <option value="">Select Gender</option>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
        </select>
        <label for="email">Email</label>
        <input type="email" id="email" name="email" required>
        <label for="phone">Phone No</label>
        <input type="text" id="phone" name="phone" required>
        <label for="address">Address</label>
        <input type="text" id="address" name="address" required>
        <label for="bdate">Birthdate</label>
        <input type="text" id="bdate" name="bdate" placeholder="YYYY-MM-DD" required>
        <label for="bg">Blood Group</label>
        <select id="bg" name="bg" required>
            <option value="">Select Blood Group</option>
            <option value="A+">A+</option>
            <option value="A-">A-</option>
            <option value="B+">B+</option>
            <option value="B-">B-</option>
            <option value="AB+">AB+</option>
            <option value="AB-">AB-</option>
            <option value="O+">O+</option>
            <option value="O-">O-</option>
        </select>
        <label for="pass">Password</label>
        <input type="password" id="pass" name="pass" required>
        <input type="submit" value="Register" name="submit">
    </form>
    <p>Already have an account? <a href="login.php">Login here</a></p>  
</div> 
</body>
</html>