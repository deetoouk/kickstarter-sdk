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
    public static function getApiKey(): string
    {
        return static::$api_key;
    }

    /**
     * @param string $api_key
     */
    public static function setApiKey($api_key): void
    {
        static::$api_key = $api_key;
    }

    /**
     * Retrieves Event host
     *
     * @return string
     */
    public static function getEndpoint(): string
    {
        return static::$endpoint;
    }

    /**
     * @param string $endpoint
     */
    public static function setEndpoint($endpoint): void
    {
        static::$endpoint = $endpoint;
    }

    /**
     * @return string
     */
    public static function getProtocol(): string
    {
        return static::$protocol;
    }

    /**
     * @param string $protocol
     */
    public static function setProtocol($protocol): void
    {
        static::$protocol = $protocol;
    }

    /**
     * @return string
     */
    public static function getVersion(): ?string
    {
        return static::$version;
    }

    /**
     * @param string $version
     */
    public static function setVersion(?string $version): void
    {
        static::$version = $version;
    }

    /**
     * @return string
     */
    public static function getLanguage(): string
    {
        return static::$language;
    }

    /**
     * @param string $language
     */
    public static function setLanguage(string $language): void
    {
        static::$language = $language;
    }

    /**
     * @return string
     */
    public static function getProxy(): ?string
    {
        return static::$proxy;
    }

    /**
     * @param string $proxy
     */
    public static function setProxy(?string $proxy): void
    {
        static::$proxy = $proxy;
    }

    /**
     * @return bool
     */
    public static function getVerifySsl(): bool
    {
        return static::$verify_ssl;
    }

    /**
     * @param bool $verify_ssl
     */
    public static function setVerifySsl($verify_ssl): void
    {
        static::$verify_ssl = $verify_ssl;
    }

    /**
     * @return bool
     */
    public static function verify(): bool
    {
        if (!is_null(static::$verify_ssl)) {
            return static::$verify_ssl;
        }

        if (!is_null(static::$proxy)) {
            return false;
        }

        return true;
    }

    /**
     * @return string
     */
    public static function getCustomHeaderPrefix(): string
    {
        return static::$custom_header_prefix;
    }

    /**
     * @param string $custom_header_prefix
     */
    public static function setCustomHeaderPrefix(string $custom_header_prefix): void
    {
        static::$custom_header_prefix = $custom_header_prefix;
    }
}
