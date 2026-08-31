# 甘肃骐霖智能装备网站 v1.1

## 运行环境

- PHP 8.0 或更高版本
- PHP 扩展：mysqli、mbstring、fileinfo
- MySQL 5.7+/MariaDB 10.3+（可选；文章会在数据库不可用时回退到 JSON 文件）
- Web 根目录应指向本项目目录

本地启动：

```bash
php -S 127.0.0.1:8080
```

打开 `http://127.0.0.1:8080/`。

## 环境变量

数据库配置：

```text
QILIN_DB_HOST=127.0.0.1
QILIN_DB_PORT=3306
QILIN_DB_USER=qilin_user
QILIN_DB_PASSWORD=replace-with-a-strong-password
QILIN_DB_NAME=qilin_cms
```

后台账号必须通过环境变量配置，项目不再提供源码内默认密码：

```text
QILIN_ADMIN_USERNAME=admin
QILIN_ADMIN_PASSWORD_HASH=<password_hash 生成的哈希>
```

生成密码哈希：

```bash
php -r "echo password_hash('请替换为强密码', PASSWORD_DEFAULT), PHP_EOL;"
```

数据库表结构位于 `sql/articles.sql` 和 `qilin_contact/qilin_contact.sql`。

## 可写目录

- `storage/`：无数据库时的文章 JSON 存储
- `images/uploads/`：后台图片上传
- 项目同级的 `qilin-private-storage/`：数据库不可用时的联系表单日志

生产环境请限制这些目录的访问权限，并使用 HTTPS。

