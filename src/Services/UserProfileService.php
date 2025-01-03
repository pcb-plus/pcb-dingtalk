<?php

namespace PcbPlus\PcbDingtalk\Services;

class UserProfileService
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
     * @param string $userAccessToken
     * @param string $unionId
     * @return array
     * @see https://open.dingtalk.com/document/orgapp/dingtalk-retrieve-user-information
     */
    public function getProfile($userAccessToken, $unionId = 'me')
    {
        return $this->client->httpGet("https://api.dingtalk.com/v1.0/contact/users/{$unionId}", [
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'x-acs-dingtalk-access-token' => $userAccessToken,
            ]
        ]);
    }
}
