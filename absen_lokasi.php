<?php
session_start();
require 'koneksi.php'; // Pastikan koneksi database Anda

// Tampilkan semua error untuk debugging
error_reporting(E_ALL);
header('Content-Type: application/json');

// Cek apakah user sudah login
// if (!isset($_SESSION['user_id'])) {
//     header("Location: index.php");
//     exit;
// }

// Ambil data lokasi dari request
$data = json_decode(file_get_contents("php://input"), true);
$userLatitude = $data['latitude'];
$userLongitude = $data['longitude'];

// Tentukan koordinat lokasi sekolah
$schoolLatitude = -7.447990; // Contoh latitude sekolah
$schoolLongitude = 110.768174; // Contoh longitude sekolah
$range = 0.001; // Batas toleransi jarak dalam derajat (kira-kira 100 meter)

// Fungsi untuk menghitung jarak
function isWithinRange($userLat, $userLong, $schoolLat, $schoolLong, $range) {
    return abs($userLat - $schoolLat) <= $range && abs($userLong - $schoolLong) <= $range;
}

// Jika user dalam jangkauan lokasi sekolah
if (isWithinRange($userLatitude, $userLongitude, $schoolLatitude, $schoolLongitude, $range)) {
    // Ambil waktu dan tanggal absen
    date_default_timezone_set('Asia/Jakarta');
    $waktu_absen = date('H:i:s');
    $tanggal = date('D, d M y');

    // Aturan jam batas absen
    $awal_absen = '06:00:00';
    $akhir_absen = '21:15:00';
    $akhir_kerja = '00:00:00';
    $status = '';

    // Tentukan status absen berdasarkan waktu
    if ($waktu_absen >= $awal_absen && $waktu_absen <= $akhir_absen) {
        $status = 'tepat waktu';
    } elseif ($waktu_absen > $akhir_absen && $waktu_absen <= $akhir_kerja) {
        $status = 'terlambat';
    } else {
        $status = 'absen di luar jam kerja';
    }

    // Ambil user_id dari sesi atau informasi login
    $user_id = $_SESSION['user_id'];

    // Simpan absensi ke tabel 'datang'
    $sql_absensi = "INSERT INTO datang (user_id, waktu_absen, tanggal, status, ip_address, metode_absen)
                    VALUES ('$user_id', '$waktu_absen', '$tanggal' ,'$status', '$_SERVER[REMOTE_ADDR]', 'lokasi')";

    if ($conn->query($sql_absensi) === TRUE) {
        echo json_encode(array("status" => "success"));
    } else {
        echo json_encode(array("status" => "error", "message" => $conn->error));
    }
} else {
    // Jika user tidak dalam jangkauan, tampilkan lokasi mereka dan beri pesan error
    echo json_encode(array(
        "status" => "error",
        "message" => "Lokasi tidak sesuai dengan lokasi sekolah.",
        "current_location" => array("latitude" => $userLatitude, "longitude" => $userLongitude)
    ));
}

$conn->close();
?>
