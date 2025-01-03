<?php

namespace PcbPlus\PcbDingtalk\Services;

use PcbPlus\PcbDingtalk\Exceptions\ApiException;

class GroupService
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
     * @param string $title
     * @param string $templateId
     * @param string $ownerUserId
     * @param array $memberUserIds
     * @return array
     * @throws \PcbPlus\PcbDingtalk\Exceptions\ApiException
     * @see https://open.dingtalk.com/document/isvapp/create-group
     */
    public function createGroup($title, $templateId, $ownerUserId, $memberUserIds = [])
    {
        $body = $this->client->httpPost('https://oapi.dingtalk.com/topapi/im/chat/scenegroup/create', [
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ],
            'query' => [
                'access_token' => $this->client->ensureAccessToken(),
            ],
            'json' => [
                'title' => $title,
                'template_id' => $templateId,
                'owner_user_id' => $ownerUserId,
                'user_ids' => implode(',', [$ownerUserId, ...$memberUserIds]),
            ],
        ]);

        if ($body['errcode'] != 0) {
            throw new ApiException($body['errmsg'], $body['errcode']);
        }

        return $body['result'];
    }
}
