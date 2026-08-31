<?php
require_once __DIR__ . '/includes/bootstrap.php';

$slug = trim((string) ($_GET['slug'] ?? ''));
$lang = in_array(($_GET['lang'] ?? 'zh'), ['zh', 'en', 'ru'], true) ? $_GET['lang'] : 'zh';
$product = qilin_find_product($slug);
if (!$product) {
    http_response_code(404);
}

$translations = [
    'en' => [
        'ps-dnj34' => ['name' => 'PS-DNJ34 Automatic Capsule Production Line', 'summary' => 'Capacity: 1,400,000 capsules/day', 'category_title' => 'Pharmaceutical Capsule Production Line', 'features' => ['Fully automated control system', 'Intelligent speed adjustment', 'Automatic fault detection', 'Product traceability support'], 'capabilities' => ['Designed for continuous hard empty capsule production', 'Production-line configuration and installation guidance', 'Project-specific discussion of key component configuration'], 'parameters' => ['Reference daily output' => '1,400,000 capsules/day', 'Capsule specifications' => 'Confirm for project', 'Power supply and consumption' => 'Confirm for project', 'Dimensions and weight' => 'Confirm for project', 'Delivery and service' => 'Confirm in project proposal']],
        'ps-dnj35sn3' => ['name' => 'PS-DNJ35SN3 Automatic Capsule Production Line', 'summary' => 'Capacity: 1,500,000 capsules/day', 'category_title' => 'Pharmaceutical Capsule Production Line', 'features' => ['Modular design', 'Energy-conscious system configuration', 'Efficient changeover support', 'Intelligent operating-status monitoring'], 'capabilities' => ['Designed for higher-output continuous production', 'Modular structure supports maintenance and expansion', 'Commissioning, training, and spare-parts support'], 'parameters' => ['Reference daily output' => '1,500,000 capsules/day', 'Capsule specifications' => 'Confirm for project', 'Power supply and consumption' => 'Confirm for project', 'Dimensions and weight' => 'Confirm for project', 'Delivery and service' => 'Confirm in project proposal']],
        'capsule-mould-components' => ['name' => 'Capsule Mould Components', 'summary' => 'Precision components for capsule forming equipment', 'category_title' => 'Supporting Components', 'features' => ['Precision machining process', 'Wear-resistant construction', 'Close-tolerance manufacturing'], 'capabilities' => ['Configuration is confirmed against equipment and capsule specifications', 'Drawing review and matched manufacturing support', 'Dimensional and assembly-fit confirmation'], 'parameters' => ['Applicable equipment' => 'Confirm for project', 'Capsule specifications' => 'Confirm for project', 'Material and finish' => 'Confirm against drawings', 'Dimensions and tolerances' => 'Confirm against drawings', 'Delivery and inspection' => 'Confirm in project proposal']],
        'intelligent-control-system' => ['name' => 'Intelligent Control System', 'summary' => 'Automation and monitoring unit for production lines', 'category_title' => 'Supporting Components', 'features' => ['Stable production control', 'Support for consistent capsule forming', 'Operating and monitoring interface'], 'capabilities' => ['Production-line control and status monitoring', 'Functions are confirmed against the project configuration', 'Installation, commissioning, and user training support'], 'parameters' => ['Applicable production line' => 'Confirm for project', 'Control functions' => 'Confirm for project', 'Electrical standard' => 'Confirm for destination country', 'Interfaces and expansion' => 'Confirm for project', 'Installation and training' => 'Confirm in project proposal']],
    ],
    'ru' => [
        'ps-dnj34' => ['name' => 'Автоматическая линия PS-DNJ34', 'summary' => 'Производительность: 1 400 000 капсул/сутки', 'category_title' => 'Линия производства фармацевтических капсул', 'features' => ['Полностью автоматизированное управление', 'Интеллектуальная регулировка скорости', 'Автоматическое обнаружение неисправностей', 'Поддержка прослеживаемости продукции'], 'capabilities' => ['Для непрерывного производства твердых пустых капсул', 'Подбор конфигурации линии и рекомендации по монтажу', 'Согласование ключевых компонентов под проект']],
        'ps-dnj35sn3' => ['name' => 'Автоматическая линия PS-DNJ35SN3', 'summary' => 'Производительность: 1 500 000 капсул/сутки', 'category_title' => 'Линия производства фармацевтических капсул', 'features' => ['Модульная конструкция', 'Энергоэффективная конфигурация', 'Поддержка быстрой переналадки', 'Интеллектуальный мониторинг работы'], 'capabilities' => ['Для непрерывного производства повышенной производительности', 'Модульная структура упрощает обслуживание и расширение', 'Пусконаладка, обучение и поддержка запасными частями']],
        'capsule-mould-components' => ['name' => 'Формовочные компоненты', 'summary' => 'Точные компоненты для оборудования формования капсул', 'category_title' => 'Комплектующие', 'features' => ['Прецизионная обработка', 'Износостойкая конструкция', 'Изготовление с малыми допусками'], 'capabilities' => ['Подбор под оборудование и спецификацию капсул', 'Проверка чертежей и изготовление совместимых деталей', 'Контроль размеров и посадки при сборке']],
        'intelligent-control-system' => ['name' => 'Интеллектуальная система управления', 'summary' => 'Автоматизация и мониторинг производственной линии', 'category_title' => 'Комплектующие', 'features' => ['Стабильное управление производством', 'Поддержка стабильного формования капсул', 'Интерфейс управления и мониторинга'], 'capabilities' => ['Управление линией и мониторинг состояния', 'Функции согласуются с конфигурацией проекта', 'Поддержка монтажа, пусконаладки и обучения']],
    ],
];
if ($product && $lang !== 'zh' && isset($translations[$lang][$slug])) {
    $product = array_merge($product, $translations[$lang][$slug]);
}
if ($product && $lang === 'ru') {
    $parameterLabels = [
        '参考日产能' => 'Ориентировочная производительность', '适用胶囊规格' => 'Спецификация капсул',
        '电源与功率' => 'Питание и мощность', '设备尺寸与重量' => 'Размеры и масса',
        '交付与服务' => 'Поставка и сервис', '适用设备' => 'Применимое оборудование',
        '材料与表面要求' => 'Материал и обработка поверхности', '尺寸与公差' => 'Размеры и допуски',
        '交付与检验' => 'Поставка и контроль', '适用生产线' => 'Применимая линия',
        '控制功能' => 'Функции управления', '电气标准' => 'Электрический стандарт',
        '接口与扩展' => 'Интерфейсы и расширение', '安装与培训' => 'Монтаж и обучение',
    ];
    $parameterValues = [
        '按项目确认' => 'Уточняется по проекту', '按图纸确认' => 'Уточняется по чертежам',
        '按使用国家确认' => 'Уточняется для страны назначения', '按项目方案确认' => 'Уточняется в проектном предложении',
        '1,400,000 粒/天' => '1 400 000 капсул/сутки', '1,500,000 粒/天' => '1 500 000 капсул/сутки',
    ];
    $localizedParameters = [];
    foreach ((array) ($product['parameters'] ?? []) as $label => $value) {
        $localizedParameters[$parameterLabels[$label] ?? $label] = $parameterValues[$value] ?? $value;
    }
    $product['parameters'] = $localizedParameters;
}

$ui = [
    'zh' => ['html' => 'zh-CN', 'notFound' => '没有找到该产品', 'notFoundBody' => '产品可能已调整，请返回产品中心查看当前内容。', 'home' => '首页', 'products' => '产品中心', 'quote' => '获取项目方案', 'call' => '电话咨询', 'note' => '具体配置、接口、交付周期及参数以双方确认的项目方案为准。', 'specEyebrow' => '技术信息', 'specTitle' => '当前可公开参数', 'specNote' => '未公开的数据标记为项目确认，避免在需求明确前提供不准确参数。', 'capEyebrow' => '应用与能力', 'capTitle' => '围绕实际项目进行配置沟通', 'next' => '下一步', 'nextTitle' => '需要完整参数或配置建议？', 'nextBody' => '请提供目标产能、胶囊规格、使用国家和现场条件，便于进一步沟通。', 'submit' => '提交需求', 'contact' => 'contact.php'],
    'en' => ['html' => 'en', 'notFound' => 'Product not found', 'notFoundBody' => 'The product may have changed. Please return to the current product list.', 'home' => 'Home', 'products' => 'Products', 'quote' => 'Request a Project Solution', 'call' => 'Call Sales', 'note' => 'Final configuration, interfaces, delivery schedule, and technical parameters are subject to the agreed project proposal.', 'specEyebrow' => 'Technical Information', 'specTitle' => 'Currently Available Parameters', 'specNote' => 'Unpublished values are marked for project confirmation to avoid presenting inaccurate specifications.', 'capEyebrow' => 'Applications and Capabilities', 'capTitle' => 'Configuration Based on Real Project Requirements', 'next' => 'Next Step', 'nextTitle' => 'Need Complete Parameters or Configuration Advice?', 'nextBody' => 'Share your target output, capsule specifications, destination country, and site conditions.', 'submit' => 'Submit Requirements', 'contact' => 'en/contact.html'],
    'ru' => ['html' => 'ru', 'notFound' => 'Продукт не найден', 'notFoundBody' => 'Возможно, продукция была обновлена. Вернитесь к актуальному каталогу.', 'home' => 'Главная', 'products' => 'Продукция', 'quote' => 'Запросить решение', 'call' => 'Позвонить', 'note' => 'Окончательная конфигурация, интерфейсы, сроки и параметры определяются согласованным проектным предложением.', 'specEyebrow' => 'Техническая информация', 'specTitle' => 'Доступные параметры', 'specNote' => 'Неопубликованные значения отмечены как уточняемые по проекту, чтобы не указывать неточные данные.', 'capEyebrow' => 'Применение и возможности', 'capTitle' => 'Конфигурация под реальные требования проекта', 'next' => 'Следующий шаг', 'nextTitle' => 'Нужны полные параметры или помощь с конфигурацией?', 'nextBody' => 'Укажите требуемую производительность, спецификацию капсул, страну и условия площадки.', 'submit' => 'Отправить требования', 'contact' => 'ru/contact.html'],
][$lang];
$pageTitle = $product ? $product['name'] : $ui['notFound'];
$layoutOptions = ['active' => 'products'];
?>
<!DOCTYPE html>
<html lang="<?php echo e($ui['html']); ?>">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e($pageTitle); ?> - Gansu Qilin Intelligent Equipment</title>
    <meta name="description" content="<?php echo e($product ? $product['summary'] . '. ' . $ui['capTitle'] : $ui['notFoundBody']); ?>">
    <meta property="og:type" content="product"><meta property="og:title" content="<?php echo e($pageTitle); ?>">
    <?php if ($product): ?><meta property="og:image" content="<?php echo e(qilin_config('site_url')); ?>/<?php echo e($product['image']); ?>"><?php endif; ?>
    <link rel="canonical" href="<?php echo e(qilin_config('site_url')); ?>/product-detail.php?slug=<?php echo e($slug); ?>&amp;lang=<?php echo e($lang); ?>">
    <link rel="stylesheet" href="styles/main.css"><link rel="stylesheet" href="styles/products.css">
    <?php if ($product): ?>
    <script type="application/ld+json"><?php echo json_encode([
        '@context' => 'https://schema.org', '@type' => 'Product', 'name' => $product['name'],
        'image' => qilin_config('site_url') . '/' . $product['image'], 'description' => $product['summary'],
        'brand' => ['@type' => 'Brand', 'name' => 'Gansu Qilin Intelligent Equipment'],
        'manufacturer' => ['@type' => 'Organization', 'name' => qilin_config('site_name'), 'url' => qilin_config('site_url')],
        'url' => qilin_config('site_url') . '/product-detail.php?slug=' . rawurlencode($slug) . '&lang=' . rawurlencode($lang),
    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?></script>
    <script type="application/ld+json"><?php echo json_encode([
        '@context' => 'https://schema.org', '@type' => 'BreadcrumbList', 'itemListElement' => [
            ['@type' => 'ListItem', 'position' => 1, 'name' => $ui['home'], 'item' => qilin_config('site_url') . '/' . ($lang === 'zh' ? 'index.php' : $lang . '/index.html')],
            ['@type' => 'ListItem', 'position' => 2, 'name' => $ui['products'], 'item' => qilin_config('site_url') . '/' . ($lang === 'zh' ? 'products.php' : $lang . '/products.html')],
            ['@type' => 'ListItem', 'position' => 3, 'name' => $product['name']],
        ],
    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?></script>
    <?php endif; ?>
</head>
<body data-page="products">
    <?php if ($lang === 'zh'): include __DIR__ . '/includes/header.php'; else: ?><header data-site-header></header><?php endif; ?>
    <main>
    <?php if (!$product): ?>
        <section class="empty-state section-pad"><div class="container"><span class="eyebrow">404</span><h1><?php echo e($ui['notFound']); ?></h1><p><?php echo e($ui['notFoundBody']); ?></p><a class="cta-button" href="<?php echo e($lang === 'zh' ? 'products.php' : $lang . '/products.html'); ?>"><?php echo e($ui['products']); ?></a></div></section>
    <?php else: ?>
        <nav class="breadcrumb container" aria-label="Breadcrumb"><a href="<?php echo e($lang === 'zh' ? 'index.php' : $lang . '/index.html'); ?>"><?php echo e($ui['home']); ?></a><span>/</span><a href="<?php echo e($lang === 'zh' ? 'products.php' : $lang . '/products.html'); ?>"><?php echo e($ui['products']); ?></a><span>/</span><span><?php echo e($product['name']); ?></span></nav>
        <section class="product-detail-hero section-pad"><div class="container product-detail-grid"><div class="product-detail-image"><img src="<?php echo e($product['image']); ?>" alt="<?php echo e($product['name']); ?>"></div><div class="product-detail-copy"><span class="eyebrow"><?php echo e($product['category_title']); ?></span><h1><?php echo e($product['name']); ?></h1><p class="product-lead"><?php echo e($product['summary']); ?></p><ul class="feature-check-list"><?php foreach ($product['features'] as $feature): ?><li><?php echo e($feature); ?></li><?php endforeach; ?></ul><div class="hero-actions"><a class="cta-button" href="<?php echo e($ui['contact']); ?>?product=<?php echo e($slug); ?>"><?php echo e($ui['quote']); ?></a><a class="button-secondary" href="tel:<?php echo e(qilin_config('contact.sales_phone')); ?>"><?php echo e($ui['call']); ?></a></div><p class="parameter-note"><?php echo e($ui['note']); ?></p></div></div></section>
        <section class="product-specifications section-pad"><div class="container narrow-container"><div class="section-heading"><span class="eyebrow"><?php echo e($ui['specEyebrow']); ?></span><h2><?php echo e($ui['specTitle']); ?></h2><p><?php echo e($ui['specNote']); ?></p></div><div class="spec-table-wrap"><table class="spec-table"><tbody><?php foreach ((array) ($product['parameters'] ?? []) as $label => $value): ?><tr><th scope="row"><?php echo e($label); ?></th><td><?php echo e($value); ?></td></tr><?php endforeach; ?></tbody></table></div></div></section>
        <section class="product-capability section-pad"><div class="container narrow-container"><div class="section-heading"><span class="eyebrow"><?php echo e($ui['capEyebrow']); ?></span><h2><?php echo e($ui['capTitle']); ?></h2></div><div class="capability-list-grid"><?php foreach ($product['capabilities'] as $index => $capability): ?><article><span>0<?php echo $index + 1; ?></span><p><?php echo e($capability); ?></p></article><?php endforeach; ?></div></div></section>
        <section class="product-next-step"><div class="container home-inquiry-inner"><div><span class="eyebrow"><?php echo e($ui['next']); ?></span><h2><?php echo e($ui['nextTitle']); ?></h2><p><?php echo e($ui['nextBody']); ?></p></div><a class="cta-button cta-light" href="<?php echo e($ui['contact']); ?>?product=<?php echo e($slug); ?>"><?php echo e($ui['submit']); ?></a></div></section>
    <?php endif; ?>
    </main>
    <?php if ($lang === 'zh'): include __DIR__ . '/includes/footer.php'; else: ?><footer data-site-footer></footer><script src="js/site-shell.js"></script><?php endif; ?>
    <script src="js/main.js"></script>
</body>
</html>
