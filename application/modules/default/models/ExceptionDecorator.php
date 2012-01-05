<?php
class App_Model_ExceptionDecorator
{
    protected $exception;

    public function __construct(Exception $exception)
    {
        $this->exception = $exception;
    }
        
    public function getException()
    {
        return $this->exception;
    }
}
