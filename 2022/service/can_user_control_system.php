<?php
/**
 * 用户是否可以控制系统
 */
session_start();
if (isset($_SESSION['usertype']) && $_SESSION['usertype'] == "manager" && $_SESSION['privilege'] == "0" && $_SESSION['type'] != 'expert')
    die("1");
else
    die("0");