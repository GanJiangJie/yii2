<?php

namespace app\components;

class Exception extends \yii\base\Exception
{
    /**
     * Exception constructor.
     * @param string $message
     * @param int $code
     */
    public function __construct(string $message = '', int $code = API_ERROR_CODE_FAIL)
    {
        $msg = $message ?: ($GLOBALS['__API_ERROR_CODE'][$code] ?? $GLOBALS['__API_ERROR_CODE'][API_ERROR_CODE_FAIL]);
        parent::__construct($msg, $code);
    }

    /**
     * @return string
     */
    public function getFileLine()
    {
        return $this->getFile() . ':' . $this->getLine();
    }
}