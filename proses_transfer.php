<?php
// Memanggil file koneksi
include 'koneksi.php';

// Ambil data dari form
$m_customer_id = 1; // Sesuaikan dengan id customer yang sedang login
$transaction_type = '01'; // Contoh transaksi transfer
$from_account_number = $_POST['rekening'];
$to_account_number = $_POST['rekening_tujuan'];
$transaction_amount = $_POST['jumlah'];
$description = $_POST['berita'];
$status = 'SUCCESS'; // Contoh status transaksi, ini bisa diubah sesuai kondisi

// Validasi input (opsional, bisa ditambahkan lebih lanjut)

// Menyimpan data ke tabel t_transaction
$sql = "INSERT INTO t_transaction (m_customer_id, transaction_type, from_account_number, to_account_number, transaction_amount, description, status)
        VALUES ('$m_customer_id', '$transaction_type', '$from_account_number', '$to_account_number', '$transaction_amount', '$description', '$status')";

if ($conn->query($sql) === TRUE) {
    echo "Transfer berhasil disimpan!";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

// Tutup koneksi
$conn->close();
?>