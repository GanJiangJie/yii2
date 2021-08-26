<?php

namespace app\common\util;

class FolderFile
{
    /**
     * 获取目录全部文件路径或者名称
     * @param array $route_paths
     * @param bool $flag
     * @param string $path
     */
    public static function readFileOne(&$route_paths, $path, $flag)
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
     * @param $dir_name
     * @return bool
     */
    public static function deleteDirFile($dir_name)
    {
        if (is_file($dir_name)) {
            $result = unlink($dir_name);
            return $result;
        }
        if (is_dir($dir_name)) {
            self::readFile1($dir_name);
        }
        return true;
    }

    /**
     * @param $path
     */
    private static function readFile1($path)
    {
        $content = scandir($path);
        foreach ($content as $v) {
            if ($v == '.' || $v == '..') {
                continue;
            }
            if (is_dir($path . '/' . $v)) {
                self::readFile2($path . '/' . $v);
            }
            if (is_file($path . '/' . $v)) {
                unlink($path . '/' . $v);
            }
        }
        rmdir($path);
    }

    /**
     * @param $path
     */
    private static function readFile2($path)
    {
        $content = scandir($path);
        foreach ($content as $v) {
            if ($v == '.' || $v == '..') {
                continue;
            }
            if (is_dir($path . '/' . $v)) {
                self::readFile1($path . '/' . $v);
            }
            if (is_file($path . '/' . $v)) {
                unlink($path . '/' . $v);
            }
        }
        rmdir($path);
    }
}