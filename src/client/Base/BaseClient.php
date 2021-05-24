<?php

namespace purchase\CmbBankSepClient\Base;

use GuzzleHttp\RequestOptions;
use purchase\CmbBankSepClient\Application;
use purchase\CmbBankSepClient\Base\Exceptions\ClientError;

/**
 * 底层请求.
 */
class BaseClient
{
    use MakesHttpRequests;

    /**
     * @var Application
     */
    protected $app;

    /**
     * @var array
     */
    protected $json = [];

    /**
     * @var array
     */
    protected $headers = [];

    /**
     * @var string
     */
    protected $language = 'zh-cn';

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Set json params.
     *
     * @param array $json Json参数
     */
    public function setParams(array $json)
    {
        $timestamp = time();

        ksort($json);

        //生成签名
        $sign = $this->sign($json);

        $api_sign = $this->apiSign(
            $this->app['config']->get('appid'),
            $this->app['config']->get('secret'),
            $sign,
            $timestamp
        );

        $headers = [
            'appid'         => $this->app['config']->get('appid'),
            'timestamp'     => $timestamp,
            'sign'          => $sign,
            'apisign'       => $api_sign,
            'verify'        => $this->app['config']->get('verify'),
            'version'       => $this->app['config']->get('version'),
        ];

        $this->json = $json;
        $this->headers = $headers;
    }

    /**
     * Set Form params.
     *
     * @param array $params 参数
     */
    public function setFormParams(array $params)
    {
        $timestamp = time();

        $string = '';

        ksort($params);

        //生成签名
        $sign = $this->sign($params);

        $api_sign = $this->apiSign(
            $this->app['config']->get('appid'),
            $this->app['config']->get('secret'),
            $sign,
            $timestamp
        );

        foreach ($params as $key => $value) {
            $string .= $key . '=' . $value . '&';
        }

        $string = substr($string, 0, -1);

        $headers = [
            'appid'         => $this->app['config']->get('appid'),
            'timestamp'     => $timestamp,
            'sign'          => $sign,
            'apisign'       => $api_sign,
            'verify'        => $this->app['config']->get('verify'),
            'version'       => $this->app['config']->get('version'),
        ];

        $this->headers = $headers;

        return $string;
    }

    /**
     * Set Headers Language params.
     *
     * @param string $language 请求头中的语种标识
     */
    public function setLanguage($language)
    {
        $this->language = $language;
    }

    /**
     * Make a get request.
     *
     * @throws ClientError
     */
    public function httpGet($uri, array $options = [])
    {
        $options = $this->_headers($options);

        return $this->request('GET', $uri, $options);
    }

    /**
     * Make a post request.
     *
     * @throws ClientError
     */
    public function httpPostJson($uri)
    {
        return $this->requestPost($uri, [RequestOptions::JSON => $this->json, RequestOptions::HEADERS => $this->headers]);
    }

    /**
     * 获取特定位数时间戳.
     * @return int
     */
    public function getTimestamp(int $digits = 10)
    {
        $digits = $digits > 10 ? $digits : 10;

        $digits = $digits - 10;

        if ((!$digits) || (10 == $digits)) {
            return time();
        }

        return number_format(microtime(true), $digits, '', '') - 50000;
    }

    /**
     * @throws ClientError
     */
    protected function requestPost($uri, array $options = [])
    {
        return $this->request('POST', $uri, $options);
    }

    /**
     * 获取报文流水号.
     * @return string
     */
    protected function generateMessageId()
    {
        return date('ymd') . substr(substr(microtime(), 2, 6)
        * time(), 2, 6) . mt_rand(1000, 9999);
    }

    /**
     * set Headers.
     *
     * @return array
     */
    private function _headers(array $options = [])
    {
        $options[RequestOptions::HEADERS] = $this->headers;

        return $options;
    }

    /**
     * 生成签名.
     */
    private function sign($data)
    {
        $string = '';

        ksort($data);

        foreach ($data as $key => $value) {
            $string .= $key . '=' . $value . '&';
        }

        $string = substr($string, 0, -1);

        $sign = hash_hmac('sha256', $string, $this->app['config']->get('secret_key'));

        return $this->strToHex($sign);
    }

    /**
     * 生成api签名.
     */
    private function apiSign($appid, $secret, $sign, $timestamp)
    {
        $string = 'appid=' . $appid . '&secret=' . $secret . '&sign=' . $sign . '&timestamp=' . $timestamp;

        return hash("sha256", $string);
    }

    /**
    * 字符串转十六进制函数
    */
    private function strToHex($str)
    {
        $hex = '';

        $hex = bin2hex($str);

        return $hex;
    }

    /**
     *  十六进制转字符串函数
     *  @pream string $hex='616263';
     */
    public function hexToStr($hex)
    {
        $str = "";
        for ($i = 0;$i < strlen($hex) - 1;$i+= 2) {
            $str.= chr(hexdec($hex[$i] . $hex[$i + 1]));
        }
        return $str;
    }
}
