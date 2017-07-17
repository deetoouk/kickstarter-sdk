<?php

namespace JTDSoft\EssentialsSdk\Exceptions;

class ErrorException extends \Exception
{
    protected $error;

    public function __construct($error, $code = 0)
    {
        $this->error   = $error;
        $this->message = var_export($this->error, true);
        $this->code = $code;
    }

    public function getError()
    {
        return $this->error;
    }
}
