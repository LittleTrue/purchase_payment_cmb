<?php

namespace purchase\CmbBankSepClient\BankSeparate;

use purchase\CmbBankSepClient\Application;
use purchase\CmbBankSepClient\Base\BaseClient;
use purchase\CmbBankSepClient\Base\Exceptions\ClientError;

/**
 * 银行基本数据查询请求客户端.
 */
class Client extends BaseClient
{
    public function __construct(Application $app)
    {
        parent::__construct($app);
    }

    /**
     * NRA付汇--发起付汇(跨境电商订单申报).
     *
     * @throws ClientError
     */
    public function submitNraPayment(array $infos)
    {
        $this->setParams($infos);

        return $this->httpPostJson('');
    }

    // -----------------------------------------------分割-----------------------------------------------

    /**
     * 一般贸易资金分账 -- 提现请求
     *
     * @throws ClientError
     */
    public function withdrawalDeposit(array $infos)
    {
        // 设置传参
        $this->setParams($infos);

        return $this->httpPostJson('midsrv/jyt/withdrawalDepositSyn');
    }

    /**
     * 一般贸易资金分账 -- 提现请求
     *
     * @throws ClientError
     */
    public function withdrawalQuery(array $infos)
    {
        // 设置传参
        $send_data = $this->setFormParams($infos);

        return $this->httpGet('midsrv/jyt/withdrawalDeposit?' . $send_data);
    }
}
