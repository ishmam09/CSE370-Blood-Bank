<?php
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'blood_bank';
$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error){
    die("Connection failed: " . $conn->connect_error);
}
$locations = [];
$locationQuery = $conn->query("SELECT DISTINCT Location FROM blood_bank ORDER BY Location");
while ($row = $locationQuery->fetch_assoc()){
    $locations[] = $row['Location'];
}
$searchResults = [];
$searchPerformed = false;
$sortBy = isset($_GET['sort']) ? $_GET['sort'] : 'location';
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['search'])){
    $searchTerm = trim($_GET['search']);
    $searchPerformed = true;
    if (!empty($searchTerm)){
        $query = "SELECT * FROM blood_bank WHERE Location LIKE ?";
        $stmt = $conn->prepare($query);
        $searchParam = "%" . $searchTerm . "%";
        $stmt->bind_param("s", $searchParam);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $searchResults[] = $row;
            }
        }
        $stmt->close();
    }
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blood Bank Search</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f1f1f1;
            margin: 0;
            padding: 0;
        }
        .search-container{
            max-width: 900px;
            margin: 30px auto;
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            padding: 30px;
        }
        h2{
            color: #8b0000;
            text-align: center;
            margin-bottom: 25px;
            font-size: 28px;
        }
        .search-header{
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-bottom: 25px;
            align-items: center;
        }
        .search-box{
            flex: 1;
            min-width: 250px;
            position: relative;
        }
        .search-input{
            width: 90%;
            padding: 12px 40px 12px 15px;
            border: 2px solid #ddd;
            border-radius: 6px;
            font-size: 16px;
            transition: border-color 0.3s;
        }
        .search-input:focus{
            border-color: #8b0000;
            outline: none;
        }
        .search-icon{
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #777;
            cursor: pointer;
        }
        .location-dropdown{
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            max-height: 250px;
            overflow-y: auto;
            background: white;
            border: 1px solid #ddd;
            border-radius: 0 0 6px 6px;
            z-index: 100;
            display: none;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .location-item{
            padding: 10px 15px;
            cursor: pointer;
            transition: background 0.2s;
        }
        .location-item:hover{
            background-color: #f0f0f0;
        }
        .search-btn{
            background-color: #8b0000;
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 6px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s;
            white-space: nowrap;
        }
        .search-btn:hover{
            background-color: #a52a2a;
        }
        .results-container{
            margin-top: 30px;
        }
        .result-card{
            background: white;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 15px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-left: 4px solid #8b0000;
            transition: transform 0.2s;
        }
        .result-card:hover{
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .bank-info{
            flex: 1;
        }
        .bank-info h3{
            margin: 0 0 10px 0;
            color: #333;
        }
        
        .bank-info p{
            margin: 5px 0;
            color: #555;
        }
        .bank-info strong{
            color: #333;
        }
        .inventory-btn{
            background-color: #2c3e50;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: 600;
            transition: background 0.3s;
            white-space: nowrap;
            margin-left: 20px;
        }
        .inventory-btn:hover{
            background-color: #1a252f;
        }
        .no-results{
            text-align: center;
            padding: 30px;
            background: #f9f9f9;
            border-radius: 8px;
            color: #8b0000;
            font-size: 18px;
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
        .navbar-left a{
            color: white;
            text-decoration: none;
            margin-left: 20px;
            font-size: 16px;           
        }
        .navbar-left a:hover{
            text-decoration: underline;
        }
        @media (max-width: 768px){
            .search-header {
                flex-direction: column;
            }
            .search-box{
                width: 100%;
            }
            .result-card{
                flex-direction: column;
                align-items: flex-start;
            }
            .inventory-btn{
                margin: 15px 0 0 0;
                align-self: flex-end;
            }
        }
    </style>
</head>
<body>
<div class="navbar">
    <div class="navbar-left">
        <a href="home.php">Home</a>
    </div>
</div>
    <div class="search-container">
        <h2>Looking for Blood Banks?</h2>
        <form method="GET" action="">
            <div class="search-header">
                <div class="search-box">
                    <input type="text" name="search" id="searchInput" class="search-input" 
                           placeholder="Search by location..." 
                           value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>"
                           required
                           list="locations">
                    <datalist id="locations">
                        <?php foreach ($locations as $location): ?>
                            <option value="<?= htmlspecialchars($location) ?>">
                        <?php endforeach; ?>
                    </datalist>
                </div>
                <button type="submit" class="search-btn">Search</button>
            </div>
        </form>
        <div class="results-container">
            <?php if ($searchPerformed && !empty($searchResults)): ?>
                <?php foreach ($searchResults as $bank): ?>
                    <div class="result-card">
                        <div class="bank-info">
                            <h3><?= htmlspecialchars($bank['Location']) ?></h3>
                            <p><strong>Bank ID:</strong> <?= htmlspecialchars($bank['Bank_ID']) ?></p>
                            <p><strong>Email:</strong> <?= htmlspecialchars($bank['Email']) ?></p>
                        </div>
                        <a href="inventory.php?bank_id=<?= $bank['Bank_ID'] ?>" class="inventory-btn">
                            View Inventory
                        </a>
                    </div>
                <?php endforeach; ?>
            <?php elseif ($searchPerformed): ?>
                <div class="no-results">
                    No blood banks found matching your search.
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>