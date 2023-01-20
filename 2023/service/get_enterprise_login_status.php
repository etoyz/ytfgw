<?php
session_start();
if ($_SESSION['usertype'] !== "enterprise") // 登录失效
    die("0");
die("1");
