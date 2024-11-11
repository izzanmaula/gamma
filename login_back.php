<?php
session_start();
include 'koneksi.php';



// Aktifkan error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    // Debug login
    error_log("Attempting login for user: " . $username);
    
    $sql = "SELECT * FROM users WHERE username = ?"; // Query untuk mendapatkan pengguna berdasarkan username
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        
        // Cek password tanpa hash
        if ($password === $row['password']) {
            // Bersihkan session lama
            session_unset();
            session_destroy();
            
            // Mulai session baru
            session_start();
            
            // Set session baru dengan ID pengguna (integer)
            $_SESSION = array(
                'user_id' => (int)$row['id'], // Ambil ID pengguna dari hasil query
                'logged_in' => true,
                'login_time' => time()
            );
            
            // Paksa write session
            session_write_close();

            // set cookies untuk pengguna
            // token untuk autentikasi
            $token = md5(uniqid($username . time(), true));

            // set cookies
            setcookie('authToken', $token, [
                'expires' => time() + (86400 * 30),
                'path' => '/',
                'secure' => true,
                'httponly' => true,
                'samesite' => 'Lax'  // Tambahan keamanan
            ]);

            // debug cookies
            error_log("Cookies set = authToken: " . $token);
            
            // Debug session
            error_log("New session created - ID: " . session_id());
            error_log("Session contents: " . print_r($_SESSION, true));
            
            // Redirect berdasarkan username
            if ($username === 'fauzinugroho') {
                header("Location: beranda_superUser.php"); // Arahkan ke beranda superuser
            } else {
                header("Location: beranda.php"); // Arahkan ke beranda biasa
            }
            exit();
        } else {
            $_SESSION["alertLupaPassJudul"] = "Password Anda salah";
            $_SESSION["alertLupaPassDesc"] = "Username Anda sudah benar, namun password Anda salah. Cek Kembali.";
        }
    } else { 
        // jika user tidak di temukan tampilkan alert lupa
        $_SESSION['alertUsernameJudul'] = "Username Anda salah";
        $_SESSION['alertUsernameDesc'] = "Kami lihat tidak ada username Anda di dalam pusat data kami, silahkan cek kembali Username Anda.";
    }
    
    // Jika login gagal
    header("Location: login.php?error=1");
    exit();
}
?>