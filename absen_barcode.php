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
 
// Ambil data dari request
$data = json_decode(file_get_contents("php://input"), true);
$barcode = trim($data['barcode']);

// Konversi $waktu_absen dari format JavaScript ke format Unix timestamp
date_default_timezone_set('Asia/Jakarta');
$waktu_absen = date('H:i:s');
$tanggal = date('D, d M y');

// Aturan jam batas
$awal_absen = '06:00:00';  // Jam awal absen
$akhir_absen = '21:15:00';  // Jam akhir absen
$akhir_kerja = '00:00:00';  // Jam akhir kerja
$status = ''; // Variabel untuk menyimpan status absen

// Cek apakah waktu absen berada dalam rentang waktu yang diizinkan
if ($waktu_absen >= $awal_absen && $waktu_absen <= $akhir_absen) {
    // Absen dalam batas waktu yang tepat
    $status = 'tepat waktu';
} elseif ($waktu_absen > $akhir_absen && $waktu_absen <= $akhir_kerja) {
    // Absen terlambat
    $status = 'terlambat';
} else {
    // Jika di luar rentang waktu, set status ke 'absen di luar jam kerja'
    $status = 'absen di luar jam kerja';
}

// Barcode yang valid
$validBarcode = "AAAAA";

// Cek barcode
if ($barcode === $validBarcode) {
    // Ambil user_id dari sesi atau informasi login
    $user_id = $_SESSION['user_id'];

    // Simpan absensi ke tabel datang
    $sql_absensi = "INSERT INTO datang (user_id, waktu_absen, tanggal, status, ip_address, metode_absen)
                    VALUES ('$user_id', '$waktu_absen', '$tanggal' ,'$status', '$_SERVER[REMOTE_ADDR]', 'barcode')";

    if ($conn->query($sql_absensi) === TRUE) {
        echo json_encode(array("status" => "success"));
    } else {
        echo json_encode(array("status" => "error", "message" => $conn->error));
    }
} else {
    echo json_encode(array("status" => "error", "message" => "Barcode tidak valid."));
}

$conn->close();
?>
