<?php
// Koneksi database
include 'Koneksi.php';
$conn = new mysqli($host, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ambil daftar rekening sumber dari database
$source_account_options = "";
$sql = "SELECT account_number FROM m_portfolio_account";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $source_account_options .= "<option value=\"" . $row['account_number'] . "\">" . $row['account_number'] . "</option>";
    }
} else {
    $source_account_options = "<option value=\"\">Tidak ada rekening</option>";
}

// Proses form jika ada pengiriman POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $source_account = $_POST['source_account'];
    $destination_account = $_POST['destination_account'];
    $transfer_amount = $_POST['transfer_amount'];

    // Validasi jumlah transfer
    if ($transfer_amount <= 0) {
        echo "<script>alert('Jumlah transfer harus lebih besar dari nol.');</script>";
    } else {
        // Ambil saldo rekening sumber
        $sql = "SELECT * FROM m_portfolio_account WHERE account_number = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $source_account);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $source_account_data = $result->fetch_assoc();
            $source_balance = $source_account_data['available_balance'];

            if ($source_balance >= $transfer_amount) {
                // Mengurangi saldo dari rekening sumber
                $new_source_balance = $source_balance - $transfer_amount;

                // Update saldo rekening sumber
                $update_sql = "UPDATE m_portfolio_account SET available_balance = ? WHERE account_number = ?";
                $update_stmt = $conn->prepare($update_sql);
                $update_stmt->bind_param("ds", $new_source_balance, $source_account);
                $update_stmt->execute();

                // Ambil saldo rekening tujuan
                $sql_dest = "SELECT * FROM m_portfolio_account WHERE account_number = ?";
                $stmt_dest = $conn->prepare($sql_dest);
                $stmt_dest->bind_param("s", $destination_account);
                $stmt_dest->execute();
                $result_dest = $stmt_dest->get_result();

                if ($result_dest->num_rows > 0) {
                    $destination_account_data = $result_dest->fetch_assoc();
                    $destination_balance = $destination_account_data['available_balance'];

                    // Menambahkan saldo ke rekening tujuan
                    $new_destination_balance = $destination_balance + $transfer_amount;

                    // Update saldo rekening tujuan
                    $update_dest_sql = "UPDATE m_portfolio_account SET available_balance = ? WHERE account_number = ?";
                    $update_dest_stmt = $conn->prepare($update_dest_sql);
                    $update_dest_stmt->bind_param("ds", $new_destination_balance, $destination_account);
                    $update_dest_stmt->execute();

                    echo "<script>alert('Transfer berhasil! Saldo baru: " . number_format($new_source_balance, 2) . "');</script>";
                } else {
                    echo "<script>alert('Rekening tujuan tidak ditemukan.');</script>";
                }
            } else {
                echo "<script>alert('Saldo tidak cukup untuk transfer.');</script>";
            }
        } else {
            echo "<script>alert('Rekening sumber tidak ditemukan.');</script>";
        }
    }
}

// Siapkan konten untuk dimasukkan ke template
$content = "
    <p class='date'>Tanggal: " . date("d/m/Y") . " Jam: " . date("H:i:s") . "</p>
    <p class='login-info'>Login Terakhir Anda tanggal: " . date("d/m/Y H:i:s") . "</p>
    <h1>Transfer Antar Rekening BCA</h1>
    <form action='' method='POST'>
        <!-- Rekening Sumber -->
        <div class='form-group'>
            <label for='source-account'>Rekening Sumber:</label>
            <select id='source-account' name='source_account' required>
                <option value=''>Pilih Rekening Sumber</option>
                $source_account_options
            </select>
        </div>

        <!-- Rekening Tujuan -->
        <div class='form-group'>
            <label for='destination-account'>Rekening Tujuan:</label>
            <input type='text' id='destination-account' name='destination_account' placeholder='Masukkan Nomor Rekening Tujuan' required>
        </div>

        <!-- Jumlah Transfer -->
        <div class='form-group'>
            <label for='transfer-amount'>Jumlah Transfer:</label>
            <input type='number' id='transfer-amount' name='transfer_amount' placeholder='Masukkan Jumlah Transfer' required>
        </div>

        <!-- Button Submit -->
        <div class='form-group'>
            <button type='submit' class='submit-btn'>Transfer</button>
        </div>
    </form>
";

// Sertakan template
$title = "Transfer Antar Rekening BCA";
include 'template.php';

// Tutup koneksi
$conn->close();
?>
