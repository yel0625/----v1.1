<?php 
session_start();
if (!isset($_SESSION['admin_logged'])) {
    header('Location: login.php');
}
include '../includes/db.php';

// 处理CRUD操作
if ($_POST['action']) {
    // 实现文章创建/编辑/删除逻辑
}
?>

<!-- 包含富文本编辑器 -->
<script src="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script>

<!-- 文章管理界面HTML -->