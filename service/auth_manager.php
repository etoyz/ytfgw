<?php
session_start();

$arr = array(
    "status" => 'notLogin'
);

if (isset($_SESSION['loginid']) && $_SESSION['usertype'] == "manager") {
    $arr['status'] = 'isLogin';
} else
    die(json_encode($arr));