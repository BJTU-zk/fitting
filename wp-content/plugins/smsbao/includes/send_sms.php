<?php
/**
 * Created by PhpStorm.
 * User: smsbao
 * Date: 2016/6/24
 * Time: 16:08
 */

function send_sms($phone_num, $msg, $username, $password)
{
    $statusStr = array(
        "0" => "短信发送成功",
        "-1" => "短信参数不全",
        "-2" => "短信宝服务器空间不支持,请确认支持curl或者fsocket，联系您的空间商解决或者更换空间！",
        "30" => "短信设置密码错误",
        "40" => "短信设置账号不存在",
        "41" => "短信宝余额不足",
        "42" => "短信宝帐户已过期",
        "43" => "短信宝IP地址限制",
        "50" => "发送模板内容含有敏感词"
    );

    $smsapi = "http://api.smsbao.com/";

    $user = $username;
    $pass = $password;
    $content = $msg;
    $phone = $phone_num;
    $sendurl = $smsapi."sms?u=".$user."&p=".$pass."&m=".$phone."&c=".urlencode($content);
    $result = file_get_contents($sendurl) ;

    if ("0" == $result) {
        return true;
    }

    return $statusStr[$result];
}