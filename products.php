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
    <meta property="og:title" content="产品介绍 - <?php echo e(qilin_config('site_name')); ?>">
    <meta property="og:description" content="专业研发生产药用硬胶囊生产线、配套零部件及精密机加工设备。">
    <meta property="og:image" content="<?php echo e(qilin_config('site_url')); ?>/images/product1.jpg">
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?php echo e(qilin_config('site_url')); ?>/products.php">
    <link rel="canonical" href="<?php echo e(qilin_config('site_url')); ?>/products.php">
    <link rel="stylesheet" href="styles/main.css">
    <link rel="stylesheet" href="styles/products.css">
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
                                        <a href="contact.php" class="btn-detail">咨询详情</a>
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
