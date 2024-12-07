<?php
$servername = "localhost";
$username = "4558122_lunarlore";
$password = "cse311@2021_";
$dbname = "4558122_lunarlore";

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_name = $_POST['username'];
    $user_email = $_POST['email'];
    $user_password = $_POST['password'];
    $user_DOB = $_POST['DOB'];
    $user_gender = $_POST['gender'];
    $user_location = $_POST['location'];

    if (empty($user_name) || empty($user_email) || empty($user_password) || empty($user_DOB)|| empty($user_gender) || empty($user_location)) {
        echo "All fields are required";
        exit;
    }
    
    if (!filter_var($user_email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format";
        exit;
    }
$stmt = $conn->prepare("SELECT * FROM users WHERE email = ? OR username = ?");
$stmt->bind_param("ss", $user_email, $user_username); 
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    echo "Email is registered, try another email";
    $stmt->close();
    exit;
} 

$stmt->close();

    $hashed_password = password_hash($user_password, PASSWORD_BCRYPT);
    $stmt = $conn->prepare("INSERT INTO users (username, email, password, DOB, location, gender) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $user_name, $user_email, $hashed_password, $user_DOB, $user_location, $user_gender,);

    if ($stmt->execute()) {
        echo "Registration successful";
        header("Location: index.html");
            exit();
    } else {
        echo "There is an error during registration.";
    }
    $stmt->close();
}

$conn->close();
?>
