<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../../config/database.php';

$database = new Database();
$db = $database->getConnection();

$query = "SELECT account_number, balance FROM accounts";
$stmt = $db->prepare($query);
$stmt->execute();

$num = $stmt->rowCount();

if($num > 0) {
    $accounts_arr = array();
    $accounts_arr["records"] = array();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $account_item = array(
            "account_number" => $account_number,
            "balance" => $balance
        );
        array_push($accounts_arr["records"], $account_item);
    }

    http_response_code(200);
    echo json_encode($accounts_arr);
} else {
    http_response_code(404);
    echo json_encode(
        array("message" => "No accounts found.")
    );
}
?>