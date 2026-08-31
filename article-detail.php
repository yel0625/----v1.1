<?php
require_once __DIR__ . '/includes/bootstrap.php';

$articleId = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$article = $articleId > 0 ? qilin_fetch_article_by_id($articleId) : null;
if (!$article) {
    http_response_code(404);
}
$articleDescription = $article
    ? trim((string) ($article['summary'] ?? ''))
    : '未找到对应的行业资料文章，请返回资料列表继续浏览。';
$layoutOptions = [
    'active' => 'information',
];
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e($article['title'] ?? '文章详情'); ?> - <?php echo e(qilin_config('brand_name')); ?></title>
    <meta name="description" content="<?php echo e($articleDescription); ?>">
    <?php if (!$article): ?><meta name="robots" content="noindex,follow"><?php endif; ?>
    <link rel="stylesheet" href="styles/main.css">
    <link rel="stylesheet" href="styles/information.css">
</head>
<body>
    <?php include __DIR__ . '/includes/header.php'; ?>

    <main>
        <section class="page-banner">
            <h1><?php echo e($article['title'] ?? '文章不存在'); ?></h1>
            <p>查看行业资料详情与设备应用背景说明</p>
        </section>

        <section class="article-content">
            <div class="container">
                <div class="standard-detail">
                    <?php if (!$article): ?>
                        <div class="article-section">
                            <h2>未找到对应文章</h2>
                            <p>当前文章可能已下线，或链接参数有误。你可以返回行业资料页查看其它内容。</p>
                            <p><a class="read-more" href="information.php">返回行业资料</a></p>
                        </div>
                    <?php else: ?>
                        <div class="article-meta">
                            <span><?php echo e(qilin_article_category_label($article['category'])); ?></span>
                            <span><?php echo e($article['publish_date']); ?></span>
                        </div>
                        <div class="article-section">
                            <h2><?php echo e($article['title']); ?></h2>
                            <p class="summary"><?php echo e($article['summary'] ?? ''); ?></p>
                            <?php echo qilin_render_article_content($article['content'] ?? ''); ?>
                        </div>
                        <p><a class="read-more" href="information.php">返回资料列表</a></p>
                    <?php endif; ?>
                </div>
            </div>
        </section>
    </main>

    <?php include __DIR__ . '/includes/footer.php'; ?>

    <script src="js/main.js"></script>
</body>
</html>
