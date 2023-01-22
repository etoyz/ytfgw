<?php
/**
 * 企业注册（需要管理员权限）
 */

session_start();

$arr = array(
    "status" => 'notLogin'
);

if (isset($_SESSION['loginid']) && $_SESSION['usertype'] == "admin") {
    $arr['status'] = 'isLogin';
} else
    die(json_encode($arr));

$arr = array(
    "nameRepetition" => null,
    "regSuccess" => null,
    "username" => null
);

require_once '../include/db.php';
$db = new DB();

//名字重复
$sql = "SELECT `loginid` FROM `enterprise` WHERE `loginid` = '" . $db->escape($_POST['loginid']) . "';";
$re = $db->query($sql);
if ($re->num_rows == 1) {
    $arr['nameRepetition'] = true;
    die(json_encode($arr));
}


$sql = "INSERT INTO `enterprise` (loginid,loginpw,name,status,address) 
        VALUES ('" . $db->escape($_POST['loginid'])
    . "', '" . password_hash($_POST['loginpw'], PASSWORD_DEFAULT)
    . "', '" . $db->escape($_POST['name'])
    . "', '" . $db->escape($_GET['status'])
    . "', '" . $db->escape($_POST['address'])
    . "')";
$re = $db->query($sql);
if ($re) {
    $arr['regSuccess'] = true;
    $arr['username'] = $_POST['loginid'];
} else {
    $arr['regSuccess'] = false;
}

die(json_encode($arr));