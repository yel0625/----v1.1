<?php
require_once __DIR__ . '/includes/bootstrap.php';

$layoutOptions = [
    'active' => 'contact',
];
$contact = qilin_config('contact');
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
                <form class="contact-form" id="contactForm" action="api/submit-form.php" method="POST">
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="name">姓名</label>
                            <input type="text" id="name" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="company">公司名称</label>
                            <input type="text" id="company" name="company" required>
                        </div>
                        <div class="form-group">
                            <label for="phone">联系电话</label>
                            <input type="tel" id="phone" name="phone" required>
                        </div>
                        <div class="form-group">
                            <label for="email">电子邮箱</label>
                            <input type="email" id="email" name="email" required>
                        </div>
                    </div>
                    <div class="form-group full-width">
                        <label for="message">留言内容</label>
                        <textarea id="message" name="message" rows="6" required></textarea>
                    </div>
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
