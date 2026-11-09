<?php
session_start();
require_once __DIR__ . '/config/database.php';

$daftarBarang = [
    'Minyak' => 16000,
    'LPG 3 Kg' => 18000,
    'Gula' => 17000,
    'Beras' => 14500,
    'Tepung Terigu' => 13000,
];

$pesanSukses = $_SESSION['pesan_sukses'] ?? null;
$pesanError = $_SESSION['pesan_error'] ?? null;
unset($_SESSION['pesan_sukses'], $_SESSION['pesan_error']);
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Koperasi Merah Putih</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css?v=<?= filemtime(__DIR__ . '/assets/css/style.css') ?>">
</head>
<body>
    <header class="app-header">
        <div class="container">
            <div class="header-content">
                <img src="assets/img/logo.png" alt="Logo Koperasi Merah Putih" class="brand-logo">
                <div class="header-text">
                    <h1>Koperasi Merah Putih</h1>
                    <p>Form pemesanan kebutuhan pokok</p>
                </div>
            </div>
        </div>
    </header>

    <main class="container pb-5">
        <?php if ($pesanSukses): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= htmlspecialchars($pesanSukses, ENT_QUOTES, 'UTF-8') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
            </div>
        <?php endif; ?>

        <?php if ($pesanError): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= htmlspecialchars($pesanError, ENT_QUOTES, 'UTF-8') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
            </div>
        <?php endif; ?>

        <?php require __DIR__ . '/pages/form_pesanan.php'; ?>
        <?php require __DIR__ . '/pages/daftar_pesanan.php'; ?>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const daftarItem = document.getElementById('daftar-item');
        const templateItem = document.getElementById('template-item');
        const tombolTambah = document.getElementById('tambah-item');
        const totalKeseluruhan = document.getElementById('total_keseluruhan');

        const formatRupiah = (nilai) => new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0
        }).format(nilai);

        function hitungBaris(baris) {
            const select = baris.querySelector('.jenis-barang');
            const jumlah = baris.querySelector('.jumlah-barang');
            const hargaField = baris.querySelector('.harga-satuan');
            const subtotalField = baris.querySelector('.subtotal-barang');

            const harga = Number(select.options[select.selectedIndex]?.dataset.harga || 0);
            const qty = Math.max(Number(jumlah.value || 0), 0);
            const subtotal = harga * qty;

            hargaField.value = formatRupiah(harga);
            subtotalField.value = formatRupiah(subtotal);
            subtotalField.dataset.nilai = subtotal;
        }

        function hitungTotalKeseluruhan() {
            let total = 0;

            daftarItem.querySelectorAll('.item-row').forEach((baris) => {
                hitungBaris(baris);
                total += Number(baris.querySelector('.subtotal-barang').dataset.nilai || 0);
            });

            totalKeseluruhan.value = formatRupiah(total);
        }

        function perbaruiTombolHapus() {
            const baris = daftarItem.querySelectorAll('.item-row');
            baris.forEach((item) => {
                item.querySelector('.hapus-item').disabled = baris.length === 1;
            });
        }

        function pasangEvent(baris) {
            baris.querySelector('.jenis-barang').addEventListener('change', hitungTotalKeseluruhan);
            baris.querySelector('.jumlah-barang').addEventListener('input', hitungTotalKeseluruhan);
            baris.querySelector('.hapus-item').addEventListener('click', () => {
                baris.remove();
                perbaruiTombolHapus();
                hitungTotalKeseluruhan();
            });
        }

        tombolTambah.addEventListener('click', () => {
            const itemBaru = templateItem.content.cloneNode(true);
            const barisBaru = itemBaru.querySelector('.item-row');
            daftarItem.appendChild(itemBaru);
            pasangEvent(barisBaru);
            perbaruiTombolHapus();
            hitungTotalKeseluruhan();
        });

        daftarItem.querySelectorAll('.item-row').forEach(pasangEvent);
        perbaruiTombolHapus();
        hitungTotalKeseluruhan();
    </script>
</body>
</html>
