<?php
include "koneksi.php";
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <title>Kebijakan - AbsenSMAGA</title>
</head>
<body>
    <!-- Header Halaman -->
    <div class="d-flex mt-3 me-4 ms-3">
        <div class="row w-100">
            <a href="beranda.php" class="col-1">
                <div>
                    <img src="assets/back.png" alt="Kembali" width="30px">
                </div>
            </a>
            <div class="col">
                <h4 style="font-weight: bold; margin: 0; padding: 0;">Kebijakan Anda</h4>
            </div>
        </div>
    </div>

    <!-- ini kontennya -->
     <div class="ms-3 me-3 mt-4">
     <h1>Kebijakan Privasi Aplikasi Absensi Sekolah</h1>

        <p>Aplikasi Absensi Sekolah ini dikembangkan untuk mendukung kegiatan absensi dengan berbagai metode yang efektif dan efisien. Kami berkomitmen untuk melindungi privasi pengguna aplikasi ini dan menjaga keamanan data yang Anda berikan. Kebijakan ini menjelaskan bagaimana data yang dikumpulkan oleh aplikasi akan digunakan dan dilindungi.</p>

        <h4>1. Data yang Dikumpulkan</h4>
        <p>Aplikasi ini akan mengumpulkan beberapa data dari perangkat Anda sebagai bagian dari proses absensi, yaitu:</p>
        <ul>
        <li><strong>Gambar Selfie</strong>: Digunakan untuk keperluan absensi berbasis selfie. Aplikasi akan mengakses kamera perangkat Anda untuk mengambil foto sebagai bukti kehadiran.</li>
        <li><strong>Alamat IP</strong>: Alamat IP perangkat akan direkam saat Anda melakukan absensi melalui WiFi sekolah. Hal ini untuk memastikan bahwa absensi dilakukan di lokasi yang ditentukan.</li>
        <li><strong>Akses Data Perangkat</strong>: Aplikasi ini dapat meminta izin akses data perangkat, seperti lokasi atau identifikasi perangkat, untuk validasi lokasi sesuai kebutuhan.</li>
        </ul>

        <h4>2. Tujuan Penggunaan Data</h4>
        <p>Data yang dikumpulkan oleh aplikasi ini hanya akan digunakan untuk tujuan berikut:</p>
        <ul>
        <li><strong>Keperluan Absensi</strong>: Data gambar, alamat IP, dan informasi perangkat digunakan sebagai bukti dan validasi kehadiran pengguna.</li>
        <li><strong>Validasi Lokasi</strong>: Alamat IP digunakan untuk memastikan bahwa absensi dilakukan di dalam area sekolah yang ditentukan.</li>
        </ul>
        <p>Data Anda tidak akan digunakan untuk tujuan lain tanpa persetujuan Anda.</p>

        <h4>3. Penyimpanan dan Keamanan Data</h4>
        <p>Kami berkomitmen untuk menjaga keamanan data yang Anda berikan. Data gambar, IP, dan informasi perangkat akan disimpan dalam database internal yang aman dan hanya dapat diakses oleh pihak berwenang di lingkungan sekolah.</p>

        <h4>4. Penggunaan Pihak Ketiga</h4>
        <p>Kami tidak membagikan data yang dikumpulkan kepada pihak ketiga di luar lingkungan sekolah, kecuali jika diwajibkan oleh hukum atau peraturan yang berlaku.</p>

        <h4>5. Hak Pengguna</h4>
        <p>Pengguna memiliki hak untuk meminta akses, koreksi, atau penghapusan data pribadi mereka sesuai dengan peraturan yang berlaku. Silakan hubungi kami jika Anda memiliki permintaan terkait data pribadi Anda.</p>

        <p>Dengan menggunakan aplikasi ini, Anda menyetujui pengumpulan dan penggunaan data Anda sesuai dengan kebijakan privasi ini.</p>

     </div>
</body>
</html>