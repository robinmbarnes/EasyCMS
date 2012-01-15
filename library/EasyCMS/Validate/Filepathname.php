<?php


class EasyCMS_Validate_Filepathname extends Zend_Validate_Abstract
{
    const INVALID      = 'invalid';
    const NOT_FILEPATH    = 'notFileptah';
    const STRING_EMPTY = 'stringEmpty';

    /**
     * Validation failure message template definitions
     *
     * @var array
     */
    protected $_messageTemplates = array(
        self::INVALID      => "Invalid type given. String, integer or float expected",
        self::NOT_FILEPATH    => "Must only contain letters, numbers, underscores and hyphens. No blank spaces allowed.",
        self::STRING_EMPTY => "'%value%' is an empty string",
    );

    public function __construct()
    {

    }

    /**
     * Defined by Zend_Validate_Interface
     *
     * Returns true if and only if $value contains only alphabetic and digit characters
     *
     * @param  string $value
     * @return boolean
     */
    public function isValid($value)
    {
        if (!is_string($value) && !is_int($value) && !is_float($value)) 
        {
            $this->_error(self::INVALID);
            return false;
        }

        $this->_setValue($value);

        if ('' === $value) 
        {
            $this->_error(self::STRING_EMPTY);
            return false;
        }

        if (preg_match('/[^a-z0-9_\-]/i', $value)) 
        {
            $this->_error(self::NOT_FILEPATH);
            return false;
        }

        return true;
    }

}
