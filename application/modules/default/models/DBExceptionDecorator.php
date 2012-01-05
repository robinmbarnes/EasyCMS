<?php
class App_Model_DBExceptionDecorator extends App_Model_ExceptionDecorator
{
    public function isDuplicateKeyViolation()
    {
        return ($this->exception->getCode() == 23000);
    }
}
