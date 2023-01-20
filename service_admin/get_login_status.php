<?php
session_start();
if (!isset($_SESSION['usertype'])) // 未登录
    die("0");
if ($_SESSION['usertype'] !== "admin") // 登录的不是管理员
    die("0");
die("1");
