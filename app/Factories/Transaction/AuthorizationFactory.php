<?php

namespace App\Factories\Transaction;

use App\Contracts\AuthorizationContract;

class AuthorizationFactory
{
    public static function factory(string $strategy): AuthorizationContract
    {
        $key = 'services.authorizer.strategies.%s';
        $strategy = sprintf($key, $strategy);
        $strategy = config($strategy);

        if (!$strategy) {
            $strategy = sprintf($key, 'default');
            $strategy = config($strategy);
        }

        return app($strategy);
    }
}
