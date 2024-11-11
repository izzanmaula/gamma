<?php
session_start();
require 'koneksi.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');

// Debug log
error_log('Request received');
error_log('Session: ' . print_r($_SESSION, true));
error_log('POST: ' . print_r($_POST, true));

if (!isset($_SESSION['user_id'])) {
    error_log('User not authenticated');
    echo json_encode(['success' => false, 'error' => 'User is not authenticated.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['foto']) && isset($_POST['id'])) {
        try {
            $imgData = base64_decode($_POST['foto']);
            if ($imgData === false) {
                throw new Exception('Failed to decode base64 image data');
            }

            $user_id = $_POST['id'];
            
            // Buat direktori uploads jika belum ada
            $uploadDir = 'uploads/';
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            
            $filePath = $uploadDir . $user_id . '_' . time() . '.png';

            // Simpan gambar
            if (file_put_contents($filePath, $imgData) === false) {
                throw new Exception('Failed to save image file');
            }

            // Set timezone
            date_default_timezone_set('Asia/Jakarta');
            
            // Penjadwalan waktu absen
            $jam_absen = date('H:i:s');
            $awal_absen = '06:00:00';
            $akhir_absen = '21:15:00';
            $akhir_kerja = '00:00:00';
            
            // Tentukan status
            if ($jam_absen >= $awal_absen && $jam_absen <= $akhir_absen) {
                $status = 'tepat waktu';
            } elseif ($jam_absen > $akhir_absen && $jam_absen <= $akhir_kerja) {
                $status = 'terlambat';
            } else {
                $status = 'absen di luar jam kerja';
            }
            
            date_default_timezone_set('Asia/Jakarta');
            $tanggal_hari_ini = date('D, d M y');
            $ip_address = $_SERVER['REMOTE_ADDR'];
            $metode_absen = 'Selfie';

            // Prepare statement
            $stmt = $conn->prepare("INSERT INTO datang (user_id, waktu_absen, tanggal, status, ip_address, metode_absen, foto) VALUES (?, ?, ?, ?, ?, ?, ?)");
            
            if (!$stmt) {
                throw new Exception('Failed to prepare statement: ' . $conn->error);
            }

            $stmt->bind_param("issssss", $user_id, $jam_absen, $tanggal_hari_ini, $status, $ip_address, $metode_absen, $filePath);

            if (!$stmt->execute()) {
                throw new Exception('Failed to execute statement: ' . $stmt->error);
            }

            echo json_encode(['success' => true]);
            
            $stmt->close();
            $conn->close();

        } catch (Exception $e) {
            error_log('Error in absen_selfie.php: ' . $e->getMessage());
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    } else {
        error_log('Missing required parameters');
        echo json_encode(['success' => false, 'error' => 'Missing required parameters']);
    }
} else {
    error_log('Invalid request method');
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
}