<?php

namespace app\controllers;

use app\models\NewsModel;
use core\App;
use core\libs\Pagination;

class NewsController extends AppController
{
    public function viewAction(): void
    {
        $news_model = new NewsModel();
        $categories = $news_model->getCategories();

        // текущая страница
        $pageNumber = isset($_GET['page']) ? (int)$_GET['page'] : 1;

        $sql_part = '';
        if (!empty($_GET['category'])) {
            $sql_part = "AND category_id = " . (int)$_GET['category'];
        }

        /** @var int $perPage количество на странице */
        $perPage = App::$app->getProperty('pagination');
        // всего
        $total = $news_model->getTotal($sql_part);
        $pagination = new Pagination($pageNumber, $perPage, $total);
        $start = $pagination->getStart();
        $articles = $news_model->getArticles($sql_part, $start, $perPage);

        $this->setMeta('Новости', 'Описание...', 'Ключевые слова...');
        $this->setData(compact('articles', 'categories', 'pagination'));
    }

    public function pageAction(): void
    {
        $news_model = new NewsModel();

        // текущая страница
        $pageNumber = isset($_POST['page']) ? (int)$_POST['page'] : 1;

        $sql_part = '';
        if (!empty($_POST['category'])) {
            $sql_part = "AND category_id = " . (int)$_POST['category'];
        }

        /** @var int $perPage количество на странице */
        $perPage = App::$app->getProperty('pagination');
        // всего
        $total = $news_model->getTotal($sql_part);
        $pagination = new Pagination($pageNumber, $perPage, $total);
        $start = $pagination->getStart();
        $articles = $news_model->getArticles($sql_part, $start, $perPage);

        $this->loadView('page', compact('articles', 'pagination'));
    }
}
