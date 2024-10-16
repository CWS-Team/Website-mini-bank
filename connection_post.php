<?php
// Membuat koneksi ke database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bank";

// Koneksi ke MySQL
$conn = new mysqli($servername, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Ambil data dari form
$rekening_asal = $_POST['rekening_asal'];
$rekening_tujuan = $_POST['rekening_tujuan'];
$jumlah = $_POST['jumlah'];
$berita = $_POST['berita'];
$tagihan = $_POST['tagihan'];
$keybca = $_POST['keybca'];
$respon_keybca = $_POST['respon_keybca'];
$jenis_transfer = $_POST['transfer-type'];

if (isset($_POST['rekening_asal']) && isset($_POST['rekening_tujuan']) && isset($_POST['jumlah']) &&
    isset($_POST['berita']) && isset($_POST['tagihan']) && isset($_POST['keybca']) && isset($_POST['respon_keybca']) && 
    isset($_POST['transfer-type'])) {

    // Simpan data ke tabel t_transaction
    $sql = "INSERT INTO t_transaction (account_source, account_destination, amount, note, invoice, keybca, keybca_response, transfer_type)
    VALUES ('$rekening_asal', '$rekening_tujuan', '$jumlah', '$berita', '$tagihan', '$keybca', '$respon_keybca', '$jenis_transfer')";

    if ($conn->query($sql) === TRUE) {
        echo "Transfer berhasil disimpan";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

} else {
    echo "Semua field harus diisi!";
}


$conn->close();
?>
