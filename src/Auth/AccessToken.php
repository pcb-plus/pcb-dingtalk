<?php

namespace PcbPlus\PcbDingtalk\Auth;

use Exception;
use PcbPlus\PcbDingtalk\Exceptions\ApiException;

class AccessToken
{
    /**
     * @var \PcbPlus\PcbDingtalk\Config
     */
    protected $config;

    /**
     * @var \GuzzleHttp\ClientInterface
     */
    protected $http;

    /**
     * @var \Psr\SimpleCache\CacheInterface
     */
    protected $cache;

    /**
     * @param \PcbPlus\PcbDingtalk\Config $config
     * @param \Psr\SimpleCache\CacheInterface $http
     * @param \GuzzleHttp\ClientInterface $cache
     */
    public function __construct($config, $http, $cache)
    {
        $this->config = $config;
        $this->http = $http;
        $this->cache = $cache;
    }

    /**
     * @return string
     * @throws \PcbPlus\PcbDingtalk\Exceptions\ApiException
     */
    public function ensureAccessToken()
    {
        $token = $this->getTokenFromCache();

        if ($token) {
            return $token;
        }

        try {
            $responseData = $this->fetchToken();

            $token = $responseData['accessToken'];

            $ttl = $responseData['expireIn'] - 30;

            $this->putTokenToCache($token, $ttl);

            return $token;
        } catch (Exception $e) {
            throw new ApiException($e->getMessage(), 0, $e);
        }
    }

    /**
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @see https://open.dingtalk.com/document/orgapp/obtain-the-access_token-of-an-internal-app
     */
    public function fetchToken()
    {
        $response = $this->http->request('POST', 'https://api.dingtalk.com/v1.0/oauth2/accessToken', [
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ],
            'json' => [
                'appKey' => $this->config['app_id'],
                'appSecret' => $this->config['app_secret'],
            ]
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * @return string
     */
    public function getTokenFromCache()
    {
        $key = $this->getCacheKey();

        return (string) $this->cache->get($key);
    }

    /**
     * @param string $token
     * @param int $ttl
     * @return bool
     */
    public function putTokenToCache($token, $ttl)
    {
        $key = $this->getCacheKey();

        return $this->cache->set($key, $token, $ttl);
    }

    /**
     * @return string
     */
    protected function getCacheKey()
    {
        $prefix = $this->config['cache_prefix'];

        $appId = $this->config['app_id'];

        return $prefix . md5(json_encode([$appId, get_class()]));
    }
}
