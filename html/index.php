<?php
require __DIR__ . '/session_bootstrap.php';


if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header('Location: /login.html');
    exit();
}

$user = $_SESSION['username'];

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

$result = $conn->execute_query('SELECT * FROM users WHERE username = ?', [$user]);
$row    = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html>
  <head>
    <title>My page</title>
    <meta charset="utf-8">

    <!-- CSP (siehe Punkt 3, gleiches Muster wie auf der Login-Seite) -->
    <meta http-equiv="Content-Security-Policy"
          content="default-src 'self';
                   script-src 'self' https://cdnjs.cloudflare.com https://cdn.jsdelivr.net;
                   style-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net;
                   img-src 'self' data:;
                   object-src 'none';
                   base-uri 'self';
                   frame-ancestors 'self';">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/dark.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r134/three.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vanta@latest/dist/vanta.net.min.js"></script>
    <script src="bg.js"></script>
  </head>
  <body>
    <div id="bg" style="position:absolute;width:100%;height:100%;z-index:-1000;top:0;left:0"></div>

    <h1>My First Website</h1>
    <h2>My Page</h2>

    Your favorite color is: <?php echo $row['color']; ?>
    <!-- In echt würde man hier escapen, aber laut Aufgabe nicht direkt fixen -->

    <br><a href="logout.php"><button>Log out</button></a>
  </body>
</html>
