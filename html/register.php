<?php
if (empty($_POST['user']) || empty($_POST['passw']) || empty($_POST['farbe'])) {
    header("Location: login.html");
    echo "Error invalid credentials.";
    exit();
}

$name  = $_POST['user'];
$passw = $_POST['passw'];
$farbe = $_POST['farbe'];

// Passwort hashen
$passwHash = password_hash($passw, PASSWORD_DEFAULT);

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

// Spalten explizit angeben
$result = $conn->execute_query(
    'INSERT INTO users (username, password, color) VALUES (?, ?, ?)',
    [$name, $passwHash, $farbe]
);

if (!$result) {
    echo "User could not be created.";
    exit();
}

echo '<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/dark.css">';
echo "<h1>Successfully registered.</h1>";
echo '<a href="index.php"><button>Back</button></a>';
exit();
