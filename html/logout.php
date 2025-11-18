<?php
require __DIR__ . '/session_bootstrap.php';


// Session-Inhalt leeren
$_SESSION = [];
session_destroy();

header('Location: /login.html');
exit();
