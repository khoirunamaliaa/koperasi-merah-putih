CREATE DATABASE IF NOT EXISTS koperasi_merah_putih
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE koperasi_merah_putih;

-- PERHATIAN:
-- Perintah berikut menghapus tabel dan data pesanan lama.
DROP TABLE IF EXISTS detail_pesanan;
DROP TABLE IF EXISTS pesanan;

CREATE TABLE pesanan (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    tanggal_pesan DATE NOT NULL,
    total_tagihan INT UNSIGNED NOT NULL DEFAULT 0,
    dibuat_pada TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE detail_pesanan (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    pesanan_id INT UNSIGNED NOT NULL,
    jenis_barang VARCHAR(50) NOT NULL,
    harga_satuan INT UNSIGNED NOT NULL,
    jumlah INT UNSIGNED NOT NULL,
    subtotal INT UNSIGNED NOT NULL,
    dibuat_pada TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_detail_pesanan_id (pesanan_id),
    CONSTRAINT fk_detail_pesanan
        FOREIGN KEY (pesanan_id)
        REFERENCES pesanan(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB;
