<?php
$host = 'localhost';
$dbname = 'bank'; // Nama database yang kamu gunakan
$username = 'root'; // Username MySQL
$password = ''; // Password MySQL, kosong jika default

// Membuat koneksi
$conn = new mysqli($host, $username, $password, $dbname);

// Mengecek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>