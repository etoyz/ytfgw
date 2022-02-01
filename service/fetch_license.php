<?php
/**
 * 获取指定企业的营业执照信息（相对路径）
 */
$response = array(
    "license" => null,
    "license_sub" => null
);

session_start();
$user = null;
if ($_SESSION['usertype'] === "enterprise") {
    $user = $_SESSION['loginid'];
} else {
    $user = $_GET['user'];
}
require_once "../include/define_error_handler_to_catch_warnings.php";
try {
    $allFile = scandir("../uploads/$user");
} catch (Exception $e) {
//    var_dump($e);
    $allFile = array();
}
require_once "../include/common.php";
if (fetch_indicator_attachment_name("企业营业执照", $allFile) != "未上传")
    $response['license'] = "../uploads/$user/" . fetch_indicator_attachment_name("企业营业执照", $allFile);
if (fetch_indicator_attachment_name("下属企业营业执照", $allFile) != "未上传")
    $response['license_sub'] = "../uploads/$user/" . fetch_indicator_attachment_name("下属企业营业执照", $allFile);

die(json_encode($response));