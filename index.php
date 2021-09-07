<?php
header("Content-Type:application/json");
require "vendor/autoload.php";

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$client = new MongoDB\Client($_ENV["CONN_STRING"]);

$db = $client->test;

$collection = $db->userData;

if ($_GET["test"]) {
    echo "hello world";
    return;
}

if (empty($_GET["name"])) {
    $users = $collection->find()->toArray();
    response(200, "All users", $users);
} else {
    $name = $_GET["name"];
    $user = $collection->findOne(["name" => $name]);

    if (!$user) {
        response(200, $name, $user);
    } else {
        response(404, $name, null);
    }
}


function response($status, $status_message, $data)
{
    header("HTTP/1.1" . $status);

    $response["status"] = $status;
    $response["status_message"] = $status_message;
    $response["data"] = $data;

    echo json_encode($data);
}
