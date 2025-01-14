<?php
include 'Koneksi.php'; 

// Query untuk mengambil data dari tabel m_portfolio_account
$sql = "SELECT account_number, account_type, currency_code, available_balance FROM m_portfolio_account";
$result = $conn->query($sql);

// Definisi variabel untuk template
$title = "Informasi Rekening - BCA Internet Banking";

$content = "
    <p class='date'>Tanggal : <strong>" . date("d/m/Y") . "</strong> Jam : <strong>" . date("H:i:s") . "</strong></p>
    <h2>ACCOUNT INFORMATION - BALANCE INQUIRY</h2>
    <table class='bordered-table'>
        <tr>
            <th>Account No.</th>
            <th>Account Type</th>
            <th>Currency</th>
            <th>Available Balance</th>
        </tr>
";

// Menampilkan data akun
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $content .= "<tr>
                        <td>" . htmlspecialchars($row["account_number"]) . "</td>
                        <td>" . htmlspecialchars($row["account_type"]) . "</td>
                        <td>" . htmlspecialchars($row["currency_code"]) . "</td>
                        <td>" . number_format($row["available_balance"], 2, ',', '.') . "</td>
                      </tr>";
    }
} else {
    $content .= "<tr><td colspan='4'>No data found</td></tr>";
}

$content .= "</table>";

// Menutup koneksi
$conn->close();

// Memasukkan template
include 'template.php';
?>
