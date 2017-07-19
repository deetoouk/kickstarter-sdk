<?php

namespace JTDSoft\EssentialsSdk\Core\Traits;

trait DefaultRequestData
{
    /**
     * @var array
     */
    protected $default_request;

    /**
     * @param $key
     * @param $value
     *
     * @return $this
     */
    public function setDefaultRequest($key, $value)
    {
        $this->default_request[$key] = $value;

        return $this;
    }

    /**
     * Combines request data with default request data
     *
     * @param array $request
     *
     * @return array
     */
    protected function prepareRequest(array $request = [])
    {
        return $request + $this->default_request;
    }
}
