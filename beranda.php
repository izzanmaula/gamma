<?php
session_start();

// error_reporting(E_ALL);
// ini_set('display_errors', 1);

// // Debug session
// echo "<div style='background: #f0f0f0; padding: 10px; margin: 10px; font-family: monospace;'>";
// echo "Session ID: " . session_id();
// echo "Session Contents: <pre>";
// print_r($_SESSION);
// echo "</pre>";
// echo "</div>";

// // File konfigurasi PHP
// $phpinfo = array(
//     'session.save_handler' => ini_get('session.save_handler'),
//     'session.save_path' => ini_get('session.save_path'),
//     'session.use_cookies' => ini_get('session.use_cookies'),
//     'session.name' => ini_get('session.name')
// );

// // Tampilkan informasi PHP
// echo "<div style='background: #f0f0f0; padding: 10px; margin: 10px; font-family: monospace;'>";
// echo "PHP Session Configuration:<pre>";
// print_r($phpinfo);
// echo "</pre></div>";

// // Cek login
// if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
//     header("Location: login.php");
//     exit();
// }

// Include koneksi database
include 'koneksi.php';

// Ambil user_id dari session
$user_id = $_SESSION['user_id'];

// Cek login
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit();
}

// Ambil namaLengkap dari tabel users
$query = "SELECT namaLengkap FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($namaLengkap);
$stmt->fetch();
$stmt->close();

// Ambil hari ini
$hari = date('N'); // Dapatkan hari dalam seminggu (1 = Senin, ..., 7 = Ahad)
$hari_ini = date('l, d F Y'); // Contoh: Friday, 07 October 2024

// ringasakn kehadiran
// Mengambil jumlah izin terlambat
$query_tl = "SELECT COUNT(*) as count FROM terlambat WHERE user_id = ?";
$stmt_tl = $conn->prepare($query_tl);
$stmt_tl->bind_param("i", $user_id);
$stmt_tl->execute();
$result_tl = $stmt_tl->get_result();
$row_tl = $result_tl->fetch_assoc();
$jumlah_izin_telambat = $row_tl['count'];

// Mengambil jumlah izin sakit
$query_skt = "SELECT COUNT(*) as count FROM sakit WHERE user_id = ?";
$stmt_skt = $conn->prepare($query_skt);
$stmt_skt->bind_param("i", $user_id);
$stmt_skt->execute();
$result_skt = $stmt_skt->get_result();
$row_skt = $result_skt->fetch_assoc();
$jumlah_izin_sakit = $row_skt['count'];

// Mengambil jumlah izin lainnya
$query_il = "SELECT COUNT(*) as count FROM izin_lain WHERE user_id = ?";
$stmt_il = $conn->prepare($query_il);
$stmt_il->bind_param("i", $user_id);
$stmt_il->execute();
$result_il = $stmt_il->get_result();
$row_il = $result_il->fetch_assoc();
$jumlah_izin_lain = $row_il['count'];

// Misalkan user ID kepala sekolah adalah '12345' atau username 'kepala_sekolah'
$allowed_user_ids = ['2']; // Ganti dengan ID yang sesuai
$allowed_usernames = ['fauzinugroho']; // Atau username

// Cek apakah pengguna yang masuk memiliki user ID atau username yang diizinkan
if (isset($_SESSION['user_id']) && in_array($_SESSION['user_id'], $allowed_user_ids) ||
    isset($_SESSION['username']) && in_array($_SESSION['username'], $allowed_usernames)) {
    // Arahkan pengguna ke beranda_superUser.php
    header("Location: beranda_superUser.php");
    exit();
}

// Tutup statement
$stmt_tl->close();
$stmt_skt->close();
$stmt_il->close();
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/html5-qrcode/minified/html5-qrcode.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <title>Halosmaga - Absen</title>
    <style>
        .custom-button {
            border: none !important; /* Menghapus border default */
            padding: 10px 20px !important; /* Menambahkan padding */
            border-radius: 25px !important; /* Membuat sudut tombol melingkar */
            background-color: #007bff !important; /* Warna latar belakang tombol */
            color: white !important; /* Warna teks tombol */
            font-size: 16px !important; /* Ukuran font */
            cursor: pointer; /* Menampilkan tangan saat hover */
            transition: background-color 0.3s, transform 0.2s; /* Transisi untuk efek visual */
        }

        .custom-button:hover {
            background-color: #0056b3 !important; /* Ubah warna latar belakang saat hover */
        }

        .custom-button:active {
            transform: scale(0.95); /* Mengurangi ukuran tombol saat ditekan */
            background-color: #004494 !important; /* Ubah warna saat ditekan */
        }
        /* css rounder */
        .rounder {
            display: none; /* Sembunyikan elemen secara default */
            width: 100px;
            height: 100px;
            background-color: #28a745; /* Warna hijau */
            border-radius: 50%; /* Membuat elemen bulat */
            position: absolute; /* Agar posisinya bisa dinamis */
            top: 50%; /* Tengah vertikal */
            left: 50%; /* Tengah horizontal */
            transform: translate(-50%, -50%); /* Pusatkan elemen */
            z-index: 10; /* Agar elemen bulat di atas semua tombol */
        }
    </style>
</head>
<body style="background-color: rgb(238, 238, 238);">
    <!-- informasi profil -->
    <div class="d-flex mt-3 me-4 ms-3">
        <div class="flex-grow-1">
            <p style="font-size: 16px; margin: 0; padding: 0;">Selamat Datang,</p>
            <p style="font-weight: bold; margin: 0; padding: 0;"><?php echo htmlspecialchars($namaLengkap); ?></p>
        </div>
        <div class="p-2">
            <img src="assets/profil.png" alt="" srcset="" style="width: 40px;">
        </div>
    </div>
    
    <!-- absen -->
    <div class="mt-3 me-3 ms-3 p-2 rounded-4 text-white" style="background-color:rgb(15, 64, 0) ;">
        <div style="" class=" pt-3 ps-3 pe-3 pb-1 text-center">
            <p style="font-weight: bold; margin: 0; padding: 0;"><?php echo($hari_ini); ?></p>
            <p style="font-size: 15px; margin: 0; padding:0;">
            <?php if ($hari >= 1 && $hari <= 4): ?>
                <p style="font-size: 15px;">Jam kerja Anda hari ini dimulai pukul dari 07.00 hingga 16.00 WIB</p>
            <?php elseif ($hari == 5): ?>
                <p style="font-size: 15px;">Jam kerja Anda hari ini dimulai pukul dari 07.00 hingga 14.00 WIB</p>
            <?php else: ?>
                <p style="font-size: 15px;">Hari libur, tidak ada jam kerja hari ini.</p>
            <?php endif; ?>
            </p>
            <div class="mb-4 text-white text-center border rounded">
                <h2 id="jam" style="font-size:70px;" class="display-1 fw-medium">

                </h2>
            </div>


    <!-- container awal absensi -->
    <div class=" justify-content-center mt-3 mb-0 rounded-4 p-0 text-white" style="background-color:rgb(15, 64, 0) ;">
        <!-- Kontainr background overlay -->

         
        <!-- container button -->
            <div class="text-center m-0 p-0 container">
                <div class="row d-flex align-items-center gap-0">
                    <!-- wifi -->
                    <div class="col text-center m-0 p-0">
                            <div class="btn pt-3 pb-1 btn rounded-4" style="" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                <img src="assets/wifi.png" alt="" srcset="" width="50px" style="background-color: rgb(241, 255, 241); padding: 10px;" class="rounded-4">
                                <p style="font-size: 14px; padding-top: 7px; color:white;">Wifi</p>
                            </div>    
                    </div>
                     <!-- lokasi -->
                    <div class="col text-center m-0 p-0">
                            <div class="pt-3 pb-1 btn rounded-4 btn" style="" data-bs-toggle="modal" data-bs-target="#lokasiModal" id="openModal">
                                <img src="assets/lokasi.png" alt="" srcset="" width="50px" style="background-color: rgb(241, 255, 241); padding: 10px;" class="rounded-4">
                                <p style="font-size: 14px; padding-top: 7px; color:white;">Lokasi</p>
                            </div>    
                    </div>
                    <!-- barcode -->
                    <div class="col text-center m-0 p-0">
                            <div class="pt-3 pb-1 btn rounded-4" style="" data-bs-toggle="modal" data-bs-target="#barcodeModal" id="openModal">
                                <img src="assets/barcode.png" alt="" srcset="" width="50px" style="background-color: rgb(241, 255, 241); padding: 10px;" class="rounded-4">
                                <p style="font-size: 14px; padding-top: 7px; color:white;">Barcode</p>
                            </div>    
                    </div>

                    <!-- selfie -->
                    <div class="col text-center m-0 p-0">
                            <div class="pt-3 pb-1 btn rounded-4" style="" data-bs-toggle="modal" data-bs-target="#selfieModal" id="openModalSelfie">
                                <img src="assets/selfie.png" alt="" srcset="" width="50px" style="background-color: rgb(241, 255, 241); padding: 10px;" class="rounded-4">
                                <p style="font-size: 14px; padding-top: 7px; color:white;">Kamera</p>
                            </div>    
                    </div>
            </div>
            </div>
        </div>


        <!-- kumpulan modal respons absen, di bawah ini ya -->


            <div class="d-grid gap-2 mb-3">
                <!-- Modal konfirmasi absen wifi -->
                <div class="modal fade text-black" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered text-start">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="exampleModalLabel">Konfirmasi Absen Wifi</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body pb-5">
                                <p>Sebelum melanjutkan, pastikan Anda telah :</p>
                                <div d-grid>
                                    <div class="container">
                                        <div class="row align-items-center">
                                            <div class="col justify-content-center text-center">
                                                <img src="assets/wifi.png" alt="" width="40px">
                                            </div>
                                            <div class="col-9">
                                                <p class="p-0 m-0"><strong>Terkoneksi Wifi LAB SMA</strong></p>
                                                <p style="font-size:14px;">Pastikan Anda telah terhubung dengan wifi LAB Kantor SMA dan memastikan terhubung dengan Internet.</p>
                                            </div>
                                        </div>
                                        <div class="row align-items-center">
                                            <div class="col justify-content-center text-center">
                                                <img src="assets/data_desc.png" alt="" width="40px">
                                            </div>
                                            <div class="col-9">
                                            <p class="p-0 m-0"><strong>Mematikan Data Seluler</strong></p>
                                                <p style="font-size:14px;">Memudahkan kami dalam mengecek IP Andress Anda.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer btn-group justify-content-between" role="group">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <button id="absen-button" type="button" class="btn btn-success" onclick="absen()">Absen</button>                        
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal untuk berhasil absen -->
                <div class="modal fade text-black" id="modal-sukses" tabindex="-1" aria-labelledby="modalSuksesLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered text-start">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="modalSuksesLabel">Absen Berhasil</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p>Terima kasih telah absen, semoga hari Anda menyenangkan.</p>
                            </div>
                            <div class="modal-footer btn-group justify-content-between" role="group">
                                <button type="button" class="btn btn-success" data-bs-dismiss="modal">Ok</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal untuk gagal absen (tidak terkoneksi ke WiFi sekolah) -->
                <div class="modal fade text-black" id="modal-gagal" tabindex="-1" aria-labelledby="modalGagalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered text-start">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="modalGagalLabel">Absen Gagal</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p>Anda tidak terkoneksi dengan WiFi Sekolah, pastikan Anda telah terkoneksi dengan Wifi LAB (SMA) untuk dapat absen</p>
                            </div>
                            <div class="modal-footer btn-group justify-content-between" role="group">
                                <button type="button" class="btn btn-success" data-bs-dismiss="modal">Ok</button>
                            </div>
                        </div>
                    </div>
                </div>

            <!-- modal konfirmasi absen lokasi -->
            <div class="modal fade text-black" id="lokasiModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered text-start">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Absensi Lokasi</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                        <p>Sebelum melanjutkan, pastikan Anda telah :</p>
                                <div d-grid>
                                    <div class="container">
                                        <div class="row align-items-center">
                                            <div class="col justify-content-center text-center">
                                                <img src="assets/lokasi_desc.png" alt="" width="40px">
                                            </div>
                                            <div class="col-9">
                                            <p class="p-0 m-0"><strong>Lokasi Telah Aktif</strong></p>
                                                <p style="font-size:14px;">Pastikan Anda telah mengaktifkan lokasi Anda dan mengizinkan kami untuk melacak lokasi Anda.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        </div>
                        <div class="modal-footer btn-group justify-content-between" role="group">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button  type="button" class="btn btn-success" id="absenLokasiButton">Lacak Saya</button>                        
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal absen lokasi Sukses -->
            <div class="modal fade text-black" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered text-start">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="successModalLabel">Absen Diterima</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            Kehadiran Anda berhasil dicatat.
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal absen lokasi Gagal -->
            <div class="modal fade text-black" id="errorModal" tabindex="-1" aria-labelledby="errorModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered text-start">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="errorModalLabel">Absen Ditolak</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            Lokasi Anda saat ini tidak sesuai dengan lokasi sekolah, atau gunakan metode absen lainya<br>
                            <div id="map" style="width: 100%; height: 300px;" class="rounded mt-2"></div> <!-- Tempat untuk peta -->
                            <p id="currentLocation"></p> <!-- Menampilkan lokasi user -->
                        </div>
                    </div>
                </div>
            </div>



                <!-- Modal untuk absen barcode -->
                <div class="modal fade text-black" id="barcodeModal" tabindex="-1" aria-labelledby="barcodeModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="barcodeModalLabel">Scan Barcode Absensi</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="alert alert-success p-1">
                                <div d-grid>
                                    <div class="container">
                                        <div class="row align-items-center">
                                            <div class="col justify-content-center text-center">
                                                <img src="assets/scan_desc.png" alt="" width="40px">
                                            </div>
                                            <div class="col-9 text-start">
                                            <p class="p-0 m-0" style="font-size: 13px;"><strong>Arahkan barcode menghadap kamera</strong></p>
                                            <p style="font-size:12px;" class="mb-1">Pastikan lingkungan Anda mempunyai pencahayaan yang cukup, kami akan otomatis mendeteksi jika barcode di dalam kamera sesuai</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                </div>
                                <video id="preview" width="100%" class="rounded-4"></video>
                            </div>
                            <div class="modal-footer btn-group justify-content-between" role="group">
                                <button id="switchCamera" type="button" class="btn btn-success" >Ganti Kamera</button>                        
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            </div>
                        </div>
                    </div>
                </div>            
            </div>
        </div>
    </div>

    <!-- Modal untuk Absensi Berhasil -->
<div class="modal fade text-black" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="successModalLabel">Absensi Berhasil</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Anda telah berhasil melakukan absensi, semoga hari Anda menyenangkan
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal untuk Barcode Tidak Dikenali -->
<div class="modal fade text-black" id="errorModal" tabindex="-1" aria-labelledby="errorModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="errorModalLabel">Barcode Tidak Dikenali</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Barcode yang Anda gunakan tidak sesuai, gunakan barcode sesuai dengan arahan tim Administrator atau gunakan metode absen lainya
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>

        <!-- Modal selfie -->
        <div class="modal fade text-black" id="selfieModal" tabindex="-1" aria-labelledby="selfieModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered text-start">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="selfieModalLabel">Ambil Foto Selfie</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-success p-1">
                                <div d-grid>
                                    <div class="container">
                                        <div class="row align-items-center">
                                            <div class="col justify-content-center text-center">
                                                <img src="assets/lingkungan_desc.png" alt="" width="40px">
                                            </div>
                                            <div class="col-9 text-start">
                                            <p class="p-0 m-0" style="font-size: 14px;"><strong>Pastikan Anda Berada di Sekolah</strong></p>
                                            <p style="font-size:12px;" class="mb-1">Potret diri Anda berada di Lokasi Sekolah, Staff TU dan Kepala Sekolah akan memeriksa hasil foto Anda.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        </div>
                        <!-- Video untuk menampilkan tampilan kamera -->
                        <video id="video" width="100%" class="rounded-4" style="transform: scaleX();" autoplay></video>
                        <!-- Canvas untuk menangkap gambar -->
                        <canvas id="canvas" style="display:none;"></canvas>
                    </div>
                    <div class="modal-footer btn-group justify-content-between" role="group">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button id="takeSelfieBtn" type="button" class="btn btn-success">Ambil Selfie</button>                        
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal preview untuk menampilkan hasil selfie -->
        <div class="modal fade text-black" id="previewModal" tabindex="-1" aria-labelledby="previewModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered text-start">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="previewModalLabel">Preview Foto Selfie</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <img id="previewImage" src="" width="100%" class="rounded-4" alt="Preview Foto Selfie">
                    </div>
                    <div class="modal-footer btn-group justify-content-between" role="group">
                        <button type="button" class="btn btn-secondary" id="retakePhotoBtn">Ambil Ulang?</button>
                        <button type="button" class="btn btn-success" id="confirmPhotoBtn">Absen dengan Foto Ini</button>
                    </div>
                </div>
            </div>
        </div>

        <script>
            // event listener jika button ambil selvie diklik
            document.getElementById('confirmPhotoBtn').addEventListener('click', function(){
                // tampilkan modal
                var modal = new bootstrap.Modal(document.getElementById('prosesSelfieModal'));
                modal.show();
            });
        </script>


        <!-- Modal untuk proses absensi selfie -->
        <div class="modal fade text-black" id="prosesSelfieModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="successModalLabel">Mohon Tunggu</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            <div d-grid>
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col justify-content-center text-center">
                            <img src="assets/dont_touch.png" alt="" width="40px">
                        </div>
                        <div class="col-9">
                            <p class="p-0 m-0"><strong>Jangan Tutup Jendela Proses</strong></p>
                            <p style="font-size:14px;">Kami sedang mengirimkan foto Anda menuju pusat data kami, jangan sentuh apapun kecuali telah muncul jendela berhasil atau gagal.</p>
                        </div>
                    </div>
                </div>
            </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
            </div>
        </div>
        </div>


        <!-- Modal untuk Absensi selfie Berhasil -->
        <div class="modal fade text-black" id="successModalSelfie" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="successModalLabel">Absensi Berhasil</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Anda telah berhasil melakukan absensi, foto Anda akan ditinjau segera oleh tim TU dan Kepala Sekolah. Semoga hari Anda menyenangkan
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
            </div>
        </div>
        </div>

        <!-- Modal untuk Absensi selfie gagal -->
        <div class="modal fade text-black" id="errorModalSelfie" tabindex="-1" aria-labelledby="errorModalSelfieLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="errorModalSelfieLabel">Absensi Selfie Gagal, silahkan gunakan metode lainya</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Absensi gagal, silahkan menggunakan metode absen lainya
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
            </div>
        </div>

    </div>
</div>

<!-- rounder -->
 <div class="rounder" id="rounder"></div>
    


<!-- segarkan -->
<div class="mt-3 me-3 ms-3 mb-0 rounded-4 p-2" style="background-color: white;">
    <div class="pt-3 ps-3 pe-3 pb-1">
        <p style="font-size: 16px; padding: 0; margin: 0; font-weight: bold;">Segarkan Absensi</p>
        <p style="font-size: 15px;">Merasa tidak beres? segarkan absensi untuk menghilangkan cache data menumpuk.</p>
        <div class="d-grid gap-2">
            <button id="refreshButton" class="btn btn-outline-success btn-block">Segarkan Sekarang</button>
        </div>
    </div>
    <script>
        document.getElementById('refreshButton').addEventListener('click', function() {
            location.reload(); // Refresh halaman
        });
    </script>
</div>


<!-- ijin ga masuk -->
    <div class="mt-3 me-3 ms-3 mb-0 rounded-4 p-2" style="background-color: white;">
        <div class="pt-3 ps-3 pe-3 pb-1">
            <p style="font-size: 16px; padding: 0; margin: 0; font-weight: bold;">Tidak dapat hadir?</p>
            <p style="font-size: 15px;">Pilih opsi di bawah sebagai penanda bahwa Anda tidak dapat hadir hari ini.</p>
        </div>
        <!-- button ijin -->
        <div class="text-center pb-0 pe-3 ps-3">
            <div class="row d-flex justify-content-center align-items-center">
                <!-- telat -->
                <div class="col-4 text-center">
                    <a href="terlambat.php">
                        <div class="pt-3 pb-1 btn rounded-4" style="">
                            <img src="assets/terlambat.png" alt="" srcset="" width="50px" style="background-color: rgb(241, 255, 241); padding: 10px;" class="rounded-4">
                            <p style="font-size: 14px; padding-top: 2px;">Terlambat</p>
                        </div>    
                    </a>
                </div>
                <!-- sakit -->
                <div class="col-4 text-center">
                    <a href="sakit.php">
                        <div class="pt-3 pb-1 btn rounded-4" style="">
                            <img src="assets/sakit.png" alt="" srcset="" width="50px" style="background-color: rgb(241, 255, 241); padding: 10px;" class="rounded-4">
                            <p style="font-size: 14px; padding-top: 2px;">Sakit</p>
                        </div>    
                    </a>
                </div>
                <!-- lainya -->
                <div class="col-4 text-center">
                    <a href="izinLainya.php">
                        <div class="pt-3 pb-1 btn rounded-4" style="">
                            <img src="assets/lainya.png" alt="" srcset="" width="50px" style="background-color: rgb(241, 255, 241); padding: 10px;" class="rounded-4">
                            <p style="font-size: 14px; padding-top: 2px;">Lainnya</p>
                        </div>    
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- container aplikasi lainya -->
    <div class="mt-3 me-3 ms-3 mb-0 rounded-4 p-2" style="background-color: white;">
        <!-- button fitur -->
            <div class="text-center pb-0 pe-3 ps-3">
                <div class="row d-flex align-items-center">
                    <!-- riwayat -->
                    <div class="col text-center">
                        <a href="riwayat.php">
                            <div class="pt-3 pb-1 btn rounded-4" style="">
                                <img src="assets/riwayat.png" alt="" srcset="" width="50px" style="background-color: rgb(241, 255, 241); padding: 10px;" class="rounded-4">
                                <p style="font-size: 14px; padding-top: 2px;">Riwayat</p>
                            </div>    
                        </a>
                    </div>
                     <!-- kebijakan -->
                    <div class="col text-center">
                        <a href="kebijakan.php">
                            <div class="pt-3 pb-1 btn rounded-4" style="">
                                <img src="assets/privasi.png" alt="" srcset="" width="50px" style="background-color: rgb(241, 255, 241); padding: 10px;" class="rounded-4">
                                <p style="font-size: 14px; padding-top: 2px;">Kebijakan</p>
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


        <!-- ringkasan  -->
    <div class="mt-3 me-3 ms-3 mb- rounded-4 p-2" style="background-color: white;">
        <div class=" pt-3 ps-3 pe-3 pb-1">
            <p style="font-size: 16px; padding: 0; margin: 0; font-weight: bold;">Sekilas Kehadiran</p>
            <p style="font-size: 15px;">Berikut adalah ringkasan perizinan Anda.</p>
        </div>
        <div class="container pe-3 ps-3 pb-3">
            <div class="row mb-1">
                <div class="col">
                    Izin Terlambat
                </div>
                <div class="col text-end">
                    <?= $jumlah_izin_telambat ?>
                </div>
            </div>
            <div class="row mb-1">
                <div class="col">
                    Izin Sakit
                </div>
                <div class="col text-end">
                    <?= $jumlah_izin_sakit ?>
                </div>
            </div>
            <div class="row mb-1">
                <div class="col">
                    Izin Lainnya
                </div>
                <div class="col text-end">
                    <?= $jumlah_izin_lain ?>
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
<script>
// jam beranda
function updateClock() {
            const now = new Date();
            const hours = String(now.getHours()).padStart(2, '0'); // Menambahkan 0 di depan jika dibutuhkan
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');
            
            const currentTime = `${hours}:${minutes}:${seconds}`;
            document.getElementById('jam').innerText = currentTime;
        }

        // Memperbarui jam setiap detik
        setInterval(updateClock, 1000);
        
        // Memanggil fungsi sekali untuk menampilkan jam saat halaman dimuat
        updateClock();


// Fungsi untuk absen dengan WiFi
function absen() {
    $.ajax({
        url: 'absen_wifi.php',
        method: 'POST',
        success: function(response) {
            console.log(response);  // Cek respon dari server

            // Tidak perlu lagi memparsing JSON karena respons sudah objek
            if (response.status === 'success') {
                $('#exampleModal').modal('hide');
                $('#modal-sukses').modal('show'); // Tampilkan modal sukses absen
            } else {
                $('#exampleModal').modal('hide');
                $('#modal-gagal').modal('show'); // Tampilkan modal gagal absen
            }
        },
        error: function() {
            alert("Terjadi kesalahan, coba lagi.");
        }
    });
}

// Inisialisasi scanner barcode
let scanner = new Instascan.Scanner({ video: document.getElementById('preview') });
let selectedCamera = null;
let camerasList = []; // Simpan daftar kamera

// Menampilkan stream video dan memilih kamera belakang
function initializeScanner() {
    Instascan.Camera.getCameras().then(function (cameras) {
        camerasList = cameras; // Simpan daftar kamera
        if (camerasList.length > 0) {
            // Pilih kamera belakang jika tersedia
            selectedCamera = camerasList.find(camera => camera.name.toLowerCase().includes('back')) || camerasList[0];

            console.log('Memulai kamera:', selectedCamera.name); // Debugging kamera yang dipilih

            // Mulai scanner dengan kamera yang dipilih
            scanner.start(selectedCamera).catch(function (e) {
                console.error('Error saat memulai kamera:', e);
                alert('Tidak bisa mengakses kamera. Pastikan Anda mengizinkan akses kamera.');
            });
        } else {
            console.error('Tidak ada kamera ditemukan.');
            alert('Tidak ada kamera ditemukan.');
        }
    }).catch(function (e) {
        console.error('Error mengakses kamera:', e);
        alert('Error mengakses kamera.');
    });
}

// Fungsi untuk mengganti kamera
function switchCamera() {
    if (camerasList.length > 1) { // Pastikan ada lebih dari satu kamera
        scanner.stop().then(function() {
            // Toggle antara kamera depan dan belakang
            let currentCameraIndex = camerasList.indexOf(selectedCamera);
            let nextCameraIndex = (currentCameraIndex + 1) % camerasList.length; // Toggle kamera
            selectedCamera = camerasList[nextCameraIndex]; // Pilih kamera berikutnya

            console.log('Beralih ke kamera:', selectedCamera.name); // Debugging kamera yang dipilih

            scanner.start(selectedCamera).catch(function (e) {
                console.error('Error saat memulai kamera:', e);
                alert('Error mengakses kamera.');
            });
        }).catch(function(e) {
            console.error('Error saat menghentikan kamera:', e);
        });
    } else {
        alert('Tidak ada kamera lain untuk diganti.');
        console.log('Jumlah kamera yang tersedia:', camerasList.length);
    }
}

// Event listener untuk modal barcode
document.getElementById('barcodeModal').addEventListener('shown.bs.modal', initializeScanner);

// Event listener untuk tombol ganti kamera
document.getElementById('switchCamera').addEventListener('click', switchCamera);



// Event listener untuk menutup modal barcode
document.getElementById('barcodeModal').addEventListener('hidden.bs.modal', function () {
    scanner.stop().catch(function (e) {
        console.error('Error saat menghentikan kamera:', e);
    });
});


scanner.addListener('scan', function (content) {
    console.log('Barcode hasil scan:', content);
    fetch('absen_barcode.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ 
            barcode: content.trim(),  // pastikan barcode yang dikirim tidak memiliki spasi
        }),
    })
    .then(response => response.json())
    .then(data => {
        var modal;
        if (data.status === "success") {
            modal = new bootstrap.Modal(document.getElementById('successModal'));
        } else {
            modal = new bootstrap.Modal(document.getElementById('errorModal'));
        }
        modal.show();
        console.log(data.message); // Log message untuk membantu debugging jika ada error
    })
    .catch((error) => {
        console.error('Error:', error);
        var errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
        errorModal.show();
    });



    // Tutup modal scanner
    var modalElement = document.querySelector('#barcodeModal');
    var modal = bootstrap.Modal.getInstance(modalElement);
    modal.hide();
});

// Mulai waktu saat halaman dimuat
window.onload = function() {
    waktu();
};

// absen dengan selfie
// Akses elemen video, canvas, dan modal
let video = document.getElementById('video');
let canvas = document.getElementById('canvas');
let context = canvas.getContext('2d');
let stream;
let selfieModal = new bootstrap.Modal(document.getElementById('selfieModal'));
let previewModal = new bootstrap.Modal(document.getElementById('previewModal'));
let imageData; // Menyimpan gambar selfie

// Mengaktifkan kamera saat modal dibuka
document.getElementById('openModalSelfie').addEventListener('click', function() {
    navigator.mediaDevices.getUserMedia({ video: true })
        .then(function(mediaStream) {
            stream = mediaStream;
            video.srcObject = mediaStream;
        })
        .catch(function(err) {
            console.log("Error: " + err);
        });
});

// Mematikan kamera saat modal ditutup
document.getElementById('selfieModal').addEventListener('hidden.bs.modal', function () {
    if (stream) {
        stream.getTracks().forEach(track => track.stop());
    }
});

// function retake kamera
function startCamera() {
    navigator.mediaDevices.getUserMedia({ video: true })
        .then(function(mediaStream) {
            stream = mediaStream;
            video.srcObject = mediaStream;
        })
        .catch(function(err) {
            console.log("Error: " + err);
        });
}

// Ambil gambar saat tombol "Ambil Selfie" diklik
document.getElementById('takeSelfieBtn').addEventListener('click', function() {
    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;
    context.drawImage(video, 0, 0, canvas.width, canvas.height);

    // Simpan gambar dalam format base64
    imageData = canvas.toDataURL('image/png');

    // Tampilkan gambar di modal preview
    document.getElementById('previewImage').src = imageData;

    // Tutup modal selfie dan buka modal preview
    selfieModal.hide();
    previewModal.show();

    // mulai ulang kamera
    startCamera();
});

// Ambil ulang foto
document.getElementById('retakePhotoBtn').addEventListener('click', function() {
    previewModal.hide();
    selfieModal.show();
    startCamera(); // Pastikan untuk memulai kamera kembali
});


document.getElementById('confirmPhotoBtn').addEventListener('click', function() {
    if (!imageData) {
        console.error('Image data is empty!');
        alert('Gambar tidak tersedia! Silakan ambil selfie lagi.');
        return;
    }

    let formData = new FormData();
    // Kirim foto sebagai base64 string
    formData.append('foto', imageData.split(',')[1]); // Hapus prefix data:image/png;base64,

    // Ambil user ID dari sesi PHP
    const userId = <?php echo json_encode($_SESSION['user_id']); ?>; 
    formData.append('id', userId);

    // Debug log
    console.log('User ID:', userId);
    console.log('Image Data length:', imageData.length);

    fetch('absen_selfie.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text()) // Ubah ke text() dulu untuk debug
    .then(text => {
        console.log('Raw response:', text); // Debug response
        return JSON.parse(text);
    })
    .then(data => {
        if (data.success) {
            var successModal = new bootstrap.Modal(document.getElementById('successModalSelfie'));
            successModal.show();
        } else {
            console.error('Server error:', data.error);
            alert('Terjadi kesalahan: ' + data.error);
            var errorModal = new bootstrap.Modal(document.getElementById('errorModalSelfie'));
            errorModal.show();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan, silakan coba lagi nanti.');
    });
});

// Fungsi untuk mendapatkan lokasi pengguna
function absenDenganLokasi() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            function(position) {
                // Dapatkan koordinat pengguna
                const userLatitude = position.coords.latitude;
                const userLongitude = position.coords.longitude;

                // Kirim data ke PHP backend
                fetch('absen_lokasi.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        latitude: userLatitude,
                        longitude: userLongitude,
                    }),
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        var modal = new bootstrap.Modal(document.getElementById('successModal'));
                        modal.show();
                    } else {
                // Menampilkan lokasi pengguna
                document.getElementById('currentLocation').innerText = 
                        `Lokasi saat ini: Latitude ${userLatitude}, Longitude ${userLongitude}`;
                    
                    // Inisialisasi peta
                    const map = L.map('map').setView([userLatitude, userLongitude], 17);
                    
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        maxZoom: 19,
                        attribution: '© OpenStreetMap'
                    }).addTo(map);

                    // Menambahkan marker
                    const marker = L.marker([userLatitude, userLongitude]).addTo(map)
                        .bindPopup('Lokasi Anda')
                        .openPopup();
                    
                    // Setelah peta dimuat, sesuaikan pusat ke marker
                    map.on('load', function() {
                        map.setView(marker.getLatLng(), 17); // Atur tampilan ke marker
                    });

                    const errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
                    errorModal.show();                        
                }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan, silakan coba lagi.');
                });
            },
            function(error) {
                alert('Tidak bisa mendapatkan lokasi. Pastikan izin lokasi diaktifkan.');
            }
        );
    } else {
        alert("Geolocation tidak didukung oleh browser ini.");
    }
}

// Tambahkan event listener pada tombol absen dengan lokasi
document.getElementById('absenLokasiButton').addEventListener('click', absenDenganLokasi);
</script>
</body>
</html>