<?php
session_start();
$response = array(
    "status" => 'notLogin',
    "data" => null
);

if (isset($_SESSION['usertype']) && $_SESSION['usertype'] === "manager") {
    $response['status'] = "isLogin";

    require_once 'db.php';
    $db = new DB();
    $sql = null;
    if ($_POST['operation'] === "pass") {
        $sql = "UPDATE `enterprise` set `status` = 4 WHERE `loginid` = '" . $db->escape($_POST["loginid"]) . "';";
        $_SESSION['status'] = 4;
    } else {
        $sql = "UPDATE `enterprise` set `status` = 3, `return_reason` = '" . $_POST['return_reason'] . "' WHERE `loginid` = '" . $db->escape($_POST["loginid"]) . "';";
        $_SESSION['status'] = 3;
    }
    $response['data'] = $db->query($sql);
    var_dump($sql);
}

die(json_encode($response));