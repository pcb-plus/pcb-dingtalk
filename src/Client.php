<?php

namespace PcbPlus\PcbDingtalk;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use PcbPlus\PcbDingtalk\Auth\AccessToken;
use PcbPlus\PcbDingtalk\Exceptions\ApiException;
use Psr\SimpleCache\CacheInterface;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Cache\Psr16Cache;

class Client
{
    /**
     * @var \PcbPlus\PcbDingtalk\Config
     */
    protected $config;

    /**
     * @var \Psr\SimpleCache\CacheInterface
     */
    protected $cache;

    /**
     * @var \GuzzleHttp\ClientInterface
     */
    protected $http;

    /**
     * @var array
     */
    protected $token = [];

    /**
     * @param array $config
     */
    public function __construct($config)
    {
        $this->config = new Config(
            array_merge([
                'app_id' => '',
                'app_secret' => '',
                'cache_prefix' => '',
            ], $config)
        );
    }

    /**
     * @param string $name
     * @param mixed $default
     * @return mixed
     */
    public function getConfig($name, $default = null)
    {
        return isset($this->config[$name]) ? $this->config[$name] : $default;
    }

    /**
     * @return \Psr\SimpleCache\CacheInterface
     */
    public function getCache()
    {
        if ($this->cache instanceof CacheInterface) {
            return $this->cache;
        }

        $prefix = (string) $this->config['cache_prefix'];

        $pool = new FilesystemAdapter($prefix);

        $this->cache = new Psr16Cache($pool);

        return $this->cache;
    }

    /**
     * @param \Psr\SimpleCache\CacheInterface $cache
     * @return void
     */
    public function setCache($cache)
    {
        $this->cache = $cache;
    }

    /**
     * @return \GuzzleHttp\ClientInterface
     */
    public function getHttp()
    {
        if ($this->http instanceof ClientInterface) {
            return $this->http;
        }

        $this->http = new HttpClient();

        return $this->http;
    }

    /**
     * @param \GuzzleHttp\ClientInterface $http
     * @return void
     */
    public function setHttp($http)
    {
        $this->http = $http;
    }

    /**
     * @param string $uri
     * @param array $options
     * @return array
     * @throws \PcbPlus\PcbDingtalk\Exceptions\ApiException
     */
    public function httpGet($uri, $options = [])
    {
        return $this->sendHttp('GET', $uri, $options);
    }

    /**
     * @param string $uri
     * @param array $options
     * @return array
     * @throws \PcbPlus\PcbDingtalk\Exceptions\ApiException
     */
    public function httpPost($uri, $options = [])
    {
        return $this->sendHttp('POST', $uri, $options);
    }

    /**
     * @param string $method
     * @param string $uri
     * @param array $options
     * @return array
     * @throws \PcbPlus\PcbDingtalk\Exceptions\ApiException
     */
    public function sendHttp($method, $uri, $options = [])
    {
        try {
            $response = $this->http->request($method, $uri, $options);
        } catch (GuzzleException $e) {
            throw new ApiException($e->getMessage(), 0, $e);
        }

        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * @return string
     * @throws \PcbPlus\PcbDingtalk\Exceptions\ApiException
     */
    public function ensureAccessToken()
    {
        $accessToken = new AccessToken($this->config, $this->getHttp(), $this->getCache());

        return $accessToken->ensureAccessToken();
    }
}
