<?php
require_once __DIR__ . '/includes/bootstrap.php';

$layoutOptions = [
    'active' => 'home',
];
$homepage = qilin_config('homepage');
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e(qilin_config('site_name')); ?> - 药用硬胶囊生产线与精密CNC机加工设备制造商</title>
    <meta name="description" content="甘肃骐霖智能装备有限公司专业研发生产药用硬胶囊生产线、胶囊抛光机、干燥设备及自动分选设备，同时提供CNC精密机加工服务。20年行业经验，设备出口全球30多个国家。">
    <meta name="keywords" content="甘肃骐霖智能装备, 胶囊生产线, 空心胶囊设备, 硬胶囊生产机, 胶囊抛光机, 胶囊分选机, 工业制药设备, 制药机械, CNC精密机加工">
    <meta property="og:title" content="<?php echo e(qilin_config('site_name')); ?>">
    <meta property="og:description" content="专业研发生产药用硬胶囊生产线、配套设备及CNC精密机加工服务。">
    <meta property="og:image" content="<?php echo e(qilin_config('site_url')); ?>/images/capsules-orange.jpg">
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?php echo e(qilin_config('site_url')); ?>/index.php">
    <link rel="canonical" href="<?php echo e(qilin_config('site_url')); ?>/index.php">
    <link rel="stylesheet" href="styles/main.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.css">
</head>
<body>
    <?php include __DIR__ . '/includes/header.php'; ?>

    <main>
        <section class="hero-section">
            <div class="swiper-container">
                <div class="swiper-wrapper">
                    <?php foreach ($homepage['hero_slides'] as $slide): ?>
                        <div class="swiper-slide">
                            <div class="slide-content">
                                <div class="slide-image">
                                    <img src="<?php echo e($slide['image']); ?>" alt="<?php echo e($slide['alt']); ?>">
                                </div>
                                <div class="slide-text">
                                    <h1><?php echo e($slide['title']); ?></h1>
                                    <p><?php echo e($slide['description']); ?></p>
                                    <a href="<?php echo e($slide['primary_cta']['href']); ?>" class="cta-button"><?php echo e($slide['primary_cta']['label']); ?></a>
                                    <a href="<?php echo e($slide['secondary_cta']['href']); ?>" class="cta-button" style="margin-left: 10px;"><?php echo e($slide['secondary_cta']['label']); ?></a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="swiper-pagination"></div>
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
            </div>
        </section>

        <section class="company-intro">
            <div class="container">
                <h2><?php echo e($homepage['company_intro']['title']); ?></h2>
                <div class="intro-content">
                    <div class="intro-text">
                        <?php foreach ($homepage['company_intro']['paragraphs'] as $paragraph): ?>
                            <p><?php echo e($paragraph); ?></p>
                        <?php endforeach; ?>
                        <a href="<?php echo e($homepage['company_intro']['more_href']); ?>" class="btn-more">了解更多</a>
                    </div>
                    <div class="intro-image">
                        <img src="<?php echo e($homepage['company_intro']['image']); ?>" alt="<?php echo e($homepage['company_intro']['image_alt']); ?>">
                    </div>
                </div>
            </div>
        </section>

        <section class="advantages">
            <div class="container">
                <h2>核心优势</h2>
                <div class="advantage-grid">
                    <?php foreach ($homepage['advantages'] as $advantage): ?>
                        <div class="advantage-item">
                            <div class="icon"><img src="<?php echo e($advantage['icon']); ?>" alt="<?php echo e($advantage['alt']); ?>"></div>
                            <h3><?php echo e($advantage['title']); ?></h3>
                            <p><?php echo e($advantage['description']); ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>

        <section class="machining-section">
            <div class="container">
                <h2><?php echo e($homepage['machining']['title']); ?></h2>
                <p class="subtitle"><?php echo e($homepage['machining']['subtitle']); ?></p>
                <div class="machining-grid">
                    <?php foreach ($homepage['machining']['items'] as $item): ?>
                        <div class="machining-item">
                            <h3><?php echo e($item['title']); ?></h3>
                            <p><?php echo e($item['description']); ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="machining-btn-wrapper">
                    <a href="<?php echo e($homepage['machining']['more_href']); ?>" class="cta-button"><?php echo e($homepage['machining']['more_label']); ?></a>
                </div>
            </div>
        </section>
    </main>

    <?php include __DIR__ . '/includes/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.js"></script>
    <script src="js/main.js"></script>
</body>
</html>
