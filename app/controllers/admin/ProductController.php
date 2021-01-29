<?php

namespace app\controllers\admin;

use app\models\admin\AdminModel;
use app\models\admin\ProductModel;
use app\models\AppModel;
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

    public function addImageAction(): void
    {
        if (isset($_GET['upload'])) {
            if ($_POST['name'] == 'single') {
                $wmax = App::$app->getProperty('img_width');
                $hmax = App::$app->getProperty('img_height');
            } else {
                $wmax = App::$app->getProperty('gallery_width');
                $hmax = App::$app->getProperty('gallery_height');
            }
            $name = $_POST['name'];
            $product = new Product();
            $product->uploadImg($name, $wmax, $hmax);
        }
    }

    /**
     * @throws \core\exceptions\IdException
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

            // данные не валидны
            if (!$product_model->validate($data)) {
                $product_model->getErrors();
                redirect();
            }
            if ($product_model->update('product', $id)) {
                $product_model->editFilter($id, $data);
                $product_model->editRelatedProduct($id, $data);
                $product_model->saveGallery($id);
                $alias = AppModel::createAlias('product', 'alias', $data['title'], $id);
                $product = \R::load('product', $id);
                $product->alias = $alias;
                \R::store($product);
                $_SESSION['success'] = 'Изменения сохранены';
                redirect();
            }
        }

        $id = $this->getRequestID();
        $product = \R::load('product', $id);
        App::$app->setProperty('parent_id', $product->category_id);
        $filter = \R::getCol('SELECT attr_id FROM attribute_product WHERE product_id = ?', [$id]);
        $related_product = \R::getAll("SELECT related_product.related_id, product.title FROM related_product JOIN product ON product.id = related_product.related_id WHERE related_product.product_id = ?", [$id]);
        $gallery = \R::getCol('SELECT img FROM gallery WHERE product_id = ?', [$id]);
        $this->setMeta("Редактирование товара {$product->title}");
        $this->set(compact('product', 'filter', 'related_product', 'gallery'));
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

            if (!$product_model->validate($data)) {
                $product_model->getErrors();
                $_SESSION['form_data'] = $data;
                redirect();
            }

            if ($id = $product_model->save('product')) {
                $alias = AdminModel::createAlias('product', 'alias', $data['title'], $id);
                $product_model->setAliasProduct($id, $alias);
                $_SESSION['success'] = 'Товар добавлен';
            }
            redirect();
        }
//        if (!empty($_POST)) {
//            $product_model = new ProductModel();
//            $data = $_POST;
//            $product_model->load($data);
//            $product_model->attributes['status'] = $product_model->attributes['status'] ? '1' : '0';
//            $product_model->attributes['hit'] = $product_model->attributes['hit'] ? '1' : '0';
//            $product_model->getImg();
//
//            if (!$product_model->validate($data)) {
//                $product_model->getErrors();
//                $_SESSION['form_data'] = $data;
//                redirect();
//            }
//
//            if ($id = $product_model->save('product')) {
//                $product_model->saveGallery($id);
//                $alias = AdminModel::createAlias('product', 'alias', $data['title'], $id);
//                $product_model->setAliasProduct($id, $alias);
//                $product_model->editFilter($id, $data);
//                $product_model->editRelatedProduct($id, $data);
//                $_SESSION['success'] = 'Товар добавлен';
//            }
//            redirect();
//        }

        $this->setMeta('Новый товар');
    }

    public function relatedProductAction()
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

        $q = isset($_GET['q']) ? $_GET['q'] : '';
        $data['items'] = [];
        $products = \R::getAssoc('SELECT id, title FROM product WHERE title LIKE ? LIMIT 10', ["%{$q}%"]);
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

    public function deleteGalleryAction()
    {
        $id = isset($_POST['id']) ? $_POST['id'] : null;
        $src = isset($_POST['src']) ? $_POST['src'] : null;
        if (!$id || !$src) {
            return;
        }
        if (\R::exec("DELETE FROM gallery WHERE product_id = ? AND img = ?", [$id, $src])) {
            @unlink(WWW . "/images/$src");
            exit('1');
        }
        return;
    }
}
