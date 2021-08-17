<?php

namespace common\util\Log;

class Log
{
    const LEVEL_ERROR = 1;//记录一个致命错误消息
    const LEVEL_WARNING = 2;//记录一个警告消息
    const LEVEL_INFO = 3;//记录一些有用信息的消息
    const LEVEL_TRACE = 4;//记录消息跟踪一段代码如何运行

    private $category = '';//目录类别
    private $prefix = '';//文件前缀
    private $filename = 'log';//文件名称
    private $suffix = '.log';//文件后缀

    /**
     * @param string $category
     * @return $this
     */
    public function category($category)
    {
        $this->category = trim($category, '/');
        return $this;
    }

    /**
     * @param string $prefix
     * @return $this
     */
    public function prefix($prefix)
    {
        $this->prefix = $prefix;
        return $this;
    }

    /**
     * @param string $filename
     * @return $this
     */
    public function filename($filename)
    {
        $this->filename = $filename;
        return $this;
    }

    /**
     * @param $suffix
     * @return $this
     */
    public function suffix($suffix)
    {
        $this->suffix = $suffix;
        return $this;
    }

    /**
     * @param $content
     * @param $level string
     */
    public function writeLog($content, $level)
    {
        //创建日志文件
        $log_file = $this->createLogFile();
        //组合日志内容
        $message = $this->contentFormat($content, $level);
        //写入日志
        file_put_contents($log_file, $message . "\r\n", FILE_APPEND);
    }

    /**
     * 创建日志文件
     */
    private function createLogFile()
    {
        $path = __DIR__ . '/log/' . exec('whoami') . '/';
        if ($this->category) {
            $path .= $this->category . '/';
        }
        $filename = $this->prefix . $this->filename . '_' . date('Y-m-d') . $this->suffix;
        //创建日志目录
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }
        //创建日志文件
        if (!file_exists($path . $filename)) {
            fopen($path . $filename, 'a');
        }
        return $path . $filename;
    }

    /**
     * 格式化日志内容
     * @param $content
     * @param $level
     * @return string
     */
    private function contentFormat($content, $level)
    {
        $message = ['time' => date('Y-m-d H:i:s')];
        switch ($level) {
            case 1:
                $message['level'] = 'error';
                break;
            case 2:
                $message['level'] = 'warning';
                break;
            case 3:
                $message['level'] = 'info';
                break;
            case 4:
                $message['level'] = 'trace';
                break;
        }
        $message['content'] = $content;
        return json_encode($message, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    public static function ERROR($content)
    {
        //创建日志文件
        $log_file = call_user_func([self::class, 'createLogFile']);
        $message = call_user_func_array([self::class, 'contentFormat'], [$content, self::LEVEL_ERROR]);
        //写入日志
        file_put_contents($log_file, $message . "\r\n", FILE_APPEND);
    }

    public static function WARNING($content)
    {
        //创建日志文件
        $log_file = call_user_func([self::class, 'createLogFile']);
        $message = call_user_func_array([self::class, 'contentFormat'], [$content, self::LEVEL_WARNING]);
        //写入日志
        file_put_contents($log_file, $message . "\r\n", FILE_APPEND);
    }

    public static function INFO($content)
    {
        //创建日志文件
        $log_file = call_user_func([self::class, 'createLogFile']);
        $message = call_user_func_array([self::class, 'contentFormat'], [$content, self::LEVEL_INFO]);
        //写入日志
        file_put_contents($log_file, $message . "\r\n", FILE_APPEND);
    }

    public static function TRACE($content)
    {
        //创建日志文件
        $log_file = call_user_func([self::class, 'createLogFile']);
        $message = call_user_func_array([self::class, 'contentFormat'], [$content, self::LEVEL_TRACE]);
        //写入日志
        file_put_contents($log_file, $message . "\r\n", FILE_APPEND);
    }
}