<?php

namespace app\controllers\admin;

use app\models\admin\AdminModel;
use app\models\admin\ArticleModel;
use core\exceptions\IdException;
use core\libs\PaginationAdmin;
use RedBeanPHP\RedException\SQL;

class ArticleController extends AdminController
{
    public function indexAction(): void
    {
        $article_model = new ArticleModel();
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $perPage = 10;
        $total = $article_model->getTotal();
        $pagination = new PaginationAdmin($page, $perPage, $total);
        $start = $pagination->getStart();
        $articles = $article_model->getArticles($start, $perPage);
        $this->setMeta('Список товаров');
        $this->setData(compact('articles', 'pagination', 'total'));
    }

    public function deleteAction(): void
    {
        $article_model = new ArticleModel();
        $id = $this->getRequestID();
        $article_model->deleteArticle($id);
        $article_model->deleteGalleryAllImg($id);
        $_SESSION['success'] = 'Удалено';
        redirect();
    }

    public function addAction(): void
    {
        $article_model = new ArticleModel();
        if (!empty($_POST)) {
            $data = $_POST;
            $article_model->load($data);
            $article_model->attributes['status'] = $article_model->attributes['status'] === 'on' ? '1' : '0';

            if (!$article_model->validate($data)) {
                $article_model->getErrors();
                $_SESSION['form_data'] = $data;
                redirect();
            }

            if ($id = $article_model->save('article')) {
                if (!empty($data['gallery'])) {
                    $article_model->saveGallery($id, $data['gallery']);
                }
                $alias = AdminModel::createAlias('article', 'alias', $data['title'], $id);
                $article_model->setAlias($id, $alias);
                $_SESSION['success'] = 'Статья добавлена';
            }
            redirect();
        }

        $categories = $article_model->getCategories();
        $this->setMeta('Новая статья');
        $this->setData(compact('categories'));
    }

    public function editAction(): void
    {
        $article_model = new ArticleModel();
        if (!empty($_POST)) {
            $id = $this->getRequestID(false);
            $data = $_POST;
            $article_model->load($data);
            $article_model->attributes['status'] = $article_model->attributes['status'] === 'on' ? '1' : '0';

            if (!$article_model->validate($data)) {
                $article_model->getErrors();
                $_SESSION['form_data'] = $data;
                redirect();
            }

            if ($article_model->update('article', $id)) {
                if (!empty($data['gallery'])) {
                    $article_model->saveGallery($id, $data['gallery']);
                }
                $alias = AdminModel::createAlias('article', 'alias', $data['title'], $id);
                $article_model->setAlias($id, $alias);
                $_SESSION['success'] = 'Изменения сохранены';
                redirect();
            }
        }

        /** @var object $this */
        $id = $this->getRequestID();
        $article = $article_model->getArticle($id);
        $categories = $article_model->getCategories();
        $gallery = $article_model->getGallery($id);
        $this->setMeta("Редактирование товара {$article->title}");
        $this->setData(compact('article', 'categories', 'gallery'));
    }

    public function deleteGalleryAction(): void
    {
        $article_model = new ArticleModel();
        $id = $_POST['id'] ?? null;
        $src = $_POST['src'] ?? null;

        if (!$id || !$src) {
            return;
        }
        if (!is_null($article_model->deleteGalleryImg($id, $src))) {
            exit('1');
        }
    }

    public function deleteGalleryAllAction(): void
    {
        $article_model = new ArticleModel();
        $id = $_POST['id'] ?? null;

        if (!is_null($article_model->deleteGalleryAllImg($id))) {
            redirect();
        }
    }

    public function removeDirAction()
    {
        $uploadDir = WWW . '/upload/remove';
        $includes = glob($uploadDir . '/*');
        foreach ($includes as $include) {
            if (is_dir($include)) {
                recursiveRemoveDir($include);
            } else {
                unlink($include);
            }
        }

        $this->layout = false;
        $this->view = false;
        redirect();
        exit;
    }
}
