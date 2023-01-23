<?php
/**
 * 删除附件
 */
require "../include/common.php";

session_start();
$response = array(
    "code" => 1,
    "msg" => get_string("NOT_LOGIN")
);

if (isset($_SESSION['usertype']) && $_SESSION['usertype'] === "enterprise") { // 只有企业用户可以删除附件
    try {
        $re = unlink("../uploads/$_SESSION[loginid]/" . fetch_attachment_name($_SESSION['loginid'], $_GET['indicator']));
        if ($re === true)
            $response['code'] = 0;
        else
            $response['msg'] = "删除失败";
    } catch (Exception $e) {
        $response['msg'] = $e->getMessage();
    }
}

die(json_encode($response));