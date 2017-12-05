<?php

namespace JTDSoft\EssentialsSdk\Exceptions;

class ErrorException extends \Exception
{
    protected $error;

    public function __construct($error, $code = 0)
    {
        $this->error = $error;

        if (is_scalar($error)) {
            $this->message = (string)$this->error;
        } else {
            $this->message = var_export($this->error, true);
        }

        $this->code = $code;
    }

    public function getError()
    {
        return $this->error;
    }
}
