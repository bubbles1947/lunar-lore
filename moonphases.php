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
$sql = "SELECT dob FROM users WHERE userid = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($dob);
$stmt->fetch();
$stmt->close();

$birthMonth = date("m", strtotime($dob));


$moonPhases = [
    "New Moon" => [
        "description" => "A time for new beginnings, setting intentions, and reflecting on the future.",
        "effects" => "This phase brings a sense of hope, renewal, and clarity. Ideal for people born at the start of a zodiac sign.",
    ],
    "Waxing Crescent" => [
        "description" => "A phase of growth and momentum, perfect for taking first steps toward goals.",
        "effects" => "This phase fuels motivation and creativity, affecting those born in the middle of a zodiac sign.",
    ],
    "First Quarter" => [
        "description" => "A phase of challenges and decision-making, requiring strength and determination.",
        "effects" => "People born during this phase are natural problem-solvers and feel energized during challenging times.",
    ],
    "Waxing Gibbous" => [
        "description" => "A phase of refinement and preparation for success.",
        "effects" => "This phase enhances perfectionism and patience, particularly for Earth signs like Taurus, Virgo, and Capricorn.",
    ],
    "Full Moon" => [
        "description" => "A phase of culmination, energy, and heightened emotions.",
        "effects" => "People with birthdays during this phase often experience emotional intensity and a strong connection to their goals.",
    ],
    "Waning Gibbous" => [
        "description" => "A phase for reflection, gratitude, and letting go of negativity.",
        "effects" => "This phase supports introspection and spiritual growth, especially for Water signs like Cancer, Scorpio, and Pisces.",
    ],
    "Last Quarter" => [
        "description" => "A phase for release, transition, and shedding what no longer serves you.",
        "effects" => "Those born during this phase excel at adapting to change and letting go of the past.",
    ],
    "Waning Crescent" => [
        "description" => "A phase of rest, recovery, and preparation for the next cycle.",
        "effects" => "This phase promotes healing and peace, resonating deeply with Air signs like Gemini, Libra, and Aquarius.",
    ],
];

function getMoonPhaseByBirthday($birthMonth) {
    $birthdayPhases = [
        1 => "New Moon",
        2 => "Waxing Crescent",
        3 => "First Quarter",
        4 => "Waxing Gibbous",
        5 => "Full Moon",
        6 => "Waning Gibbous",
        7 => "Last Quarter",
        8 => "Waning Crescent",
        9 => "New Moon",
        10 => "Waxing Crescent",
        11 => "First Quarter",
        12 => "Waxing Gibbous",
    ];
    return $birthdayPhases[$birthMonth] ?? "Unknown Phase";
}

$moonPhase = getMoonPhaseByBirthday($birthMonth);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Moon Phases and Their Effects</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f6f9;
            color: #333;
            margin: 0;
            padding: 20px;
            line-height: 1.6;
        }

        h1 {
            text-align: center;
            color: #2c3e50;
            font-size: 36px;
            margin-bottom: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 30px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
        }

        .moon-phases {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
            margin-top: 30px;
        }

        .phase {
            background: linear-gradient(135deg, #f5f5f5, #e0e0e0);
            padding: 25px;
            margin: 15px;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
            width: 45%;
            transition: all 0.3s ease;
        }

        .phase:hover {
            transform: scale(1.05);
        }

        .phase h2 {
            color: #007bff;
            font-size: 24px;
            margin-bottom: 10px;
        }

        .phase p {
            font-size: 16px;
            line-height: 1.6;
        }

        .result {
            background: #e7f5ff;
            padding: 20px;
            margin-top: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .result h3 {
            color: #007bff;
            font-size: 24px;
        }

        select, button {
            padding: 10px 20px;
            margin: 10px;
            font-size: 16px;
            border-radius: 8px;
            border: 2px solid #007bff;
            background-color: #fff;
            color: #007bff;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        select:focus, button:hover {
            background-color: #007bff;
            color: #fff;
            border-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Moon Phases and Their Astrological Effects</h1>

        <section class="birth-phase">
            <h2>Your Moon Phase</h2>
            <div class="result">
                <h3>Your Birth Moon Phase: <?= $moonPhase ?></h3>
                <p><?= $moonPhases[$moonPhase]['description'] ?? "No description available." ?></p>
                <p><strong>Effects:</strong> <?= $moonPhases[$moonPhase]['effects'] ?? "No effects available." ?></p>
            </div>
        </section>

        <section class="moon-phases">
            <h2>All Moon Phases</h2>
            <?php foreach ($moonPhases as $phase => $details): ?>
                <div class="phase">
                    <h2><?= $phase ?></h2>
                    <p><strong>Description:</strong> <?= $details['description'] ?></p>
                    <p><strong>Effects:</strong> <?= $details['effects'] ?></p>
                </div>
            <?php endforeach; ?>
        </section>
    </div>
</body>
</html>
