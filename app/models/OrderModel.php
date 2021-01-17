<?php

namespace app\models;

use core\exceptions\ParameterException;
use RedBeanPHP\R;
use core\App;
use RedBeanPHP\RedException\SQL;
use RedBeanPHP\OODBBean;
use Swift_Mailer;
use Swift_Message;
use Swift_SmtpTransport;

class OrderModel extends AppModel
{

    /**
     * сохранить заказ
     * @param array $data
     * @return int|string
     * @throws SQL
     */
    public static function saveOrder(array $data)
    {
        /** @var array|object|OODBBean $order */
        $order = R::dispense('order');
        $order->user_id = $data['user_id'];
        $order->note = $data['note'];
        $order->currency = $_SESSION['cart.currency']['code'];
        $order_id = R::store($order);
        self::saveOrderProduct($order_id);
        return $order_id;
    }

    /**
     * сохранить продукты заказа
     * @param int|string $order_id
     */
    public static function saveOrderProduct($order_id): void
    {
        $sql_part = '';
        foreach ($_SESSION['cart'] as $product_id => $product) {
            $product_id = (int)$product_id;
            $sql_part .= "($order_id, $product_id, {$product['qty']}, '{$product['title']}', {$product['price']}),";
        }
        $sql_part = rtrim($sql_part, ',');
        R::exec("INSERT INTO order_product (order_id, product_id, qty, title, price) VALUES $sql_part");
    }

    /**
     * оправить на почту
     * @param int|string $order_id
     * @param string $user_email
     * @throws ParameterException
     */
    public static function mailOrder($order_id, string $user_email): void
    {
        // Create the Transport
        $transport = (new Swift_SmtpTransport(
            App::$app->getProperty('smtp_host'),
            App::$app->getProperty('smtp_port'),
            App::$app->getProperty('smtp_protocol')
        ))
            ->setUsername(App::$app->getProperty('smtp_login'))
            ->setPassword(App::$app->getProperty('smtp_password'));
        // Create the Mailer using your created Transport
        $mailer = new Swift_Mailer($transport);

        // Create a message
        ob_start();
        require_once APP . '/views/mail/mail_order.php';
        $body = ob_get_clean();

        $message_client = (new Swift_Message(
            "Вы совершили заказ №" . $order_id . " на сайте " . App::$app->getProperty('shop_name')
        ))
            ->setFrom([App::$app->getProperty('smtp_login') => App::$app->getProperty('shop_name')])
            ->setTo($user_email)
            ->setBody($body, 'text/html');

        $message_admin = (new Swift_Message("Сделан заказ №{$order_id}"))
            ->setFrom([App::$app->getProperty('smtp_login') => App::$app->getProperty('shop_name')])
            ->setTo(App::$app->getProperty('admin_email'))
            ->setBody($body, 'text/html');

        // Send the message
        $mailer->send($message_client);
        $mailer->send($message_admin);
        unset(
            $_SESSION['cart'],
            $_SESSION['cart.qty'],
            $_SESSION['cart.sum'],
            $_SESSION['cart.currency']
        );
        $_SESSION['success'] = 'Спасибо за Ваш заказ. 
        В ближайшее время с Вами свяжется менеджер для согласования заказа';
    }
}
