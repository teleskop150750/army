<?php

namespace app\controllers;

use app\models\BreadcrumbsModel;
use app\models\ProductModel;
use core\exceptions\ParameterException;
use core\exceptions\ProductException;

class ProductController extends AppController
{
    /**
     * @throws ProductException|ParameterException
     */
    public function viewAction(): void
    {
        $product_modal = new ProductModel();

        $alias = $this->route['alias'];
        /** @var object продукт*/
        $product = $product_modal->getProduct($alias);
        /** @var array модификации */
        $mods = $product_modal->getModifications($product);
        /** @var array галерея */
        $gallery = $product_modal->getGallery($product);
        /** @var array связанные товары */
        $related = $product_modal->getRelatedProducts($product);
        /** @var string хлебные крошки */
        $breadcrumbs = BreadcrumbsModel::getBreadcrumbs($product->category_id, $product->title);

        // запись в куки запрошенного товара
        $product_modal->setRecentlyViewed($product->id);
        /** @var array id просмотренных товаров */
        $recentlyViewedIds = $product_modal->getRecentlyViewedIds();
        /** @var array простомотренные товары */
        $recentlyViewed = null;

        // есть простотренные товары?
        if ($recentlyViewedIds) {
            $recentlyViewed = $product_modal->getRecentlyViewed($recentlyViewedIds);
        }

        $this->setMeta($product->title, $product->description, $product->keywords);
        $this->setData(compact(
            'product',
            'mods',
            'related',
            'gallery',
            'breadcrumbs',
            'recentlyViewed',
        ));
    }
}
