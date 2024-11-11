<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['user_id']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit();
}

// Set timezone
date_default_timezone_set('Asia/Jakarta');

// Ambil data perizinan terlambat dari database
$query_izin = "
    SELECT d.waktu_kedatangan, d.tanggal_izin, d.alasan_terlambat, u.namaLengkap
    FROM izin d 
    JOIN users u ON d.user_id = u.id 
    WHERE d.kebijakan = 1" // Misalkan kebijakan 1 berarti izin terlambat
;

// Persiapkan dan jalankan query
$stmt_izin = $conn->prepare($query_izin);
$stmt_izin->execute();
$result_izin = $stmt_izin->get_result();

// Tutup statement
$stmt_izin->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Tabel Izin Terlambat</title>
</head>
<body style="background-color: rgb(238, 238, 238);">
    <div class="container mt-5">
        <h2 class="text-center">Data Izin Terlambat</h2>

        <table class="table table-bordered mt-4">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Tanggal Izin</th>
                    <th>Waktu Kedatangan</th>
                    <th>Alasan Terlambat</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result_izin->num_rows > 0): ?>
                    <?php while ($row = $result_izin->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['namaLengkap']) ?></td>
                            <td><?= htmlspecialchars($row['tanggal_izin']) ?></td>
                            <td><?= date('H:i', strtotime($row['waktu_kedatangan'])) ?></td>
                            <td><?= htmlspecialchars($row['alasan_terlambat']) ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="text-center">Tidak ada data izin terlambat.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>