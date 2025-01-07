<?php
header('Content-Type: application/json');

// Konfigurasi koneksi database
$host = 'localhost'; // Sesuaikan dengan host database MySQL
$dbname = 'bank'; // Ganti dengan nama database kamu
$username = 'root'; // Username database
$password = ''; // Password database, jika ada

// Membuat koneksi ke database MySQL
$conn = new mysqli($host, $username, $password, $dbname);

// Cek apakah koneksi berhasil
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["error" => "Connection failed: " . $conn->connect_error]);
    exit;
}

// Fungsi untuk menangani permintaan API
function handleRequest($conn) {
    $method = $_SERVER['REQUEST_METHOD'];
    $endpoint = isset($_GET['endpoint']) ? $_GET['endpoint'] : '';

    switch ($endpoint) {
        case 'balance_inquiry':
            if ($method === 'GET') {
                getBalanceInquiry($conn);
            } else {
                sendErrorResponse(405, "Method not allowed");
            }
            break;

        default:
            sendErrorResponse(404, "Endpoint not found");
            break;
    }
}

// Fungsi untuk mengambil data saldo akun
function getBalanceInquiry($conn) {
    $sql = "SELECT account_number, account_type, currency_code, available_balance FROM m_portfolio_account";
    $result = $conn->query($sql);

    if ($result) {
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = [
                "account_number" => $row["account_number"],
                "account_type" => $row["account_type"],
                "currency_code" => $row["currency_code"],
                "available_balance" => number_format($row["available_balance"], 2, '.', '')
            ];
        }

        http_response_code(200);
        echo json_encode(["status" => "success", "data" => $data]);
    } else {
        sendErrorResponse(500, "Error fetching data: " . $conn->error);
    }
}

// Fungsi untuk mengirimkan respons error
function sendErrorResponse($code, $message) {
    http_response_code($code);
    echo json_encode(["status" => "error", "message" => $message]);
    exit;
}

// Menangani permintaan
handleRequest($conn);

// Menutup koneksi database
$conn->close();
?>
