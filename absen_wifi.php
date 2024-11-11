<?php
session_start();
require 'koneksi.php'; // Pastikan koneksi database Anda

// Tampilkan semua error untuk debugging
error_reporting(E_ALL);
header('Content-Type: application/json');

// Cek apakah user sudah login
$user_id = $_SESSION['user_id']; // Ambil ID user dari sesi
if (!$user_id) {
    echo json_encode(['status' => 'error', 'message' => 'User ID tidak ditemukan']);
    exit;
}

// Alamat IP yang diizinkan (contoh: IP tunggal dari jaringan sekolah)
$allowed_ips = ['202.152.154.3']; // Sesuaikan dengan IP WiFi sekolah Anda

// Dapatkan alamat IP pengguna
$user_ip = $_SERVER['REMOTE_ADDR'];

// Cek apakah IP user ada di daftar IP yang diizinkan
if (!in_array($user_ip, $allowed_ips)) {
    // Jika tidak terhubung ke WiFi sekolah, tampilkan pesan error
    echo json_encode(['status' => 'error', 'message' => 'Anda tidak terkoneksi dengan WiFi sekolah']);
    exit;
}

// Cek status tepat waktu atau terlambat
date_default_timezone_set('Asia/Jakarta');
$waktu_absen = date('H:i:s');

// Aturan jam batas
$awal_absen = '06:00:00';  // Jam awal absen
$akhir_absen = '07:15:00';  // Jam akhir absen
$akhir_kerja = '16:00:00';  // Jam akhir kerja
$status = ''; // Variabel untuk menyimpan status absen

// Mendapatkan waktu absen dari database (asumsi waktu_absen dalam format timestamp UNIX)
$waktu_absen = date('H:i:s'); // Misalnya $waktu_absen berasal dari database

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

// Set metode absen
$metode_absen = 'wifi';

// Simpan ke database
$query = "INSERT INTO datang (user_id, waktu_absen, status, ip_address, metode_absen) VALUES (?, ?, ?, ?, ?)";
$stmt = $conn->prepare($query);

if ($stmt === false) {
    echo json_encode(['status' => 'error', 'message' => 'Gagal menyiapkan statement: ' . $conn->error]);
    exit;
}

$stmt->bind_param('issss', $user_id, $waktu_absen, $status, $user_ip, $metode_absen);

if ($stmt->execute()) {
    // Absen berhasil
    echo json_encode(['status' => 'success', 'message' => 'Anda telah absen']);
} else {
    // Gagal menyimpan data absen
    echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan data absen: ' . $stmt->error]);
}

// Tutup statement dan koneksi
$stmt->close();
$conn->close();
?>
