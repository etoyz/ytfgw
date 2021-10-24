<?php
session_start();
$arr = array(
    "status" => 'notLogin'
);

require_once 'db.php';
$sql = "SELECT * FROM `manager` WHERE `loginid` = '{$_POST['loginid']}' AND `loginpw` = '{$_POST['loginpw']}';";
$db = new DB();
$re = $db->query($sql);

if ($re->num_rows == 1) { // 后台校验成功
    $arr['status'] = 'isLogin';
    $_SESSION['usertype'] = "manager";
    $_SESSION['loginid'] = $_POST['loginid'];
}

die(json_encode($arr));