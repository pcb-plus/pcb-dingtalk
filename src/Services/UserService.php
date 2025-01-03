<?php

namespace PcbPlus\PcbDingtalk\Services;

use PcbPlus\PcbDingtalk\Exceptions\ApiException;

class UserService
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
     * @param string $userId
     * @return array
     * @throws \PcbPlus\PcbDingtalk\Exceptions\ApiException
     * @see https://open.dingtalk.com/document/orgapp/query-user-details
     */
    public function getUser($userId)
    {
        $body = $this->client->httpPost('https://oapi.dingtalk.com/topapi/v2/user/get', [
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ],
            'query' => [
                'access_token' => $this->client->ensureAccessToken(),
            ],
            'json' => [
                'userid' => $userId,
                'language' => 'zh_CN',
            ]
        ]);

        if ($body['errcode'] != 0) {
            throw new ApiException($body['errmsg'], $body['errcode']);
        }

        return $body['result'];
    }

    /**
     * @param string $phone
     * @return array
     * @throws \PcbPlus\PcbDingtalk\Exceptions\ApiException
     * @see https://open.dingtalk.com/document/orgapp/query-users-by-phone-number
     */
    public function getUserByPhoneNumber($phone)
    {
        $body = $this->client->httpPost('https://oapi.dingtalk.com/topapi/v2/user/getbymobile', [
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ],
            'query' => [
                'access_token' => $this->client->ensureAccessToken(),
            ],
            'json' => [
                'mobile' => $phone,
            ]
        ]);

        if ($body['errcode'] != 0) {
            throw new ApiException($body['errmsg'], $body['errcode']);
        }

        return $body['result'];
    }

    /**
     * @param string $unionId
     * @return array
     * @throws \PcbPlus\PcbDingtalk\Exceptions\ApiException
     * @see https://open.dingtalk.com/document/orgapp/query-a-user-by-the-union-id
     */
    public function getUserByUnionId($unionId)
    {
        $body = $this->client->httpPost('https://oapi.dingtalk.com/topapi/user/getbyunionid', [
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ],
            'query' => [
                'access_token' => $this->client->ensureAccessToken(),
            ],
            'json' => [
                'unionid' => $unionId,
            ]
        ]);

        if ($body['errcode'] != 0) {
            throw new ApiException($body['errmsg'], $body['errcode']);
        }

        return $body['result'];
    }
}
