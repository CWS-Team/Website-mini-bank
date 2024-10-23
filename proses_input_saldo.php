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

// Ambil data dari form POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $account_number = $_POST['account_number'];
    $account_type = $_POST['account_type'];
    $currency_code = $_POST['currency_code'];
    $available_balance = $_POST['available_balance'];

    // Mengubah saldo menjadi tipe desimal
    $available_balance_decimal = floatval($available_balance);

    // Query untuk mencari akun berdasarkan nomor rekening dan tipe akun
    $sql = "SELECT * FROM m_portfolio_account WHERE account_number = '$account_number' AND account_type = '$account_type' AND currency_code = '$currency_code'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Akun ditemukan, tambahkan saldo baru ke saldo yang ada
        $row = $result->fetch_assoc();
        $new_balance = $row['available_balance'] + $available_balance_decimal;

        // Update saldo dalam database
        $update_sql = "UPDATE m_portfolio_account SET available_balance = '$new_balance' WHERE account_number = '$account_number' AND account_type = '$account_type'";
        
        if ($conn->query($update_sql) === TRUE) {
            // Catat transaksi dalam tabel t_transaction
            $m_customer_id = $row['m_customer_id'];
            $transaction_type = 'CR'; // 'CR' untuk kredit
            $transaction_date = date("Y-m-d H:i:s"); // Tanggal otomatis sesuai inputan
            $description = "Input saldo to account $account_number";
            $status = 'SUCCESS';

            // Query untuk menambahkan transaksi
            $transaction_sql = "INSERT INTO t_transaction (m_customer_id, transaction_type, transaction_amount, transaction_date, description, status) 
                                VALUES ('$m_customer_id', '$transaction_type', '$available_balance_decimal', '$transaction_date', '$description', '$status')";

            if ($conn->query($transaction_sql) === TRUE) {
                echo "<script>alert('Successfully added $available_balance to account $account_number');</script>";
            } else {
                echo "<script>alert('Error saving transaction: " . $conn->error . "');</script>";
            }
        } else {
            echo "<script>alert('Error updating balance: " . $conn->error . "');</script>";
        }
    } else {
        // Akun tidak ditemukan, masukkan sebagai akun baru
        $insert_sql = "INSERT INTO m_portfolio_account (account_number, account_type, currency_code, available_balance) 
                       VALUES ('$account_number', '$account_type', '$currency_code', '$available_balance_decimal')";

        if ($conn->query($insert_sql) === TRUE) {
            // Ambil id customer setelah memasukkan akun baru
            $m_customer_id = $conn->insert_id;
            $transaction_type = 'CR';
            $transaction_date = date("Y-m-d H:i:s");
            $description = "Input saldo to new account $account_number";
            $status = 'SUCCESS';

            // Query untuk menambahkan transaksi
            $transaction_sql = "INSERT INTO t_transaction (m_customer_id, transaction_type, transaction_amount, transaction_date, description, status) 
                                VALUES ('$m_customer_id', '$transaction_type', '$available_balance_decimal', '$transaction_date', '$description', '$status')";

            if ($conn->query($transaction_sql) === TRUE) {
                echo "<script>alert('Account created and saldo added successfully');</script>";
            } else {
                echo "<script>alert('Error saving transaction for new account: " . $conn->error . "');</script>";
            }
        } else {
            echo "<script>alert('Error creating new account: " . $conn->error . "');</script>";
        }
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
    <title>Input Saldo BCA</title>
    <link rel="stylesheet" href="styles_input.css">
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
                <li><a href="home.php">Back <p>to Main Menu</p></a></li>
            </ul>
        </div>
        <div class="main-content">
            <p class="date">Tanggal: <?php echo date("d/m/Y"); ?> Jam: <?php echo date("H:i:s"); ?></p>
            <p class="login-info">Login Terakhir Anda tanggal: <?php echo date("d/m/Y H:i:s"); ?></p>
            <br>
            <br>
            <h1>Input Saldo ke Rekening BCA</h1>
            <form action="" method="POST">
                <!-- Nomor Rekening -->
                <div class="form-group">
                    <label for="account-number">Nomor Rekening:</label>
                    <input type="text" id="account-number" name="account_number" placeholder="Masukkan Nomor Rekening" required>
                </div>

                <!-- Tipe Akun -->
                <div class="form-group">
                    <label for="account-type">Tipe Akun:</label>
                    <select id="account-type" name="account_type" required>
                        <option value="">Pilih Tipe Akun</option>
                        <option value="tabungan">Tabungan</option>
                        <option value="giro">Giro</option>
                        <option value="deposito">Deposito</option>
                    </select>
                </div>

                <!-- Mata Uang -->
                <div class="form-group">
                    <label for="currency">Mata Uang:</label>
                    <select id="currency" name="currency_code" required>
                        <option value="">Pilih Mata Uang</option>
                        <option value="IDR">IDR (Rupiah)</option>
                        <option value="USD">USD (Dollar)</option>
                        <option value="EUR">EUR (Euro)</option>
                    </select>
                </div>

                <!-- Saldo -->
                <div class="form-group">
                    <label for="balance">Saldo:</label>
                    <input type="number" id="balance" name="available_balance" placeholder="Masukkan Saldo" required>
                </div>

                <!-- Button Submit -->
                <div class="form-group">
                    <button type="submit" class="submit-btn">Input Saldo</button>
                </div>
            </form>
        </div>
    </div>
    <footer>
        <div class="footer-content">
            <div class="white-bar">
                <p>Copyright &copy; 2000 <img src="bca.png" alt="Bank BCA" class="logo2"> All Rights Reserved</p>
            </div>
        </div>
    </footer>
</body>
</html>
