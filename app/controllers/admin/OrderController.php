<?php

namespace app\controllers\admin;

use app\models\admin\OrderModel;
use core\exceptions\IdException;
use core\exceptions\OrderException;
use core\libs\Pagination;
use RedBeanPHP\RedException\SQL;

class OrderController extends AdminController
{
    public function indexAction(): void
    {
        $order_modal = new OrderModel();

        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $perPage = 3;
        $total = $order_modal->getTotal();
        $pagination = new Pagination($page, $perPage, $total);
        $start = $pagination->getStart();

        $orders = $order_modal->getOrders($start, $perPage);

        $this->setMeta('Список заказов');
        $this->setData(compact('orders', 'pagination', 'total'));
    }

    /**
     * @throws IdException
     */
    public function viewAction(): void
    {
        $order_modal = new OrderModel();

        $order_id = $this->getRequestID();
        $order = $order_modal->getOrder($order_id);

        $order_products = $order_modal->getOrderProducts($order_id);

        $this->setMeta("Заказ №{$order_id}");
        $this->setData(compact('order', 'order_products'));
    }

    /**
     * @throws IdException
     * @throws SQL|OrderException
     */
    public function changeAction(): void
    {
        $order_modal = new OrderModel();

        $order_id = $this->getRequestID();
        $status = !empty($_GET['status']) ? '1' : '0';

        $order_modal->updateOrder($order_id, $status);

        $_SESSION['success'] = 'Изменения сохранены';
        redirect();
    }

    public function deleteAction(): void
    {
        $order_modal = new OrderModel();

        $order_id = $this->getRequestID();

        $order_modal->deleteOrder($order_id);

        $_SESSION['success'] = 'Заказ удален';
        redirect(ADMIN . '/order');
    }
}
