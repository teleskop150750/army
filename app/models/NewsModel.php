<?php


namespace app\models;

use RedBeanPHP\R;

class NewsModel extends AppModel
{
    /**
     * @param string $sql_part
     * @param int $start
     * @param int $perPage
     * @return array|null
     */
    public function getArticles(string $sql_part, int $start, int $perPage): ?array
    {
        return R::getAll("
        SELECT 
            article.id, 
            img, 
            alias, 
            title, 
            article.`date`,
            views,
            (SELECT COUNT(*) FROM comments WHERE comments.article_id = article.id) AS comm
        FROM article 
        WHERE status = '1' $sql_part
        ORDER BY article.`date` DESC
        LIMIT $start, $perPage
        ");
    }

    public function getCategories(): array
    {
        return R::findAll('category', 'ORDER BY id ASC');
    }

    /**
     * получить количество записей
     * @param string $sql_part
     * @return int
     */
    public function getTotal(string $sql_part): int
    {
        return R::count('article', "status = '1' $sql_part");
    }
}
