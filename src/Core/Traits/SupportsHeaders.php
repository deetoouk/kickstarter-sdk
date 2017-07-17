<?php

namespace JTDSoft\EssentialsSdk\Core\Traits;

trait SupportsHeaders
{
    /**
     * @var array
     */
    protected $headers;

    /**
     * @return mixed
     */
    public function getHeaders()
    {
        return $this->headers ?: [];
    }

    /**
     * @param array $headers
     *
     * @return $this
     */
    public function setHeaders(array $headers)
    {
        $this->headers = $headers;

        return $this;
    }

    /**
     * @param string $header
     * @param string $value
     */
    public function setHeader(string $header, string $value)
    {
        $this->headers[$header] = $value;
    }

    /**
     * @param string $header
     * @param string $value
     */
    public function setCustomHeader(string $header, string $value)
    {
        $this->headers[self::$custom_header_prefix . $header] = $value;
    }

    /**
     * Adds all headers
     */
    protected function prepareHeaders()
    {
        $this->setCustomHeader('Version', static::getVersion());
        $this->setHeader('Accept-Language', static::getLanguage());
        $this->setHeader('Authorization', 'Bearer ' . static::getApiKey());
    }
}
