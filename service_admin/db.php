<?php

class DB
{
    private $host;
    private $port;
    private $user;
    private $pass;
    private $db;
    private $charset;
    private $link;

    // TODO 暂时硬编码连接
    public function __construct()
    {
        $this->host = "127.0.0.1";
        $this->port = 3306;
        $this->user = "root";
        $this->pass = "ytfgw0535ytu123";
        $this->db = 'ytfgw-admin';
        $this->charset = 'utf8';
        //连接数据库
        $this->db_connect();
        //选择数据库
        $this->db_usedb();
        //设置字符集
        $this->db_charset();
    }

    private function db_connect()
    {
        $this->link = mysqli_connect($this->host, $this->user, $this->pass, $this->db, $this->port);
        if (!$this->link) {
            die(mysqli_errno($this->link));
            echo "数据库连接失败<br>";
            echo "错误编码" . mysqli_errno($this->link) . "<br>";
            echo "错误信息" . mysqli_error($this->link) . "<br>";
            exit;
        }
    }

    private function db_charset()
    {
        mysqli_query($this->link, "set names {$this->charset}");
    }

    private function db_usedb()
    {
        mysqli_query($this->link, "use {$this->db}");
    }

    /**
     * 执行sql语句
     * @param string $sql sql语句
     * @return bool|mysqli_result For successful SELECT, SHOW, DESCRIBE or EXPLAIN queries, query() will return a mysqli_result object. For other queries query() will return TRUE on success, returns ERROR CODE on failure.
     */
    public function query($sql)
    {
        $res = mysqli_query($this->link, $sql);
        if (!$res) {
            return mysqli_errno($this->link);
//            echo "sql语句执行失败<br>";
//            echo "错误编码是" . mysqli_errno($this->link) . "<br>";
//            echo "错误信息是" . mysqli_error($this->link) . "<br>";
//            echo "SQL:" . $sql . "<br>";
        }
        return $res;
    }

    /**
     * @param $value
     * @return mixed
     */
    public function escape($value)
    {
        return $this->link->real_escape_string($value);
    }
}