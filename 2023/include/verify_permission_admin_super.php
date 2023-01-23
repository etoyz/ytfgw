<?php
/**
 * 验证登录状态
 */
if (!(is_login() && has_permission_admin() && has_permission_admin_super()))
    die(json_encode(array(
        "code" => 1,
        "msg" => get_string("PERMISSION_DENY")
    )));