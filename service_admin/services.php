<?php

/**
 * @param string $loginid 登录id
 * @param string $new_pw 新密码
 * @return string 修改结果
 */
function change_password_for_admin($loginid, $new_pw)
{
    include_once "db.php";
    $db = new DB();
    $sql = "UPDATE `user` SET `loginpw` = '" . $db->escape($new_pw) . "'WHERE `loginid` = '" . $db->escape($loginid) . "'";
    if ($db->query($sql) === true)
        return "密码修改成功！";
    else
        return "密码修改失败！";
}
