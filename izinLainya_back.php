<?php
session_start();
include 'koneksi.php'; // Pastikan ini mengarah ke koneksi database Anda

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized user']);
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari formulir
    $izin_type = $_POST['izin_type'];
    $tanggal_mulai = $_POST['tanggal_mulai'];
    $tanggal_selesai = $_POST['tanggal_selesai'];
    $alasan_perizinan = $_POST['alasan_perizinan'];
    $kebijakan = isset($_POST['kebijakan']) ? 1 : 0; // Checkbox kebijakan

    // Siapkan query untuk memasukkan ke dalam database
    $query = "INSERT INTO izin_lain (user_id, izin_type, tanggal_mulai, tanggal_selesai, alasan, kebijakan) VALUES (?, ?, ?, ?, ?, ?)";
    
    if ($stmt = $conn->prepare($query)) {
        // Bind parameter
        $stmt->bind_param("issssi", $user_id, $izin_type, $tanggal_mulai, $tanggal_selesai, $alasan_perizinan, $kebijakan);
        
        // Eksekusi statement
        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Data izin lainnya berhasil ditambahkan!']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error: ' . $stmt->error]);
        }

        // Tutup statement
        $stmt->close();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error preparing statement: ' . $conn->error]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}

$conn->close();
?>