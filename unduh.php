<?php
include 'koneksi.php';

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=database_export.csv');

// Buat file output
// simpen di server
$file_path= '/laporan';
$output = fopen('php://output', 'w');


if ($output == false) {
    die('gagal membuat file di server');
}

// Tulis header kolom ke CSV
fputcsv($output, array('Kolom1', 'Kolom2', 'Kolom3')); // Sesuaikan nama kolom

// Query untuk mengambil data dari database
$query = "SELECT kolom1, kolom2, kolom3 FROM tabel"; // Sesuaikan query
$result = $conn->query($query);

// Tulis data ke CSV
while ($row = $result->fetch_assoc()) {
    fputcsv($output, $row);
}

// Tutup koneksi database
fclose($output);
$conn->close();

// Memberikan file untuk diunduh setelah menyimpannya di server 
header('Content-Type: application/octet-stream'); 
header('Content-Disposition: attachment; filename=' . basename($file_path)); 
header('Content-Length: ' . filesize($file_path)); 
readfile($file_path);
exit();
?>
