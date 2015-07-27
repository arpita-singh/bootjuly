<?php
    require_once __DIR__ .'/src/bootstrap.php';

    $sessionId = isset($_COOKIE['lrnuser'])? $_COOKIE['lrnuser'] : null;
    if ($sessionId) {
        session_id($sessionId);
    }
    else {
        session_id();
    }
    session_start();
    $_SESSION['playsLeft'] = isset($_SESSION['playsLeft'])? $_SESSION['playsLeft'] : 2;
?>

<html>
    <head>
    <script src="//items.learnosity.com"></script>
    </head>
    <body>
    Success - plays updated.
    </body>
</html>