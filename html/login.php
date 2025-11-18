<?php
require __DIR__ . '/session_bootstrap.php';


if (empty($_POST['user']) || empty($_POST['passw'])) {
    header("Location: login.html");
    exit();
}

$user  = $_POST['user'];
$passw = $_POST['passw'];

try {
    $conn = new mysqli("db", "root", "supersecure", "customers");
} catch (mysqli_sql_exception $e) {
    echo "Database error.";
    exit();
}

if ($conn->connect_errno) {
    echo "Error connecting to database";
    exit();
}

// Nur das Nötigste holen
$result = $conn->execute_query(
    'SELECT username, password FROM users WHERE username = ? LIMIT 1',
    [$user]
);

// Dummy-Hash gegen Timing-Angriffe.
// In der Praxis: EINMALIG mit password_hash('dummy_password', ...) erzeugen
// und als KONSTANTE in den Code kopieren.
$dummyHash = password_hash('dummy_password', PASSWORD_DEFAULT);

$row        = null;
$hashToCheck = $dummyHash;

if ($result->num_rows === 1) {
    $row        = $result->fetch_assoc();
    $hashToCheck = $row['password'];
}

// Immer password_verify aufrufen – egal ob User existiert
$loginOk = password_verify($passw, $hashToCheck);

if (!$loginOk || $row === null) {
    // Generische Fehlermeldung – keine Info, ob User existiert
    echo "User/Password not found.<br>";
    echo '<a href="index.php"><button>Back</button></a>';
    exit();
}

// Ab hier: Login erfolgreich
session_regenerate_id(true); // Session-Fixation verhindern
$_SESSION['logged_in'] = true;
$_SESSION['username']  = $row['username'];

header('Location: /');
exit();
