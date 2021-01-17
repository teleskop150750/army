<?php


namespace core\exceptions;

class MainException extends \Exception
{
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->errorNumber = substr(strrchr(get_class($this), "\\"), 1);
    }
}