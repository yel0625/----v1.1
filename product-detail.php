<?php
require_once __DIR__ . '/includes/bootstrap.php';

// Product details change frequently during catalogue updates. Prevent stale
// markup or styles from leaving action labels invisible in long-lived tabs.
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');

$slug = trim((string) ($_GET['slug'] ?? ''));
$requestedLang = (string) ($_GET['lang'] ?? 'zh');
$lang = in_array($requestedLang, ['zh', 'en', 'ru'], true) ? $requestedLang : 'zh';
$product = qilin_find_product($slug);
if (!$product) {
    http_response_code(404);
}

$translations = [
    'en' => [
        'ql-alhcs-v2' => ['name' => 'QL-ALHCS-V2 Single-Row Pin Automatic Capsule Line', 'summary' => 'Automatic hard empty capsule production line with integrated process control', 'category_title' => 'Pharmaceutical Capsule Production Line', 'features' => ['30–32 mould bars/min with gelatin', 'Capsule sizes 00#–4#', 'Automatic oiling, dipping, drying, stripping, trimming and joining', 'Servo drives with process and fault monitoring'], 'capabilities' => ['Integrated production from pin oiling to finished-capsule output', 'Automatic gelatin temperature, viscosity and liquid-level control', 'Commissioning, training and project-specific configuration support'], 'parameters' => ['Reference speed' => '30–32 mould bars/min with gelatin', 'Capsule sizes' => '00#–4#', 'Power supply' => '380 V ±10%, 50 Hz, three-phase five-wire', 'Control voltage' => '24 V', 'Reference dimensions' => 'Approx. 28.1 × 1.75 × 2.7 m'], 'process' => ['Pin oiling', 'Gelatin dipping', 'Continuous conveying and drying', 'Stripping and trimming', 'Capsule joining', 'Finished-product output'], 'systems' => ['Automatic pin lubrication', 'Gelatin temperature and viscosity control', 'Automatic liquid-level control', 'Servo dipping and propulsion', 'Variable-frequency drying air system', 'Fault alarm and status monitoring']],
        'ql-alhcs-v4' => ['name' => 'QL-ALHCS-V4 Double-Head Automatic Capsule Line', 'summary' => 'Double-head automatic line for compact, coordinated hard capsule production', 'category_title' => 'Pharmaceutical Capsule Production Line', 'features' => ['28–30 mould bars/min with gelatin', 'Capsule sizes 00#–4#', 'Dual automatic machines and dual oiling units', 'Output counting, product identification and fault records'], 'capabilities' => ['Coordinated double-head production configuration', 'Automatic temperature, viscosity and liquid-level control', 'Production counting and operating-record support'], 'parameters' => ['Reference speed' => '28–30 mould bars/min with gelatin', 'Capsule sizes' => '00#–4#', 'Power supply' => '380 V ±10%, 50 Hz', 'Control voltage' => '24 V', 'Reference dimensions' => 'Approx. 22 × 1.8 × 2.6 m'], 'process' => ['Dual pin oiling', 'Gelatin dipping', 'Continuous drying', 'Double-head stripping and trimming', 'Capsule joining', 'Identification and output'], 'systems' => ['Coordinated dual-machine control', 'Dual oiling units', 'Gelatin temperature and level control', 'Automatic viscosity adjustment', 'Fault alarm and records', 'Output counting and product identification']],
        'ql-sahcm-v2' => ['name' => 'QL-SAHCM-V2 Semi-Automatic Capsule Line', 'summary' => 'Semi-automatic hard empty capsule production line for flexible operation', 'category_title' => 'Pharmaceutical Capsule Production Line', 'features' => ['30–32 mould bars/min with gelatin', '34–36 mould bars/min in cold running', 'Capsule sizes 00#–4#', 'Operator-assisted production and maintenance'], 'capabilities' => ['Balances line automation with operator participation', 'Suitable for flexible production arrangements', 'Installation, commissioning and operator training support'], 'parameters' => ['Reference speed' => '30–32 mould bars/min with gelatin; 34–36 cold', 'Capsule sizes' => '00#–4#', 'Power supply' => '380 V ±10%, 50 Hz', 'Control voltage' => '24 V', 'Reference dimensions' => 'Approx. 28.023 × 1.8 × 2.7 m'], 'process' => ['Process preparation', 'Pin oiling and dipping', 'Conveying and drying', 'Operator-assisted operation', 'Stripping and trimming', 'Joining and collection'], 'systems' => ['Temperature control', 'Gelatin process control', 'Line speed adjustment', 'Fault indication and safety protection', 'Drying air system', 'Operation and maintenance training']],
        'capsule-mould-components' => ['name' => 'Capsule Mould Components', 'summary' => 'Precision components for capsule forming equipment', 'category_title' => 'Supporting Components', 'features' => ['Precision machining process', 'Wear-resistant construction', 'Close-tolerance manufacturing'], 'capabilities' => ['Configuration is confirmed against equipment and capsule specifications', 'Drawing review and matched manufacturing support', 'Dimensional and assembly-fit confirmation'], 'parameters' => ['Applicable equipment' => 'Confirm for project', 'Capsule specifications' => 'Confirm for project', 'Material and finish' => 'Confirm against drawings', 'Dimensions and tolerances' => 'Confirm against drawings', 'Delivery and inspection' => 'Confirm in project proposal']],
        'intelligent-control-system' => ['name' => 'Intelligent Control System', 'summary' => 'Automation and monitoring unit for production lines', 'category_title' => 'Supporting Components', 'features' => ['Stable production control', 'Support for consistent capsule forming', 'Operating and monitoring interface'], 'capabilities' => ['Production-line control and status monitoring', 'Functions are confirmed against the project configuration', 'Installation, commissioning, and user training support'], 'parameters' => ['Applicable production line' => 'Confirm for project', 'Control functions' => 'Confirm for project', 'Electrical standard' => 'Confirm for destination country', 'Interfaces and expansion' => 'Confirm for project', 'Installation and training' => 'Confirm in project proposal']],
    ],
    'ru' => [
        'ql-alhcs-v2' => ['name' => 'Автоматическая линия QL-ALHCS-V2', 'summary' => 'Однорядная автоматическая линия для твердых пустых капсул', 'category_title' => 'Линия производства фармацевтических капсул', 'features' => ['30–32 формовочные планки/мин с желатином', 'Размеры капсул 00#–4#', 'Автоматический цикл от смазки до соединения', 'Сервоприводы и контроль неисправностей'], 'capabilities' => ['Полный цикл до выхода готовых капсул', 'Контроль температуры, вязкости и уровня желатина', 'Пусконаладка, обучение и проектная конфигурация'], 'parameters' => ['Ориентировочная скорость' => '30–32 планки/мин с желатином', 'Размеры капсул' => '00#–4#', 'Электропитание' => '380 В ±10%, 50 Гц, 3 фазы, 5 проводов', 'Напряжение управления' => '24 В', 'Ориентировочные габариты' => 'Около 28,1 × 1,75 × 2,7 м'], 'process' => ['Смазка штифтов', 'Погружение в желатин', 'Транспортировка и сушка', 'Снятие и обрезка', 'Соединение капсул', 'Выход продукции'], 'systems' => ['Автоматическая смазка штифтов', 'Контроль температуры и вязкости', 'Автоматический контроль уровня', 'Сервоприводы погружения и подачи', 'Частотное управление сушкой', 'Сигнализация и мониторинг']],
        'ql-alhcs-v4' => ['name' => 'Двухголовочная линия QL-ALHCS-V4', 'summary' => 'Компактная двухголовочная автоматическая линия для твердых капсул', 'category_title' => 'Линия производства фармацевтических капсул', 'features' => ['28–30 формовочных планок/мин с желатином', 'Размеры капсул 00#–4#', 'Две автоматические машины и два узла смазки', 'Учет выпуска, идентификация и журнал ошибок'], 'capabilities' => ['Согласованная работа двухголовочной конфигурации', 'Автоконтроль температуры, вязкости и уровня', 'Учет выпуска и рабочих записей'], 'parameters' => ['Ориентировочная скорость' => '28–30 планок/мин с желатином', 'Размеры капсул' => '00#–4#', 'Электропитание' => '380 В ±10%, 50 Гц', 'Напряжение управления' => '24 В', 'Ориентировочные габариты' => 'Около 22 × 1,8 × 2,6 м'], 'process' => ['Двойная смазка штифтов', 'Погружение в желатин', 'Непрерывная сушка', 'Снятие и обрезка двумя головками', 'Соединение капсул', 'Идентификация и выход'], 'systems' => ['Координация двух машин', 'Два узла смазки', 'Контроль температуры и уровня', 'Автоматическая регулировка вязкости', 'Сигнализация и журнал ошибок', 'Учет выпуска и идентификация']],
        'ql-sahcm-v2' => ['name' => 'Полуавтоматическая линия QL-SAHCM-V2', 'summary' => 'Полуавтоматическая линия твердых пустых капсул для гибкой эксплуатации', 'category_title' => 'Линия производства фармацевтических капсул', 'features' => ['30–32 планки/мин с желатином', '34–36 планок/мин на холостом ходу', 'Размеры капсул 00#–4#', 'Производство с участием оператора'], 'capabilities' => ['Сочетает автоматизацию и работу оператора', 'Подходит для гибкой организации производства', 'Поддержка монтажа, пусконаладки и обучения'], 'parameters' => ['Ориентировочная скорость' => '30–32 с желатином; 34–36 на холостом ходу', 'Размеры капсул' => '00#–4#', 'Электропитание' => '380 В ±10%, 50 Гц', 'Напряжение управления' => '24 В', 'Ориентировочные габариты' => 'Около 28,023 × 1,8 × 2,7 м'], 'process' => ['Подготовка процесса', 'Смазка и погружение', 'Транспортировка и сушка', 'Работа с оператором', 'Снятие и обрезка', 'Соединение и сбор'], 'systems' => ['Контроль температуры', 'Управление процессом желатина', 'Регулировка скорости', 'Защита и индикация ошибок', 'Система сушки', 'Обучение эксплуатации']],
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

$fallback = [
    'zh' => ['process' => ['需求确认', '配置设计', '制造与装配', '运行检查', '交付与培训'], 'faq' => [['q' => '如何获取完整技术参数？', 'a' => '提交目标产能、胶囊规格、使用国家及现场条件后，我们将按项目提供配置方案。'], ['q' => '是否支持安装和培训？', 'a' => '安装、调试、培训及备件范围会在项目方案中逐项确认。']]],
    'en' => ['process' => ['Requirement review', 'Configuration design', 'Manufacturing and assembly', 'Operating checks', 'Delivery and training'], 'faq' => [['q' => 'How can I obtain complete technical data?', 'a' => 'Share target output, capsule size, destination country and site conditions for a project-specific proposal.'], ['q' => 'Are installation and training supported?', 'a' => 'Installation, commissioning, training and spare-parts scope are confirmed in the proposal.']]],
    'ru' => ['process' => ['Анализ требований', 'Проектирование конфигурации', 'Изготовление и сборка', 'Проверка работы', 'Поставка и обучение'], 'faq' => [['q' => 'Как получить полные технические данные?', 'a' => 'Сообщите производительность, размер капсул, страну назначения и условия площадки.'], ['q' => 'Предусмотрены ли монтаж и обучение?', 'a' => 'Объем монтажа, пусконаладки, обучения и запасных частей согласуется в предложении.']]],
][$lang];
if ($product) {
    $product['process'] = (array) ($product['process'] ?? $fallback['process']);
    $product['systems'] = (array) ($product['systems'] ?? $product['features']);
    $product['faq'] = (array) ($product['faq'] ?? $fallback['faq']);
}

$ui = [
    'zh' => ['html' => 'zh-CN', 'notFound' => '没有找到该产品', 'notFoundBody' => '产品可能已调整，请返回产品中心查看当前内容。', 'home' => '首页', 'products' => '产品中心', 'quote' => '获取项目方案', 'call' => '电话咨询', 'note' => '具体配置、接口、交付周期及参数以双方确认的项目方案为准。', 'specEyebrow' => '技术信息', 'specTitle' => '当前可公开参数', 'specNote' => '未公开的数据标记为项目确认，避免在需求明确前提供不准确参数。', 'processTitle' => '生产流程', 'systemsTitle' => '关键系统与技术特点', 'faqTitle' => '常见问题', 'capEyebrow' => '应用与能力', 'capTitle' => '围绕实际项目进行配置沟通', 'next' => '下一步', 'nextTitle' => '需要完整参数或配置建议？', 'nextBody' => '请提供目标产能、胶囊规格、使用国家和现场条件，便于进一步沟通。', 'submit' => '提交需求', 'contact' => 'contact.php'],
    'en' => ['html' => 'en', 'notFound' => 'Product not found', 'notFoundBody' => 'The product may have changed. Please return to the current product list.', 'home' => 'Home', 'products' => 'Products', 'quote' => 'Request a Project Solution', 'call' => 'Call Sales', 'note' => 'Final configuration, interfaces, delivery schedule, and technical parameters are subject to the agreed project proposal.', 'specEyebrow' => 'Technical Information', 'specTitle' => 'Currently Available Parameters', 'specNote' => 'Unpublished values are marked for project confirmation to avoid presenting inaccurate specifications.', 'processTitle' => 'Production Process', 'systemsTitle' => 'Key Systems and Technical Features', 'faqTitle' => 'Frequently Asked Questions', 'capEyebrow' => 'Applications and Capabilities', 'capTitle' => 'Configuration Based on Real Project Requirements', 'next' => 'Next Step', 'nextTitle' => 'Need Complete Parameters or Configuration Advice?', 'nextBody' => 'Share your target output, capsule specifications, destination country, and site conditions.', 'submit' => 'Submit Requirements', 'contact' => 'en/contact.html'],
    'ru' => ['html' => 'ru', 'notFound' => 'Продукт не найден', 'notFoundBody' => 'Возможно, продукция была обновлена. Вернитесь к актуальному каталогу.', 'home' => 'Главная', 'products' => 'Продукция', 'quote' => 'Запросить решение', 'call' => 'Позвонить', 'note' => 'Окончательная конфигурация, интерфейсы, сроки и параметры определяются согласованным проектным предложением.', 'specEyebrow' => 'Техническая информация', 'specTitle' => 'Доступные параметры', 'specNote' => 'Неопубликованные значения отмечены как уточняемые по проекту, чтобы не указывать неточные данные.', 'processTitle' => 'Производственный процесс', 'systemsTitle' => 'Ключевые системы и особенности', 'faqTitle' => 'Частые вопросы', 'capEyebrow' => 'Применение и возможности', 'capTitle' => 'Конфигурация под реальные требования проекта', 'next' => 'Следующий шаг', 'nextTitle' => 'Нужны полные параметры или помощь с конфигурацией?', 'nextBody' => 'Укажите требуемую производительность, спецификацию капсул, страну и условия площадки.', 'submit' => 'Отправить требования', 'contact' => 'ru/contact.html'],
][$lang];
$actionCopy = [
    'zh' => ['quote' => '咨询配置与报价', 'quoteHint' => '填写产能和胶囊规格', 'call' => '致电销售顾问', 'callHint' => '+86 189 1900 6708'],
    'en' => ['quote' => 'Request Configuration & Quote', 'quoteHint' => 'Share output and capsule size', 'call' => 'Call a Sales Advisor', 'callHint' => '+86 189 1900 6708'],
    'ru' => ['quote' => 'Запросить конфигурацию и цену', 'quoteHint' => 'Укажите объем и размер капсул', 'call' => 'Позвонить консультанту', 'callHint' => '+86 189 1900 6708'],
][$lang];
$serviceCopy = [
    'zh' => ['label' => '从需求到投产', 'items' => [['方案配置', '依据产能、规格与现场条件确认'], ['制造与装配', '按照确认后的技术方案组织生产'], ['运行与验收', '围绕关键工序及连续运行检查'], ['安装与培训', '按项目约定提供交付支持']]],
    'en' => ['label' => 'From Requirement to Production', 'items' => [['Configuration', 'Confirm output, size and site conditions'], ['Manufacturing', 'Build against the agreed technical proposal'], ['Testing', 'Check key processes and continuous operation'], ['Installation & Training', 'Provide agreed delivery support']]],
    'ru' => ['label' => 'От требований до производства', 'items' => [['Конфигурация', 'Уточнение объема, размеров и площадки'], ['Изготовление', 'Производство по согласованному проекту'], ['Испытания', 'Проверка процессов и непрерывной работы'], ['Монтаж и обучение', 'Согласованная поддержка при поставке']]],
][$lang];
$relatedCopy = [
    'zh' => ['eyebrow' => '相关设备', 'title' => '继续比较其他设备与配置', 'link' => '查看设备详情'],
    'en' => ['eyebrow' => 'Related Equipment', 'title' => 'Compare Other Equipment and Configurations', 'link' => 'View Equipment Details'],
    'ru' => ['eyebrow' => 'Связанное оборудование', 'title' => 'Сравните другие модели и конфигурации', 'link' => 'Подробнее'],
][$lang];
$relatedProducts = [];
foreach (qilin_config('products', []) as $category) {
    foreach ($category['items'] as $candidate) {
        if ($candidate['slug'] === $slug) {
            continue;
        }
        if ($lang !== 'zh' && isset($translations[$lang][$candidate['slug']])) {
            $candidate = array_merge($candidate, $translations[$lang][$candidate['slug']]);
        }
        $relatedProducts[] = $candidate;
    }
}
$relatedProducts = array_slice($relatedProducts, 0, 3);
$heroParameters = array_slice((array) ($product['parameters'] ?? []), 0, 3, true);
$pageTitle = $product ? $product['name'] : $ui['notFound'];
$siteUrl = rtrim((string) qilin_config('site_url'), '/');
$canonicalUrl = $siteUrl . '/product-detail.php?slug=' . rawurlencode($slug) . '&lang=' . rawurlencode($lang);
$languageUrls = [];
foreach (['zh', 'en', 'ru'] as $languageCode) {
    $languageUrls[$languageCode] = $siteUrl . '/product-detail.php?slug=' . rawurlencode($slug) . '&lang=' . $languageCode;
}
$metaDescription = $product ? $product['summary'] . '. ' . $ui['capTitle'] : $ui['notFoundBody'];
$layoutOptions = ['active' => 'products'];
?>
<!DOCTYPE html>
<html lang="<?php echo e($ui['html']); ?>">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e($pageTitle); ?> - Gansu Qilin Intelligent Equipment</title>
    <meta name="description" content="<?php echo e($metaDescription); ?>">
    <meta name="robots" content="<?php echo $product ? 'index,follow,max-image-preview:large,max-snippet:-1,max-video-preview:-1' : 'noindex,follow'; ?>">
    <meta property="og:type" content="product"><meta property="og:title" content="<?php echo e($pageTitle); ?>">
    <meta property="og:description" content="<?php echo e($metaDescription); ?>"><meta property="og:url" content="<?php echo e($canonicalUrl); ?>">
    <meta property="og:site_name" content="Gansu Qilin Intelligent Equipment"><meta property="og:locale" content="<?php echo e($lang === 'zh' ? 'zh_CN' : ($lang === 'ru' ? 'ru_RU' : 'en_US')); ?>">
    <?php if ($product): ?><meta property="og:image" content="<?php echo e(qilin_config('site_url')); ?>/<?php echo e($product['image']); ?>"><?php endif; ?>
    <meta name="twitter:card" content="summary_large_image"><meta name="twitter:title" content="<?php echo e($pageTitle); ?>"><meta name="twitter:description" content="<?php echo e($metaDescription); ?>">
    <link rel="canonical" href="<?php echo e($canonicalUrl); ?>">
    <?php if ($product): ?><link rel="alternate" hreflang="zh-CN" href="<?php echo e($languageUrls['zh']); ?>"><link rel="alternate" hreflang="en" href="<?php echo e($languageUrls['en']); ?>"><link rel="alternate" hreflang="ru" href="<?php echo e($languageUrls['ru']); ?>"><link rel="alternate" hreflang="x-default" href="<?php echo e($languageUrls['en']); ?>"><?php endif; ?>
    <?php if ($product): ?><link rel="preload" as="image" href="<?php echo e($product['image']); ?>" fetchpriority="high"><?php endif; ?>
    <link rel="stylesheet" href="styles/main.css?v=20260902-1"><link rel="stylesheet" href="styles/products.css?v=20260902-1">
    <?php if ($product): ?>
    <script type="application/ld+json"><?php echo json_encode([
        '@context' => 'https://schema.org', '@type' => 'Product', 'name' => $product['name'],
        'sku' => strtoupper($slug), 'category' => $product['category_title'],
        'image' => qilin_config('site_url') . '/' . $product['image'], 'description' => $product['summary'],
        'brand' => ['@type' => 'Brand', 'name' => 'Gansu Qilin Intelligent Equipment'],
        'manufacturer' => ['@type' => 'Organization', 'name' => qilin_config('site_name'), 'url' => qilin_config('site_url')],
        'url' => $canonicalUrl,
        'additionalProperty' => array_map(static fn ($label, $value) => ['@type' => 'PropertyValue', 'name' => (string) $label, 'value' => (string) $value], array_keys((array) ($product['parameters'] ?? [])), array_values((array) ($product['parameters'] ?? []))),
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
        <section class="product-detail-hero section-pad"><div class="container product-detail-grid"><div class="product-detail-image"><img src="<?php echo e($product['image']); ?>" alt="<?php echo e($product['name']); ?>"><div class="image-caption"><span><?php echo e($product['category_title']); ?></span><strong><?php echo e($product['name']); ?></strong></div></div><div class="product-detail-copy"><span class="eyebrow"><?php echo e($product['category_title']); ?></span><h1><?php echo e($product['name']); ?></h1><p class="product-lead"><?php echo e($product['summary']); ?></p><div class="hero-parameter-grid"><?php foreach ($heroParameters as $label => $value): ?><div><span><?php echo e($label); ?></span><strong><?php echo e($value); ?></strong></div><?php endforeach; ?></div><ul class="feature-check-list"><?php foreach ($product['features'] as $feature): ?><li><?php echo e($feature); ?></li><?php endforeach; ?></ul><div class="hero-actions product-actions"><a class="cta-button product-action product-action-primary" href="<?php echo e($ui['contact']); ?>?product=<?php echo e($slug); ?>"><strong><?php echo e($actionCopy['quote']); ?></strong><small><?php echo e($actionCopy['quoteHint']); ?></small></a><a class="button-secondary product-action product-action-phone" href="tel:<?php echo e(qilin_config('contact.sales_phone')); ?>"><strong><?php echo e($actionCopy['call']); ?></strong><small><?php echo e($actionCopy['callHint']); ?></small></a></div><p class="parameter-note"><?php echo e($ui['note']); ?></p></div></div></section>
        <section class="project-service-strip"><div class="container"><div class="service-strip-label"><span>01—04</span><strong><?php echo e($serviceCopy['label']); ?></strong></div><div class="service-strip-grid"><?php foreach ($serviceCopy['items'] as $index => $item): ?><article><span>0<?php echo $index + 1; ?></span><div><strong><?php echo e($item[0]); ?></strong><p><?php echo e($item[1]); ?></p></div></article><?php endforeach; ?></div></div></section>
        <section class="product-specifications section-pad"><div class="container narrow-container"><div class="section-heading"><span class="eyebrow"><?php echo e($ui['specEyebrow']); ?></span><h2><?php echo e($ui['specTitle']); ?></h2><p><?php echo e($ui['specNote']); ?></p></div><div class="spec-table-wrap"><table class="spec-table"><tbody><?php foreach ((array) ($product['parameters'] ?? []) as $label => $value): ?><tr><th scope="row"><?php echo e($label); ?></th><td><?php echo e($value); ?></td></tr><?php endforeach; ?></tbody></table></div></div></section>
        <section class="product-process section-pad"><div class="container narrow-container"><div class="section-heading"><h2><?php echo e($ui['processTitle']); ?></h2></div><ol class="process-flow"><?php foreach ($product['process'] as $index => $step): ?><li><span><?php echo str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT); ?></span><strong><?php echo e($step); ?></strong></li><?php endforeach; ?></ol></div></section>
        <section class="product-systems section-pad"><div class="container narrow-container"><div class="section-heading"><h2><?php echo e($ui['systemsTitle']); ?></h2></div><div class="system-grid"><?php foreach ($product['systems'] as $system): ?><article><span aria-hidden="true">✓</span><p><?php echo e($system); ?></p></article><?php endforeach; ?></div></div></section>
        <section class="product-capability section-pad"><div class="container narrow-container"><div class="section-heading"><span class="eyebrow"><?php echo e($ui['capEyebrow']); ?></span><h2><?php echo e($ui['capTitle']); ?></h2></div><div class="capability-list-grid"><?php foreach ($product['capabilities'] as $index => $capability): ?><article><span>0<?php echo $index + 1; ?></span><p><?php echo e($capability); ?></p></article><?php endforeach; ?></div></div></section>
        <?php if ($relatedProducts): ?><section class="related-products section-pad"><div class="container"><div class="section-heading"><span class="eyebrow"><?php echo e($relatedCopy['eyebrow']); ?></span><h2><?php echo e($relatedCopy['title']); ?></h2></div><div class="related-product-grid"><?php foreach ($relatedProducts as $related): ?><article><a class="related-product-image" href="product-detail.php?slug=<?php echo e($related['slug']); ?>&amp;lang=<?php echo e($lang); ?>"><img src="<?php echo e($related['image']); ?>" alt="<?php echo e($related['name']); ?>" loading="lazy" decoding="async"></a><div><span><?php echo e($related['category_title'] ?? $relatedCopy['eyebrow']); ?></span><h3><a href="product-detail.php?slug=<?php echo e($related['slug']); ?>&amp;lang=<?php echo e($lang); ?>"><?php echo e($related['name']); ?></a></h3><p><?php echo e($related['summary']); ?></p><a class="text-link" href="product-detail.php?slug=<?php echo e($related['slug']); ?>&amp;lang=<?php echo e($lang); ?>"><?php echo e($relatedCopy['link']); ?> →</a></div></article><?php endforeach; ?></div></div></section><?php endif; ?>
        <section class="product-faq section-pad"><div class="container narrow-container"><div class="section-heading"><h2><?php echo e($ui['faqTitle']); ?></h2></div><div class="faq-list"><?php foreach ($product['faq'] as $item): ?><details><summary><?php echo e($item['q']); ?></summary><p><?php echo e($item['a']); ?></p></details><?php endforeach; ?></div></div></section>
        <section class="product-next-step"><div class="container home-inquiry-inner"><div><span class="eyebrow"><?php echo e($ui['next']); ?></span><h2><?php echo e($ui['nextTitle']); ?></h2><p><?php echo e($ui['nextBody']); ?></p></div><a class="cta-button cta-light" href="<?php echo e($ui['contact']); ?>?product=<?php echo e($slug); ?>"><?php echo e($ui['submit']); ?></a></div></section>
    <?php endif; ?>
    </main>
    <?php if ($lang === 'zh'): include __DIR__ . '/includes/footer.php'; else: ?><footer data-site-footer></footer><script src="js/site-shell.js"></script><?php endif; ?>
    <script src="js/main.js"></script>
</body>
</html>
