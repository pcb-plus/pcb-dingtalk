<?php

namespace PcbPlus\PcbDingtalk\Services;

use PcbPlus\PcbDingtalk\Enums\GroupMessageTemplateEnum;
use PcbPlus\PcbDingtalk\Exceptions\ApiException;

class GroupMessageService
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
     * @param string $messageTemplate
     * @param array $data
     * @param string $robotId
     * @param string $groupId
     * @param bool $isAtAll
     * @param array|string $atUsers
     * @return void
     * @throws \PcbPlus\PcbDingtalk\Exceptions\ApiException
     * @see https://open.dingtalk.com/document/orgapp/send-group-helper-message
     */
    public function sendMessage($messageTemplate, $data, $robotId, $groupId, $isAtAll = false, $atUsers = [])
    {
        $body = $this->client->httpPost('https://oapi.dingtalk.com/topapi/im/chat/scencegroup/message/send_v2', [
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ],
            'query' => [
                'access_token' => $this->client->ensureAccessToken(),
            ],
            'json' => $this->createPayload($messageTemplate, $data, $robotId, $groupId, $isAtAll, $atUsers),
        ]);

        if ($body['errcode'] != 0) {
            throw new ApiException($body['errmsg'], $body['errcode']);
        }
    }

    /**
     * @param string $messageTemplate
     * @param array $data
     * @param string $robotId
     * @param string $groupId
     * @param bool $isAtAll
     * @param array|string $atUsers
     * @return array
     */
    protected function createPayload($messageTemplate, $data, $robotId, $groupId, $isAtAll = false, $atUsers = [])
    {
        $jsonArr = [
            'robot_code' => $robotId,
            'target_open_conversation_id' => $groupId,
            'is_at_all' => $isAtAll,
            'at_users' => is_array($atUsers) ? implode(',', $atUsers) : $atUsers,
            'msg_template_id' => 'inner_app_template_text',
            'msg_param_map' => [],
            'msg_media_id_param_map' => [],
        ];

        switch ($messageTemplate) {
            case GroupMessageTemplateEnum::INNER_APP_TEMPLATE_PHOTO:
                $jsonArr['msg_media_id_param_map'] = json_encode($data);
                break;

            default:
                $jsonArr['msg_param_map'] = json_encode($data);
                break;
        }

        return array_filter($jsonArr);
    }
}
