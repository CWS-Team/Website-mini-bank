<?php
// Pastikan sudah terhubung ke database
include 'koneksi.php'; 

// Query untuk mengambil data user
$sql = "SELECT customer_name, last_login FROM m_customer WHERE id = 1"; // Sesuaikan query dengan kebutuhan

// Eksekusi query
$result = $conn->query($sql);

// Pengecekan apakah query berhasil dijalankan
if ($result) {
    // Jika ada hasil, lanjutkan pengecekan jumlah baris
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $userName = $row['m_customer'];
        $lastLogin = $row['last_login'];
    } else {
        // Jika tidak ada data ditemukan
        $userName = "Guest";
        $lastLogin = "N/A";
    }
} else {
    // Jika query gagal dijalankan
    echo "Error: " . $conn->error;
}


// Close the database connection
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BCA Internet Banking</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div >
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

        <div class="content">
            <div class="sidebar">
                <ul>
                    <li><a href="#">Pembelian</a></li>
                    <li><a href="#">Pembayaran</a></li>
                    <li><a href="#">Pembayaran e-Commerce</a></li>
                    <li><a href="transfer_dana.php">Transfer Dana</a></li>
                    <li><a href="input_saldo.php">Input Saldo</a></li>
                    <li><a href="info_saldo.php">Informasi Rekening</a></li>
                    <li><a href="#">Informasi Kartu Kredit</a></li>
                    <li><a href="#">Informasi Kredit Konsumer</a></li>
                    <li><a href="#">Informasi Produk Investasi</a></li>
                    <li><a href="#">Informasi Lainnya</a></li>
                    <li><a href="#">Status Transaksi</a></li>
                    <li><a href="#">Histori Transaksi</a></li>
                    <li><a href="#">Administrasi</a></li>
                    <li><a href="#">E-Mail</a></li>
                    <li><a href="#">[ LOGOUT ]</a></li>
                </ul>
            </div>

            <div class="main-content">
            <p class="date">Tanggal : <strong><?php echo date("d/m/Y"); ?></strong> Jam : <strong><?php echo date("H:i:s"); ?></strong></p>
                <p class="login-info">Login Terakhir Anda tanggal : <strong><?php echo date("d/m/Y   H:i:s"); ?></strong> 
                <br>
                <br>
                <h1>HANAFI, Selamat Datang Di Internet Banking BCA</h1>
                <p class="info">UNTUK MENINGKATKAN KEAMANAN TRANSAKSI<br>
                    <strong class="security-info" >MOHON SEGERA DAFTARKAN NO HANDPHONE ANDA</strong><br>
                    <strong class="security-info">MELALUI <a href="" class="security-info">ATM</a> ATAU CABANG BCA TERDEKAT</strong>
                </p>
                <p class="instruction">
                    MOHON PASTIKAN NOMOR HANDPHONE YANG DIDAFARKAN ADALAH MILIK ANDA DAN TERKINI, AGAR KAMI DAPAT MENGIRIMKAN SMS KE NOMOR TERSEBUT
                </p>
                <p class="contact-info">
                    UNTUK INFO LEBIH LANJUT HUBUNGI HALO BCA DI 500888 ATAU (021) 500888 DARI PONSEL
                </p>
            </div>
        </div>

        
    </div>
   
        <footer>
            <div class="footer-content">
                <div class="white-bar">
                    <p> Copyright &copy; 2000 <img src="bca.png" alt="Bank BCA" class="logo2">All Rights Reserved</p>
                </div>
            </div>
        </footer>
    
</body>
</html>
