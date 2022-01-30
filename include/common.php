<?php
function fetch_indicator_attachment_name($indicator, $allFile)
{
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
    foreach(glob("{$directory}/*") as $file)
    {
        if(is_dir($file)) {
            recursiveRemoveDirectory($file);
        } else {
            unlink($file);
        }
    }
    rmdir($directory);
}