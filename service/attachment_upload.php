<?php
session_start();

$response = array(
    "status" => "notLogin",
    "data" => null
);

//若未登录、登录者不是企业，则不能上传文件。（申报数据后才能上传文件）
if (!isset($_SESSION['usertype']) || $_SESSION['usertype'] === "manager") {
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
    $dst_url = "../uploads/" . $_SESSION['loginid'] . "/附件_" . $_POST['indicator'] . "_" . $_FILES['file']['name'];
    $response['data'] = move_uploaded_file($_FILES['file']['tmp_name'], $dst_url);
    $response['url'] = $dst_url;
} catch (Exception $e) {
    $response['error'] = json_encode($e);
}

die(json_encode($response));
