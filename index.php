<?php
include 'Koneksi.php'; 

// Mengambil data pengguna
$sql = "SELECT customer_name, last_login FROM m_customer WHERE id = 1"; // Sesuaikan query dengan kebutuhan
$result = $conn->query($sql);

if ($result) {
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $userName = $row['customer_name'];
        $lastLogin = $row['last_login'];
    } else {
        $userName = "Guest";
        $lastLogin = "N/A";
    }
} else {
    echo "Error: " . $conn->error;
}
$conn->close();

// Definisi variabel untuk template
$title = "Selamat Datang di BCA Internet Banking";
$content = "
    <p class='date'>Tanggal : <strong>" . date("d/m/Y") . "</strong> Jam : <strong>" . date("H:i:s") . "</strong></p>
    <p class='login-info'>Login Terakhir Anda tanggal : <strong>$lastLogin</strong></p>
    <h1>" . strtoupper($userName) . ", Selamat Datang Di Internet Banking BCA</h1>
    <p class='info'>
        UNTUK MENINGKATKAN KEAMANAN TRANSAKSI<br>
        <strong class='security-info'>MOHON SEGERA DAFTARKAN NO HANDPHONE ANDA</strong><br>
        <strong class='security-info'>
            MELALUI <a href='' class='security-info'>ATM</a> ATAU CABANG BCA TERDEKAT
        </strong>
    </p>
    <p class='instruction'>
        MOHON PASTIKAN NOMOR HANDPHONE YANG DIDAFARKAN ADALAH MILIK ANDA DAN TERKINI, AGAR KAMI DAPAT MENGIRIMKAN SMS KE NOMOR TERSEBUT
    </p>
    <p class='contact-info'>
        UNTUK INFO LEBIH LANJUT HUBUNGI HALO BCA DI 500888 ATAU (021) 500888 DARI PONSEL
    </p>
";
include 'template.php';
?>
