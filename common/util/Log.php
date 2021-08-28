<?php

namespace common\util;

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
    private $level;

    /**
     * @param string $category
     * @return Log
     */
    public function category($category): self
    {
        $this->category = trim($category, '/');
        return $this;
    }

    /**
     * @param string $prefix
     * @return Log
     */
    public function prefix($prefix): self
    {
        $this->prefix = $prefix;
        return $this;
    }

    /**
     * @param string $filename
     * @return Log
     */
    public function filename($filename): self
    {
        $this->filename = $filename;
        return $this;
    }

    /**
     * @param string $suffix
     * @return Log
     */
    public function suffix($suffix): self
    {
        $this->suffix = $suffix;
        return $this;
    }

    /**
     * @param int $level
     * @return Log
     */
    public function level($level): self
    {
        $this->level = $level;
        return $this;
    }

    /**
     * @param string|array $content
     * @param int $level
     */
    public function writeLog($content, int $level = self::LEVEL_INFO)
    {
        //创建日志文件
        $log_file = self::createLogFile();
        //组合日志内容
        $message = self::contentFormat($content, $this->level ?? $level);
        //写入日志
        file_put_contents($log_file, $message . "\r\n", FILE_APPEND);
    }

    /**
     * 创建日志文件
     * @return string
     */
    private function createLogFile(): string
    {
        $path = BASE_PATH . '/runtime/logs/' . exec('whoami') . '/';
        $this->category and $path .= $this->category . '/';
        $filename = $this->prefix . $this->filename . '_' . date('Y-m-d') . $this->suffix;
        //创建日志目录
        file_exists($path) or mkdir($path, 0777, true);
        //创建日志文件
        file_exists($path . $filename) or fopen($path . $filename, 'a');
        return $path . $filename;
    }

    /**
     * 格式化日志内容
     * @param string $content
     * @param int $level
     * @return string
     */
    private function contentFormat($content, int $level): string
    {
        $message = ['time' => date('Y-m-d H:i:s')];
        switch ($level) {
            case self::LEVEL_ERROR:
                $message['level'] = 'error';
                break;
            case self::LEVEL_WARNING:
                $message['level'] = 'warning';
                break;
            case self::LEVEL_INFO:
                $message['level'] = 'info';
                break;
            case self::LEVEL_TRACE:
                $message['level'] = 'trace';
                break;
        }
        $message['content'] = $content;
        return json_encode($message, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    /**
     * @param string $content
     */
    public static function ERROR($content)
    {
        //创建日志文件
        $log_file = call_user_func([self::class, 'createLogFile']);
        $message = call_user_func_array([self::class, 'contentFormat'], [$content, self::LEVEL_ERROR]);
        //写入日志
        file_put_contents($log_file, $message . "\r\n", FILE_APPEND);
    }

    /**
     * @param string $content
     */
    public static function WARNING($content)
    {
        //创建日志文件
        $log_file = call_user_func([self::class, 'createLogFile']);
        $message = call_user_func_array([self::class, 'contentFormat'], [$content, self::LEVEL_WARNING]);
        //写入日志
        file_put_contents($log_file, $message . "\r\n", FILE_APPEND);
    }

    /**
     * @param string $content
     */
    public static function INFO($content)
    {
        //创建日志文件
        $log_file = call_user_func([self::class, 'createLogFile']);
        $message = call_user_func_array([self::class, 'contentFormat'], [$content, self::LEVEL_INFO]);
        //写入日志
        file_put_contents($log_file, $message . "\r\n", FILE_APPEND);
    }

    /**
     * @param string $content
     */
    public static function TRACE($content)
    {
        //创建日志文件
        $log_file = call_user_func([self::class, 'createLogFile']);
        $message = call_user_func_array([self::class, 'contentFormat'], [$content, self::LEVEL_TRACE]);
        //写入日志
        file_put_contents($log_file, $message . "\r\n", FILE_APPEND);
    }
}