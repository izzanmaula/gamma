<?php
// Mulai sesi
session_start();

// Hapus semua data sesi
$_SESSION = array();

// Jika menggunakan cookie sesi, hapus juga cookienya
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Akhiri sesi
session_destroy();

// Redirect ke halaman login atau halaman lain yang diinginkan
header("Location: index.php");
exit;
?>
