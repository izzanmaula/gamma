<?php
session_start();
include 'koneksi.php'; // Pastikan ini mengarah ke koneksi database Anda

if (!isset($_SESSION['user_id']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
date_default_timezone_set('Asia/Jakarta');

// Mendapatkan tanggal hari ini
$today = date('Y-m-d');


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/html5-qrcode/minified/html5-qrcode.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <title>Halosmaga - Absen</title>
</head>
<body style="background-color: rgb(238, 238, 238);">
    <!-- informasi profil -->
    <div class="d-flex mt-3 me-4 ms-3">
        <div class="flex-grow-1">
            <p style="font-size: 16px; margin: 0; padding: 0;">Selamat Datang,</p>
            <p style="font-weight: bold; margin: 0; padding: 0;">Fauzi Nugroho</p>
        </div>
        <div class="p-2">
            <img src="assets/profil.png" alt="" srcset="" style="width: 40px;">
        </div>
    </div>
    
    <!-- absen -->
    <div class="mt-3 me-3 ms-3 p-2 rounded-4" style="background-color: white;">
        <div style="" class=" pt-3 ps-3 pe-3 pb-1">
            <h5 class="display-6 mb-1 fw-semibold" style="font-size: 25px;">Selamat datang, <br>Kepala Sekolah!</h5>
            <p class="mb-4" style="font-size: 15px;">Berikut adalah laporan hari ini mengenai status karyawan Anda.</p>
                <div class="row d-flex justify-content-center align-items-center">
                </div>
        </div>

                <!-- button laporan -->
                <div class="text-center pb-0 pe-3 ps-3">
                <div class="row d-flex justify-content-center align-items-center">
                <!-- hadir -->
                <div class="col-4 text-center">
                    <a href="hadir_superUser.php">
                        <div class="pt-3 pb-1 btn rounded-4" style="">
                            <img src="assets/laporanHadir.png" alt="" srcset="" width="50px" style="background-color: rgb(241, 255, 241); padding: 10px;" class="rounded-4">
                            <p style="font-size: 14px; padding-top: 2px;">Laporan Hadir</p>
                        </div>    
                    </a>
                </div>
                <!-- terlambat -->
                <div class="col-4 text-center">
                    <a href="terlambat_superUser.php">
                        <div class="pt-3 pb-1 btn rounded-4" style="">
                            <img src="assets/laporanTerlambat.png" alt="" srcset="" width="50px" style="background-color: rgb(241, 255, 241); padding: 10px;" class="rounded-4">
                            <p style="font-size: 14px; padding-top: 2px;">Laporan Terlambat</p>
                        </div>    
                    </a>
                </div>
                <!-- lainya -->
                <div class="col-4 text-center">
                    <a href="izinLainya.php">
                        <div class="pt-3 pb-1 btn rounded-4" style="">
                            <img src="assets/belumAbsen.png" alt="" srcset="" width="50px" style="background-color: rgb(241, 255, 241); padding: 10px;" class="rounded-4">
                            <p style="font-size: 14px; padding-top: 2px;">Belum Absen</p>
                        </div>    
                    </a>
                </div>
            </div>


                            <!-- button laporan tidak masuk -->
                <div class="text-center pb-0 pe-3 ps-3">
                <div class="row d-flex justify-content-center align-items-center">
                <!-- laporan sakit -->
                <div class="col-4 text-center">
                    <a href="laporanSakit_superUser.php">
                        <div class="pt-3 pb-1 btn rounded-4" style="">
                            <img src="assets/laporanSakit.png" alt="" srcset="" width="50px" style="background-color: rgb(241, 255, 241); padding: 10px;" class="rounded-4">
                            <p style="font-size: 14px; padding-top: 2px;">Laporan Sakit</p>
                        </div>    
                    </a>
                </div>
                <!-- lainya -->
                <div class="col-4 text-center">
                    <a href="izinLainya_superUser.php">
                        <div class="pt-3 pb-1 btn rounded-4" style="">
                            <img src="assets/laporanLainya.png" alt="" srcset="" width="50px" style="background-color: rgb(241, 255, 241); padding: 10px;" class="rounded-4">
                            <p style="font-size: 14px; padding-top: 2px;">Laporan Lainya</p>
                        </div>    
                    </a>
                </div>
            </div>
                </div>
        </div>
    </div>







    <!-- container aplikasi lainya -->
    <div class="mt-3 me-3 ms-3 mb-0 rounded-4 p-2" style="background-color: white;">
        <!-- button fitur -->
            <div class="text-center pb-0 pe-3 ps-3">
                <div class="row d-flex align-items-center">
                    <!-- unduh -->
                    <div class="col text-center">
                        <a href="unduh.php">
                            <div class="pt-3 pb-1 btn rounded-4" style="">
                                <img src="assets/unduh.png" alt="" srcset="" width="50px" style="background-color: rgb(241, 255, 241); padding: 10px;" class="rounded-4">
                                <p style="font-size: 14px; padding-top: 2px;">Unduh</p>
                            </div>    
                        </a>
                    </div>
                    <!-- tambah karyawan -->
                    <div class="col text-center">
                        <a href="tambahGuru_superUser.php">
                            <div class="pt-3 pb-1 btn rounded-4" style="">
                                <img src="assets/tambah.png" alt="" srcset="" width="50px" style="background-color: rgb(241, 255, 241); padding: 10px;" class="rounded-4">
                                <p style="font-size: 14px; padding-top: 2px;">Tambah</p>
                            </div>    
                        </a>
                    </div>

                    <!-- keluar -->
                    <div class="col text-center">
                        <a href="logout_back.php">
                            <div class="pt-3 pb-1 btn rounded-4" style="">
                                <img src="assets/keluar.png" alt="" srcset="" width="50px" style="background-color: rgb(241, 255, 241); padding: 10px;" class="rounded-4">
                                <p style="font-size: 14px; padding-top: 2px;">Keluar</p>
                            </div>    
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>


    <!-- feedback  -->
    <div class="mt-3 me-3 ms-3 mb-5 rounded-4 p-2" style="background-color: white;">
        <div class=" pt-3 ps-3 pe-3 pb-1">
                <p style="font-size: 16px; padding: 0; margin: 0; font-weight: bold;">Terjadi kesalahan?</p>
                <p style="font-size: 12px;">Laporkan kesalahan Anda melalui tombol di bawah.</p>
        </div>
        <div class="d-grid pe-3 ps-3 pb-3">
            <div class="btn btn-success">
                    Laporkan
            </div>
        </div>
    </div>

    <div class="text-center mt-5">
        <p style="font-size: 12px;">Tata Usaha SMAGA Gatak - 2024</p>
    </div>
</body>
</html>