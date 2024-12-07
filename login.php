<?php
session_start();

$servername = "localhost";
$username = "4558122_lunarlore";
$password = "cse311@2021_";
$dbname = "4558122_lunarlore";


$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['action']) && $_POST['action'] == 'login') {
        
        $email = $_POST['email'];
        $password = $_POST['password'];

        $sql = "SELECT userid, password FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($id, $hashed_password);
            $stmt->fetch();

            if (password_verify($password, $hashed_password)) {
                $_SESSION['user_id'] = $id;
                $_SESSION['email'] = $email;

                header("Location: home.html");
                exit();
            } else {
                $error = "Incorrect email or password.";
            }
        } else {
            $error = "No account found with that email.";
        }
        $stmt->close();
    } elseif (isset($_POST['action']) && $_POST['action'] == 'signup') {
       
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $dob = $_POST['DOB'];
        $location = $_POST['location'];
        $gender = $_POST['gender'];

        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO users (username, email, password, dob, location, gender) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssss", $username, $email, $hashed_password, $dob, $location, $gender);

        if ($stmt->execute()) {
            $success = "Account created successfully. Please login.";
        } else {
            $error = "Error: Could not create account. " . $stmt->error;
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
    <title>Login</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <div class="auth-container">
        <div class="auth-box">
           
            <div class="tab">
                <a href="?form=login" class="tab-btn <?php echo (!isset($_GET['form']) || $_GET['form'] == 'login') ? 'active' : ''; ?>">Login</a>
                <a href="?form=signup" class="tab-btn <?php echo (isset($_GET['form']) && $_GET['form'] == 'signup') ? 'active' : ''; ?>">Sign Up</a>
            </div>

           
            <?php if (!isset($_GET['form']) || $_GET['form'] == 'login'): ?>
            <div class="form-content active">
                <h2>Login</h2>
                <?php if (!empty($error)) { echo "<p class='error'>$error</p>"; } ?>
                <form action="login.php" method="POST">
                    <label for="login-email">Email:</label>
                    <input type="email" id="login-email" name="email" required>

                    <label for="login-password">Password:</label>
                    <input type="password" id="login-password" name="password" required>

                    <input type="hidden" name="action" value="login">
                    <button type="submit" class="submit-btn">Login</button>
                </form>
            </div>
            <?php endif; ?>

            <?php if (isset($_GET['form']) && $_GET['form'] == 'signup'): ?>
            <div class="form-content active">
                <h2>Sign Up</h2>
                <?php if (!empty($success)) { echo "<p class='success'>$success</p>"; } ?>
                <?php if (!empty($error)) { echo "<p class='error'>$error</p>"; } ?>
                <form action="login.php" method="POST">
                    <label for="signup-username">Username:</label>
                    <input type="text" id="signup-username" name="username" required>

                    <label for="signup-email">Email:</label>
                    <input type="email" id="signup-email" name="email" required>

                    <label for="signup-password">Password:</label>
                    <input type="password" id="signup-password" name="password" required>

                    <label for="signup-DOB">Date of Birth:</label>
                    <input type="date" id="signup-DOB" name="DOB" required>

                    <label for="signup-location">Location:</label>
                    <input type="text" id="signup-location" name="location" required>

                    <label for="signup-gender">Gender:</label>
                    <input type="text" id="signup-gender" name="gender" required>

                    <input type="hidden" name="action" value="signup">
                    <button type="submit" class="submit-btn">Sign Up</button>
                </form>
            </div>
            <?php endif; ?>
            <div class="back-to-home">
                <a href="index.html">Homepage</a>
            </div>
        </div>
    </div>
</body>
</html>

