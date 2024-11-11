<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized user']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    $user_id = $data['user_id'];
    $waktu_absen = $data['waktu_absen']; // Waktu untuk absensi yang ingin dihapus

    // Buat query untuk menghapus dari tabel datang
    $query = "DELETE FROM datang WHERE user_id = ? AND waktu_kedatangan = ?";
    
    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("is", $user_id, $waktu_absen);
        
        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Absensi berhasil dihapus!']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error: ' . $stmt->error]);
        }

        $stmt->close();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error preparing statement: ' . $conn->error]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}

$conn->close();
?>