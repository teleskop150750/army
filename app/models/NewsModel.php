<?php

namespace app\models;

use RedBeanPHP\R;

class NewsModel extends AppModel
{
    public function getCategories(): array
    {
        return R::findAll('category', 'ORDER BY id ASC');
    }

    public function getTotal(string $sql_part): int
    {
        return R::count('article', "status = '1' $sql_part");
    }

    public function getArticles(string $sql_part, int $start, int $perPage): ?array
    {
        return R::getAll("
        SELECT 
            article.id, 
            article.img, 
            article.alias, 
            article.title, 
            article.`date`,
            article.views,
            (SELECT COUNT(*) FROM comments WHERE comments.article_id = article.id) AS comm
        FROM article 
        WHERE article.status = '1' $sql_part
        ORDER BY article.`date` DESC
        LIMIT $start, $perPage
        ");
    }
}
