<?php

use purchase\CmbBankSepClient\Application;
use purchase\CmbBankSepService\BankSeparateService;

require_once __DIR__ . '/vendor/autoload.php';

//主体参数
$config = [
    'base_uri'      => 'https://api.cmburl.cn:8065/',
    'appid'         => 'beb94776-8238-4c97-b29e-5e32584f98f9',
    'verify'        => 'SHA256Verify',
    'version'       => '0.2.0',
    'secret'        => '123456',  // 用于apisign 
    'secret_key'    => '123456', // 用于sign
];

$ioc_con_app = new Application($config);

$bankService = new BankSeparateService($ioc_con_app);

$process = 3; // 1:nra付汇申请 2: 3:提现请求 4:提现结果查询

//nra付汇申请
if (1 == $process) {
    $info = [
        [
            'merchNo'           => '308999160120006',
            'orderNo'           => '200224204630010020000486',
            'orderDate'         => '2021-04-13',
            'mainOrderMerchNo'  => '308999170120GK3',
            'mainOrderNo'       => '2358327362443478',
            'payerName'         => '张三',
            'payerIdNo'         => '352203123456780001',
            'logisticsDate'     => '2021-04-14',
            'orderAmount'       => '18.01',
            'remitFlag'         => 'Y',
        ]
    ];
    
    try {
        $tmp = $bankService->submitNraPayment($info);
    } catch(\Exception $e) {
        var_dump($e->getMessage());die();
    }

    var_dump($tmp);
}

/** ----------------------------------------
 * 平台自有电子记账簿 --  平台信息查询接口    |
 *------------------------------------------
 */
if (66 == $process) {
    $info = [
        'platformNo' => 'P00007',
    ];
    
    try {
        $tmp = $bankService->platformActInfo($info);var_dump($tmp);die();
    } catch(\Exception $e) {
        var_dump($e->getMessage());die();
    }

    var_dump($tmp);
}

/** -----------------
 * 提现 --  提现请求 |
 *-------------------
 */
if (3 == $process) {
    $info = [
        'platformNo'        => 'P00007',
        'memberNo'          => 'M0000121',
        'memberName'        => '平台金融中心_电商模式_菜美美_003',
        'withdrawalReqNo'   => '20200409140114',
        'amount'            => '1.01',
        'remark'            => '提现测试',
        'subActType'        => '0', //子单元类型(选填) 0平台应分资金记账子单元 1平台手续费记账子单元 2平台补贴记账子单元 3平台退款垫资记账子单元
    ];
    
    try {
        $tmp = $bankService->withdrawalDeposit($info);var_dump($tmp);
    } catch(\Exception $e) {
        var_dump($e->getMessage());die();
    }

    var_dump($info);
    die();
}

/** -----------------
 * 提现 --  提现结果查询 |
 *-------------------
 */
if (4 == $process) {
    $info = [
        'platformNo'        => 'P00007',
        'withdrawalReqNo'   => '20200409140114',
    ];
    
    try {
        $tmp = $bankService->withdrawalQuery($info);
    } catch(\Exception $e) {
        var_dump($e->getMessage());die();
    }

    var_dump($tmp);
    die();
}