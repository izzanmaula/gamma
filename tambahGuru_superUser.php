<?php
include"koneksi.php";
session_start();

// debug
// error_reporting(E_ALL);
// ini_set('display_errors', 1);

// var_dump($_SESSION);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <title>Pendaftaran Karyawan</title>
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
                <h4 style="font-weight: bold; margin: 0; padding: 0;">Pendaftaran Karyawan</h4>
            </div>
        </div>
    </div>

    <!-- form registrasi -->
     <div class="mt-3 me-4 ms-4 mb-3">
        <h1 class="display-2">Pendaftaran</h1>
        <p>Daftar Guru dan Karyawan Anda melalui pendaftaran di bawah ini  </p>
     </div>
     <div class="ms-4 me-4 mt-">
        <div class="alert alert-warning" role="alert">
            Silahkan untuk screenshot formulir Anda terlebih dahulu sebelum mengirimkannya ke database.
        </div>
        <form action="tambahGuru_back_superUser.php" method="post">
        <div class="mb-3">
            <label for="namalengkap" class="form-label">Nama Lengkap Karyawan Baru</label>
            <input type="text" class="form-control" id="namalengkap" name="namalengkap" aria-describedby="emailHelp">
            <!-- <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div> -->
        </div>
        <div class="mb-3">
            <label for="namalengkap" class="form-label">Username</label>
            <input type="text" class="form-control" id="username" name="username" aria-describedby="emailHelp">
            <div id="emailHelp" class="form-text">Anda bebas memilihkan nama username karyawan Anda</div>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name="password">
        </div>
        <div class="btn-group justify-content-between" role="group">
            <button type="submit" class="btn btn-success">Daftarkan</button>
        </div>
        </form>
     </div>


    <!-- Modal untuk berhasil -->
    <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered text-start">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="successModalLabel">Sukses Menambahkan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Data guru berhasil ditambahkan!
                </div>
                <div class="modal-footer btn-group justify-content-between" role="group">
                    <button type="button" class="btn btn-success" data-bs-dismiss="modal">OK</button>
                </div>
            </div>
        </div>
    </div>


<script>
document.addEventListener('DOMContentLoaded', function() {
    <?php if(isset($_SESSION['success_message'])): ?>
        var myModal = new bootstrap.Modal(document.getElementById('successModal'));
        myModal.show();
        <?php unset($_SESSION['success_message']); ?>
    <?php endif; ?>
});
</script>
</body>
</html>