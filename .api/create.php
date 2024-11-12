<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../../config/database.php';

$database = new Database();
$db = $database->getConnection();

$data = json_decode(file_get_contents("php://input"));

if (
    !empty($data->account_number) &&
    !empty($data->balance)
) {
    $query = "INSERT INTO accounts SET account_number=:account_number, balance=:balance";

    $stmt = $db->prepare($query);

    $stmt->bindParam(":account_number", $data->account_number);
    $stmt->bindParam(":balance", $data->balance);

    if($stmt->execute()) {
        http_response_code(201);
        echo json_encode(array("message" => "Account was created."));
    } else {
        http_response_code(503);
        echo json_encode(array("message" => "Unable to create account."));
    }
} else {
    http_response_code(400);
    echo json_encode(array("message" => "Unable to create account. Data is incomplete."));
}
?>