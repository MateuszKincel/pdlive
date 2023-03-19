<?php

// logout.php

session_start();

// Unset session variables
unset($_SESSION['user_session']);
unset($_SESSION['admin_session']);

// Destroy the session
session_destroy();

// Clear cookies
if (isset($_COOKIE['user_session'])) {
    setcookie('user_session', '', time() - 3600, '/');
}

if (isset($_COOKIE['admin_session'])) {
    setcookie('admin_session', '', time() - 3600, '/');
}

if (isset($_COOKIE['PHPSESSID'])) {
    setcookie('PHPSESSID', '', time() - 3600, '/');
}

header("location:/index.php");

?>
