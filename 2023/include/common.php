<?php
require "db.php";
require "INI.class.php";

// 检查指定附件是否存在
function is_attachment_exist($loginid, $indicator): bool
{
    if (fetch_attachment_name($loginid, $indicator) === "未上传") // 未上传，即不存在
        return false;
    else
        return true;
}

// 返回固定的string
function get_string($key): string
{
    $ini = new INI('../app.ini');
    return $ini->data['Strings'][$key];
}

// 获取系统是否上锁
function is_lock(): bool
{
    $ini = new INI("../app.ini");
    return $ini->data['Function']['SYSTEM_LOCK'] === "ON";
}

// 获取登录状态
function is_login(): bool
{
    return isset($_SESSION['usertype']);
}

// 是否有管理员权限
function has_permission_admin(): bool
{
    return isset($_SESSION['usertype']) && $_SESSION['usertype'] === "admin";
}

// 是否有超级管理员权限
function has_permission_admin_super(): bool
{
    return isset($_SESSION['usertype']) && $_SESSION['usertype'] === "admin" && $_SESSION['privilege'] == "0";
}

// 是否有专家管理员权限
function has_permission_admin_expert(): bool
{
    return isset($_SESSION['usertype']) && $_SESSION['usertype'] === "admin" && $_SESSION['privilege'] == "专家";
}

// 是否是企业用户
function is_enterprise(): bool
{
    return isset($_SESSION['usertype']) && $_SESSION['usertype'] === "enterprise";
}

/**
 * 获取某用户上传的附件的文件名
 * @param string $user 用户的loginid
 * @param string $indicator 指标名
 * @return mixed|string 附件文件名 || “未上传”
 */
function fetch_attachment_name(string $user, string $indicator)
{
    require_once "define_error_handler_to_catch_warnings.php";
    try {
        $allFile = scandir("../uploads/$user");
    } catch (Exception $e) {
        $allFile = array();
    }
    if ($allFile === false) $allFile = array();
    foreach (preg_grep("/^附件_" . $indicator . "_.*$/", $allFile) as $r)
        return $r;
    return "未上传";
}

/**
 * @param $loginid
 * @return mixed
 */
function get_user_status($loginid)
{
    require_once "db.php";
    $db = new DB();
    return $db->query("SELECT `status` FROM `enterprise` WHERE `loginid` = '" . $db->escape($loginid) . "'")->fetch_assoc()['status'];
}

function recursiveRemoveDirectory($directory)
{
    foreach (glob("{$directory}/*") as $file) {
        if (is_dir($file)) {
            recursiveRemoveDirectory($file);
        } else {
            unlink($file);
        }
    }
    rmdir($directory);
}
