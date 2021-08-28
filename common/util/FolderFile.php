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
    public static function getDirFile(string $path, bool $flag = false): array
    {
        $files = [];
        self::readFileOne($files, $path, $flag);
        return $files;
    }

    /**
     * @param array $route_paths
     * @param string $path
     * @param bool $flag
     */
    private static function readFileOne(array &$route_paths, string $path, bool $flag)
    {
        $content = scandir($path);
        foreach ($content as $v) {
            if ($v == '.' || $v == '..') continue;
            if (is_file($path . '/' . $v)) {
                $route_paths[] = $flag ? $path . '/' . $v : $v;
                continue;
            }
            is_dir($path . '/' . $v) and self::readFileTwo($route_paths, $path . '/' . $v, $flag);
        }
    }

    /**
     * @param array $route_paths
     * @param string $path
     * @param bool $flag
     */
    private static function readFileTwo(array &$route_paths, string $path, bool $flag)
    {
        $content = scandir($path);
        foreach ($content as $v) {
            if ($v == '.' || $v == '..') continue;
            if (is_file($path . '/' . $v)) {
                $route_paths[] = $flag ? $path . '/' . $v : $v;
                continue;
            }
            is_dir($path . '/' . $v) and self::readFileOne($route_paths, $path . '/' . $v, $flag);
        }
    }

    /**
     * 删除文件夹及其下的文件
     * @param string $dir_name
     * @return bool
     */
    public static function delDirFile(string $dir_name)
    {
        if (is_file($dir_name)) {
            $result = unlink($dir_name);
            return $result;
        }
        is_dir($dir_name) and self::dealFileOne($dir_name);
        return true;
    }

    /**
     * @param string $path
     */
    private static function dealFileOne(string $path)
    {
        $content = scandir($path);
        foreach ($content as $v) {
            if ($v == '.' || $v == '..') continue;
            if (is_file($path . '/' . $v)) {
                unlink($path . '/' . $v);
                continue;
            }
            is_dir($path . '/' . $v) and self::DealFileTwo($path . '/' . $v);

        }
        rmdir($path);
    }

    /**
     * @param string $path
     */
    private static function DealFileTwo(string $path)
    {
        $content = scandir($path);
        foreach ($content as $v) {
            if ($v == '.' || $v == '..') continue;
            if (is_file($path . '/' . $v)) {
                unlink($path . '/' . $v);
                continue;
            }
            is_dir($path . '/' . $v) and self::dealFileOne($path . '/' . $v);
        }
        rmdir($path);
    }
}