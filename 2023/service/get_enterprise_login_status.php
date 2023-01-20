<?php
session_start();
if (!isset($_SESSION['usertype'])) // 未登录
    die("0");
if ($_SESSION['usertype'] !== "enterprise")
    die("0");
die("1");
