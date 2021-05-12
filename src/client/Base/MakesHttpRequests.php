<?php

namespace purchase\CmbBankClient\Base;

use GuzzleHttp\Psr7\Response;
use purchase\CmbBankClient\Base\Exceptions\ClientError;

/**
 * Trait MakesHttpRequests.
 */
trait MakesHttpRequests
{
    /**
     * @var bool
     */
    protected $transform = true;

    /**
     * @var string
     */
    protected $baseUri = '';

    /**
     * @throws ClientError
     */
    public function request($method, $uri, array $options = [])
    {
        $uri = $this->app['config']->get('base_uri') . $uri;

        $response = $this->app['http_client']->request($method, $uri, $options);

        return $this->transform ? $this->transformResponse($response) : $response;
    }

    /**
     * @throws ClientError
     */
    protected function transformResponse(Response $response)
    {
        if (200 != $response->getStatusCode()) {
            throw new ClientError(
                "接口连接异常，异常码：{$response->getStatusCode()}，请联系管理员",
                $response->getStatusCode()
            );
        }

        return json_decode($response->getBody()->getContents(), true);
        // switch ($result['code']) {
        //     /* 3002、3003 <==> 无效、过期 */
        //     case 3002:
        //     case 3003:
        //         /** @var Credential $credential */
        //         $credential = $this->app['credential'];
        //         $credential->token();
        //         throw new ClientError($result['msg'], $result['code']);
        //     case 1002967:
        //         //该种错误需要额外解析数组, 此时针对商品信息, 统一处理错误信息
        //         $error_arr = $result['data'];

        //         $total_message = '';

        //         foreach ($error_arr as $key => $value) {
        //             $total_message .= $value['msg'] .  ' || ';
        //         }
        //         throw new ClientError($total_message, $result['code']);
        //     case 200:
        //         return $result;
        //     default:
        //         throw new ClientError($result['msg'], $result['code']);
        // }
    }
}
