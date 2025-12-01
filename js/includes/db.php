<?php
$conn = new mysqli('localhost', 'db_user', 'db_password', 'qilin_cms');
if ($conn->connect_error) {
    die("数据库连接失败: " . $conn->connect_error);
}
?>