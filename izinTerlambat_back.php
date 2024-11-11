<?php
// Mengambil koneksi database
include 'koneksi.php'; // Pastikan ini mengarah ke file koneksi Anda
session_start(); // Memulai sesi jika belum ada

// Pastikan pengguna sudah login dan memiliki user_id
if (!isset($_SESSION['user_id'])) {
    die(json_encode(['status' => 'error', 'message' => 'Unauthorized user'])); // Atau lakukan redirect jika tidak terautentikasi
}

$user_id = $_SESSION['user_id']; // Mendapatkan user_id dari sesi

// Periksa apakah permintaan metode POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Mengambil data dari formulir
    $tanggal_izin = $_POST['tanggal_izin'];
    $waktu_kedatangan = $_POST['waktu_kedatangan'];
    $alasan_terlambat = $_POST['alasan_terlambat'];
    $kebijakan = isset($_POST['kebijakan']) ? 1 : 0; // Jika checkbox dipilih, set kebijakan ke 1

    // Menyiapkan query untuk memasukkan data ke tabel
    $query = "INSERT INTO terlambat (tanggal_izin, waktu_kedatangan, alasan_terlambat, kebijakan, user_id) VALUES (?, ?, ?, ?, ?)";
    
    if ($stmt = $conn->prepare($query)) {
        // Bind parameter
        $stmt->bind_param("ssssi", $tanggal_izin, $waktu_kedatangan, $alasan_terlambat, $kebijakan, $user_id);
        
        // Eksekusi statement
        if ($stmt->execute()) {
            // Kirimkan respon sukses dalam format JSON
            echo json_encode(['status' => 'success', 'message' => 'Data Izin berhasil ditambahkan!']);
        } else {
            // Kirimkan respon error dalam format JSON
            echo json_encode(['status' => 'error', 'message' => 'Error: ' . $stmt->error]);
        }

        // Tutup statement
        $stmt->close();
    } else {
        // Kirimkan respon error dalam format JSON
        echo json_encode(['status' => 'error', 'message' => 'Error preparing statement: ' . $conn->error]);
    }
} else {
    // Kirimkan respon error dalam format JSON
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}

// Tutup koneksi
$conn->close();
?>