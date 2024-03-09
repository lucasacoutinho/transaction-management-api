<?php

namespace App\Repositories\Eloquent;

use App\Models\Account;
use App\Repositories\AccountRepositoryInterface;
use App\Repositories\Eloquent\BaseRepository;

class AccountRepository extends BaseRepository implements AccountRepositoryInterface
{
    protected $model;

    public function __construct(Account $model)
    {
        $this->model = $model;
    }
}
