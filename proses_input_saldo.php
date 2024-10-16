<?php
// Menghubungkan ke database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bank";

// Buat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ambil data dari form
$account_number = $_POST['account_number'];
$account_type = $_POST['account_type'];
$currency_code = $_POST['currency_code'];
$available_balance = $_POST['available_balance'];
 
// Query untuk memasukkan data ke tabel m_portfolio_account
$sql = "INSERT INTO m_portfolio_account (account_number, account_type, currency_code, available_balance) 
        VALUES ('$account_number', '$account_type', '$currency_code', '$available_balance')";

// Eksekusi query
if ($conn->query($sql) === TRUE) {
    echo "Saldo berhasil dimasukkan ke database.";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

// Tutup koneksi
$conn->close();
?>