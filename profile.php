<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$servername = "localhost";
$username = "4558122_lunarlore";
$password = "cse311@2021_";
$dbname = "4558122_lunarlore";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id'];
$sql = "SELECT username, email, balance, DOB, gender, location FROM users WHERE userid = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($username, $email, $balance, $DOB, $gender, $location);
$stmt->fetch();
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }
        .profile-container {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 300px;
            text-align: center;
        }
        h2 {
            margin-bottom: 20px;
        }
        p {
            margin: 10px 0;
            font-size: 1em;
        }
        form {
            margin-top: 15px;
        }
        input[type="number"] {
            padding: 5px;
            margin-right: 10px;
            width: 70px;
        }
        button {
            padding: 5px 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        a {
            display: inline-block;
            margin-top: 10px;
            text-decoration: none;
            color: #007bff;
        }
        a:hover {
            text-decoration: none;
        }
        .history-btn {
            display: block;
            margin: 15px auto;
            padding: 10px;
            background-color: #28a745;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        .history-btn:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <div class="profile-container">
        <h2>Your Profile</h2>
        <p><strong>Username:</strong> <?php echo htmlspecialchars($username); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></p>
        <p><strong>Balance:</strong> $<?php echo htmlspecialchars($balance); ?></p>
        <p><strong>Date of Birth:</strong> <?php echo htmlspecialchars($DOB); ?></p>
        <p><strong>Gender:</strong> <?php echo htmlspecialchars($gender); ?></p>
        <p><strong>Location:</strong> <?php echo htmlspecialchars($location); ?></p>

        <form action="updateBalance.php" method="POST">
            <label for="amount">Add Balance:</label>
            <input type="number" id="amount" name="amount" min="1" required>
            <button type="submit">Add Balance</button>
        </form>

        <a href="bougthistory.php" class="history-btn">Purchase History</a>
        <a href="index.html">Back to Homepage</a>
        <a href="logout.php">Logout</a>
    </div>
</body>
</html>

