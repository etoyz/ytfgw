<?php

class DB
{
    private $host; //数据库主机名
    private $port; //数据库端口号
    private $user; //数据库用户名
    private $pass; //数据库密码
    private $db; //数据库名
    private $charset; //数据库字符集
    private $link; //数据库连接

    public function __construct()
    {
        // TODO 暂时用2023年用的库
        include "../2023/include/INI.class.php";
        $db_config = (new INI('adminDB.ini'))->data['Database'];
        $this->host = $db_config['DB_HOST'];
        $this->port = $db_config['DB_PORT'];
        $this->user = $db_config['DB_USER'];
        $this->pass = $db_config['DB_PASSWORD'];
        $this->db = $db_config['DB_NAME'];
        $this->charset = 'utf8';
        //连接数据库
        $this->db_connect();
        //选择数据库
        $this->db_usedb();
        //设置字符集
        $this->db_charset();
    }

    // private function db_connect()
    // {
    //     $this->link = mysqli_connect($this->host, $this->user, $this->pass, $this->db, $this->port);
    //     if (!$this->link) {
    //         die(mysqli_errno($this->link));
    //         echo "数据库连接失败<br>";
    //         echo "错误编码" . mysqli_errno($this->link) . "<br>";
    //         echo "错误信息" . mysqli_error($this->link) . "<br>";
    //         exit;
    //     }
    // }
    private function db_connect()
    {
        $this->link = mysqli_connect($this->host, $this->user, $this->pass, $this->db, $this->port);
        if (!$this->link) {
            echo "数据库连接失败<br>";
            echo "错误编码" . mysqli_errno($this->link) . "<br>";
            echo "错误信息" . mysqli_error($this->link) . "<br>";
            exit;
        }
    }

    private function db_charset()
    {
        mysqli_query($this->link, "set names {$this->charset}"); //设置字符集
    }

    private function db_usedb()
    {
        mysqli_query($this->link, "use {$this->db}"); //选择数据库
    }

    /**
     * 执行sql语句
     * @param string $sql sql语句
     * @return bool|mysqli_result 对于成功的SELECT、SHOW、DESCRIBE或EXPLAIN查询，query()将返回一个mysqli_result对象。对于其他查询，query()将在成功时返回TRUE，失败时返回ERROR CODE。
     */
    public function query($sql)
    {
        $res = mysqli_query($this->link, $sql); //执行sql语句
        if (!$res) {
            return mysqli_errno($this->link); //返回错误编码
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
        return $this->link->real_escape_string($value); //转义字符串中的特殊字符
    }
}
