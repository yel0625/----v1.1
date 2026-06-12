<?php
require_once __DIR__ . '/includes/bootstrap.php';

$selectedCategory = isset($_GET['category']) ? trim((string) $_GET['category']) : '';
$search = isset($_GET['q']) ? trim((string) $_GET['q']) : '';
$filters = [
    'category' => $selectedCategory,
    'search' => $search,
];
$articles = qilin_fetch_articles($filters);
$categories = qilin_config('article_categories');
$layoutOptions = [
    'active' => 'information',
];
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>行业资料 - <?php echo e(qilin_config('brand_name')); ?></title>
    <meta name="description" content="查看甘肃骐霖智能装备有限公司整理的技术文章、行业动态与政策法规资料。">
    <link rel="stylesheet" href="styles/main.css">
    <link rel="stylesheet" href="styles/information.css">
</head>
<body>
    <?php include __DIR__ . '/includes/header.php'; ?>

    <main>
        <section class="page-banner">
            <h1>行业资料</h1>
            <p>集中查看技术文章、行业动态与政策法规，便于客户快速了解项目背景和设备应用。</p>
        </section>

        <section class="info-detail">
            <div class="container">
                <div class="category-nav">
                    <a href="information.php"<?php echo $selectedCategory === '' ? ' class="active"' : ''; ?>>全部</a>
                    <?php foreach ($categories as $code => $label): ?>
                        <a href="information.php?category=<?php echo e($code); ?>"<?php echo $selectedCategory === $code ? ' class="active"' : ''; ?>>
                            <?php echo e($label); ?>
                        </a>
                    <?php endforeach; ?>
                </div>

                <form class="search-box" method="get" action="information.php">
                    <?php if ($selectedCategory !== ''): ?>
                        <input type="hidden" name="category" value="<?php echo e($selectedCategory); ?>">
                    <?php endif; ?>
                    <input type="text" name="q" value="<?php echo e($search); ?>" placeholder="搜索标题或摘要关键词">
                    <button type="submit">搜索</button>
                </form>

                <div class="info-list-detailed">
                    <?php if (!$articles): ?>
                        <div class="info-item">
                            <h3>暂无匹配内容</h3>
                            <p class="summary">可以调整分类或关键词，或者稍后再查看更新内容。</p>
                        </div>
                    <?php endif; ?>

                    <?php foreach ($articles as $article): ?>
                        <article class="info-item">
                            <h3>
                                <a href="article-detail.php?id=<?php echo (int) $article['id']; ?>">
                                    <?php echo e($article['title']); ?>
                                </a>
                            </h3>
                            <div class="info-meta">
                                <span class="category"><?php echo e(qilin_article_category_label($article['category'])); ?></span>
                                <span class="date"><?php echo e($article['publish_date']); ?></span>
                            </div>
                            <p class="summary"><?php echo e($article['summary'] ?? ''); ?></p>
                            <a class="read-more" href="article-detail.php?id=<?php echo (int) $article['id']; ?>">阅读全文</a>
                        </article>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
    </main>

    <?php include __DIR__ . '/includes/footer.php'; ?>

    <script src="js/main.js"></script>
</body>
</html>
