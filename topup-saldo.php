<?php
include 'Koneksi.php';
$conn = new mysqli($host, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ambil daftar rekening dari database
$account_options = "";
$sql = "SELECT account_number FROM m_portfolio_account";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $account_options .= "<option value=\"" . $row['account_number'] . "\">" . $row['account_number'] . "</option>";
    }
} else {
    $account_options = "<option value=\"\">Tidak ada rekening</option>";
}

// Proses form jika ada pengiriman POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $account_number = $_POST['account_number'];
    $available_balance = $_POST['available_balance'];

    // Mengubah saldo menjadi tipe desimal
    $available_balance_decimal = floatval($available_balance);

    // Query untuk mencari rekening yang sesuai
    $sql = "SELECT * FROM m_portfolio_account WHERE account_number = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $account_number);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Rekening ditemukan
        $row = $result->fetch_assoc();
        $new_balance = $row['available_balance'] + $available_balance_decimal;

        // Update saldo rekening
        $update_sql = "UPDATE m_portfolio_account SET available_balance = ? WHERE account_number = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("ds", $new_balance, $account_number);

        if ($update_stmt->execute()) {
            echo "<script>alert('Saldo berhasil ditambahkan! Saldo baru: " . number_format($new_balance, 2) . "');</script>";
        } else {
            echo "<script>alert('Error updating balance: " . $conn->error . "');</script>";
        }
    } else {
        // Rekening tidak ditemukan
        echo "<script>alert('Nomor rekening tidak ditemukan. Mohon periksa kembali.');</script>";
    }
}

// Tutup koneksi
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Top Up Saldo BCA</title>
    <link rel="stylesheet" href="styles_input.css">
</head>
<body>
    <header>
        <div class="yellow-bar">
            <button class="logout-btn">[ LOGOUT ]</button>
        </div>
        <div class="header-blue">
            <div class="header-left">
                <img src="Asset/logo.png" alt="BCA Logo" class="logo">
                <span class="header-title">INDIVIDUAL</span>
            </div>
        </div>
    </header>
    <div class="container">
        <div class="content">
            <div class="sidebar">
                <h2>INPUT SALDO</h2>
                <hr>
                <ul>
                    <li><a href="#">Balance Inquiry</a></li>
                    <li><a href="#">Account Statement</a></li>
                    <li><a href="#">Time Deposit</a></li>
                    <li><a href="#">Tahapan Berjangka</a></li>
                    <li><a href="#">Fund Account</a></li>
                    <li><a href="#">Balance Inquiry RDN</a></li>
                    <hr>
                    <li><a href="index.php">Back <p>to Main Menu</p></a></li>
                </ul>
            </div>
            <div class="main-content">
                <p class="date">Tanggal: <?php echo date("d/m/Y"); ?> Jam: <?php echo date("H:i:s"); ?></p>
                <p class="login-info">Login Terakhir Anda tanggal: <?php echo date("d/m/Y H:i:s"); ?></p>
                <br><br>
                <h1>Top Up Saldo ke Rekening BCA</h1>
                <form action="" method="POST">
                    <!-- Nomor Rekening -->
                    <div class="form-group">
                        <label for="account-number">Nomor Rekening:</label>
                        <select id="account-number" name="account_number" required>
                            <option value="">Pilih Nomor Rekening</option>
                            <?php echo $account_options; ?>
                        </select>
                    </div>

                    <!-- Saldo -->
                    <div class="form-group">
                        <label for="balance">Saldo:</label>
                        <input type="number" id="balance" name="available_balance" placeholder="Masukkan Saldo" required>
                    </div>

                    <!-- Button Submit -->
                    <div class="form-group">
                        <button type="submit" class="submit-btn">Top Up Saldo</button>
                    </div>
                </form>
            </div>
        </div>
        <footer>
            <div class="footer-content">
                <div class="white-bar">
                    <p>Copyright &copy; 2000 <img src="Asset/bca.png" alt="Bank BCA" class="logo2"> All Rights Reserved</p>
                </div>
            </div>
        </footer>
    </div>
</body>
</html>
