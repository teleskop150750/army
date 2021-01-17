<?php

namespace app\controllers;

use app\models\BreadcrumbsModel;
use app\models\CategoryModel;
use app\widgets\filter\FilterWidget;
use core\exceptions\CategoryException;
use core\exceptions\ParameterException;
use core\App;
use core\libs\Pagination;

class CategoryController extends AppController
{
    /**
     * @throws CategoryException
     * @throws ParameterException
     */
    public function viewAction(): void
    {
        $cat_model = new CategoryModel();

        /** @var string $alias алиас */
        $alias = $this->route['alias'];

        /** @var object $category */
        $category = $cat_model->getCategory($alias);

        // крошки
        $breadcrumbs = BreadcrumbsModel::getBreadcrumbs($category->id);
        // id категорий
        $ids = $cat_model->getIds($category->id);
        $ids = !$ids ? $category->id : $ids . $category->id;

        // текущая страница
        $pageNumber = isset($_GET['page']) ? (int)$_GET['page'] : 1;

        /** @var int $perPage количество на странице*/
        $perPage = App::$app->getProperty('pagination');

        $sql_part = '';

        if (!empty($_GET['filter'])) {
            /** @var string|null фильтры*/
            $filter = FilterWidget::getFilter();

            if ($filter) {
                $cnt = FilterWidget::getCountGroups($filter);
                $sql_part = "AND id IN (SELECT product_id FROM attribute_product 
                WHERE attr_id IN ($filter) 
                GROUP BY product_id 
                HAVING COUNT(product_id) = $cnt)";
            }
        }

        // всего
        $total = $cat_model->getTotal($ids, $sql_part);
        $pagination = new Pagination($pageNumber, $perPage, $total);
        $start = $pagination->getStart();
        // продукты
        $products = $cat_model->getProducts($ids, $sql_part, $start, $perPage);


        if ($this->isAjax()) {
            $this->layout = false;
            $this->view = 'filter';
            $this->data = compact('products', 'total', 'pagination');
        } else {
            $this->setMeta($category->title, $category->description, $category->keywords);
            $this->setData(compact('products', 'breadcrumbs', 'pagination', 'total'));
        }
    }
}
