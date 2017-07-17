<?php

namespace JTDSoft\EssentialsSdk\Core;

/**
 * Class Identity
 *
 * Holds the api credentials for the SDK REST calls
 *
 * @package Identity
 */
class Config
{
    /**
     * @var string
     */
    protected static $api_key;

    /**
     * @var string
     */
    protected static $endpoint;

    /**
     * @var string
     */
    protected static $protocol;

    /**
     * @var string
     */
    protected static $version;

    /**
     * @var string
     */
    protected static $language = 'en';

    /**
     * @var string
     */
    protected static $proxy = null;

    /**
     * @var bool
     */
    protected static $verify_ssl = null;

    /**
     * @var string
     */
    protected static $custom_header_prefix = 'SDK-';

    /**
     * Retrieves Event API key
     *
     * @return string
     */
    public static function getApiKey()
    {
        return self::$api_key;
    }

    /**
     * @param string $api_key
     */
    public static function setApiKey($api_key)
    {
        self::$api_key = $api_key;
    }

    /**
     * Retrieves Event host
     *
     * @return string
     */
    public static function getEndpoint()
    {
        return self::$endpoint;
    }

    /**
     * @param string $endpoint
     */
    public static function setEndpoint($endpoint)
    {
        self::$endpoint = $endpoint;
    }

    /**
     * @return string
     */
    public static function getProtocol()
    {
        return self::$protocol;
    }

    /**
     * @param string $protocol
     */
    public static function setProtocol($protocol)
    {
        self::$protocol = $protocol;
    }

    /**
     * @return string
     */
    public static function getVersion(): string
    {
        return self::$version;
    }

    /**
     * @param string $version
     */
    public static function setVersion(string $version)
    {
        self::$version = $version;
    }

    /**
     * @return string
     */
    public static function getLanguage(): string
    {
        return self::$language;
    }

    /**
     * @param string $language
     */
    public static function setLanguage(string $language)
    {
        self::$language = $language;
    }

    /**
     * @return string
     */
    public static function getProxy()
    {
        return self::$proxy;
    }

    /**
     * @param string $proxy
     */
    public static function setProxy($proxy)
    {
        self::$proxy = $proxy;
    }

    /**
     * @return bool
     */
    public static function getVerifySsl()
    {
        return self::$verify_ssl;
    }

    /**
     * @param bool $verify_ssl
     */
    public static function setVerifySsl($verify_ssl)
    {
        self::$verify_ssl = $verify_ssl;
    }

    /**
     * @return bool
     */
    public static function verify()
    {
        if (!is_null(self::$verify_ssl)) {
            return self::$verify_ssl;
        }

        if (!is_null(self::$proxy)) {
            return false;
        }

        return true;
    }

    /**
     * @return string
     */
    public static function getHeaderPrefix(): string
    {
        return self::$header_prefix;
    }

    /**
     * @param string $header_prefix
     */
    public static function setHeaderPrefix(string $header_prefix)
    {
        self::$header_prefix = $header_prefix;
    }
}
