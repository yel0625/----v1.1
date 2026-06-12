<?php

function qilin_db(): ?mysqli
{
    static $conn = false;

    if ($conn instanceof mysqli || $conn === null) {
        return $conn;
    }

    mysqli_report(MYSQLI_REPORT_OFF);

    $host = getenv('QILIN_DB_HOST') ?: '127.0.0.1';
    $port = (int) (getenv('QILIN_DB_PORT') ?: 3306);
    $user = getenv('QILIN_DB_USER') ?: 'db_user';
    $password = getenv('QILIN_DB_PASSWORD') ?: 'db_password';
    $database = getenv('QILIN_DB_NAME') ?: 'qilin_cms';

    $mysqli = @new mysqli($host, $user, $password, $database, $port);

    if ($mysqli->connect_errno) {
        $conn = null;
        return null;
    }

    $mysqli->set_charset('utf8mb4');
    $conn = $mysqli;

    return $conn;
}

function qilin_article_storage_file(): string
{
    return qilin_storage_path('articles.json');
}

function qilin_normalize_article(array $article, ?int $fallbackId = null): array
{
    $categories = array_keys((array) qilin_config('article_categories', []));
    $defaultCategory = $categories[0] ?? 'news';
    $category = (string) ($article['category'] ?? $defaultCategory);

    if (!in_array($category, $categories, true)) {
        $category = $defaultCategory;
    }

    $publishDate = trim((string) ($article['publish_date'] ?? ''));
    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $publishDate)) {
        $publishDate = date('Y-m-d');
    }

    return [
        'id' => (int) ($article['id'] ?? $fallbackId ?? 0),
        'title' => trim((string) ($article['title'] ?? '')),
        'category' => $category,
        'publish_date' => $publishDate,
        'thumbnail' => trim((string) ($article['thumbnail'] ?? '')),
        'summary' => trim((string) ($article['summary'] ?? '')),
        'content' => qilin_prepare_article_content((string) ($article['content'] ?? '')),
    ];
}

function qilin_load_file_articles(): ?array
{
    $file = qilin_article_storage_file();

    if (!is_file($file)) {
        return null;
    }

    $json = @file_get_contents($file);
    if ($json === false) {
        return [];
    }

    $decoded = json_decode($json, true);
    if (!is_array($decoded)) {
        return [];
    }

    $articles = [];

    foreach ($decoded as $index => $article) {
        if (!is_array($article)) {
            continue;
        }

        $articles[] = qilin_normalize_article($article, $index + 1);
    }

    usort($articles, static fn(array $a, array $b): int => strcmp($b['publish_date'], $a['publish_date']));

    return $articles;
}

function qilin_save_file_articles(array $articles): bool
{
    if (!qilin_ensure_storage_dir()) {
        return false;
    }

    $normalized = [];

    foreach ($articles as $index => $article) {
        if (!is_array($article)) {
            continue;
        }

        $normalized[] = qilin_normalize_article($article, $index + 1);
    }

    usort($normalized, static function (array $a, array $b): int {
        $dateCompare = strcmp($b['publish_date'], $a['publish_date']);

        if ($dateCompare !== 0) {
            return $dateCompare;
        }

        return $b['id'] <=> $a['id'];
    });

    $json = json_encode($normalized, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

    if ($json === false) {
        return false;
    }

    return @file_put_contents(qilin_article_storage_file(), $json, LOCK_EX) !== false;
}

function qilin_fetch_local_articles(array $filters = []): array
{
    $articles = qilin_load_file_articles();

    if ($articles === null) {
        return qilin_filter_articles($filters);
    }

    return qilin_apply_article_filters($articles, $filters);
}

function qilin_fetch_local_article_by_id(int $articleId): ?array
{
    $articles = qilin_load_file_articles();

    if ($articles === null) {
        return qilin_find_seed_article($articleId);
    }

    foreach ($articles as $article) {
        if ((int) $article['id'] === $articleId) {
            return $article;
        }
    }

    return null;
}

function qilin_fetch_articles(array $filters = []): array
{
    $conn = qilin_db();

    if (!$conn) {
        return qilin_fetch_local_articles($filters);
    }

    $where = [];
    $types = '';
    $params = [];

    if (!empty($filters['category'])) {
        $where[] = 'category = ?';
        $types .= 's';
        $params[] = $filters['category'];
    }

    if (!empty($filters['search'])) {
        $where[] = '(title LIKE ? OR summary LIKE ?)';
        $types .= 'ss';
        $search = '%' . $filters['search'] . '%';
        $params[] = $search;
        $params[] = $search;
    }

    $sql = 'SELECT id, title, category, publish_date, thumbnail, summary, content FROM articles';
    if ($where) {
        $sql .= ' WHERE ' . implode(' AND ', $where);
    }
    $sql .= ' ORDER BY publish_date DESC LIMIT 50';

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        return qilin_fetch_local_articles($filters);
    }

    if ($types !== '') {
        $stmt->bind_param($types, ...$params);
    }

    if (!$stmt->execute()) {
        $stmt->close();
        return qilin_fetch_local_articles($filters);
    }

    $result = $stmt->get_result();
    if (!$result) {
        $stmt->close();
        return qilin_fetch_local_articles($filters);
    }

    $rows = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    return array_map(static fn(array $article): array => qilin_normalize_article($article), $rows);
}

function qilin_fetch_article_by_id(int $articleId): ?array
{
    $conn = qilin_db();

    if (!$conn) {
        return qilin_fetch_local_article_by_id($articleId);
    }

    $stmt = $conn->prepare('SELECT id, title, category, publish_date, thumbnail, summary, content FROM articles WHERE id = ? LIMIT 1');
    if (!$stmt) {
        return qilin_fetch_local_article_by_id($articleId);
    }

    $stmt->bind_param('i', $articleId);

    if (!$stmt->execute()) {
        $stmt->close();
        return qilin_fetch_local_article_by_id($articleId);
    }

    $result = $stmt->get_result();
    $article = $result ? $result->fetch_assoc() : null;
    $stmt->close();

    return $article ? qilin_normalize_article($article) : null;
}

function qilin_save_article(array $data): ?array
{
    $article = qilin_normalize_article($data);
    $conn = qilin_db();

    if ($conn) {
        if ($article['id'] > 0) {
            $stmt = $conn->prepare('UPDATE articles SET title = ?, category = ?, publish_date = ?, thumbnail = ?, summary = ?, content = ? WHERE id = ?');

            if ($stmt) {
                $stmt->bind_param(
                    'ssssssi',
                    $article['title'],
                    $article['category'],
                    $article['publish_date'],
                    $article['thumbnail'],
                    $article['summary'],
                    $article['content'],
                    $article['id']
                );

                if ($stmt->execute()) {
                    $stmt->close();
                    return qilin_fetch_article_by_id($article['id']);
                }

                $stmt->close();
            }
        } else {
            $stmt = $conn->prepare('INSERT INTO articles (title, category, publish_date, thumbnail, summary, content) VALUES (?, ?, ?, ?, ?, ?)');

            if ($stmt) {
                $stmt->bind_param(
                    'ssssss',
                    $article['title'],
                    $article['category'],
                    $article['publish_date'],
                    $article['thumbnail'],
                    $article['summary'],
                    $article['content']
                );

                if ($stmt->execute()) {
                    $articleId = (int) $stmt->insert_id;
                    $stmt->close();
                    return qilin_fetch_article_by_id($articleId);
                }

                $stmt->close();
            }
        }
    }

    $articles = qilin_load_file_articles();
    if ($articles === null) {
        $articles = qilin_seed_articles();
    }

    if ($article['id'] > 0) {
        $updated = false;

        foreach ($articles as $index => $existingArticle) {
            if ((int) $existingArticle['id'] !== $article['id']) {
                continue;
            }

            $articles[$index] = $article;
            $updated = true;
            break;
        }

        if (!$updated) {
            $articles[] = $article;
        }
    } else {
        $article['id'] = qilin_next_article_id($articles);
        $articles[] = $article;
    }

    if (!qilin_save_file_articles($articles)) {
        return null;
    }

    return qilin_fetch_local_article_by_id($article['id']);
}

function qilin_delete_article(int $articleId): bool
{
    $conn = qilin_db();

    if ($conn) {
        $stmt = $conn->prepare('DELETE FROM articles WHERE id = ?');

        if ($stmt) {
            $stmt->bind_param('i', $articleId);

            if ($stmt->execute()) {
                $deleted = $stmt->affected_rows > 0;
                $stmt->close();
                return $deleted;
            }

            $stmt->close();
        }
    }

    $articles = qilin_load_file_articles();
    if ($articles === null) {
        $articles = qilin_seed_articles();
    }

    $remaining = array_values(array_filter(
        $articles,
        static fn(array $article): bool => (int) ($article['id'] ?? 0) !== $articleId
    ));

    if (count($remaining) === count($articles)) {
        return false;
    }

    return qilin_save_file_articles($remaining);
}
