<?php

function qilin_config(?string $key = null, mixed $default = null): mixed
{
    static $config;

    if ($config === null) {
        $config = require __DIR__ . '/site-config.php';
    }

    if ($key === null) {
        return $config;
    }

    $segments = explode('.', $key);
    $value = $config;

    foreach ($segments as $segment) {
        if (!is_array($value) || !array_key_exists($segment, $value)) {
            return $default;
        }

        $value = $value[$segment];
    }

    return $value;
}

function e(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function qilin_base_url(string $basePath = ''): string
{
    return $basePath === '' ? '' : rtrim($basePath, '/') . '/';
}

function qilin_url(string $path, string $basePath = ''): string
{
    return qilin_base_url($basePath) . ltrim($path, '/');
}

function qilin_now_year(): string
{
    return date('Y');
}

function qilin_article_category_label(string $code): string
{
    return qilin_config('article_categories.' . $code, $code);
}

function qilin_seed_articles(): array
{
    return [
        [
            'id' => 1,
            'title' => '硬胶囊生产线选型要点',
            'category' => 'tech',
            'publish_date' => '2025-03-21',
            'thumbnail' => 'images/product1.jpg',
            'summary' => '从产能、稳定性、维护便利性和扩展能力四个维度，梳理硬胶囊生产线选型时最值得优先比较的技术指标。',
            'content' => "<p>硬胶囊生产线的选型通常要围绕目标产能、胶囊规格、设备连续运行稳定性以及后续维护成本展开。</p><p>对于出口型客户，还需要额外关注备件供应周期、控制系统标准化程度以及远程技术支持能力。</p>",
        ],
        [
            'id' => 2,
            'title' => '胶囊抛光与分选环节的质量控制建议',
            'category' => 'tech',
            'publish_date' => '2025-03-18',
            'thumbnail' => 'images/capsules-petri.jpg',
            'summary' => '抛光与分选是保证胶囊成品外观一致性的重要环节，现场管理应重点关注粉尘控制、筛选效率和异常剔除规则。',
            'content' => "<p>在抛光与分选环节，设备转速、风量和筛网状态会直接影响成品一致性。</p><p>建议建立标准化巡检记录，及时复核异常剔除比例和物料损耗情况。</p>",
        ],
        [
            'id' => 3,
            'title' => '制药装备企业如何评估精密机加工外协能力',
            'category' => 'news',
            'publish_date' => '2025-03-15',
            'thumbnail' => 'images/company/exterior.jpg',
            'summary' => '面向设备制造商，总结精密机加工供应商在材质覆盖、尺寸稳定性、交期和沟通效率上的评估维度。',
            'content' => "<p>精密机加工能力不仅体现在设备配置，也体现在工艺沉淀、检验流程和交付协同效率。</p><p>建议优先评估供应商对铝件、不锈钢件和结构件的持续加工经验。</p>",
        ],
        [
            'id' => 4,
            'title' => '药用空心胶囊设备维护周期建议',
            'category' => 'tech',
            'publish_date' => '2025-03-10',
            'thumbnail' => 'images/equipment-line.jpg',
            'summary' => '结合日常运行经验，整理空心胶囊设备在清洁、润滑、易损件更换和校准上的推荐维护周期。',
            'content' => "<p>建立明确的日检、周检和月检机制，可以显著降低突发停机风险。</p><p>对关键旋转部件和易损件，应结合产量和运行环境动态调整维护周期。</p>",
        ],
        [
            'id' => 5,
            'title' => '制药装备出口项目中的资料准备清单',
            'category' => 'policy',
            'publish_date' => '2025-03-05',
            'thumbnail' => 'images/product2.jpg',
            'summary' => '面向海外项目，汇总设备资料、装箱清单、验收文档和售后支持资料的准备建议。',
            'content' => "<p>出口项目资料准备不仅影响清关效率，也直接关系到客户安装和后续维护体验。</p><p>建议将电气图纸、备件清单、操作说明和视频资料提前标准化归档。</p>",
        ],
        [
            'id' => 6,
            'title' => '胶囊生产项目常见咨询问题汇总',
            'category' => 'news',
            'publish_date' => '2025-03-01',
            'thumbnail' => 'images/capsules-orange.jpg',
            'summary' => '整理客户在前期咨询阶段最常见的产能、配置、交付周期和售后支持问题，便于快速响应。',
            'content' => "<p>在项目前期，客户最关注的是设备产能、换型效率、安装周期和后续备件支持能力。</p><p>统一整理 FAQ 有助于销售和技术团队提升沟通效率。</p>",
        ],
    ];
}

function qilin_apply_article_filters(array $articles, array $filters = []): array
{
    if (!empty($filters['category'])) {
        $articles = array_values(array_filter($articles, static fn(array $article): bool => $article['category'] === $filters['category']));
    }

    if (!empty($filters['search'])) {
        $search = mb_strtolower((string) $filters['search'], 'UTF-8');
        $articles = array_values(array_filter($articles, static function (array $article) use ($search): bool {
            $haystack = mb_strtolower($article['title'] . ' ' . $article['summary'], 'UTF-8');
            return str_contains($haystack, $search);
        }));
    }

    usort($articles, static fn(array $a, array $b): int => strcmp($b['publish_date'], $a['publish_date']));

    return $articles;
}

function qilin_filter_articles(array $filters = []): array
{
    return qilin_apply_article_filters(qilin_seed_articles(), $filters);
}

function qilin_find_seed_article(int $articleId): ?array
{
    foreach (qilin_seed_articles() as $article) {
        if ((int) $article['id'] === $articleId) {
            return $article;
        }
    }

    return null;
}

function qilin_storage_path(string $filename): string
{
    return dirname(__DIR__) . '/storage/' . ltrim($filename, '/');
}

function qilin_ensure_storage_dir(): bool
{
    $directory = dirname(qilin_storage_path('placeholder.txt'));

    if (is_dir($directory)) {
        return true;
    }

    return mkdir($directory, 0775, true);
}

function qilin_prepare_article_content(string $content): string
{
    return trim($content);
}

function qilin_render_article_content(?string $content): string
{
    $content = trim((string) $content);

    if ($content === '') {
        return '';
    }

    if (str_contains($content, '<')) {
        return $content;
    }

    $paragraphs = preg_split('/\R{2,}/u', $content) ?: [];
    $html = [];

    foreach ($paragraphs as $paragraph) {
        $lines = preg_split('/\R/u', trim($paragraph)) ?: [];
        $safeLines = array_map(
            static fn(string $line): string => e(trim($line)),
            array_values(array_filter($lines, static fn(string $line): bool => trim($line) !== ''))
        );

        if ($safeLines) {
            $html[] = '<p>' . implode('<br>', $safeLines) . '</p>';
        }
    }

    return implode('', $html);
}

function qilin_next_article_id(array $articles): int
{
    $maxId = 0;

    foreach ($articles as $article) {
        $maxId = max($maxId, (int) ($article['id'] ?? 0));
    }

    return $maxId + 1;
}
