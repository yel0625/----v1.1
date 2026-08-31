<?php

function qilin_render_header(array $options = []): void
{
    $active = $options['active'] ?? '';
    $title = $options['title'] ?? qilin_config('brand_name');
    $basePath = $options['base_path'] ?? '';
    $nav = qilin_config('nav');
    ?>
<header>
    <div class="language-switcher">
        <div class="container">
            <div class="language-links">
                <span class="language-btn active">中文</span>
                <a class="language-btn" href="<?php echo e(qilin_url('en/index.html', $basePath)); ?>">EN</a>
                <a class="language-btn" href="<?php echo e(qilin_url('ru/index.html', $basePath)); ?>">RU</a>
            </div>
        </div>
    </div>
    <nav class="main-nav">
        <div class="container">
            <div class="nav-content">
                <a class="logo" href="<?php echo e(qilin_url('index.php', $basePath)); ?>" aria-label="返回首页">
                    <img src="<?php echo e(qilin_url('images/logo.png', $basePath)); ?>" alt="<?php echo e($title); ?> logo">
                    <span class="logo-text"><?php echo e(qilin_config('brand_name')); ?></span>
                </a>
                <ul class="nav-links">
                    <?php foreach ($nav as $key => $item): ?>
                        <li>
                            <a href="<?php echo e(qilin_url($item['href'], $basePath)); ?>"<?php echo $active === $key ? ' class="active"' : ''; ?>>
                                <?php echo e($item['label']); ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <a class="nav-quote" href="<?php echo e(qilin_url('contact.php', $basePath)); ?>">获取方案</a>
                <div class="mobile-menu">
                    <span></span><span></span><span></span>
                </div>
            </div>
        </div>
    </nav>
    <div class="mobile-language-switcher">
        <span class="language-btn active">中文</span>
        <a class="language-btn" href="<?php echo e(qilin_url('en/index.html', $basePath)); ?>">EN</a>
        <a class="language-btn" href="<?php echo e(qilin_url('ru/index.html', $basePath)); ?>">RU</a>
    </div>
</header>
    <?php
}

function qilin_render_footer(array $options = []): void
{
    $basePath = $options['base_path'] ?? '';
    $contact = qilin_config('contact');
    ?>
<footer>
    <div class="footer-content">
        <div class="footer-brand">
            <h3><?php echo e(qilin_config('brand_name')); ?></h3>
            <p>药用硬胶囊生产线、配套设备与精密机加工服务。</p>
            <a href="<?php echo e(qilin_url('products.php', $basePath)); ?>">查看产品</a>
        </div>
        <div class="contact-info">
            <h3>联系我们</h3>
            <p>地址：<?php echo e($contact['address']); ?></p>
            <p>电话：<?php echo e($contact['sales_phone']); ?></p>
            <p>邮箱：<?php echo e($contact['primary_email']); ?></p>
        </div>
        <div class="footer-links">
            <h3>快速入口</h3>
            <a href="<?php echo e(qilin_url('about.html', $basePath)); ?>">公司介绍</a>
            <a href="<?php echo e(qilin_url('technology.html', $basePath)); ?>">技术实力</a>
            <a href="<?php echo e(qilin_url('information.php', $basePath)); ?>">行业资料</a>
            <a href="<?php echo e(qilin_url('contact.php', $basePath)); ?>">提交询盘</a>
        </div>
    </div>
    <div class="copyright">
        <p>© <?php echo e(qilin_now_year()); ?> <?php echo e(qilin_config('site_name')); ?> 版权所有</p>
    </div>
</footer>
<div class="mobile-contact-dock" aria-label="快捷联系">
    <a href="tel:<?php echo e($contact['sales_phone']); ?>">电话咨询</a>
    <a href="<?php echo e(qilin_url('contact.php', $basePath)); ?>">获取报价</a>
</div>
    <?php
}
