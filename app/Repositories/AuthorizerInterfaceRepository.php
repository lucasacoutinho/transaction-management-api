<?php

namespace App\Repositories;

interface AuthorizerInterfaceRepository
{
    public function authorize(array $data = []): array;
}
