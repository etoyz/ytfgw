<?php
session_start();
$arr = array(
    "status" => 'notLogin'
);

//$response = json_decode(file_get_contents('php://input'));

require_once 'db.php';
$sql = "SELECT * FROM `enterprise` WHERE `loginid` = '{$_POST['loginid']}' AND `loginpw` = '{$_POST['loginpw']}';";
$db = new DB();
$re = $db->query($sql);

if ($re->num_rows == 1) { // 后台校验成功
    $arr['status'] = 'isLogin';
    $_SESSION['usertype'] = "enterprise";
    $_SESSION['loginid'] = $_POST['loginid'];
    $_SESSION['status'] = mysqli_fetch_assoc($re)['status']; // 0刚注册未完善信息 1已完善信息未申报数据 2已申报数据审核中 3已审核但是退回 4已审核通过
}

die(json_encode($arr));

