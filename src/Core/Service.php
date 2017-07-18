<?php

namespace JTDSoft\EssentialsSdk\Core;

use GuzzleHttp\Client as Guzzle;
use JTDSoft\EssentialsSdk\Contracts\Client;
use JTDSoft\EssentialsSdk\Core\Traits\SupportsHeaders;

/**
 * Class Service
 *
 * @package JTDSoft\EssentialsSdk\Services
 */
class Service extends Config
{
    use SupportsHeaders;

    /**
     * @var Client
     */
    protected $client;

    /**
     * @param       $verb
     * @param       $method
     * @param array $request
     *
     * @return mixed
     * @throws \JTDSoft\EssentialsSdk\Exceptions\ErrorException
     * @throws \JTDSoft\EssentialsSdk\Exceptions\ResponseException
     * @throws \JTDSoft\EssentialsSdk\Exceptions\ServerException
     * @throws \JTDSoft\EssentialsSdk\Exceptions\UnauthorizedException
     */
    protected final function call($verb, $method, array $request = null)
    {
        if (is_null($request)) {
            $request = [];
        }

        $this->prepareHeaders();

        $client = new GuzzleClient(new Guzzle([
            'proxy'  => static::getProxy(),
            'verify' => static::verify(),
        ]));

        return $client->request(
            $verb,
            $this->getUrl($method),
            $request,
            $this->getHeaders()
        );
    }

    /**
     * @param            $method
     * @param array|null $request
     *
     * @return mixed
     */
    public final function get($method, array $request = null)
    {
        return $this->call('get', $method, $request);
    }

    /**
     * @param            $method
     * @param array|null $request
     *
     * @return mixed
     */
    public final function post($method, array $request = null)
    {
        return $this->call('post', $method, $request);
    }

    /**
     * @param            $method
     * @param array|null $request
     *
     * @return mixed
     */
    public final function put($method, array $request = null)
    {
        return $this->call('put', $method, $request);
    }

    /**
     * @param            $method
     * @param array|null $request
     *
     * @return mixed
     */
    public final function patch($method, array $request = null)
    {
        return $this->call('patch', $method, $request);
    }

    /**
     * @param            $method
     * @param array|null $request
     *
     * @return mixed
     */
    public final function delete($method, array $request = null)
    {
        return $this->call('delete', $method, $request);
    }

    /**
     * @param string $method
     *
     * @return string
     */
    protected function getUrl($method)
    {
        $endpoint = sprintf(
            '%s://%s/',
            static::getProtocol(),
            static::getEndpoint(),
            $method
        );

        return $endpoint;
    }
}
