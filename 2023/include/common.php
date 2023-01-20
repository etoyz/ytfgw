<?php
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