<?php
session_start();
require ("koneksi.php");
// Jika sudah login, redirect ke beranda
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    header("Location: beranda.php");
    exit();
}

// deklarasi error username
$alertUsernameJudul = isset($_SESSION["alertUsernameJudul"]) ? $_SESSION["alertUsernameJudul"] :null;
// deklarasi username desc
$alertUsernameDesc = isset($_SESSION["alertUsernameDesc"]) ? $_SESSION["alertUsernameDesc"] : null;
// deklarasi error password judul 
$alertLupaPassJudul = isset($_SESSION["alertLupaPassJudul"]) ? $_SESSION["alertLupaPassJudul"] : null;
// deklrasai error password deskripsi
$alertLupaPassDesc = isset($_SESSION["alertLupaPassDesc"]) ? $_SESSION["alertLupaPassDesc"] : null;


// hapus session setelah di tampilkan
unset($_SESSION["alertUsernameJudul"]);
unset($_SESSION["alertUsernameDesc"]);
unset($_SESSION["alertLupaPassDesc"]);
unset($_SESSION["alertLupaPassJudul"]);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <title>Halosmaga - Login</title>
</head>
<body class="p-4">
    <!-- header -->
    <div id="lupaPasswordReminder">
        <h1 class="display-3" style="font-weight:400;">AbsenSmaga</h1>
        <h1 class="display-6">Sebelum absen, silahkan login terlebih dahulu.</h1>
    </div>

        <!-- sparator -->
    <div class="mt-5 mb-5">
        <p style="color: white;">ini pemisah, Anda seharusnya tidak melihat ini</p>
    </div>
    <?php
        if (isset($_SESSION['error_message'])) {
          echo '<div class="alert alert-danger" role="start">'. $_SESSION['error_message'].'</div>';
          unset($_SESSION['error_message']);
        };
        ?>

    <!-- peringatan lupa -->
    <div class="alert alert-success mt-1" role="alert">
        <p class="p-0 m-0"><strong>Lupa Password atau Nama Alias Anda?</strong></p>
        <p class="p-0 m-0">Silahkan untuk menghubungi Staf Tata Usaha SMAGA.</p>
    </div> 

        <!-- peringatan username ga ketemu -->
        <?php if ($alertUsernameJudul !== null): ?>
            <div class="alert alert-warning mt-1" role="alert">
                <p class="p-0 m-0"><strong><?php echo $alertUsernameJudul ?></strong></p>
                <p class="p-0 m-0"><?php echo $alertUsernameDesc ?></p>
            </div> 
        <?php endif; ?>

        <!-- peringatan lupa password -->
                 <!-- peringatan username -->
        <?php if ($alertLupaPassJudul !== null): ?>
            <div class="alert alert-warning mt-1" role="alert">
                <p class="p-0 m-0"><strong><?php echo $alertLupaPassJudul ?></strong></p>
                <p class="p-0 m-0"><?php echo $alertLupaPassDesc ?></p>
            </div> 
        <?php endif; ?>


    

    <!-- login box -->
    <div class="">
    <form method="post" action="login_back.php">
    <div class="mb-3">
        <p>Nama Alias Anda</p>
        <div class="input-group">
            <span class="input-group-text" id="username">@</span>
            <input type="text" class="form-control" id="username" name="username" placeholder="" required>
        </div>
    </div>
    <div class="mt-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" class="form-control" id="password" name="password" required>
    </div>  
    <div class="d-flex">
        <button type="submit" class="btn btn-success flex-fill mt-3">Masuk</button>
    </div> 
    </form>
        
    </div>
</body>
</html>