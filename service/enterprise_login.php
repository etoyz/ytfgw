<?php
session_start();
$arr = array(
    "status" => 'notLogin'
);

//$response = json_decode(file_get_contents('php://input'));

require_once '../include/db.php';
$db = new DB();
$sql = "SELECT `loginpw`,`status` FROM `enterprise` WHERE `loginid` = '" . $db->escape($_POST['loginid']) . "';";
$re = $db->query($sql);

if ($re->num_rows == 1) { // 后台校验成功
    $data = $re->fetch_assoc();
    if (password_verify($_POST['loginpw'], $data['loginpw'])) {
        $arr['status'] = 'isLogin';
        $_SESSION['usertype'] = "enterprise";
        $_SESSION['loginid'] = $_POST['loginid'];
        $_SESSION['status'] = $data['status']; // 0刚注册未完善信息 1已完善信息未填报数据 2已申报数据审核中 3已审核但是退回 4已审核通过
    }
}

die(json_encode($arr));

