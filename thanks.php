<?php
$lang = in_array(($_GET['lang'] ?? 'zh'), ['zh', 'en', 'ru'], true) ? $_GET['lang'] : 'zh';
$copy = [
    'zh' => ['html' => 'zh-CN', 'title' => '提交成功', 'heading' => '感谢您的项目咨询', 'body' => '您的需求已经提交。我们会根据所填联系方式尽快与您沟通。', 'back' => '返回首页', 'href' => 'index.php'],
    'en' => ['html' => 'en', 'title' => 'Inquiry submitted', 'heading' => 'Thank you for your inquiry', 'body' => 'Your project requirements have been submitted. We will contact you using the details provided.', 'back' => 'Return home', 'href' => 'en/index.html'],
    'ru' => ['html' => 'ru', 'title' => 'Запрос отправлен', 'heading' => 'Спасибо за ваш запрос', 'body' => 'Требования к проекту отправлены. Мы свяжемся с вами по указанным контактным данным.', 'back' => 'На главную', 'href' => 'ru/index.html'],
][$lang];
?>
<!DOCTYPE html>
<html lang="<?php echo htmlspecialchars($copy['html'], ENT_QUOTES, 'UTF-8'); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex,nofollow">
    <title><?php echo htmlspecialchars($copy['title'], ENT_QUOTES, 'UTF-8'); ?> - 甘肃骐霖智能装备</title>
    <link rel="stylesheet" href="styles/main.css">
</head>
<body>
    <main class="thank-you-page">
        <section class="empty-state section-pad">
            <div class="container">
                <span class="success-mark" aria-hidden="true">✓</span>
                <span class="eyebrow"><?php echo htmlspecialchars($copy['title'], ENT_QUOTES, 'UTF-8'); ?></span>
                <h1><?php echo htmlspecialchars($copy['heading'], ENT_QUOTES, 'UTF-8'); ?></h1>
                <p><?php echo htmlspecialchars($copy['body'], ENT_QUOTES, 'UTF-8'); ?></p>
                <a class="cta-button" href="<?php echo htmlspecialchars($copy['href'], ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($copy['back'], ENT_QUOTES, 'UTF-8'); ?></a>
            </div>
        </section>
    </main>
</body>
</html>
