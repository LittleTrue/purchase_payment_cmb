<?php

namespace purchase\CmbBankClient\Base;

use purchase\CmbBankClient\Application;
use sm3\SM3;

/**
 * 身份验证.
 */
class Credential
{
    use MakesHttpRequests;

    /**
     * @var Application
     */
    protected $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * 签名.
     * @param param [签名对象数据]
     * @return string
     */
    public function signData(array $param)
    {
        $app_secret = $this->app['config']->get('bank_app_secret');
        $string     = '';

        $param['Data'] = json_encode($param['Data'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        foreach ($param as $key => $value) {
            if ('Sign' != $key) {
                $string = $string . $key . '=' . $value . '&';
            }
        }

        $sign_string = substr($string, 0, strlen($string) - 1) . '&appsecret=' . $app_secret;
        $sm3         = new SM3($sign_string);

        return strtolower($sm3);
    }
}
