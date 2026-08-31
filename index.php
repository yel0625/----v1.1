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
    <script type="application/ld+json"><?php echo json_encode([
        '@context' => 'https://schema.org', '@type' => 'Organization',
        'name' => qilin_config('site_name'), 'url' => qilin_config('site_url'),
        'logo' => qilin_config('site_url') . '/images/logo.png',
        'email' => qilin_config('contact.primary_email'), 'telephone' => qilin_config('contact.sales_phone'),
        'address' => ['@type' => 'PostalAddress', 'streetAddress' => qilin_config('contact.address'), 'addressCountry' => 'CN'],
    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?></script>
</head>
<body>
    <?php include __DIR__ . '/includes/header.php'; ?>

    <main>
        <section class="home-hero">
            <div class="container home-hero-grid">
                <div class="home-hero-copy">
                    <span class="eyebrow">胶囊生产设备 · 精密制造</span>
                    <h1>药用硬胶囊生产线与精密零部件制造商</h1>
                    <p>提供胶囊成型、干燥、抛光、分选设备及完整生产线方案，同时承接 CNC 精密机加工项目。</p>
                    <div class="hero-actions">
                        <a href="contact.php" class="cta-button">获取生产线方案</a>
                        <a href="products.php" class="button-secondary">查看设备参数</a>
                    </div>
                    <ul class="hero-proof" aria-label="服务能力">
                        <li>方案沟通</li><li>设备制造</li><li>安装指导</li><li>售后与备件</li>
                    </ul>
                </div>
                <div class="home-hero-media">
                    <img src="images/equipment-line.jpg" alt="硬胶囊自动化生产设备">
                    <div class="hero-media-note"><strong>完整生产线方案</strong><span>根据产能、规格与现场条件进行配置沟通</span></div>
                </div>
            </div>
        </section>

        <section class="home-products section-pad">
            <div class="container">
                <div class="section-heading">
                    <span class="eyebrow">核心产品</span>
                    <h2>从单机设备到完整生产线</h2>
                    <p>围绕生产目标选择设备与配套组件，技术参数以项目确认方案为准。</p>
                </div>
                <div class="featured-product-grid">
                    <?php foreach (qilin_config('products', []) as $category): ?>
                        <?php foreach ($category['items'] as $item): ?>
                            <article class="featured-product-card">
                                <a class="featured-product-image" href="product-detail.php?slug=<?php echo e($item['slug']); ?>">
                                    <img src="<?php echo e($item['image']); ?>" alt="<?php echo e($item['alt']); ?>" loading="lazy">
                                </a>
                                <div class="featured-product-body">
                                    <span class="product-type"><?php echo e($category['title']); ?></span>
                                    <h3><a href="product-detail.php?slug=<?php echo e($item['slug']); ?>"><?php echo e($item['name']); ?></a></h3>
                                    <p><?php echo e($item['summary']); ?></p>
                                    <a class="text-link" href="product-detail.php?slug=<?php echo e($item['slug']); ?>">查看详情 →</a>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>

        <section class="company-intro section-pad">
            <div class="container">
                <div class="section-heading align-left"><span class="eyebrow">关于骐霖</span><h2><?php echo e($homepage['company_intro']['title']); ?></h2></div>
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

        <section class="advantages section-pad">
            <div class="container">
                <div class="section-heading"><span class="eyebrow">制造与服务</span><h2>围绕项目交付建立完整支持</h2><p>从方案沟通到设备调试及备件支持，为客户提供持续服务。</p></div>
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

        <section class="machining-section section-pad">
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

        <section class="service-process section-pad">
            <div class="container">
                <div class="section-heading"><span class="eyebrow">合作流程</span><h2>让设备项目的每一步更清楚</h2></div>
                <ol class="process-grid">
                    <li><span>01</span><h3>需求沟通</h3><p>确认产能、规格、现场条件及交付目标。</p></li>
                    <li><span>02</span><h3>方案确认</h3><p>沟通设备配置、接口、周期及服务范围。</p></li>
                    <li><span>03</span><h3>制造与调试</h3><p>按确认方案生产，并完成装配和运行检查。</p></li>
                    <li><span>04</span><h3>交付与支持</h3><p>提供安装指导、培训、售后与备件支持。</p></li>
                </ol>
            </div>
        </section>

        <section class="home-inquiry">
            <div class="container home-inquiry-inner">
                <div><span class="eyebrow">开始沟通</span><h2>告诉我们您的产能与设备需求</h2><p>提交目标产能、胶囊规格或加工图纸信息，我们将结合项目情况与您联系。</p></div>
                <a href="contact.php" class="cta-button cta-light">获取方案与报价</a>
            </div>
        </section>
    </main>

    <?php include __DIR__ . '/includes/footer.php'; ?>

    <script src="js/main.js"></script>
</body>
</html>
