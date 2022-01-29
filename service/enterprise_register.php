<?php
require_once "auth_manager.php";
$arr = array(
    "nameRepetition" => null,
    "regSuccess" => null,
    "username" => null
);

require_once '../include/db.php';
$db = new DB();

//名字重复
$sql = "SELECT eid FROM `enterprise` WHERE `loginid` = '" . $db->escape($_POST['loginid']) . "';";
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