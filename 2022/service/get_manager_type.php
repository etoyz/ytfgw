<?php
/**
 * 获取管理员类型（县市区管理员或专家账号？）
 */
session_start();
if (isset($_SESSION['loginid'])) {
    die($_SESSION['type']);
}