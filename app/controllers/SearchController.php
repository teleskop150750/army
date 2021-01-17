<?php

namespace app\controllers;

use app\models\SearchModel;
use core\App;
use core\exceptions\ParameterException;
use core\libs\Pagination;
use JsonException;
use RedBeanPHP\R;

class SearchController extends AppController
{
    /**
     * @throws JsonException
     */
    public function typeaheadAction(): void
    {
        if ($this->isAjax()) {
            $query = !empty(trim($_GET['query'])) ? trim($_GET['query']) : null;
            if ($query) {
                $products = R::getAll('SELECT id, title FROM product WHERE title LIKE ? LIMIT 11', ["%{$query}%"]);
                echo json_encode($products, JSON_THROW_ON_ERROR);
            }
        }
        die;
    }

    /**
     * @throws ParameterException
     */
    public function indexAction(): void
    {
        $search_modal = new SearchModel();
        $query = !empty(trim($_GET['s'])) ? trim($_GET['s']) : null;
        if ($query) {
            /** @var int текущая страница */
            $pageNumber = isset($_GET['page']) ? (int)$_GET['page'] : 1;

            /** @var int количество на странице */
            $perPage = App::$app->getProperty('pagination');

            /** @var int всего */
            $total = $search_modal->getTotal($query);
            $pagination = new Pagination($pageNumber, $perPage, $total);
            $start = $pagination->getStart();
            /** @var array продукты */
            $products = $search_modal->getProducts($query, $start, $perPage);
        }

        $this->setMeta('Поиск по: ' . h($query));
        $this->setData(compact('products', 'query', 'pagination', 'total'));
    }
}
