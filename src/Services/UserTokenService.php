<?php

namespace PcbPlus\PcbDingtalk\Services;

class UserTokenService
{
    /**
     * @var \PcbPlus\PcbDingtalk\Client
     */
    protected $client;

    /**
     * @param \PcbPlus\PcbDingtalk\Client $client
     */
    public function __construct($client)
    {
        $this->client = $client;
    }

    /**
     * @param string $authCode
     * @return array
     * @see https://open.dingtalk.com/document/orgapp/obtain-user-token
     */
    public function fetchToken($authCode)
    {
        return $this->client->httpPost('https://api.dingtalk.com/v1.0/oauth2/userAccessToken', [
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ],
            'json' => [
                'clientId' => $this->client->getConfig('app_id'),
                'clientSecret' => $this->client->getConfig('app_secret'),
                'code' => $authCode,
                'refreshToken' => '',
                'grantType' => 'authorization_code',
            ]
        ]);
    }
}
