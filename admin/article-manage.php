<?php
require_once dirname(__DIR__) . '/includes/bootstrap.php';

qilin_require_admin();

$categories = (array) qilin_config('article_categories', []);
$defaultCategory = array_key_first($categories) ?: 'tech';
$status = trim((string) ($_GET['status'] ?? ''));
$editId = isset($_GET['edit']) ? (int) $_GET['edit'] : 0;
$errors = [];

$formData = [
    'id' => 0,
    'title' => '',
    'category' => $defaultCategory,
    'publish_date' => date('Y-m-d'),
    'thumbnail' => '',
    'summary' => '',
    'content' => '',
];

if ($editId > 0) {
    $editingArticle = qilin_fetch_article_by_id($editId);

    if ($editingArticle) {
        $formData = array_merge($formData, $editingArticle);
    } else {
        $errors[] = '未找到要编辑的文章，已切换为新增模式。';
        $editId = 0;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!qilin_validate_csrf()) {
        $errors[] = '表单已过期，请刷新页面后重试。';
    } else {
        $action = trim((string) ($_POST['action'] ?? 'save'));

        if ($action === 'delete') {
            $deleteId = (int) ($_POST['id'] ?? 0);

            if ($deleteId <= 0) {
                $errors[] = '缺少可删除的文章 ID。';
            } elseif (!qilin_delete_article($deleteId)) {
                $errors[] = '删除失败，请检查服务器写入权限或数据库配置。';
            } else {
                header('Location: article-manage.php?status=deleted');
                exit;
            }
        } else {
            $submittedCategory = trim((string) ($_POST['category'] ?? $defaultCategory));
            $formData = [
                'id' => (int) ($_POST['id'] ?? 0),
                'title' => trim((string) ($_POST['title'] ?? '')),
                'category' => array_key_exists($submittedCategory, $categories) ? $submittedCategory : $defaultCategory,
                'publish_date' => trim((string) ($_POST['publish_date'] ?? date('Y-m-d'))),
                'thumbnail' => trim((string) ($_POST['thumbnail'] ?? '')),
                'summary' => trim((string) ($_POST['summary'] ?? '')),
                'content' => trim((string) ($_POST['content'] ?? '')),
            ];

            if ($formData['title'] === '') {
                $errors[] = '请输入文章标题。';
            }

            if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $formData['publish_date'])) {
                $errors[] = '发布日期格式应为 YYYY-MM-DD。';
            }

            if ($formData['summary'] === '') {
                $errors[] = '请输入文章摘要，方便前台列表展示。';
            }

            if ($formData['content'] === '') {
                $errors[] = '请输入文章正文。';
            }

            if (!$errors) {
                $savedArticle = qilin_save_article($formData);

                if (!$savedArticle) {
                    $errors[] = '保存失败，请检查服务器写入权限或数据库配置。';
                } else {
                    header('Location: article-manage.php?status=saved&edit=' . (int) $savedArticle['id']);
                    exit;
                }
            }
        }
    }
}

$articles = qilin_fetch_articles();
$statusMessages = [
    'saved' => '文章已保存，前台资料页会立即读取最新内容。',
    'deleted' => '文章已删除。',
];
$statusMessage = $statusMessages[$status] ?? '';
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>文章管理 - <?php echo e(qilin_config('brand_name')); ?></title>
    <link rel="stylesheet" href="../styles/main.css">
    <style>
        body { background: #f4f7fb; }
        .admin-page { max-width: 1200px; margin: 48px auto; padding: 0 20px 40px; }
        .admin-grid { display: grid; grid-template-columns: minmax(320px, 420px) minmax(0, 1fr); gap: 24px; align-items: start; }
        .admin-card { background: #fff; border-radius: 18px; box-shadow: 0 16px 40px rgba(15, 45, 96, 0.08); padding: 28px; }
        .admin-head { display: flex; justify-content: space-between; align-items: center; gap: 16px; margin-bottom: 18px; }
        .admin-head h1, .admin-card h2 { margin: 0; color: #1e4b94; }
        .admin-note { color: #5b6473; margin: 0 0 18px; line-height: 1.7; }
        .admin-actions { display: flex; gap: 12px; flex-wrap: wrap; }
        .admin-status, .admin-error { border-radius: 12px; padding: 14px 16px; margin-bottom: 16px; }
        .admin-status { background: #ebf7ee; color: #1f6a36; }
        .admin-error { background: #fff1f1; color: #b42318; }
        .admin-error p { margin: 0; }
        .admin-form label { display: block; color: #1e4b94; font-weight: 600; margin: 0 0 8px; }
        .admin-form input, .admin-form select, .admin-form textarea { width: 100%; border: 1px solid #d6dfeb; border-radius: 10px; padding: 12px 14px; font: inherit; }
        .admin-form textarea { min-height: 120px; resize: vertical; }
        .admin-form .field { margin-bottom: 16px; }
        .admin-form .field-help { margin-top: 8px; color: #687385; font-size: 13px; line-height: 1.6; }
        .admin-form .button-row { display: flex; gap: 12px; flex-wrap: wrap; margin-top: 8px; }
        .secondary-button, .danger-button, .ghost-button { display: inline-flex; align-items: center; justify-content: center; padding: 11px 18px; border-radius: 10px; text-decoration: none; font-weight: 600; }
        .secondary-button { background: #edf3ff; color: #1e4b94; }
        .ghost-button { border: 1px solid #d6dfeb; color: #39516f; background: #fff; }
        .danger-button { border: 0; background: #c93c37; color: #fff; cursor: pointer; }
        .admin-table { width: 100%; border-collapse: collapse; }
        .admin-table th, .admin-table td { text-align: left; padding: 14px 10px; border-bottom: 1px solid #edf1f7; vertical-align: top; }
        .admin-table th { color: #1e4b94; font-size: 14px; }
        .admin-table td { color: #31445d; }
        .admin-badge { display: inline-block; padding: 4px 10px; border-radius: 999px; background: #e8f0ff; color: #1e4b94; font-size: 12px; }
        .table-title { font-weight: 600; margin-bottom: 6px; }
        .table-summary { color: #6a7485; font-size: 13px; line-height: 1.6; max-width: 380px; }
        .table-actions { display: flex; gap: 10px; flex-wrap: wrap; }
        .table-actions a, .table-actions button { font: inherit; }
        .table-actions form { margin: 0; }
        @media (max-width: 960px) {
            .admin-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <div class="admin-page">
        <div class="admin-head">
            <div>
                <h1>文章管理</h1>
                <p class="admin-note">现在已经支持新增、编辑、删除，并且在没有数据库时会自动写入服务器上的 JSON 文件作为兜底存储。</p>
            </div>
            <div class="admin-actions">
                <a class="secondary-button" href="../information.php">查看前台资料页</a>
                <a class="ghost-button" href="article-manage.php">新建文章</a>
            </div>
        </div>

        <?php if ($statusMessage !== ''): ?>
            <div class="admin-status"><?php echo e($statusMessage); ?></div>
        <?php endif; ?>

        <?php if ($errors): ?>
            <div class="admin-error">
                <?php foreach ($errors as $error): ?>
                    <p><?php echo e($error); ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <div class="admin-grid">
            <section class="admin-card">
                <h2><?php echo (int) ($formData['id'] ?? 0) > 0 ? '编辑文章' : '新增文章'; ?></h2>
                <p class="admin-note">正文支持普通文本及有限的安全排版标签（段落、标题、列表、粗体和斜体）；危险标签和属性会自动移除。</p>

                <form class="admin-form" method="post">
                    <?php echo qilin_csrf_input(); ?>
                    <input type="hidden" name="action" value="save">
                    <input type="hidden" name="id" value="<?php echo (int) ($formData['id'] ?? 0); ?>">

                    <div class="field">
                        <label for="title">文章标题</label>
                        <input id="title" name="title" type="text" value="<?php echo e($formData['title'] ?? ''); ?>" required>
                    </div>

                    <div class="field">
                        <label for="category">文章分类</label>
                        <select id="category" name="category">
                            <?php foreach ($categories as $code => $label): ?>
                                <option value="<?php echo e($code); ?>"<?php echo ($formData['category'] ?? '') === $code ? ' selected' : ''; ?>>
                                    <?php echo e($label); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="field">
                        <label for="publish_date">发布日期</label>
                        <input id="publish_date" name="publish_date" type="date" value="<?php echo e($formData['publish_date'] ?? date('Y-m-d')); ?>" required>
                    </div>

                    <div class="field">
                        <label for="thumbnail">缩略图地址</label>
                        <input id="thumbnail" name="thumbnail" type="text" value="<?php echo e($formData['thumbnail'] ?? ''); ?>" placeholder="例如 images/product1.jpg">
                        <div class="field-help">可填写站内图片路径，前台后续可以继续扩展为上传功能。</div>
                    </div>

                    <div class="field">
                        <label for="summary">文章摘要</label>
                        <textarea id="summary" name="summary" required><?php echo e($formData['summary'] ?? ''); ?></textarea>
                    </div>

                    <div class="field">
                        <label for="content">文章正文</label>
                        <textarea id="content" name="content" style="min-height: 220px;" required><?php echo e($formData['content'] ?? ''); ?></textarea>
                    </div>

                    <div class="button-row">
                        <button class="cta-button" type="submit">保存文章</button>
                        <?php if ((int) ($formData['id'] ?? 0) > 0): ?>
                            <a class="secondary-button" href="../article-detail.php?id=<?php echo (int) $formData['id']; ?>" target="_blank" rel="noopener noreferrer">预览前台详情</a>
                        <?php endif; ?>
                    </div>
                </form>
            </section>

            <section class="admin-card">
                <h2>现有文章</h2>
                <p class="admin-note">列表页和详情页都已经走统一数据源。你在这里保存后，前台 `行业资料` 会读取同一份内容。</p>

                <?php if (!$articles): ?>
                    <p class="admin-note">当前还没有文章，先在左侧创建第一篇内容即可。</p>
                <?php else: ?>
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>标题</th>
                                <th>分类</th>
                                <th>发布日期</th>
                                <th>操作</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($articles as $article): ?>
                                <tr>
                                    <td><?php echo (int) $article['id']; ?></td>
                                    <td>
                                        <div class="table-title"><?php echo e($article['title']); ?></div>
                                        <div class="table-summary"><?php echo e($article['summary'] ?? ''); ?></div>
                                    </td>
                                    <td><span class="admin-badge"><?php echo e(qilin_article_category_label($article['category'])); ?></span></td>
                                    <td><?php echo e($article['publish_date']); ?></td>
                                    <td>
                                        <div class="table-actions">
                                            <a class="secondary-button" href="article-manage.php?edit=<?php echo (int) $article['id']; ?>">编辑</a>
                                            <a class="ghost-button" href="../article-detail.php?id=<?php echo (int) $article['id']; ?>" target="_blank" rel="noopener noreferrer">预览</a>
                                            <form method="post" onsubmit="return confirm('确定删除这篇文章吗？');">
                                                <?php echo qilin_csrf_input(); ?>
                                                <input type="hidden" name="action" value="delete">
                                                <input type="hidden" name="id" value="<?php echo (int) $article['id']; ?>">
                                                <button class="danger-button" type="submit">删除</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </section>
        </div>
    </div>
</body>
</html>
