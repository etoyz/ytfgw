<?php
session_start();

$response = array(
    "status" => "notLogin",
    "data" => null
);

//若未登录、登录者不是企业、登录企业状态不为2即已经申报数据，则不能上传文件。（申报数据后才能上传文件）
if (!isset($_SESSION['usertype']) || $_SESSION['usertype'] === "manager" || $_SESSION['status'] != 2) {
    die(json_encode($response));
}
$response["status"] = "isLogin";

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
