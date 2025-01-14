<?php
header('Content-Type: application/json'); // Set content type to JSON
include 'Koneksi.php'; 
// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    http_response_code(500); // Internal Server Error
    die(json_encode(array('error' => 'Database connection failed: ' . $conn->connect_error)));
}

// Get the HTTP method (GET, POST, etc.)
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'POST':
        createAccount($conn);
        break;
    case 'GET':
        getAccounts($conn);
        break;
    default:
        http_response_code(405); // Method Not Allowed
        echo json_encode(array('error' => 'Method not allowed'));
        break;
}

// Function to handle creating a new account (POST method)
function createAccount($conn)
{
    // Get the raw POST data and decode it
    $data = json_decode(file_get_contents('php://input'), true);

    // Get the data from JSON and validate
    $account_number = isset($data['account_number']) ? $conn->real_escape_string($data['account_number']) : null;
    $account_type = isset($data['account_type']) ? $conn->real_escape_string($data['account_type']) : null;
    $currency_code = isset($data['currency_code']) ? $conn->real_escape_string($data['currency_code']) : null;
    $available_balance = isset($data['available_balance']) ? (float)$data['available_balance'] : null;

    // Check if all required fields are present
    if (!$account_number || !$account_type || !$currency_code || $available_balance === null) {
        http_response_code(400); // Bad Request
        echo json_encode(array('error' => 'Missing required fields. Please provide account_number, account_type, currency_code, and available_balance.'));
        return;
    }

    // Prepare the SQL statement to avoid SQL injection
    $stmt = $conn->prepare("INSERT INTO m_portfolio_account (account_number, account_type, currency_code, available_balance) VALUES (?, ?, ?, ?)");
    if ($stmt) {
        $stmt->bind_param("sssd", $account_number, $account_type, $currency_code, $available_balance);

        // Execute the query
        if ($stmt->execute()) {
            http_response_code(201); // Created
            echo json_encode(array('success' => 'Account created successfully'));
        } else {
            http_response_code(500); // Internal Server Error
            echo json_encode(array('error' => 'Failed to create account: ' . $stmt->error));
        }

        // Close the statement
        $stmt->close();
    } else {
        http_response_code(500); // Internal Server Error
        echo json_encode(array('error' => 'Failed to prepare statement: ' . $conn->error));
    }
}

// Function to get accounts (GET method)
function getAccounts($conn)
{
    $sql = "SELECT * FROM m_portfolio_account ORDER BY id DESC";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $accounts = [];

        // Fetch all rows as associative arrays
        while ($row = $result->fetch_assoc()) {
            $accounts[] = array(
                'id' => $row['id'],
                'account_number' => $row['account_number'],
                'account_type' => $row['account_type'],
                'currency_code' => $row['currency_code'],
                'available_balance' => $row['available_balance'],
                'created' => $row['created']
            );
        }

        // Return the accounts as JSON
        http_response_code(200); // OK
        echo json_encode(array('data' => $accounts));
    } else {
        http_response_code(404); // No Data Found
        echo json_encode(array('error' => 'No accounts found'));
    }
}

// Close the connection
$conn->close();
?>
