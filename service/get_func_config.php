<?php
session_start();
include "../include/INI.class.php";

if(isset($_SESSION['loginid'])){ // 验证登录
    $a = (new INI("../app.ini"))->data['Function'];
    die(json_encode($a));
}