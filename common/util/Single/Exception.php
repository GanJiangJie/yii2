<?php

namespace app\common\util\Single;

use app\common\traits\SingleTrait;

class Exception
{
    use SingleTrait;

    /**
     * @var string $file
     */
    public $file;
    /**
     * @var int $line
     */
    public $line;
    /**
     * @var string $class
     */
    public $class;
    /**
     * @var string $function
     */
    public $function;
    /**
     * @var string $type
     */
    public $type;
    /**
     * @var string $message
     */
    public $message;
    /**
     * @var int $code
     */
    public $code;

    /**
     * @param array $back
     * @param array $trace
     */
    public function backtrace(array $back, array $trace)
    {
        $this->file = $back['file'];
        $this->line = $back['line'];
        @list($this->message, $this->code) = $back['args'];
        $this->class = $trace['class'];
        $this->function = $trace['function'];
        $this->type = $trace['type'];
    }
}