<?php
session_start();
$response = array(
    "status" => 'notLogin',
    "data" => null
);
if (isset($_SESSION['usertype'])) {
    require_once 'db.php';
    $db = new DB();
    if ($_SESSION['status'] <= 4)
        $dst_status = 1;
    else
        $dst_status = 6;
    $sql = "UPDATE `enterprise` 
        SET `name` = '" . $db->escape($_POST["name"]) . "', 
            `address` = '" . $db->query($_POST["address"]) . "', 
            `numofsub` = '" . $db->escape($_POST["numofsub"]) . "', 
            `mainbus` = '" . $db->escape($_POST["mainbus"]) . "]', 
            `industry` = '" . $db->escape($_POST["industry"]) . "', 
            `created_year` = '" . $db->escape($_POST["created_year"]) . "', 
            `chargeperson` = '" . $db->escape($_POST["chargeperson"]) . "', 
            `phone` = '" . $db->escape($_POST["phone"]) . "', 
            `remark` = '" . $db->escape($_POST["remark"]) . "',
            `status` = $dst_status
        WHERE `loginid` = '" . $db->escape($_SESSION["loginid"]) . "';";
    $_SESSION['status'] = $dst_status;
    $response['status'] = "isLogin";
    $response['data'] = $db->query($sql);
}
die(json_encode($response));