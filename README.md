# Koperasi Merah Putih — Multi Barang

Aplikasi PHP sederhana yang memungkinkan satu transaksi pemesanan memiliki beberapa jenis barang.

## Cara memasang

1. Salin folder ke `C:\xampp\htdocs\koperasi-merah-putih`.
2. Pertahankan atau salin logo Anda ke `assets/img/logo.png`.
3. Jalankan Apache dan MySQL pada XAMPP.
4. Buka phpMyAdmin.
5. Pilih menu SQL atau Import, kemudian jalankan file `database/koperasi_merah_putih.sql`.
6. Buka `http://localhost/koperasi-merah-putih/`.

## Perhatian database

File SQL menghapus tabel pesanan lama dan membuat struktur baru:

- `pesanan`: data utama transaksi.
- `detail_pesanan`: daftar barang dalam setiap transaksi.

Cadangkan data lama terlebih dahulu apabila masih diperlukan.
