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

        //Yii::$app->params['menu']
    }

    public function getForm($button = '<button class="btn btn-success btn-sm">Pay invoice</button>')
    {
        if ($this->owner->type == $this->type)
        {
            $liqpay = new LiqPay('i21394330880', 'Tlvw7N04QB3ltEeWsGBIx1gtguantkwHfviYRM0K');

            return $liqpay->cnb_form([
                'action'   => 'hold',
                'version'  => '3',
                'language' => 'en',
                'currency' => 'UAH',
                'sandbox'  => true,

                'amount'      => $this->owner->{$this->amountAttr},
                'description' => "Invoice #" . $this->owner->{$this->order_idAttr},
                'order_id'    => $this->owner->{$this->order_idAttr},
                'server_url'  => $_SERVER['HTTP_HOST'] . '/'.$this->owner->id.'/' . $this->callbackAction,
                'button'      => $button
            ]);
        }
    }
}