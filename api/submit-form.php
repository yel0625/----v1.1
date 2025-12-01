<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 接收表单数据
    $name = htmlspecialchars($_POST['name']);
    $company = htmlspecialchars($_POST['company']);
    $phone = htmlspecialchars($_POST['phone']);
    $email = htmlspecialchars($_POST['email']);
    $message = htmlspecialchars($_POST['message']);
    
    // 配置数据库连接
    $servername = "localhost";
    $username = "your_db_username";
    $password = "your_db_password";
    $dbname = "qilin_contact";
    
    // 创建连接
    $conn = new mysqli($servername, $username, $password, $dbname);
    
    // 检查连接
    if ($conn->connect_error) {
        die("连接失败: " . $conn->connect_error);
    }
    
    // 准备SQL语句
    $sql = "INSERT INTO messages (name, company, phone, email, message, submit_time)
    VALUES (?, ?, ?, ?, ?, NOW())";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $name, $company, $phone, $email, $message);
    
    // 执行并返回结果
    if ($stmt->execute()) {
        echo "<script>alert('留言提交成功！'); window.location.href='/contact.html';</script>";
    } else {
        echo "错误: " . $sql . "<br>" . $conn->error;
    }
    
    $stmt->close();
    $conn->close();
}
?>