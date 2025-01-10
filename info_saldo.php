<?php
include 'Koneksi.php'; 
$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Proses input saldo
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $account_number = $_POST['account_number'];
    $account_type = $_POST['account_type'];
    $currency_code = $_POST['currency_code'];
    $available_balance = $_POST['available_balance'];

    // Mengkonversi input saldo menjadi tipe Decimal (format PHP)
    $available_balance_decimal = floatval($available_balance);

    // Mencari akun berdasarkan nomor akun
    $account_query = $conn->prepare("SELECT * FROM m_portfolio_account WHERE account_number = ? AND account_type = ?");
    $account_query->bind_param("ss", $account_number, $account_type);
    $account_query->execute();
    $account_result = $account_query->get_result();
    $account = $account_result->fetch_assoc();

    if ($account) {
        // Memperbarui saldo akun
        $new_balance = $account['available_balance'] + $available_balance_decimal;
        $update_query = $conn->prepare("UPDATE m_portfolio_account SET available_balance = ? WHERE id = ?");
        $update_query->bind_param("di", $new_balance, $account['id']);

        if ($update_query->execute()) {
            // Menyimpan transaksi
            $transaction_query = $conn->prepare("INSERT INTO t_transaction (m_customer_id, transaction_type, transaction_amount, transaction_date, description, status) VALUES (?, ?, ?, ?, ?, ?)");
            $transaction_type = 'CR'; // 'CR' untuk kredit
            $transaction_date = date("Y-m-d H:i:s");
            $description = "Input saldo to account $account_number";
            $status = 'SUCCESS';

            $transaction_query->bind_param("ssds", $account['m_customer_id'], $transaction_type, $available_balance_decimal, $transaction_date, $description, $status);
            if ($transaction_query->execute()) {
                echo "<script>alert('Successfully added $available_balance to account $account_number');</script>";
            } else {
                echo "<script>alert('Error saving transaction: " . $transaction_query->error . "');</script>";
            }

            $transaction_query->close();
        } else {
            echo "<script>alert('Error updating balance: " . $update_query->error . "');</script>";
        }

        $update_query->close();
    } else {
        echo "<script>alert('Account not found with account number $account_number');</script>";
    }

    $account_query->close();
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
            <h2>Account Information</h2>
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
    <p class="date">Tanggal : <strong><?php echo date("d/m/Y"); ?></strong> Jam : <strong><?php echo date("H:i:s"); ?></strong></p>
    <h2>ACCOUNT INFORMATION - BALANCE INQUIRY</h2>
    <table class="bordered-table">
        <tr>
            <th>Account No.</th>
            <th>Account Type</th>
            <th>Currency</th>
            <th>Available Balance</th>
        </tr>
        <?php
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>" . htmlspecialchars($row["account_number"]) . "</td>
                        <td>" . htmlspecialchars($row["account_type"]) . "</td>
                        <td>" . htmlspecialchars($row["currency_code"]) . "</td>
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
    </div>
    <footer>
        <div class="footer-content">
            <div class="white-bar">
                <p> Copyright &copy; 2000 <img src="Asset/bca.png" alt="Bank BCA" class="logo2">All Rights Reserved</p>
            </div>
        </div>
    </footer>
</body>
</html>
