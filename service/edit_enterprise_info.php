<?php
session_start();
$response = array(
    "status" => 'notLogin',
    "data" => null
);
if (isset($_SESSION['usertype'])) {
    $sql = "UPDATE `enterprise` 
        SET `name` = '$_POST[name]', 
            `address` = '$_POST[address]', 
            `numofsub` = '$_POST[numofsub]', 
            `mainbus` = '$_POST[mainbus]', 
            `industry` = '$_POST[industry]', 
            `created_year` = '$_POST[created_year]', 
            `chargeperson` = '$_POST[chargeperson]', 
            `phone` = '$_POST[phone]', 
            `remark` = '$_POST[remark]',
            `status` = 1
        WHERE `loginid` = '$_SESSION[loginid]';";
    $_SESSION['status'] = 1;
    require_once 'db.php';
    $db = new DB();
    $response['status'] = "isLogin";
    $response['data'] = $db->query($sql);
}
die(json_encode($response));