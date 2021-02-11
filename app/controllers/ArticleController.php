<?php

namespace app\controllers;

use app\models\ArticleModel;

class ArticleController extends AppController
{
    public function viewAction(): void
    {
        $article_model = new ArticleModel();
        $alias = $this->route['alias'];
        $article = $article_model->getArticle($alias);
        $gallery = $article_model->getGallery($article['id']);
        $categories = $article_model->getCategories();
        $article_model->updateViews($article['id'], $article['views']);
        $comments = $article_model->getComments($article['id']);

        $this->setMeta($article->title, $article->description, $article->keywords);
        $this->setData(compact('article', 'categories', 'comments', 'gallery'));
    }
}
