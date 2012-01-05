<?php
class EasyCMS_Util_FlashMessage
{
    const TYPE_ERROR = 'flasherror';
    const TYPE_SUCCESS = 'success';
    const TYPE_NOTICE = 'notice';
    const TYPE_GREETING = 'greeting';

    private $message = '';
    private $type;

    public function __construct($message, $type)
    {
        if($type != self::TYPE_ERROR && $type != self::TYPE_SUCCESS && $type != self::TYPE_NOTICE && $type != self::TYPE_GREETING)
        {
            throw new Exception($type . ' is not a valid flash messaage type');
        }
        $this->message = $message;
        $this->type = $type;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function getType()
    {
        return $this->type;
    }
}
