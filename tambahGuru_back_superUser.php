<?php
require "koneksi.php";
session_start();

// ambil data dari formulir
$namaLengkap = $_POST['namalengkap'];
$username = $_POST['username'];
$password = $_POST['password'];

// masuk ke query
$sql = "INSERT INTO users (namaLengkap, username, password)
VALUES (?, ?, ?)";

// Siapkan statement
$stmt = $conn->prepare($sql);

// Bind parameter
$stmt->bind_param("sss", $namaLengkap, $username, $password);

// Eksekusi query
if ($stmt->execute()) {
    $_SESSION['success_message'] = true;
    header("Location: tambahGuru_superUser.php");
    exit();
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>