<?php
/**
 * 上传附件
 */
session_start();

require "../include/common.php";
require "../include/verify_login.php";
require "../include/verify_is_enterprise.php";

$response = array(
    "code" => 1,
    "msg" => ""
);

require "../include/define_error_handler_to_catch_warnings.php";
try {
    mkdir("../uploads/" . $_SESSION['loginid']);
} catch (Exception $e) {
    $response['msg'] .= $e->getMessage();
}
ini_set("upload_max_filesize", "30M"); //TODO It does not work on some system
try {
    $dst_url = "../uploads/" . $_SESSION['loginid'] . "/附件_" . $_POST['indicator'] . "_" . $_FILES['file']['name'];
    if (move_uploaded_file($_FILES['file']['tmp_name'], $dst_url)) { //上传操作完成
        $response['code'] = 0;
        $response['url'] = $dst_url;
    }
} catch (Exception $e) {
    $response['msg'] .= $e->getMessage();
}

die(json_encode($response));
