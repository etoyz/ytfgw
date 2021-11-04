<?php
$response = array(
    "license" => null,
    "license_sub" => null
);

session_start();
require_once "define_error_handler_to_catch_warnings.php";
try {
    $allFile = scandir("../uploads/$_SESSION[loginid]");
} catch (Exception $e) {
//    var_dump($e);
    $allFile = array();
}
require_once "common.php";
if (fetch_indicator_attachment_name("企业营业执照", $allFile) != "未上传")
    $response['license'] = "../uploads/$_SESSION[loginid]/" . fetch_indicator_attachment_name("企业营业执照", $allFile);
if (fetch_indicator_attachment_name("下属企业营业执照", $allFile) != "未上传")
    $response['license_sub'] = "../uploads/$_SESSION[loginid]/" . fetch_indicator_attachment_name("下属企业营业执照", $allFile);

die(json_encode($response));