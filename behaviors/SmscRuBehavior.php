<?php

namespace blackice\smscru\behaviors;

use yii;
use yii\base\Behavior;

class SmscRuBehavior extends Behavior
{

    public function init()
    {
        parent::init();
    }

    private static function confirmCode($phone)
    {
        $config = Yii::$app->params['smsc'];

        $params = [
            'charset'   => $config['charset'],
            'fmt'       => $config['fmt'],
            'login'     => $config['login'],
            'psw'       => $config['password'],
            'phones'    => urlencode($phone)
        ];

        if ($config['call'])
        {
            $params['call'] = 1;
            $params['mes']  = 'code';
        }

        else {
            $code           = mt_rand(1,9).mt_rand(0,9).mt_rand(0,9).mt_rand(0,9).mt_rand(0,9);
            $params['mes']  = urlencode(\Yii::t('smsc.ru', 'confirm_code_txt').': '.$code);
        }

        $fields = http_build_query($params, '', '&');

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_URL, $config['url']."?".$fields);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($ch);
        curl_close($ch);

        $result         = json_decode($result, true);
        $result['code'] = ($config['call']) ? $result['code'] : $code;

        return $result;
    }
}