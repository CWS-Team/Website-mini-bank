<?php
// Konfigurasi koneksi database
$host = 'localhost'; // Sesuaikan dengan host database MySQL
$dbname = 'bank'; // Ganti dengan nama database kamu
$username = 'root'; // Username database
$password = ''; // Password database, jika ada

// Membuat koneksi ke database MySQL
$conn = new mysqli($host, $username, $password, $dbname);

// Cek apakah koneksi berhasil
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query untuk mengambil data dari tabel
$sql = "SELECT account_number, account_type, currency_code, available_balance FROM m_portfolio_account";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Balance Inquiry - KlikBCA</title>
    <link rel="stylesheet" href="stylee.css">
</head>
<body>
            <header>
                <div class="yellow-bar">
                    <button class="logout-btn">[ LOGOUT ]</button>
                </div>
                <div class="header-blue">
                    <div class="header-left">
                        <img src="logo.png" alt="BCA Logo" class="logo">
                        <span class="header-title">INDIVIDUAL</span>
                    </div>
                </div>
            </header>
    <div class="container">
        <div class="header">
            <h1>klikBCA Individual</h1>
        </div>
        <div class="balance-info">
            <p>Date : <strong><?php echo date("d/m/Y"); ?></strong> Time : <strong><?php echo date("H:i:s"); ?></strong></p>
            <h2>ACCOUNT INFORMATION - BALANCE INQUIRY</h2>
            <table>
                <tr>
                    <th>Account No.</th>
                    <th>Account Type</th>
                    <th>Currency</th>
                    <th>Available Balance</th>
                </tr>
                <?php
                if ($result->num_rows > 0) {
                    // Output data dari setiap baris
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>" . $row["account_number"] . "</td>
                                <td>" . $row["account_type"] . "</td>a
                                <td>" . $row["currency_code"] . "</td>
                                <td>" . number_format($row["available_balance"], 2, ',', '.') . "</td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>No data found</td></tr>";
                }
                $conn->close();
                ?>
            </table>
        </div>
    </div>
</body>
</html>
