<?php

namespace blackice\smscru\behaviors;

use blackice\liqpay\LiqPay;
use yii;
use yii\base\Behavior;

class SmscRuBehavior extends Behavior
{
    public $attribute = 'amount';
    public $order_idAttr = 'order_id';
    public $amountAttr = 'total';
    public $type = null;
    public $callbackAction = 'callback';

    public function init()
    {
        parent::init();
    }

    public static function send($data)
    {
        $config = Yii::$app->params['smsc'];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        //curl_setopt($ch, CURLOPT_URL, "http://smsc.ru/sys/send.php?flash=1&charset=utf-8&login=".$config['login']."&psw=".$config['password']."&phones=".urlencode($data['phone'])."&mes=".urlencode($data['message']));
        curl_setopt($ch, CURLOPT_URL, "http://smsc.ru/sys/send.php?charset=utf-8&login=".$config['login']."&psw=".$config['password']."&phones=".urlencode($data['phone'])."&mes=".urlencode($data['message']));
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($ch);
        curl_close($ch);
    }
}