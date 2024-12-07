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

$sql = "SELECT balance FROM users WHERE userid = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($balance);
$stmt->fetch();
$stmt->close();

$total = array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $_SESSION['cart']));

if ($balance >= $total) {
    $new_balance = $balance - $total;
    $sql = "UPDATE users SET balance = ? WHERE userid = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("di", $new_balance, $user_id);
    $stmt->execute();
    $stmt->close();

    $purchase_date = date("Y-m-d H:i:s");

    $stmt = $conn->prepare("INSERT INTO boughthistory (userid, itemNAME, itemprice, purchaseDate) VALUES (?, ?, ?, ?)");
    foreach ($_SESSION['cart'] as $item) {
        $stmt->bind_param("isds", $user_id, $item['name'], $item['price'], $purchase_date);
        $stmt->execute();
    }
    $stmt->close();

    unset($_SESSION['cart']);

    $message = "Purchase successful, new balance is \$$new_balance.";
    $success = true;
} else {
    $message = "Not enough money, You need \$$total, but your current balance is \$$balance.";
    $success = false;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }
        .container {
            text-align: center;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .message {
            font-size: 1.2em;
            margin: 20px 0;
        }
        .success {
            color: green;
        }
        .error {
            color: red;
        }
        .actions {
            margin-top: 20px;
        }
        .actions a {
            display: inline-block;
            margin: 0 10px;
            padding: 10px 20px;
            text-decoration: none;
            color: white;
            background-color: #007bff;
            border-radius: 4px;
            transition: background-color 0.3s ease;
        }
        .actions a:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <p class="message <?php echo $success ? 'success' : 'error'; ?>">
            <?php echo htmlspecialchars($message); ?>
        </p>
        <div class="actions">
            <a href="store.php">Back to Store</a>
        </div>
    </div>
</body>
</html>



