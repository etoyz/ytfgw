<?php
/**
 * 查看附件
 */
session_start();

require "../include/common.php";

if (is_login()) {  // 验证登录
    if (has_permission_admin()) {  // 管理员
        header('Content-Type: application/pdf');
        header('Content-Disposition: inline;');

        readfile($_GET['path']);
    } else { // 非管理员
        preg_match("/(?<=^..\/uploads\/).*(?=\/附件_.*$)/", $_GET['path'], $file_owner); //获取文件拥有者
        if ($_SESSION['loginid'] == $file_owner[0]) { // 验证是否越权
            header('Content-Type: application/pdf');
            header('Content-Disposition: inline;');

            readfile($_GET['path']);
        } else // 越权
            die(get_string("PERMISSION_DENY"));
    }
} else
    die(get_string("NOT_LOGIN"));
