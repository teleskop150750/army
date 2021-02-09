<?php

namespace app\models;

use RedBeanPHP\R;

class ArticleModel extends AppModel
{
    /**
     * @param string $alias
     * @return mixed
     */
    public function getArticle(string $alias)
    {
        $article = R::findOne('article', "alias = ? AND status = '1'", [$alias]);
        if (!$article) {
            throw new \Exception('статья не найдена', 404);
        }
        return $article;
    }

    public function updateViews(int $id, int $views): void
    {
        /** @var object $article */
        $article = R::load('article', $id);
        if (!$article) {
            throw new \Exception('статья не найдена', 404);
        }
        $article->views = $views +  1;
        R::store($article);
    }

    public function getCategories(): array
    {
        return R::findAll('category', 'ORDER BY id DESC');
    }

    public function getComments(int $id): array
    {
        return R::getAll("SELECT `comments`.*,
            user.login,
            user.id as user_id,
            user.img
            FROM comments
            JOIN user ON `comments`.user_id = user.id
            WHERE comments.article_id = {$id}
            GROUP BY comments.id, `comments`.`date`
            ORDER BY `comments`.`date` DESC");
    }
}
