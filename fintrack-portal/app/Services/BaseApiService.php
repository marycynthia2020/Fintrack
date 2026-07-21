<?php

namespace App\Services;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

abstract class BaseApiService
{
    protected function client(): PendingRequest
    {
        $client = Http::baseUrl(config('services.fintrack.api_url'))
            ->acceptJson();

        if (session()->has('api_token')) {
            $client = $client->withToken(session('api_token'));
        }

        return $client;
    }

    protected function get(string $uri, array $query = [])
    {
        return $this->client()->get($uri, $query);
    }

    protected function post(string $uri, array $data = [])
    {
        return $this->client()->post($uri, $data);
    }

    protected function put(string $uri, array $data = [])
    {
        return $this->client()->put($uri, $data);
    }

    protected function delete(string $uri)
    {
        return $this->client()->delete($uri);
    }
}