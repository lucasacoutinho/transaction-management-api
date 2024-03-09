<?php

namespace App\Repositories\Api;

use App\Repositories\AuthorizerInterfaceRepository;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class AuthorizerRepository implements AuthorizerInterfaceRepository
{
    protected PendingRequest $client;

    public function __construct()
    {
        $this->client = Http::withToken(token: config('services.authorizer.token'));
    }

    public function authorize(array $data = []): array
    {
        $response = $this->client->post(
            url: config('services.authorizer.endpoint'),
            data: $data,
        );

        return $response->json();
    }
}
