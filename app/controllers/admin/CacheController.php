<?php

namespace app\controllers\admin;

use core\Cache;

class CacheController extends AdminController
{
    public function indexAction(): void
    {
        $this->setMeta('Очистка кэша');
    }

    public function deleteAction(): void
    {
        $key = $_GET['key'] ?? null;
        /** @var Cache $cache */
        $cache = Cache::getInstance();

        switch ($key) {
            case 'category':
                $cache->delete('cats');
                $cache->delete('site_menu');
                break;
            case 'filter':
                $cache->delete('filter_group');
                $cache->delete('filter_attrs');
                break;
            default:
                $_SESSION['error'] = 'Ошибка!';
                redirect();
                break;
        }

        $_SESSION['success'] = 'Выбранный кэш удален';
        redirect();
    }
}
