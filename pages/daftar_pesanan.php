<?php
$sql = 'SELECT
            p.id,
            p.nama,
            p.tanggal_pesan,
            p.total_tagihan,
            p.dibuat_pada,
            d.jenis_barang,
            d.harga_satuan,
            d.jumlah,
            d.subtotal
        FROM pesanan p
        LEFT JOIN detail_pesanan d ON d.pesanan_id = p.id
        ORDER BY p.id DESC, d.id ASC';

$result = $conn->query($sql);
$daftarPesanan = [];

if ($result) {
    while ($baris = $result->fetch_assoc()) {
        $pesananId = (int) $baris['id'];

        if (!isset($daftarPesanan[$pesananId])) {
            $daftarPesanan[$pesananId] = [
                'nama' => $baris['nama'],
                'tanggal_pesan' => $baris['tanggal_pesan'],
                'total_tagihan' => (int) $baris['total_tagihan'],
                'detail' => [],
            ];
        }

        if ($baris['jenis_barang'] !== null) {
            $daftarPesanan[$pesananId]['detail'][] = [
                'jenis_barang' => $baris['jenis_barang'],
                'harga_satuan' => (int) $baris['harga_satuan'],
                'jumlah' => (int) $baris['jumlah'],
                'subtotal' => (int) $baris['subtotal'],
            ];
        }
    }
}
?>
<section class="card app-card border-0 shadow-sm">
    <div class="card-body p-4">
        <div class="section-heading mb-4">
            <span class="section-number">2</span>
            <div>
                <h2 class="h5 mb-1">Riwayat Pesanan</h2>
                <p class="text-secondary mb-0">Daftar transaksi beserta seluruh barang yang dipesan.</p>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Nama</th>
                        <th>Tanggal</th>
                        <th>Rincian Barang</th>
                        <th class="text-end">Total Tagihan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($daftarPesanan) > 0): ?>
                        <?php $nomor = 1; ?>
                        <?php foreach ($daftarPesanan as $pesanan): ?>
                            <tr>
                                <td><?= $nomor++ ?></td>
                                <td><?= htmlspecialchars($pesanan['nama'], ENT_QUOTES, 'UTF-8') ?></td>
                                <td><?= date('d-m-Y', strtotime($pesanan['tanggal_pesan'])) ?></td>
                                <td>
                                    <div class="d-grid gap-1">
                                        <?php foreach ($pesanan['detail'] as $detail): ?>
                                            <div class="detail-barang">
                                                <strong><?= htmlspecialchars($detail['jenis_barang'], ENT_QUOTES, 'UTF-8') ?></strong>
                                                — <?= $detail['jumlah'] ?> × Rp<?= number_format($detail['harga_satuan'], 0, ',', '.') ?>
                                                = <span class="fw-semibold">Rp<?= number_format($detail['subtotal'], 0, ',', '.') ?></span>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </td>
                                <td class="text-end fw-bold text-danger">
                                    Rp<?= number_format($pesanan['total_tagihan'], 0, ',', '.') ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center text-secondary py-5">
                                Belum ada pesanan yang tersimpan.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>
