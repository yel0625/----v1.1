<?php
require_once __DIR__ . '/includes/bootstrap.php';

$layoutOptions = [
    'active' => 'contact',
];
$contact = qilin_config('contact');
$selectedProduct = trim((string) ($_GET['product'] ?? ''));
$selectedProductData = $selectedProduct !== '' ? qilin_find_product($selectedProduct) : null;
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>联系我们 - <?php echo e(qilin_config('site_name')); ?> | 药用硬胶囊生产线与精密CNC机加工设备制造商</title>
    <meta name="description" content="联系甘肃骐霖智能装备有限公司，获取药用硬胶囊生产线、胶囊抛光分选设备及CNC精密机加工服务的详细信息与报价。">
    <meta property="og:title" content="联系我们 - <?php echo e(qilin_config('site_name')); ?>">
    <meta property="og:description" content="欢迎联系我们获取药用硬胶囊生产线、配套设备及精密机加工服务信息。">
    <meta property="og:image" content="<?php echo e(qilin_config('site_url')); ?>/images/company/exterior.jpg">
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?php echo e(qilin_config('site_url')); ?>/contact.php">
    <link rel="canonical" href="<?php echo e(qilin_config('site_url')); ?>/contact.php">
    <link rel="stylesheet" href="styles/main.css">
    <link rel="stylesheet" href="styles/contact.css">
</head>
<body>
    <?php include __DIR__ . '/includes/header.php'; ?>

    <main>
        <section class="page-banner">
            <h1>联系我们</h1>
            <p>欢迎咨询药用硬胶囊生产线、配套设备与精密机加工服务</p>
        </section>

        <section class="contact-info-section">
            <div class="container">
                <div class="contact-grid">
                    <div class="contact-card">
                        <div class="icon">
                            <img src="images/icons/location.png" alt="地址">
                        </div>
                        <h3>公司地址</h3>
                        <p><?php echo e($contact['address']); ?></p>
                    </div>
                    <div class="contact-card">
                        <div class="icon">
                            <img src="images/icons/phone.png" alt="电话">
                        </div>
                        <h3>联系电话</h3>
                        <p>销售热线：<?php echo e($contact['sales_phone']); ?></p>
                        <p>服务热线：<?php echo e($contact['service_phone']); ?></p>
                    </div>
                    <div class="contact-card">
                        <div class="icon">
                            <img src="images/icons/email.png" alt="邮箱">
                        </div>
                        <h3>电子邮箱</h3>
                        <p>商务邮箱：<?php echo e($contact['primary_email']); ?></p>
                        <p>备用邮箱：<?php echo e($contact['secondary_email']); ?></p>
                    </div>
                </div>
            </div>
        </section>

        <section class="contact-form-section">
            <div class="container">
                <h2>在线留言</h2>
                <p class="form-intro">请填写项目需求，我们通常会尽快与您联系。公司名称为选填项。</p>
                <form class="contact-form" id="contactForm" action="api/submit-form.php" method="POST">
                    <input type="hidden" name="locale" value="zh">
                    <div class="form-trap" aria-hidden="true"><label for="website">网站</label><input type="text" id="website" name="website" tabindex="-1" autocomplete="off"></div>
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="name">姓名</label>
                            <input type="text" id="name" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="company">公司名称 <span>（选填）</span></label>
                            <input type="text" id="company" name="company">
                        </div>
                        <div class="form-group">
                            <label for="phone">联系电话 <span>（与邮箱至少填写一项）</span></label>
                            <input type="tel" id="phone" name="phone">
                        </div>
                        <div class="form-group">
                            <label for="email">电子邮箱 <span>（与电话至少填写一项）</span></label>
                            <input type="email" id="email" name="email">
                        </div>
                    </div>
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="country">国家或地区 <span>（选填）</span></label>
                            <input type="text" id="country" name="country">
                        </div>
                        <div class="form-group">
                            <label for="target_capacity">目标产能 <span>（选填）</span></label>
                            <input type="text" id="target_capacity" name="target_capacity" placeholder="例如：每日 150 万粒">
                        </div>
                    </div>
                    <div class="form-group full-width">
                        <label for="message">留言内容</label>
                        <textarea id="message" name="message" rows="6" required placeholder="请说明胶囊规格、设备需求、现场条件或加工要求"><?php echo $selectedProductData ? e('咨询产品：' . $selectedProductData['name'] . "\n") : ''; ?></textarea>
                    </div>
                    <p class="privacy-note">提交即表示您同意我们仅将所填信息用于本次业务联系。请勿提交与项目无关的敏感个人信息。</p>
                    <button type="submit" class="submit-btn">提交留言</button>
                </form>
            </div>
        </section>
    </main>

    <?php include __DIR__ . '/includes/footer.php'; ?>

    <script src="js/main.js"></script>
    <script src="js/contact.js"></script>
</body>
</html>
