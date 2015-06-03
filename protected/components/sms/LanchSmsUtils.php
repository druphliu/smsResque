<?php

/**
 * Created by smsResque.
 * User: Administrator
 * Author: druphliu@gmail.com
 * Date: 2015/6/3
 * Time: 15:56
 */
class LanchSmsUtils extends BaseSmsUtils
{

    const SEND_URL = 'http://www.lanz.net.cn/LANZGateway/DirectSendSMSs.asp?UserID=%s&Account=%s&Password=%s&SMSType=1&Content=%s&Phones=%s&sendDate=&sendtime=';

    public function sendOneSms($phone, $content, $account, $pswd, $userid = '')
    {
        $content = iconv('UTF-8', 'GB2312', $content);
        $return = array('success' => false);
        $url = sprintf(self::SEND_URL, $userid, $account, $pswd, $content, $phone);
        $result = parent::sendHttpRequest($url);
        if ($result['content']) {
            $resp_str = $result['content'];
            $error = substr($resp_str, strpos($resp_str, "<ErrorNum>") + 10, strpos($resp_str, "</ErrorNum>") - strpos($resp_str, "<ErrorNum>") - 10);
            if ($error == 0) {
                $return['success'] = true;
            } else {
                $return['msg'] = $error;
            }
        }
        return $return;
    }
}