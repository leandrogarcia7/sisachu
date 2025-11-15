<?php
session_start();

// Limpia todas las variables de la sesión.
$_SESSION = [];

// Finaliza la sesión actual y elimina la cookie asociada.
if (ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
}

session_destroy();

header('Location: ../login.php');
exit;
