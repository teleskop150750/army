<?php

namespace app\models\admin;

use app\models\base\AppBaseModel;
use core\exceptions\OrderException;
use RedBeanPHP\R;
use RedBeanPHP\RedException\SQL;

class OrderModel extends AdminModel
{
    /**
     * получить количество записей
     * @return int
     */
    public function getTotal(): int
    {
        return R::count('order');
    }

    /**
     * получить заказы
     * @param int $start страница
     * @param int $perPage количетво на странице
     * @return array|null заказы
     */
    public function getOrders(int $start, int $perPage): ?array
    {
        return R::getAll("
        SELECT `order`.`id`, 
               `order`.`user_id`, 
               `order`.`status`, 
               `order`.`date`, 
               `order`.`update_at`, 
               `order`.`currency`, 
               `user`.`name`, 
               ROUND(SUM(`order_product`.`price`), 2) AS `sum` 
        FROM `order`
        JOIN `user` ON `order`.`user_id` = `user`.`id`
        JOIN `order_product` ON `order`.`id` = `order_product`.`order_id`
        GROUP BY `order`.`id`, `order`.`status` 
        ORDER BY `order`.`status`, `order`.`id` 
        LIMIT $start, $perPage
        ");
    }

    /**
     * получить заказ
     * @param int $order_id id заказа
     * @return array заказ
     * @throws OrderException
     */
    public function getOrder(int $order_id): array
    {
        $order = R::getRow("
        SELECT `order`.*, 
               `user`.`name`, 
               ROUND(SUM(`order_product`.`price`), 2) AS `sum` 
        FROM `order`
        JOIN `user` ON `order`.`user_id` = `user`.`id`
        JOIN `order_product` ON `order`.`id` = `order_product`.`order_id`
        WHERE `order`.`id` = ?
        GROUP BY `order`.`id`, `order`.`status` 
        ORDER BY `order`.`status`, `order`.`id` 
        LIMIT 1", [$order_id]);

        if (!$order) {
            throw new OrderException('Страница не найдена', 404);
        }

        return  $order;
    }

    /**
     * получить продукты заказа
     * @param int $order_id id заказа
     * @return array
     */
    public function getOrderProducts(int $order_id): array
    {
        return R::findAll('order_product', "order_id = ?", [$order_id]);
    }

    /**
     * обновить статус заказа
     * @param int $order_id id заказа
     * @param string $status статус
     * @throws OrderException|SQL
     */
    public function updateOrder(int $order_id, string $status): void
    {
        /** @var object $order */
        $order = R::load('order', $order_id);
        if (!$order) {
            throw new OrderException('Страница не найдена', 404);
        }
        $order->status = $status;
        $order->update_at = date("Y-m-d H:i:s");
        R::store($order);
    }

    /**
     * удалить заказ
     * @param int $order_id
     */
    public function deleteOrder(int $order_id): void
    {
        $order = R::load('order', $order_id);
        R::trash($order);
    }
}
