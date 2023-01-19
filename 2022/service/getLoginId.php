<?php
/**
 * 获取登录ID，同时验证登录是否过期
 */
session_start();
if (isset($_SESSION['loginid']))
    die($_SESSION['loginid']);
die("");
