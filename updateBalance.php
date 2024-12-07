<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: loginPage.html");
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
$amount = $_POST['amount'];

$sql = "UPDATE users SET balance = balance + ? WHERE userid = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("di", $amount, $user_id);
$stmt->execute();
$stmt->close();
$conn->close();

header("Location: profile.php");
exit();
?>
