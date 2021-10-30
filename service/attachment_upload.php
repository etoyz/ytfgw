<?php
session_start();

$response = array(
    "code" => 0,
    "msg" => "",
    "data" => null
);

require_once "define_error_handler_to_catch_warnings.php";

try {
    mkdir("../uploads/" . $_SESSION['loginid']);
} catch (Exception $e) {
}
ini_set("upload_max_filesize", "10M"); //TODO It does not work on some system
try {
    $response['data'] = move_uploaded_file($_FILES['file']['tmp_name'],
        "../uploads/" . $_SESSION['loginid'] . "/附件_" . $_POST['indicator'] . "_" . $_FILES['file']['name']);
} catch (Exception $e) {
    $response['error'] = json_encode($e);
}

die(json_encode($response));
