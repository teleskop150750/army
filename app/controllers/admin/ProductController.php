<?php

namespace app\controllers\admin;

use app\models\admin\AdminModel;
use app\models\admin\ProductModel;
use app\models\AppModel;
use core\App;
use core\exceptions\IdException;
use core\libs\Pagination;
use RedBeanPHP\R;
use RedBeanPHP\RedException\SQL;

class ProductController extends AdminController
{
    public function indexAction(): void
    {
        $product_model = new ProductModel();
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $perPage = 10;
        $total = $product_model->getTotal();
        $pagination = new Pagination($page, $perPage, $total);
        $start = $pagination->getStart();
        $products = $product_model->getProducts($start, $perPage);
        $this->setMeta('Список товаров');
        $this->setData(compact('products', 'pagination', 'total'));
    }

    /**
     * @throws SQL
     */
    public function addAction(): void
    {
        if (!empty($_POST)) {
            $product_model = new ProductModel();
            $data = $_POST;
            $product_model->load($data);
            $product_model->attributes['status'] = $product_model->attributes['status'] ? '1' : '0';
            $product_model->attributes['hit'] = $product_model->attributes['hit'] ? '1' : '0';
            $product_model->getImg();

            if (!$product_model->validate($data)) {
                $product_model->getErrors();
                $_SESSION['form_data'] = $data;
                redirect();
            }

            if ($id = $product_model->save('product')) {
                $product_model->saveGallery($id);
                $alias = AdminModel::createAlias('product', 'alias', $data['title'], $id);
                $product_model->setAliasProduct($id, $alias);
                $product_model->editRelatedProduct($id, $data);
                $product_model->editFilter($id, $data);
                $_SESSION['success'] = 'Товар добавлен';
            }
            redirect();
        }

        $this->setMeta('Новый товар');
    }

    public function relatedProductAction(): void
    {
        /*$data = [
            'items' => [
                [
                    'id' => 1,
                    'text' => 'Товар 1',
                ],
                [
                    'id' => 2,
                    'text' => 'Товар 2',
                ],
            ]
        ];*/

        $q = $_GET['q'] ?? '';
        $data['items'] = [];
        $products = R::getAssoc('SELECT id, title FROM product WHERE title LIKE ? LIMIT 10', ["%{$q}%"]);

        // товары получены?
        if ($products) {
            $i = 0;
            foreach ($products as $id => $title) {
                $data['items'][$i]['id'] = $id;
                $data['items'][$i]['text'] = $title;
                $i++;
            }
        }
        echo json_encode($data);
        die;
    }

    public function addImageAction(): void
    {
        if (isset($_GET['upload'])) {
            if ($_POST['name'] === 'single') {
                $wMax = App::$app->getProperty('img_width');
                $hMax = App::$app->getProperty('img_height');
            } else {
                $wMax = App::$app->getProperty('gallery_width');
                $hMax = App::$app->getProperty('gallery_height');
            }
            $name = $_POST['name'];
            $product_model = new ProductModel();
            $product_model->uploadImg($name, $wMax, $hMax);
        }
    }

    /**
     * @throws IdException
     * @throws SQL
     */
    public function editAction(): void
    {
        if (!empty($_POST)) {
            $id = $this->getRequestID(false);
            $product_model = new ProductModel();
            $data = $_POST;
            $product_model->load($data);
            $product_model->attributes['status'] = $product_model->attributes['status'] ? '1' : '0';
            $product_model->attributes['hit'] = $product_model->attributes['hit'] ? '1' : '0';
            $product_model->getImg();
            if (!$product_model->validate($data)) {
                $product_model->getErrors();
                redirect();
            }
            if ($product_model->update('product', $id)) {
                $product_model->editFilter($id, $data);
                $product_model->editRelatedProduct($id, $data);
                $product_model->saveGallery($id);
                $alias = AdminModel::createAlias('product', 'alias', $data['title'], $id);
                $product_model->setAlias($id, $alias);
                $_SESSION['success'] = 'Изменения сохранены';
                redirect();
            }
        }

        /** @var object $this */
        $id = $this->getRequestID();
        $product_model = new ProductModel();
        $product = $product_model->getProduct($id);
        App::$app->setProperty('parent_id', $product->category_id);
        $filter = $product_model->getFilter($id);
        $related_product = $product_model->getRelated($id);
        $gallery = $product_model->getGallery($id);
        $this->setMeta("Редактирование товара {$product->title}");
        $this->setData(compact('product', 'filter', 'related_product', 'gallery'));
    }

    public function deleteGalleryAction(): void
    {
        $product_modal = new ProductModel();
        $id = $_POST['id'] ?? null;
        $src = $_POST['src'] ?? null;
        if (!$id || !$src) {
            return;
        }
        if ($product_modal->deleteGalleryImg($id, $src)) {
            @unlink(WWW . "/images/$src");
            exit('1');
        }
        return;
    }
}
