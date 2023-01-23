<?php
/**
 * 上传附件
 */
require "../include/common.php";

session_start();
$response = array(
    "code" => 1,
    "msg" => get_string("NOT_LOGIN")
);

//若未登录、登录者不是企业，则不能上传文件。（申报数据后才能上传文件）
if (!isset($_SESSION['usertype']) || $_SESSION['usertype'] !== "enterprise") {
    die(json_encode($response));
}
$response['msg'] = ""; // 登录状态校验成功

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
