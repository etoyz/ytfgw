<?php
$arr = array(
    "nameRepetition" => null,
    "regSuccess" => null,
    "username" => null
);

require_once 'db.php';
$db = new DB();

//名字重复
$sql = "SELECT eid FROM `enterprise` WHERE `loginid` = '{$_POST['loginid']}';";
$re = $db->query($sql);
if ($re->num_rows == 1) {
    $arr['nameRepetition'] = true;
    die(json_encode($arr));
}


$sql = "INSERT INTO `enterprise` (loginid,loginpw,name,status) 
        VALUES ('{$_POST['loginid']}','" . password_hash($_POST['loginpw'], PASSWORD_DEFAULT)
    . "','{$_POST['name']}', 0)";
$re = $db->query($sql);
if ($re) {
    $arr['regSuccess'] = true;
    $arr['username'] = $_POST['loginid'];
} else {
    $arr['regSuccess'] = false;
}

die(json_encode($arr));