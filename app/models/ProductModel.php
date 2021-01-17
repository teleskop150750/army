<?php

namespace app\models;

use core\exceptions\ProductException;
use RedBeanPHP\R;

class ProductModel extends AppModel
{
    /**
     * получить продукт
     * @param string $alias алиас
     * @return object продукт
     * @throws ProductException
     */
    public function getProduct(string $alias): object
    {
        $product = R::findOne('product', "alias = ? AND status = '1'", [$alias]);
        if (!$product) {
            throw new ProductException('продукт не найден', 404);
        }
        return $product;
    }

    /**
     * получить модификации товара
     * @param object $product модификации
     * @return array
     */
    public function getModifications(object $product): array
    {
        return R::findAll('modification', 'product_id = ?', [$product->id]);
    }

    /**
     * получить галерею
     * @param object $product продукт
     * @return array галерея
     */
    public function getGallery(object $product): array
    {
        return R::findAll('gallery', 'product_id = ?', [$product->id]);
    }

    /**
     * получить сопутсвующие товары
     * @param object $product продукт
     * @return array|null сопутсвующие товары
     */
    public function getRelatedProducts(object $product): ?array
    {
        return R::getAll(
            "SELECT * 
                FROM related_product JOIN product ON product.id = related_product.related_id
                WHERE related_product.product_id = ?",
            [$product->id]
        );
    }

    /**
     * записать в куки просмотренный товар
     * @param int $id id продукста
     */
    public function setRecentlyViewed(int $id): void
    {
        $recentlyViewed = $this->getAllRecentlyViewed();
        if (!$recentlyViewed) {
            setcookie('recentlyViewed', $id, time() + 3600 * 24, '/');
        } else {
            $recentlyViewed = explode('.', $recentlyViewed);

            if (!in_array($id, $recentlyViewed, true)) {
                $recentlyViewed[] = $id;
                $recentlyViewed = implode('.', $recentlyViewed);
                setcookie('recentlyViewed', $recentlyViewed, time() + 3600 * 24, '/');
            }
        }
    }

    /**
     * получить все просметрнные продукты
     * @return bool|string продукты
     */
    public function getAllRecentlyViewed()
    {
        if (!empty($_COOKIE['recentlyViewed'])) {
            return $_COOKIE['recentlyViewed'];
        }
        return false;
    }


    /**
     * получить 3 id просмотренных товаров
     * @return array|false id товаров
     */
    public function getRecentlyViewedIds()
    {
        // есть простометрные товары
        if (!empty($_COOKIE['recentlyViewed'])) {
            $recentlyViewed = $_COOKIE['recentlyViewed'];
            $recentlyViewed = explode('.', $recentlyViewed);
            return array_slice($recentlyViewed, -3);
        }
        return false;
    }

    /**
     * получить 3 просмотренныя товара
     * @param array $productsId id товаров
     * @return array товары
     */
    public function getRecentlyViewed(array $productsId): array
    {
        return R::find('product', 'id IN (' . R::genSlots($productsId) . ') LIMIT 3', $productsId);
    }
}
