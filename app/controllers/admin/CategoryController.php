<?php

namespace app\controllers\admin;

use app\models\admin\AdminModel;
use app\models\admin\CategoryModel;
use app\models\admin\AppModel;
use core\App;
use core\exceptions\IdException;
use RedBeanPHP\RedException\SQL;

class CategoryController extends AdminController
{
    public function indexAction(): void
    {
        $this->setMeta('Список категорий');
    }

    /**
     * @throws IdException
     */
    public function deleteAction(): void
    {
        $category_model = new CategoryModel();

        $id = $this->getRequestID();
        $children = $category_model->getCountChildren($id);
        $errors = '';

        // есть потомки?
        if ($children) {
            $errors .= '<li>Удаление невозможно, в категории есть вложенные категории</li>';
        }

        $products = $category_model->getCountProducts($id);

        // есть продукты?
        if ($products) {
            $errors .= '<li>Удаление невозможно, в категории есть товары</li>';
        }

        // ошибки есть?
        if ($errors) {
            $_SESSION['error'] = "<ul>$errors</ul>";
            redirect();
        }

        $category_model->deleteCategory($id);
        $_SESSION['success'] = 'Категория удалена';
        redirect();
    }

    /**
     * @throws SQL
     */
    public function addAction(): void
    {
        if (!empty($_POST)) {
            $category_model = new CategoryModel();
            $data = $_POST;
            $category_model->load($data);

            // данные не валидны?
            if (!$category_model->validate($data)) {
                $category_model->getErrors();
                redirect();
            }

            // записано в таблицу?
            if ($id = $category_model->save('category')) {
                $alias = AdminModel::createAlias('category', 'alias', $data['title'], $id);

                $category_model->setAlias($id, $alias);
                $_SESSION['success'] = 'Категория добавлена';
            }
            redirect();
        }
        $this->setMeta('Новая категория');
    }

    /**
     * @throws IdException
     * @throws SQL
     */
    public function editAction(): void
    {
        $category_model = new CategoryModel();
        if (!empty($_POST)) {
            $id = $this->getRequestID(false);
            $data = $_POST;
            $category_model->load($data);

            // данные не валидны?
            if (!$category_model->validate($data)) {
                $category_model->getErrors();
                redirect();
            }

            // записано в таблицу?
            if ($category_model->update('category', $id)) {
                $alias = AdminModel::createAlias('category', 'alias', $data['title'], $id);

                $category_model->setAlias($id, $alias);
                $_SESSION['success'] = 'Изменения сохранены';
            }
            redirect();
        }

        /** @var $id */
        $id = $this->getRequestID();
        $category = $category_model->getCategory($id);
        App::$app->setProperty('parent_id', $category->parent_id);
        $this->setMeta("Редактирование категории {$category->title}");
        $this->setData(compact('category'));
    }
}
