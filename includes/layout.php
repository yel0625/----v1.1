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
                <div class="logo">
                    <img src="<?php echo e(qilin_url('images/logo.png', $basePath)); ?>" alt="<?php echo e($title); ?> logo">
                    <span class="logo-text"><?php echo e(qilin_config('brand_name')); ?></span>
                </div>
                <ul class="nav-links">
                    <?php foreach ($nav as $key => $item): ?>
                        <li>
                            <a href="<?php echo e(qilin_url($item['href'], $basePath)); ?>"<?php echo $active === $key ? ' class="active"' : ''; ?>>
                                <?php echo e($item['label']); ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
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
        <div class="contact-info">
            <h3>联系我们</h3>
            <p>地址：<?php echo e($contact['address']); ?></p>
            <p>电话：<?php echo e($contact['sales_phone']); ?></p>
            <p>邮箱：<?php echo e($contact['primary_email']); ?></p>
        </div>
    </div>
    <div class="copyright">
        <p>© <?php echo e(qilin_now_year()); ?> <?php echo e(qilin_config('site_name')); ?> 版权所有</p>
    </div>
</footer>
    <?php
}
