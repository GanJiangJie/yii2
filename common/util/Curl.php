<?php

namespace app\common\util;

class Curl
{
    /**
     * post请求
     * @param $url
     * @param int $timeout
     * @param array $post
     * @param array $header
     * @return mixed|string
     */
    public static function post($url, $timeout = 10, $post = [], $header = [])
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HEADER, false);
        if (!empty($header)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        }
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        if (!empty($post)) {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        }
        $content = curl_exec($ch);
        $curl_error = '';
        if ($content == false) {
            $curl_error = curl_error($ch);
        }
        curl_close($ch);
        if ($content == false) {
            return 'curl error:' . $curl_error;
        }
        return $content;
    }

    /**
     * get请求
     * @param $url
     * @param int $timeout
     * @param array $header
     * @return mixed|string
     */
    public static function get($url, $timeout = 10, $header = [])
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HEADER, false);
        if (!empty($header)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        }
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        $content = curl_exec($ch);
        $curl_error = '';
        if ($content == false) {
            $curl_error = curl_error($ch);
        }
        curl_close($ch);
        if ($content == false) {
            return 'curl error:' . $curl_error;
        }
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
    public static function request($url, $method = 'GET', $timeout = 10, $post = [], $header = [])
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HEADER, false);
        if (!empty($header)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        }
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);

        //无需证书
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        //需要证书
        /*curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch,CURLOPT_SSLCERTTYPE,'PEM');
        curl_setopt($ch,CURLOPT_SSLCERT, $this->cert_path);//证书文件路径
        curl_setopt($ch,CURLOPT_SSLKEYTYPE,'PEM');
        curl_setopt($ch,CURLOPT_SSLKEY, $this->key_path);//秘钥文件路径*/

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