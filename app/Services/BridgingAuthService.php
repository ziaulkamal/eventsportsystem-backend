<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Exception\RequestException;

class BridgingAuthService
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    public function getAccessToken()
    {
        $clientId = env('SATUSEHAT_CLIENT_ID');
        $clientSecret = env('SATUSEHAT_CLIENT_SECRET');
        $baseUrl = env('SATUSEHAT_BASE_URL');

        $url = "{$baseUrl}/accesstoken?grant_type=client_credentials";

        $options = [
            'form_params' => [
                'client_id' => $clientId,
                'client_secret' => $clientSecret,
            ]
        ];

        try {
            $request = new Request('POST', $url);
            $response = $this->client->sendAsync($request, $options)->wait();
            return json_decode($response->getBody(), true);
        } catch (RequestException $e) {
            $errorResponse = $e->getResponse();
            $errorMessage = $errorResponse ? $errorResponse->getBody()->getContents() : $e->getMessage();
            throw new \Exception("Failed to fetch access token: $errorMessage");
        }
    }
}
