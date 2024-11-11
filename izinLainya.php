<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <title>Halosmaga - Perizinan</title>
</head>
<body style="background-color: rgb(238, 238, 238);">
    <!-- Header Halaman -->
    <div class="d-flex mt-3 me-4 ms-3">
        <div class="row">
            <a href="beranda.php" class="col-1">
                <div>
                    <img src="assets/back.png" alt="Kembali" width="30px">
                </div>
            </a>
            <div class="col">
                <h4 style="font-weight: bold; margin: 0; padding: 0;">Perizinan Lainnya</h4>
            </div>
        </div>
    </div>

    <!-- Formulir Perizinan Lainnya -->
    <form action="izinLainya_back.php" method="post" id="izinLainyaForm">
        <!-- Alasan Izin -->
        <div class="mt-3 me-3 ms-3 p-2 rounded-4" style="background-color: white;">
            <div class="pt-3 ps-3 pe-3 pb-1">
                <p style="font-weight: bold; margin: 0; padding: 0;">Kalau boleh tau, Anda mau izin apa ya?</p>
                <p style="font-size: 15px;">Pilih satu dari pilihan di bawah</p>
            </div>
            <div class="me-3 ms-3 mb-3">
                <!-- Radio button untuk pilihan izin -->
                <div class="form-check mb-1">
                    <input type="radio" class="form-check-input" name="izin_type" id="urusanKeluarga" value="Urusan Keluarga" required>
                    <label for="urusanKeluarga" class="form-check-label">Urusan Keluarga</label>
                </div>
                <div class="form-check mb-1">
                    <input type="radio" class="form-check-input" name="izin_type" id="acaraPribadi" value="Acara Pribadi" required>
                    <label for="acaraPribadi" class="form-check-label">Acara Pribadi</label>
                </div>
                <div class="form-check mb-1">
                    <input type="radio" class="form-check-input" name="izin_type" id="kepentinganMendadak" value="Kepentingan Mendadak" required>
                    <label for="kepentinganMendadak" class="form-check-label">Kepentingan Mendadak</label>
                </div>
                <div class="form-check mb-1">
                    <input type="radio" class="form-check-input" name="izin_type" id="izinCuti" value="Izin Cuti" required>
                    <label for="izinCuti" class="form-check-label">Izin Cuti</label>
                </div>
                <div class="form-check mb-1">
                    <input type="radio" class="form-check-input" name="izin_type" id="lainya" value="Lainnya" required>
                    <label for="lainya" class="form-check-label">Lainnya</label>
                </div>
            </div>
        </div>

        <!-- Waktu Izin -->
        <div class="mt-3 me-3 ms-3 p-2 rounded-4" style="background-color: white;">
            <div class="pt-3 ps-3 pe-3 pb-1">
                <p style="font-weight: bold; margin: 0; padding: 0;">Berapa lama Anda izin?</p>
                <p style="font-size: 15px;">Input waktu Anda izin di bawah</p>
            </div>
            <div class="me-3 ms-3 mb-3">
                <p class="mb-2">Saya izin mulai dari ..</p>
                <div class="input-group mb-3">
                    <span class="input-group-text"><i class="bi bi-calendar-date"></i></span>
                    <input type="date" class="form-control" name="tanggal_mulai" required>
                </div>
            </div>
            <div class="me-3 ms-3 mb-3">
                <p class="mb-2">Sampai tanggal ..</p>
                <div class="input-group mb-3">
                    <span class="input-group-text"><i class="bi bi-calendar-date"></i></span>
                    <input type="date" class="form-control" name="tanggal_selesai" required>
                </div>
            </div>
        </div>

        <!-- Deskripsi Izin -->
        <div class="mt-3 me-3 ms-3 p-2 rounded-4" style="background-color: white;">
            <div class="pt-3 ps-3 pe-3 pb-1">
                <p style="font-weight: bold; margin: 0; padding: 0;">Apa alasan Perizinan Anda?</p>
                <p style="font-size: 15px;">Tuliskan alasan perizinan Anda di bawah ini</p>
            </div>
            <div class="me-3 ms-3">
                <div class="input-group mb-3">
                    <textarea class="form-control" name="alasan_perizinan" rows="3" placeholder="Tuliskan alasan" required></textarea>
                </div>
            </div>
        </div>

        <!-- Check Kebijakan -->
        <div class="mt-3 me-3 ms-3 p-2 rounded-4" style="background-color: white;">
            <div class="pt-3 ps-3 pe-3 pb-0 container">
                <div class="row">
                    <div class="col-1">
                        <input type="checkbox" class="form-check-input" id="kebijakan" name="kebijakan" required>
                    </div>
                    <div class="col">
                        <label for="kebijakan" class="form-check-label">Saya menyatakan bahwa saya benar-benar tidak dapat hadir dikarenakan izin dan informasi yang saya berikan adalah benar adanya.</label>
                    </div>
                </div>
            </div>
            <div class="pe-3 ps-3 pt-4 pb-3 d-grid">
                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#confirmModal">Izin Sekarang</button>
            </div>
        </div>
    </form>

    <!-- Modal Konfirmasi -->
    <div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmModalLabel">Konfirmasi Pengiriman</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin ingin mengajukan izin ini?
                </div>
                <div class="modal-footer btn-group justify-content-between" role="group">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-success" id="confirmSubmit">Kirim</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Status Pengiriman -->
    <div class="modal fade" id="statusModal" tabindex="-1" aria-labelledby="statusModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered text-start">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="statusModalLabel">Status Pengiriman</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body btn-group justify-content-between" id="modalBody">
                    <!-- Pesan akan diisi dengan JavaScript -->
                </div>
                <div class="modal-footer btn-group justify-content-between" id="modalFooter">
                    <!-- Tombol akan diisi dengan JavaScript -->
                </div>
            </div>
        </div>
    </div>


    <!-- Footer -->
    <div class="text-center mt-5">
        <p style="font-size: 12px;">Tata Usaha SMAGA Gatak</p>
    </div>

<script>
    // Event listener untuk tombol konfirmasi
    document.getElementById('confirmSubmit').addEventListener('click', function() {
        const formData = new FormData(document.getElementById('izinLainyaForm'));

        // Kirim permintaan AJAX
        fetch('izinLainya_back.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            const modalBody = document.getElementById('modalBody');
            const modalFooter = document.getElementById('modalFooter');

            // Tentukan isi modal berdasarkan status
            if (data.status === 'success') {
                modalBody.textContent = data.message;
                modalFooter.innerHTML = `<button type="button" class="btn btn-success" data-bs-dismiss="modal">Tutup</button>`;
            } else {
                modalBody.textContent = data.message;
                modalFooter.innerHTML = `<button type="button" class="btn btn-danger" data-bs-dismiss="modal">Tutup</button>`;
            }

            // Tampilkan modal status
            const statusModal = new bootstrap.Modal(document.getElementById('statusModal'));
            statusModal.show();
        })
        .catch(error => {
            console.error('Error:', error);
        });
    });
</script>
</body>
</html>