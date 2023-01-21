<?php
/**
 * 查看附件
 */
session_start();
if (isset($_SESSION['loginid'])) {  // 验证登录
    if ($_SESSION['usertype'] == "admin") {  // 管理员
        header('Content-Type: application/pdf');
        header('Content-Disposition: inline;');

        readfile($_GET['path']);
    } else {
        preg_match("/(?<=^..\/uploads\/).*(?=\/附件_.*$)/", $_GET['path'], $file_owner); //获取文件拥有者
        if ($_SESSION['loginid'] == $file_owner[0]) {
            header('Content-Type: application/pdf');
            header('Content-Disposition: inline;');

            readfile($_GET['path']);
        }
    }
}
die("无法验证登录状态，请尝试重新登录！");
