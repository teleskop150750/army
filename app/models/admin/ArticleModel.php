<?php

namespace app\models\admin;

use RedBeanPHP\OODBBean;
use RedBeanPHP\R;
use RedBeanPHP\RedException\SQL;

class ArticleModel extends AdminModel
{
    public array $attributes = [
        'title' => '',
        'alias' => '',
        'content' => '',
        'img' => 'article-default.jpeg',
        'category_id' => '',
        'status' => '1',
        'keywords' => '',
        'description' => '',
    ];

    public array $rules = [
        'required' => [
            ['title'],
            ['category_id'],
        ],
        'integer' => [
            ['category_id'],
        ],
    ];

    public function getTotal(): int
    {
        return R::count('article');
    }

    public function getArticles(int $start, int $perPage): ?array
    {
        return R::getAll("
        SELECT article.*,
               category.title AS cat 
        FROM article 
        JOIN category ON category.id = article.category_id 
        ORDER BY article.date DESC
        LIMIT $start, $perPage
        ");
    }

    public function getCategories(): ?array
    {
        return R::findAll("category");
    }

    /**
     * Записать алиас
     * @param int $id id товара
     * @param string $alias алиас
     * @throws SQL
     */
    public function setAliasProduct(int $id, string $alias): void
    {
        /** @var OODBBean|object $product */
        $product = R::load('product', $id);
        $product->alias = $alias;
        R::store($product);
    }



    public function saveGallery($id, $data): void
    {
        if (!empty($data)) {
            $sql_part = '';
            foreach ($data as $v) {
                $sql_part .= "('$v', $id),";
            }
            $sql_part = rtrim($sql_part, ',');
            R::exec("INSERT INTO gallery (img, article_id) VALUES $sql_part");
        }
    }

    /*
     * edit
     * -----------------------------------------
     */

    public function getArticle(int $id): object
    {
        return R::load('article', $id);
    }

    public function deleteArticle(int $id): void
    {
        R::exec("DELETE FROM article WHERE id  = ?", [$id]);
    }

    public function setAlias(int $id, string $alias)
    {
        /** @var object $product */
        $article = R::load('article', $id);
        $article->alias = $alias;
        R::store($article);
    }

    public function getGallery(int $id): ?array
    {
        return R::getCol('SELECT img FROM gallery WHERE article_id = ?', [$id]);
    }

    public function deleteGalleryImg(int $id, string $src)
    {
        return R::exec("DELETE FROM gallery WHERE article_id = ? AND img = ?", [$id, $src]);
    }

    public function deleteGalleryAllImg(int $id)
    {
        return R::exec("DELETE FROM gallery WHERE article_id = ?", [$id]);
    }
}
