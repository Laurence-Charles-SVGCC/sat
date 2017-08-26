<?php

namespace yii\custom;

/**
 * ModelNotFoundException represents a "Model Retreval Failure" exception with status code 500.
 *
 * @author LaurenceCharles <charles.laurence1@gmail.com>
 * @since 24/08/2017
 */
class ModelNotFoundException extends \yii\web\HttpException
{
    /**
     * Constructor.
     * @param string $message error message
     * @param integer $code error code
     * @param \Exception $previous The previous exception used for the exception chaining.
     */
    public function __construct($message = null, $code = 0, \Exception $previous = null)
    {
        parent::__construct(500, $message, $code, $previous);
    }
}
