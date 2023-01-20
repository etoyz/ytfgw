<?php
/**
 * 用户是否可以控制系统
 */
session_start();
if (isset($_SESSION['usertype']) && $_SESSION['usertype'] == "admin" && $_SESSION['privilege'] == "0")
    die("1");
else
    die("0");