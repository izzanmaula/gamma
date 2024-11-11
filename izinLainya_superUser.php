<?php

session_start();
include "koneksi.php";

// jika belum login 
if (!isset($_SESSION['user_id']) || $_SESSION['logged_in'] !== true) {
    header("Location: index.php");
    exit();
}

// timezone
date_default_timezone_set('Asia/Jakarta');

$limit = 15; // Jumlah baris per halaman
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Halaman saat ini
$offset = ($page - 1) * $limit; // Offset untuk query

$total_query = "SELECT COUNT(*) as total FROM izin_lain";
$total_result = $conn->query($total_query);
if (!$total_result) {
    die("Query error: " . $conn->error);
}
$total_row = $total_result->fetch_assoc();
$total_rows = $total_row['total'];
$total_pages = ceil($total_rows / $limit); // Total halaman

$query_izinLain = "
    SELECT u.namaLengkap, d.izin_type, d.tanggal_mulai, d.tanggal_selesai, d.alasan, d.created_at
    FROM izin_lain d 
    JOIN users u ON d.user_id = u.id 
    ORDER BY d.tanggal_mulai ASC
    LIMIT ? OFFSET ?
";

$stmt_izinLain = $conn->prepare($query_izinLain);
if (!$stmt_izinLain) {
    die("Prepare statement failed: " . $conn->error);
}
$stmt_izinLain->bind_param("ii", $limit, $offset);
$stmt_izinLain->execute();
$result_terlambat = $stmt_izinLain->get_result();
if (!$result_terlambat) {
    die("Query execution failed: " . $stmt_izinLain->error);
}

// close statement
$stmt_izinLain->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Tabel Laporan Sakit</title>
</head>
<body style="background-color: rgb(238, 238, 238);">

    <!-- Header Halaman -->
    <div class="d-flex mt-3 me-4 ms-3">
        <div class="row w-100">
            <a href="beranda_superUser.php" class="col-1">
                <div>
                    <img src="assets/back.png" alt="Kembali" width="30px">
                </div>
            </a>
            <div class="col">
                <h4 style="font-weight: bold; margin: 0; padding: 0;">Data Laporan Sakit</h4>
            </div>
        </div>
    </div>

    <div class="container mt-5" style="font-size: 12px;">
        <table class="table table-bordered mt-4">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Waktu Ijin</th>
                    <th>Alasan</th>
                    <th>Tanggal Dibuat</th>
                    <th>Tanggal Perizinan</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result_terlambat->num_rows > 0): ?>
                    <?php while ($row = $result_terlambat->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['namaLengkap']) ?></td>
                            <td><?= htmlspecialchars($row['tanggal_mulai']) ?> sampai <?= htmlspecialchars($row['tanggal_selesai']) ?></td>
                            <td><?= htmlspecialchars($row['izin_type']) ?></td>
                            <td><?= htmlspecialchars($row['alasan']) ?></td>
                            <td><?= htmlspecialchars($row['created_at']) ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center">Tidak ada data laporan sakit.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- Tombol Lihat Lebih Lanjut -->
        <?php if ($total_rows > $limit): ?>
            <div class="text-center">
                <a href="?page=<?= $page + 1; ?>" class="btn btn-primary">
                    Lihat Lebih Lanjut
                </a>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
