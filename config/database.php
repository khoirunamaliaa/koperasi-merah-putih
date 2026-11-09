<?php
// Konfigurasi database MySQL.
// Sesuaikan nilai berikut dengan konfigurasi server lokal Anda.
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'koperasi_merah_putih';

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die('Koneksi database gagal: ' . $conn->connect_error);
}

$conn->set_charset('utf8mb4');
