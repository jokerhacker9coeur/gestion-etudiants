<?php
session_start();
$_SESSION = [];
session_destroy();

// Supprimer le cookie de session (optionnel)
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Redirection avec message
header("Location: index.php?logout=1");
exit();
