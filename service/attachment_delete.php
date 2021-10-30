<?php
session_start();
$response = array(
    "status" => 'notLogin',
    "data" => null
);

if (isset($_SESSION['usertype']) && $_SESSION['usertype'] === "enterprise") { // 只有企业用户可以删除附件
    $response["status"] = "isLogin";

    require_once "common.php";
    require_once "define_error_handler_to_catch_warnings.php";
    try {
        $response['data'] = unlink("../uploads/$_SESSION[loginid]/" . fetch_indicator_attachment_name($_GET['indicator'], scandir("../uploads/$_SESSION[loginid]")));
    } catch (Exception $e) {
//        var_dump($e);
        $response['data'] = false;
    }
}
die(json_encode($response));