<?php
session_start();
require_once __DIR__ . '/config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$daftarBarang = [
    'Minyak' => 16000,
    'LPG 3 Kg' => 18000,
    'Gula' => 17000,
    'Beras' => 14500,
    'Tepung Terigu' => 13000,
];

$nama = trim($_POST['nama'] ?? '');
$tanggalPesan = $_POST['tanggal_pesan'] ?? '';
$jenisBarangList = $_POST['jenis_barang'] ?? [];
$jumlahList = $_POST['jumlah'] ?? [];

$tanggalValid = DateTime::createFromFormat('Y-m-d', $tanggalPesan);
$tanggalValid = $tanggalValid && $tanggalValid->format('Y-m-d') === $tanggalPesan;

if ($nama === '' || strlen($nama) > 100) {
    $_SESSION['pesan_error'] = 'Nama pemesan wajib diisi dan maksimal 100 karakter.';
    header('Location: index.php');
    exit;
}

if (!$tanggalValid) {
    $_SESSION['pesan_error'] = 'Tanggal pesanan tidak valid.';
    header('Location: index.php');
    exit;
}

if (!is_array($jenisBarangList) || !is_array($jumlahList) || count($jenisBarangList) !== count($jumlahList)) {
    $_SESSION['pesan_error'] = 'Data barang tidak lengkap.';
    header('Location: index.php');
    exit;
}

$itemPesanan = [];
$totalTagihan = 0;

foreach ($jenisBarangList as $index => $jenisBarang) {
    $jenisBarang = trim((string) $jenisBarang);
    $jumlah = filter_var($jumlahList[$index] ?? null, FILTER_VALIDATE_INT);

    if (!array_key_exists($jenisBarang, $daftarBarang)) {
        $_SESSION['pesan_error'] = 'Terdapat jenis barang yang tidak tersedia.';
        header('Location: index.php');
        exit;
    }

    if ($jumlah === false || $jumlah < 1) {
        $_SESSION['pesan_error'] = 'Jumlah setiap barang minimal 1.';
        header('Location: index.php');
        exit;
    }

    if (!isset($itemPesanan[$jenisBarang])) {
        $itemPesanan[$jenisBarang] = [
            'harga_satuan' => $daftarBarang[$jenisBarang],
            'jumlah' => 0,
        ];
    }

    $itemPesanan[$jenisBarang]['jumlah'] += $jumlah;
}

if (count($itemPesanan) < 1) {
    $_SESSION['pesan_error'] = 'Minimal satu barang harus dipilih.';
    header('Location: index.php');
    exit;
}

foreach ($itemPesanan as $jenisBarang => &$item) {
    $item['subtotal'] = $item['harga_satuan'] * $item['jumlah'];
    $totalTagihan += $item['subtotal'];
}
unset($item);

$conn->begin_transaction();

try {
    $stmtPesanan = $conn->prepare(
        'INSERT INTO pesanan (nama, tanggal_pesan, total_tagihan) VALUES (?, ?, ?)'
    );

    if (!$stmtPesanan) {
        throw new RuntimeException('Gagal menyiapkan data pesanan.');
    }

    $stmtPesanan->bind_param('ssi', $nama, $tanggalPesan, $totalTagihan);

    if (!$stmtPesanan->execute()) {
        throw new RuntimeException('Gagal menyimpan pesanan.');
    }

    $pesananId = $conn->insert_id;
    $stmtPesanan->close();

    $stmtDetail = $conn->prepare(
        'INSERT INTO detail_pesanan (pesanan_id, jenis_barang, harga_satuan, jumlah, subtotal)
         VALUES (?, ?, ?, ?, ?)'
    );

    if (!$stmtDetail) {
        throw new RuntimeException('Gagal menyiapkan detail pesanan.');
    }

    foreach ($itemPesanan as $jenisBarang => $item) {
        $hargaSatuan = $item['harga_satuan'];
        $jumlah = $item['jumlah'];
        $subtotal = $item['subtotal'];

        $stmtDetail->bind_param('isiii', $pesananId, $jenisBarang, $hargaSatuan, $jumlah, $subtotal);

        if (!$stmtDetail->execute()) {
            throw new RuntimeException('Gagal menyimpan salah satu barang.');
        }
    }

    $stmtDetail->close();
    $conn->commit();
    $_SESSION['pesan_sukses'] = 'Pesanan dengan beberapa barang berhasil disimpan.';
} catch (Throwable $error) {
    $conn->rollback();
    $_SESSION['pesan_error'] = 'Pesanan gagal disimpan. Pastikan struktur database sudah diperbarui.';
}

$conn->close();
header('Location: index.php');
exit;
