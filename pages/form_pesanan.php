<section class="card app-card border-0 shadow-sm mb-4">
    <div class="card-body p-4">
        <div class="section-heading mb-4">
            <span class="section-number">1</span>
            <div>
                <h2 class="h5 mb-1">Form Pemesanan</h2>
                <p class="text-secondary mb-0">Satu pesanan dapat berisi beberapa jenis barang.</p>
            </div>
        </div>

        <form action="proses_pesanan.php" method="post" id="form-pesanan">
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label for="nama" class="form-label">Nama Pemesan</label>
                    <input type="text" class="form-control" id="nama" name="nama" maxlength="100" required>
                </div>

                <div class="col-md-6">
                    <label for="tanggal_pesan" class="form-label">Tanggal Pesan</label>
                    <input type="date" class="form-control" id="tanggal_pesan" name="tanggal_pesan" value="<?= date('Y-m-d') ?>" required>
                </div>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3 class="h6 mb-0">Daftar Barang</h3>
                <button type="button" class="btn btn-outline-danger btn-sm" id="tambah-item">
                    + Tambah Barang
                </button>
            </div>

            <div id="daftar-item" class="d-grid gap-3">
                <div class="item-row border rounded-3 p-3">
                    <div class="row g-2 align-items-end">
                        <div class="col-lg-4 col-md-6">
                            <label class="form-label">Jenis Barang</label>
                            <select class="form-select jenis-barang" name="jenis_barang[]" required>
                                <option value="" data-harga="0">Pilih barang</option>
                                <?php foreach ($daftarBarang as $namaBarang => $harga): ?>
                                    <option value="<?= htmlspecialchars($namaBarang, ENT_QUOTES, 'UTF-8') ?>" data-harga="<?= $harga ?>">
                                        <?= htmlspecialchars($namaBarang, ENT_QUOTES, 'UTF-8') ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-lg-2 col-md-3">
                            <label class="form-label">Harga</label>
                            <input type="text" class="form-control harga-satuan" value="Rp0" readonly>
                        </div>

                        <div class="col-lg-2 col-md-3">
                            <label class="form-label">Jumlah</label>
                            <input type="number" class="form-control jumlah-barang" name="jumlah[]" min="1" value="1" required>
                        </div>

                        <div class="col-lg-3 col-md-9">
                            <label class="form-label">Subtotal</label>
                            <input type="text" class="form-control subtotal-barang total-field" value="Rp0" data-nilai="0" readonly>
                        </div>

                        <div class="col-lg-1 col-md-3 d-grid">
                            <button type="button" class="btn btn-outline-secondary hapus-item" title="Hapus barang">
                                Hapus
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <template id="template-item">
                <div class="item-row border rounded-3 p-3">
                    <div class="row g-2 align-items-end">
                        <div class="col-lg-4 col-md-6">
                            <label class="form-label">Jenis Barang</label>
                            <select class="form-select jenis-barang" name="jenis_barang[]" required>
                                <option value="" data-harga="0">Pilih barang</option>
                                <?php foreach ($daftarBarang as $namaBarang => $harga): ?>
                                    <option value="<?= htmlspecialchars($namaBarang, ENT_QUOTES, 'UTF-8') ?>" data-harga="<?= $harga ?>">
                                        <?= htmlspecialchars($namaBarang, ENT_QUOTES, 'UTF-8') ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-lg-2 col-md-3">
                            <label class="form-label">Harga</label>
                            <input type="text" class="form-control harga-satuan" value="Rp0" readonly>
                        </div>

                        <div class="col-lg-2 col-md-3">
                            <label class="form-label">Jumlah</label>
                            <input type="number" class="form-control jumlah-barang" name="jumlah[]" min="1" value="1" required>
                        </div>

                        <div class="col-lg-3 col-md-9">
                            <label class="form-label">Subtotal</label>
                            <input type="text" class="form-control subtotal-barang total-field" value="Rp0" data-nilai="0" readonly>
                        </div>

                        <div class="col-lg-1 col-md-3 d-grid">
                            <button type="button" class="btn btn-outline-secondary hapus-item" title="Hapus barang">
                                Hapus
                            </button>
                        </div>
                    </div>
                </div>
            </template>

            <div class="row justify-content-end mt-4">
                <div class="col-lg-4 col-md-6">
                    <label for="total_keseluruhan" class="form-label fw-semibold">Total Keseluruhan</label>
                    <input type="text" class="form-control total-field grand-total" id="total_keseluruhan" value="Rp0" readonly>
                </div>
            </div>

            <div class="d-flex justify-content-end pt-3">
                <button type="submit" class="btn btn-danger px-4">Simpan Pesanan</button>
            </div>
        </form>
    </div>
</section>
