<?php

namespace PcbPlus\PcbDingtalk\Services;

class OAuthService
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
     * @param string $redirectUrl
     * @param string $state
     * @return string
     * @see https://open.dingtalk.com/document/orgapp/tutorial-obtaining-user-personal-information
     */
    public function buildAuthUrl($redirectUrl, $state = '')
    {
        $query = [
            'redirect_uri' => $redirectUrl,
            'response_type' => 'code',
            'client_id' => $this->client->getConfig('app_id'),
            'scope' => 'openid',
            'state' => $state,
            'prompt' => 'consent',
        ];

        return sprintf('https://login.dingtalk.com/oauth2/auth?%s', http_build_query($query));
    }
}
