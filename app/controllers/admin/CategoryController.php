<?php

namespace app\controllers\admin;

use app\models\admin\CategoryModel;
use app\models\admin\FilterAttrModel;
use app\models\admin\FilterGroupModel;

class CategoryController extends AdminController
{
    public function indexAction(): void
    {
        $category_modal = new CategoryModel();
        $categories = $category_modal->getCategories();
        $this->setMeta('Фильтры');
        $this->setData(compact('categories'));
    }

    public function categoryDeleteAction(): void
    {
        $category_modal = new CategoryModel();
        $id = $this->getRequestID();
        $category_modal->deleteCategory($id);
        $_SESSION['success'] = 'Удалено';
        redirect();
    }

    public function categoryAddAction(): void
    {
        if (!empty($_POST)) {
            $data = $_POST;
            $category_modal = new CategoryModel();
            $category_modal->load($data);
            if (!$category_modal->validate($data)) {
                $category_modal->getErrors();
                redirect();
            }
            if ($category_modal->save('category', false)) {
                $_SESSION['success'] = 'Атрибут добавлен';
                redirect();
            }
        }
        $this->setMeta('Новый фильтр');
    }

    public function categoryEditAction(): void
    {
        $category_modal = new CategoryModel();
        if (!empty($_POST)) {
            $id = $this->getRequestID(false);
            $data = $_POST;
            $category_modal->load($data);
            if (!$category_modal->validate($data)) {
                $category_modal->getErrors();
                redirect();
            }
            if ($category_modal->update('category', $id)) {
                $_SESSION['success'] = 'Изменения сохранены';
                redirect();
            }
        }
        $id = $this->getRequestID();
        $category = $category_modal->getCategory($id);
        $this->setMeta('Редактирование атрибута');
        $this->setData(compact('category'));
    }
}
