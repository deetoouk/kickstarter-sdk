<?php

namespace DeeToo\KickstarterSdk\Exceptions;

class ErrorException extends \Exception
{
    protected $error;

    public function __construct($error, $code = 0)
    {
        $this->error = $error;

        if (!is_array($error)) {
            $this->message = (string)$this->error;
        } else {
            $this->message = implode(';', array_flatten($this->error));
        }

        $this->code = $code;
    }

    public function getError()
    {
        return $this->error;
    }
}
