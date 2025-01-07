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
    die("Koneksi gagal: " . $conn->connect_error);
}

// Cek apakah form sudah disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validasi input
    if (empty($_POST['jumlah']) || empty($_POST['rekening_tujuan'])) {
        echo "Jumlah dan rekening tujuan harus diisi!";
        exit;
    }
    // Ambil data dari form
    $jumlah = $_POST['jumlah'];
    $rekening_tujuan = $_POST['rekening_tujuan'];
    
    // Menyusun query untuk memasukkan data ke dalam tabel t_transaction dengan prepared statements
    $sql = "INSERT INTO t_transaction (
        m_customer_id, mti, transaction_type, card_number, transaction_amount,
        fee_indicator, fee, transmission_date, transaction_date, value_date,
        conversion_rate, stan, merchant_type, terminal_id, reference_number,
        approval_number, response_code, currency_code, customer_reference, biller_name,
        from_account_number, to_account_number, from_account_type, to_account_type,
        balance, description, to_bank_code, execution_type, status, translation_code,
        free_data1, free_data2, free_data3, free_data4, free_data5, delivery_channel,
        delivery_channel_id, created, created_by, updated, updated_by, archive
    ) VALUES (
        NULL, '0000', '01', NULL, ?, '0', '0', NOW(), NOW(), NOW(),
        '1', '000000', '0000', NULL, NULL, NULL, NULL, 'IDR', NULL, NULL, ?, 
        NULL, NULL, '00', '00', NULL, NULL, NULL, 'N', 'Pending', NULL, NULL, NULL,
        NULL, NULL, 'WEB', '1', NOW(), 1, NOW(), 1, 0
    )";


    // Menyiapkan statement untuk menghindari SQL injection
    if ($stmt = $conn->prepare($sql)) {
        // Binding parameter (jumlah, rekening_tujuan)
        $stmt->bind_param("ss", $jumlah, $rekening_tujuan);

        // Mengeksekusi query
        if ($stmt->execute()) {
            echo "Transfer berhasil!";
        } else {
            echo "Error: " . $stmt->error;
        }

        // Menutup statement
        $stmt->close();
    } else {
        echo "Prepared statement gagal: " . $conn->error;
    }
}

// Menutup koneksi
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transfer Dana BCA</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <header>
            <img src="logo.png" alt="Klik BCA Logo">
            <div class="session-info">
                <p class="date">Tanggal : <strong><?php echo date("d/m/Y"); ?></strong> Jam : <strong><?php echo date("H:i:s"); ?></strong></p>
            </div>
        </header>

        <div class="main-content">
            <div class="left-menu">
                <ul>
                    <li><b>Transfer Dana</b></li>
                    <li>Daftar Rekening Tujuan</li>
                    <li>Transfer ke Rek. BCA</li>
                    <li>Transfer ke BCA Virtual Account</li>
                    <li>Transfer ke Rek. Bank lain Dalam Negeri</li>
                </ul>
                <a href="home.php" class="back-link">Kembali ke Menu Utama</a>
            </div>

            <div class="transfer-form">
                <h2>TRANSFER DANA - TRANSFER KE REK. BCA</h2>
                
                <form method="POST" action="">
                    <div class="rekening-asal-container">
                        <div class="radio-group">
                            <div>
                                <label for="daftar-transfer">Dari Rekening</label>
                                <span>:</span>
                                <select name="rekening_asal">
                                    <option>---Pilih---</option>
                                    <option>BCA</option>
                                    <option>BNI</option>
                                    <option>Mandiri</option>
                                    <option>BRI</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="rekening-tujuan-container">
                        <label>Silakan Pilih Rekening Tujuan</label>
                        <div class="radio-group">
                            <div>
                                <input type="radio" id="rekening-sendiri" name="rekening_tujuan" checked>
                                <label for="rekening-sendiri">Rekening Sendiri</label>
                                <span>:</span>
                                <input type="text" name="rekening_tujuan" placeholder="Masukkan No. Rekening">
                            </div>
                            <div>
                                <input type="radio" id="daftar-transfer" name="rekening_tujuan">
                                <label for="daftar-transfer">Dari Daftar Transfer</label>
                                <span>:</span>
                                <select name="rekening_tujuan">
                                    <option>---Pilih---</option>
                                    <option>BCA</option>
                                    <option>BNI</option>
                                    <option>Mandiri</option>
                                    <option>BRI</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="rekening-asal-container">
                        <div class="radio-group">
                            <div>
                                <label for="jumlah">Jumlah</label>
                                <span>:</span>
                                <input type="text" name="jumlah" placeholder="Jumlah Transfer">
                            </div>
                        </div>
                    </div>

                    <div class="rekening-asal-container">
                        <div class="radio-group">
                            <div>
                                <label for="berita">Berita</label>
                                <span>:</span>
                                <input type="text" name="berita" placeholder="Keterangan">
                                <input type="text" name="kode" placeholder="Kode">
                            </div>
                        </div>
                    </div>

                    <div class="rekening-asal-container">
                        <div class="radio-group">
                            <div>
                                <label for="keybca-response">Masukkan 8 Angka Ini Pada KeyBCA</label>
                                <span>:</span>
                                <input type="text" name="keybca_response" placeholder="Masukkan Angka">
                            </div>
                        </div>
                    </div>

                    <label for="daftar-transfer">Pastikan 6 Angka Terakhir Sesuai Dengan Nomor Rekening Tujuan Anda</label>

                    <div class="rekening-asal-container">
                        <div class="radio-group">
                            <div>
                                <label for="keybca-response">Respon KeyBCA Appli 2</label>
                                <span>:</span>
                                <input type="password" name="keybca_response2" placeholder="Masukkan Respon KeyBCA">
                                <a href="#">HELP KEYBCA</a>
                            </div>
                        </div>
                    </div>

                    <label>Jenis Transfer:</label>
                    <div>
                        <input type="radio" id="now" name="jenis_transfer" checked>
                        <label for="now">Transfer Sekarang</label>
                    </div>

                    <button type="submit" class="submit-button">Transfer Sekarang</button>
                </form>
            </div>
        </div>
        
        <footer>
            <p>&copy; 2000 BCA All Rights Reserved</p>
        </footer>
    </div>
</body>
</html>
