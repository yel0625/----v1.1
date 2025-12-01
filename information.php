<?php 
include 'includes/db.php';
include 'includes/security.php';
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <title>行业资料 - 甘肃骐霖智能装备</title>
    <link rel="stylesheet" href="styles/main.css">
    <link rel="stylesheet" href="styles/information.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <main class="information-container">
        <div class="category-nav">
            <a href="#all" class="active">全部</a>
            <a href="#tech">技术文章</a>
            <a href="#news">行业动态</a>
            <a href="#policy">政策法规</a>
        </div>

        <div class="search-box">
            <input type="text" placeholder="搜索关键词...">
            <button>搜索</button>
        </div>

        <div class="article-grid">
            <?php
            $sql = "SELECT id, title, category, publish_date, thumbnail 
                    FROM articles ORDER BY publish_date DESC LIMIT 12";
            $result = $conn->query($sql);
            
            while($row = $result->fetch_assoc()) {
                echo '<article class="article-card" data-category="'.$row['category'].'">';
                echo '<img src="'.$row['thumbnail'].'" alt="'.$row['title'].'">';
                echo '<div class="article-meta">';
                echo '<span class="category-tag">'.$row['category'].'</span>';
                echo '<h3><a href="article-detail.php?id='.$row['id'].'">'.$row['title'].'</a></h3>';
                echo '<time>'.$row['publish_date'].'</time>';
                echo '</div></article>';
            }
            ?>
        </div>
    </main>

    <?php include 'includes/footer.php'; ?>
    
    <script src="js/information-filter.js"></script>
</body>
</html>