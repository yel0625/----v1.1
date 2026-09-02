<?php
require_once __DIR__ . '/includes/bootstrap.php';

$layoutOptions = [
    'active' => 'products',
];
$productCategories = qilin_config('products', []);
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>产品介绍 - <?php echo e(qilin_config('site_name')); ?> | 药用硬胶囊生产线与精密机加工设备</title>
    <meta name="description" content="查看甘肃骐霖智能装备有限公司的药用硬胶囊生产线、配套零部件和精密机加工能力。">
    <meta name="robots" content="index,follow,max-image-preview:large,max-snippet:-1">
    <meta property="og:title" content="产品介绍 - <?php echo e(qilin_config('site_name')); ?>">
    <meta property="og:description" content="专业研发生产药用硬胶囊生产线、配套零部件及精密机加工设备。">
    <meta property="og:image" content="<?php echo e(qilin_config('site_url')); ?>/images/product1.jpg">
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?php echo e(qilin_config('site_url')); ?>/products.php">
    <link rel="canonical" href="<?php echo e(qilin_config('site_url')); ?>/products.php">
    <link rel="alternate" hreflang="zh-CN" href="<?php echo e(qilin_config('site_url')); ?>/products.php">
    <link rel="alternate" hreflang="en" href="<?php echo e(qilin_config('site_url')); ?>/en/products.html">
    <link rel="alternate" hreflang="ru" href="<?php echo e(qilin_config('site_url')); ?>/ru/products.html">
    <link rel="alternate" hreflang="x-default" href="<?php echo e(qilin_config('site_url')); ?>/en/products.html">
    <link rel="stylesheet" href="styles/main.css">
    <link rel="stylesheet" href="styles/products.css">
    <script type="application/ld+json"><?php
        $position = 0;
        $productList = [];
        foreach ($productCategories as $category) {
            foreach ($category['items'] as $item) {
                $productList[] = ['@type' => 'ListItem', 'position' => ++$position, 'url' => qilin_config('site_url') . '/product-detail.php?slug=' . rawurlencode($item['slug']) . '&lang=zh', 'name' => $item['name']];
            }
        }
        echo json_encode(['@context' => 'https://schema.org', '@type' => 'CollectionPage', 'name' => '药用硬胶囊生产设备产品目录', 'url' => qilin_config('site_url') . '/products.php', 'mainEntity' => ['@type' => 'ItemList', 'numberOfItems' => count($productList), 'itemListElement' => $productList]], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    ?></script>
</head>
<body>
    <?php include __DIR__ . '/includes/header.php'; ?>

    <main>
        <section class="page-banner">
            <h1>产品介绍</h1>
            <p>专业研发生产药用硬胶囊生产线及精密机加工设备</p>
        </section>

        <section class="products-showcase">
            <div class="container">
                <?php foreach ($productCategories as $category): ?>
                    <div class="product-category">
                        <h2><?php echo e($category['title']); ?></h2>
                        <div class="product-grid">
                            <?php foreach ($category['items'] as $item): ?>
                                <div class="product-card">
                                    <div class="product-image">
                                        <img src="<?php echo e($item['image']); ?>" alt="<?php echo e($item['alt']); ?>">
                                    </div>
                                    <div class="product-info">
                                        <h3><?php echo e($item['name']); ?></h3>
                                        <p><?php echo e($item['summary']); ?></p>
                                        <ul class="features">
                                            <?php foreach ($item['features'] as $feature): ?>
                                                <li><?php echo e($feature); ?></li>
                                            <?php endforeach; ?>
                                        </ul>
                                        <div class="product-card-actions">
                                            <a href="product-detail.php?slug=<?php echo e($item['slug']); ?>" class="btn-detail">查看详情</a>
                                            <a href="contact.php?product=<?php echo e($item['slug']); ?>" class="text-link">咨询方案</a>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
    </main>

    <?php include __DIR__ . '/includes/footer.php'; ?>

    <script src="js/main.js"></script>
</body>
</html>
