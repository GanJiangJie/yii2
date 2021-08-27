<?php

namespace app\common\util;

class FolderFile
{
    /**
     * 获取目录全部文件路径或者名称
     * @param string $path 文件夹的路径: BASE_PATH . '/common'
     * @param bool $flag true返回文件路径, false返回文件名称
     * @return array
     */
    public static function getDirFile($path, $flag = false)
    {
        $files = [];
        self::readFileOne($files, $path, $flag);
        return $files;
    }

    /**
     * @param array $route_paths
     * @param bool $flag
     * @param string $path
     */
    private static function readFileOne(&$route_paths, $path, $flag)
    {
        $content = scandir($path);
        foreach ($content as $v) {
            if ($v == '.' || $v == '..') {
                continue;
            }
            if (is_file($path . '/' . $v)) {
                $route_paths[] = $flag ? $path . '/' . $v : $v;
                continue;
            }
            if (is_dir($path . '/' . $v)) {
                self::readFileTwo($route_paths, $path . '/' . $v, $flag);
            }
        }
    }

    /**
     * @param array $route_paths
     * @param bool $flag
     * @param string $path
     */
    private static function readFileTwo(&$route_paths, $path, $flag)
    {
        $content = scandir($path);
        foreach ($content as $v) {
            if ($v == '.' || $v == '..') {
                continue;
            }
            if (is_file($path . '/' . $v)) {
                $route_paths[] = $flag ? $path . '/' . $v : $v;
                continue;
            }
            if (is_dir($path . '/' . $v)) {
                self::readFileOne($route_paths, $path . '/' . $v, $flag);
            }
        }
    }

    /**
     * 删除文件夹及其下的文件
     * @param string $dir_name
     * @return bool
     */
    public static function delDirFile($dir_name)
    {
        if (is_file($dir_name)) {
            $result = unlink($dir_name);
            return $result;
        }
        if (is_dir($dir_name)) {
            self::dealFileOne($dir_name);
        }
        return true;
    }

    /**
     * @param string $path
     */
    private static function dealFileOne($path)
    {
        $content = scandir($path);
        foreach ($content as $v) {
            if ($v == '.' || $v == '..') {
                continue;
            }
            if (is_dir($path . '/' . $v)) {
                self::DealFileTwo($path . '/' . $v);
            }
            if (is_file($path . '/' . $v)) {
                unlink($path . '/' . $v);
            }
        }
        rmdir($path);
    }

    /**
     * @param string $path
     */
    private static function DealFileTwo($path)
    {
        $content = scandir($path);
        foreach ($content as $v) {
            if ($v == '.' || $v == '..') {
                continue;
            }
            if (is_dir($path . '/' . $v)) {
                self::dealFileOne($path . '/' . $v);
            }
            if (is_file($path . '/' . $v)) {
                unlink($path . '/' . $v);
            }
        }
        rmdir($path);
    }
}