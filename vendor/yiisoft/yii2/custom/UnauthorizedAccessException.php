<?php

namespace yii\custom;

/**
 * UnauthorizedAccessException represents a user perform action that they are not authorized to execute with status code 401.
 *
 * @author LaurenceCharles <charles.laurence1@gmail.com>
 * @since 2017_08_24
 */
class UnauthorizedAccessException extends \yii\web\HttpException
{ 
    /**
     * Constructor.
     * @param string $message error message
     * @param integer $code error code
     * @param \Exception $previous The previous exception used for the exception chaining.
     */
    public function __construct($message = "You are not authorized to perform the current function.", $code = 0, \Exception $previous = null)
    {
        parent::__construct(401, $message, $code, $previous);
    }
}
