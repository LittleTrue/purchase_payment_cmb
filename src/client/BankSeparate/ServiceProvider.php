<?php

namespace purchase\CmbBankSepClient\BankSeparate;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

/**
 * Class ServiceProvider.
 */
class ServiceProvider implements ServiceProviderInterface
{
    public function register(Container $app)
    {
        $app['bank_separate'] = function ($app) {
            return new Client($app);
        };
    }
}
