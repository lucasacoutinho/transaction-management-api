<?php

namespace App\Providers;

use App\Repositories\AccountRepositoryInterface;
use App\Repositories\Api\AuthorizerRepository;
use App\Repositories\AuthorizerInterfaceRepository;
use App\Repositories\Eloquent\AccountRepository;
use App\Repositories\Eloquent\BaseRepository;
use App\Repositories\Eloquent\TransactionRepository;
use App\Repositories\EloquentRepositoryInterface;
use App\Repositories\TransactionRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(EloquentRepositoryInterface::class, BaseRepository::class);
        $this->app->bind(AccountRepositoryInterface::class, AccountRepository::class);
        $this->app->bind(TransactionRepositoryInterface::class, TransactionRepository::class);
        $this->app->bind(AuthorizerInterfaceRepository::class, AuthorizerRepository::class);
    }

    public function boot()
    {
        //
    }
}
