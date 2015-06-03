<?php

/**
 * Created by smsResque.
 * User: Administrator
 * Author: druphliu@gmail.com
 * Date: 2015/6/3
 * Time: 15:48
 */
abstract class BaseSmsUtils
{

    /**
     * @param $phone
     * @param $content
     * @param $account
     * @param $pswd
     * @param string $userid
     * @return mixed
     */
    abstract function sendOneSms($phone, $content, $account, $pswd, $userid = '');

    protected function sendHttpRequest($url, $params = array(), $method = 'GET', $header = array(), $timeout = 5)
    {
        if (!function_exists('curl_init'))
            exit('curl_init not exit');
        $ch = curl_init();
        if ($method == 'GET') {
            if (strpos($url, '?')) $url .= '&' . is_array($params) ? http_build_query($params) : $params;
            else $url .= '?' . is_array($params) ? http_build_query($params) : $params;

            curl_setopt($ch, CURLOPT_URL, $url);
        } elseif ($method == 'POST') {
            $post_data = is_array($params) ? http_build_query($params) : $params;
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
            curl_setopt($ch, CURLOPT_POST, true);
        }
        curl_setopt($ch, CURLOPT_HEADER, TRUE);
        //https不验证证书
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        if (!empty($header)) {
            //curl_setopt($ch, CURLOPT_NOBODY,FALSE);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
            curl_setopt($ch, CURLINFO_HEADER_OUT, TRUE);
        }
        if ($timeout) curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        $content = curl_exec($ch);
        $info = curl_getinfo($ch);
        $errors = curl_error($ch);
        if (curl_getinfo($ch, CURLINFO_HTTP_CODE) == '200') {
            $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
            $header = substr($content, 0, $headerSize);
            $content = substr($content, $headerSize);
        }
        return array('content' => $content, 'info' => $info, 'error' => $errors, 'header' => $header);
    }
}