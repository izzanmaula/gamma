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
    $tanggal_mulai = $_POST['tanggal_sakit_mulai'];
    $tanggal_selesai = $_POST['tanggal_sakit_selesai'];
    $alasan = $_POST['alasan'];
    $kebijakan = isset($_POST['kebijakan']) ? 1 : 0; // Checkbox kebijakan

    // Variabel untuk menyimpan path surat keterangan dokter
    $surat_keterangan_dokter = null;

    // Periksa apakah file diunggah
    if (isset($_FILES['surat_keterangan']) && $_FILES['surat_keterangan']['error'] == UPLOAD_ERR_OK) {
        $target_dir = "suratIzinSakit/"; // Folder penyimpanan
        $target_file = $target_dir . basename($_FILES["surat_keterangan"]["name"]);

        // Memindahkan file yang diunggah
        if (move_uploaded_file($_FILES["surat_keterangan"]["tmp_name"], $target_file)) {
            // Jika upload sukses, simpan jalurnya
            $surat_keterangan_dokter = $target_file;
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error uploading file.']);
            exit();
        }
    }
    
    // Siapkan query untuk memasukkan ke dalam database
    $query = "INSERT INTO sakit (user_id, tanggal_mulai, tanggal_selesai, alasan, kebijakan, surat_keterangan_dokter) VALUES (?, ?, ?, ?, ?, ?)";
    
    if ($stmt = $conn->prepare($query)) {
        // Bind parameter, termasuk surat keterangan dokter
        $stmt->bind_param("isssis", $user_id, $tanggal_mulai, $tanggal_selesai, $alasan, $kebijakan, $surat_keterangan_dokter);
        
        // Eksekusi statement
        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Data izin sakit berhasil ditambahkan!']);
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