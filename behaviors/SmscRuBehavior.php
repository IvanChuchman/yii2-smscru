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

    public static function confirmCode($phone)
    {
        $config = Yii::$app->params['smsc'];
        $code   = mt_rand(1,9).mt_rand(0,9).mt_rand(0,9).mt_rand(0,9).mt_rand(0,9).mt_rand(0,9);
        if ($config['test']) return ['code' => $code];

        $params = [
            'fmt'       => 3, // json
            'charset'   => $config['charset'],
            'login'     => $config['login'],
            'psw'       => $config['password'],
            'phones'    => urlencode($phone)
        ];

        if ($config['call'])
        {
            $params['call'] = 1;
            $params['mes']  = 'code';
        }

        else
            $params['mes']  = urlencode(\Yii::t('smsc.ru', 'confirm_code_txt').': '.$code);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_URL, $config['url']."?".http_build_query($params, '', '&'));
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($ch);
        curl_close($ch);

        $result         = json_decode($result, true);
        $result['code'] = ($config['call']) ? $result['code'] : $code;

        return $result;
    }
}