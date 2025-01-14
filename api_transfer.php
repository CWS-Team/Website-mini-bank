<?php
// Koneksi database
include 'Koneksi.php';
$conn = new mysqli($host, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Connection failed: " . $conn->connect_error]);
    exit();
}

// Fungsi untuk mengembalikan response JSON
function sendResponse($status, $message, $data = null) {
    echo json_encode([
        "status" => $status,
        "message" => $message,
        "data" => $data
    ]);
    exit();
}

// Fungsi untuk log transaksi
function logTransaction($conn, $source_account, $destination_account, $transfer_amount, $transaction_type, $description) {
    // Ambil data customer dan account details
    $sql = "SELECT * FROM m_portfolio_account WHERE account_number = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $source_account);
    $stmt->execute();
    $result = $stmt->get_result();
    $source_account_data = $result->fetch_assoc();

    // Log transaksi ke t_transaction
    $insert_sql = "
        INSERT INTO t_transaction 
        (m_customer_id, transaction_type, transaction_amount, from_account_number, to_account_number, description, status, created_by, updated_by)
        VALUES 
        (?, ?, ?, ?, ?, ?, ?, ?, ?)
    ";
    
    // Siapkan data untuk insert
    $m_customer_id = $source_account_data['m_customer_id']; // Ambil m_customer_id dari data rekening sumber
    $status = "Success"; // Status transaksi
    $created_by = "1"; // Pembuat transaksi
    $updated_by = "1"; // Pembaruan oleh API

    $insert_stmt = $conn->prepare($insert_sql);
    $insert_stmt->bind_param("sssssssss", 
        $m_customer_id, $transaction_type, $transfer_amount, $source_account, $destination_account, $description, $status, $created_by, $updated_by
    );
    $insert_stmt->execute();
}


// Proses permintaan POST untuk transfer dana
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari request
    $data = json_decode(file_get_contents("php://input"), true);
    
    // Validasi dan ambil data dengan nilai default jika kosong
    $source_account = $data['source_account'] ?? '';
    $destination_account = $data['destination_account'] ?? '';
    $transfer_amount = $data['transfer_amount'] ?? 0;
    $transaction_type = $data['transaction_type'] ?? "MB"; // Jika kosong, default ke "MB"
    $description = $data['description'] ?? "Transfer dari rekening $source_account ke $destination_account"; // Default deskripsi
    $status = $data['status'] ?? "Success"; // Default status
    $created_by = $data['created_by'] ?? "API"; // Default pembuat
    $updated_by = $data['updated_by'] ?? "API"; // Default pembaruan

    // Validasi input
    if (!$source_account || !$destination_account || $transfer_amount <= 0) {
        sendResponse("error", "Parameter tidak lengkap atau jumlah transfer tidak valid.");
    }

    // Ambil saldo rekening sumber
    $sql = "SELECT * FROM m_portfolio_account WHERE account_number = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $source_account);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $source_account_data = $result->fetch_assoc();
        $source_balance = $source_account_data['available_balance'];

        // Validasi apakah saldo cukup
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

                // Log transaksi ke t_transaction
                logTransaction($conn, $source_account, $destination_account, $transfer_amount, $transaction_type, $description);

                sendResponse("success", "Transfer berhasil!", [
                    "source_account_balance" => number_format($new_source_balance, 2),
                    "destination_account_balance" => number_format($new_destination_balance, 2)
                ]);
            } else {
                sendResponse("error", "Rekening tujuan tidak ditemukan.");
            }
        } else {
            sendResponse("error", "Saldo rekening sumber tidak cukup untuk transfer.");
        }
    } else {
        sendResponse("error", "Rekening sumber tidak ditemukan.");
    }
}

// Tutup koneksi
$conn->close();
?>
