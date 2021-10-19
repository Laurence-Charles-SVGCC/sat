<?php

namespace common\models;

class ErrorObject
{
    private $message;

    function __construct($msg)
    {
        $this->message = $msg;
    }

    public function getMessage()
    {
        return $this->message;
    }
}
