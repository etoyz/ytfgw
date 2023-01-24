<?php
/**
 * 验证登录状态
 */
if (!is_login())
    die(json_encode(array(
        "code" => 1,
        "msg" => get_string("NOT_LOGIN"),
        "count" => 0,
        "data" => []
    )));