<?php

namespace purchase\CmbBankSepService;

use purchase\CmbBankSepClient\Application;
use purchase\CmbBankSepClient\Base\Exceptions\ClientError;

/**
 * 银行基本数据查询请求客户端.
 */
class BankSeparateService
{
    /**
     * @var BaseSeparate
     */
    private $_BankSeparateClient;

    public function __construct(Application $app)
    {
        $this->_BankSeparateClient = $app['bank_separate'];
    }

    /**
     * NRA付汇--发起付汇(跨境电商订单申报).
     *
     * @throws ClientError
     * @throws \Exception
     */
    public function submitNraPayment(array $infos)
    {
        if (empty($infos)) {
            throw new ClientError('参数缺失', 1000001);
        }

        return $this->_BankSeparateClient->submitNraPayment($infos);
    }

    // ----------------------------------分隔符---------------------------------------

    /**
     * 一般贸易资金分账 -- 提现请求
     */
    public function withdrawalDeposit(array $infos)
    {
        if (empty($infos)) {
            throw new ClientError('参数缺失', 1000001);
        }

        return $this->_BankSeparateClient->withdrawalDeposit($infos);
    }

    /**
     * 一般贸易资金分账 -- 提现结果查询
     */
    public function withdrawalQuery(array $infos)
    {
        if (empty($infos)) {
            throw new ClientError('参数缺失', 1000001);
        }

        return $this->_BankSeparateClient->withdrawalQuery($infos);
    }

    /**
     * 平台自有电子记账簿 -- 平台信息查询接口
     */
    public function platformActInfo(array $infos)
    {
        if (empty($infos)) {
            throw new ClientError('参数缺失', 1000001);
        }

        return $this->_BankSeparateClient->platformActInfo($infos);
    }

    /**
     * 平台自有电子记账簿 -- 平台电子记账簿账务记录查询接口
     */
    public function platformSubActTradeInfo(array $infos)
    {
        if (empty($infos)) {
            throw new ClientError('参数缺失', 1000001);
        }

        return $this->_BankSeparateClient->platformSubActTradeInfo($infos);
    }
}
