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

if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    

    $delete_sql = "DELETE FROM boughthistory WHERE id = ? AND userid = ?";
    $stmt = $conn->prepare($delete_sql);
    $stmt->bind_param("ii", $delete_id, $user_id);
    $stmt->execute();
    $stmt->close();

    header("Location: bougthistory.php");
    exit();
}


$sql = "SELECT id, itemNAME, itemprice, purchaseDate FROM boughthistory WHERE userid = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase History</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            background: white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #007bff;
            color: white;
        }
        a {
            display: inline-block;
            margin: 20px 0;
            text-decoration: none;
            color: #007bff;
        }
        a:hover {
            text-decoration: none;
        }
        .delete-btn {
            color: red;
            text-decoration: none;
            font-weight: bold;
            cursor: pointer;
        }
        .delete-btn:hover {
            color: darkred;
        }
    </style>
</head>
<body>
    <h2>Purchase History</h2>
    <table>
        <tr>
            <th>Item Name</th>
            <th>Item Price</th>
            <th>Purchase Date</th>
            <th>Action</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['itemNAME']); ?></td>
                <td>$<?php echo htmlspecialchars($row['itemprice']); ?></td>
                <td><?php echo htmlspecialchars($row['purchaseDate']); ?></td>
                <td>
                    <a class="delete-btn" href="bougthistory.php?delete_id=<?php echo $row['id']; ?>">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
    <a href="profile.php">Back to Profile</a>
</body>
</html>

