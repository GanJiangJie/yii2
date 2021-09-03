<?php

namespace app\common\util;

class Curl
{
    /**
     * post请求
     * @param string $url
     * @param int $timeout
     * @param array $post
     * @param array $header
     * @return mixed|string
     */
    public static function post(string $url, int $timeout = 10, array $post = [], array $header = [])
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HEADER, false);
        empty($header) or curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        if (!empty($post)) {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        }
        $content = curl_exec($ch);
        $curl_error = $content ? '' : curl_error($ch);
        curl_close($ch);
        if (!$content) return 'curl error:' . $curl_error;
        return $content;
    }

    /**
     * get请求
     * @param string $url
     * @param int $timeout
     * @param array $header
     * @return mixed|string
     */
    public static function get(string $url, int $timeout = 10, array $header = [])
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HEADER, false);
        empty($header) or curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        $content = curl_exec($ch);
        $curl_error = $content ? '' : curl_error($ch);
        curl_close($ch);
        if (!$content) return 'curl error:' . $curl_error;
        return $content;
    }

    /**
     * @param $url
     * @param string $method
     * @param int $timeout
     * @param array $post
     * @param array $header
     * @return array
     */
    public static function request(string $url, string $method = 'GET', int $timeout = 10, array $post = [], array $header = [])
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HEADER, false);
        empty($header) or curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        switch ($method) {
            case 'GET' :
                curl_setopt($ch, CURLOPT_HTTPGET, true);
                break;
            case 'POST' :
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
                break;
            case 'PUT' :
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
                curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
                break;
            case 'PATCH' :
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH');
                curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
                break;
            case 'DELETE' :
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
                curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
                break;
        }
        $content = curl_exec($ch);
        $curl_info = curl_getinfo($ch);
        if ($curl_info['http_code'] == 200 || $curl_info['http_code'] == 204) {
            curl_close($ch);
            return [
                'status' => true,
                'content' => $content,
                'curl_info' => $curl_info,
                'curl_error' => ''
            ];
        }
        $curl_error = curl_error($ch);
        curl_close($ch);
        return [
            'status' => false,
            'content' => $content,
            'curl_info' => $curl_info,
            'curl_error' => $curl_error
        ];
    }
}