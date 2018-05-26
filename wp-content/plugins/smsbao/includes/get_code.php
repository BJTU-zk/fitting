<?php
/**
 * Created by PhpStorm.
 * User: smsbao
 * Date: 2016/6/24
 * Time: 15:14
 */
if(isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER["HTTP_X_REQUESTED_WITH"])=="xmlhttprequest"){
    global $wpdb;

    if (!isset($wpdb)) {
        include_once $_POST['dburl'];
        require_wp_db();
    }

    if (!isset($_SESSION)) {
        session_start();
        session_regenerate_id(TRUE);
    }

    $ret = array();
    $err = '';
    $phone = $_POST['phone'];
    $captcha =  $_POST['captcha'];
    $tmp = $_POST['tmp'];
    $username = $_POST['username'];
    $passowrd = $_POST['password'];
    $sign = $_POST['sign'];
    $is_phone = preg_match('/^1[34578]{1}\d{9}$/', $phone);

    if (empty($phone)) {
        $err = '手机号码没有填写！';
    } else if (false == $is_phone) {
        $err = '手机格式不正确！';
    } else {
        if(empty($captcha) || empty($_SESSION['sms_code'])) {
            $err = '验证码必须填写！';
        } else if ((trim(strtolower($captcha)) != $_SESSION['sms_code'])) {
            $err = '验证码填写不正确！';
        } else {
            unset($_SESSION['sms_code']);
            include 'send_sms.php';
            $setActiveCode = rand(100000, 999999);
            $tmp = $sign . str_replace('{code}', $setActiveCode, $tmp);
            $smsRet = send_sms($phone, $tmp, $username, $passowrd);

            if (true === $smsRet) {
                $table = $wpdb->prefix . 'user_active';
                $ret['flg'] = true;
                $sql = "select id from {$table} where phone='{$phone}'";
                $id =  $wpdb->get_var($sql) + 0;
                $currentTime = time();

                if ($id > 0) {
                    $res = $wpdb->update($table, array('active_num'=>$setActiveCode, 'active_time'=>$currentTime, 'is_active'=>0), array('id'=>$id));
                } else {
                    $res = $wpdb->insert($table, array('phone'=>$phone, 'active_num'=>$setActiveCode, 'active_time'=>$currentTime));
                }

                if (!$res) {
                    $err = '服务器内部错误!';
                }

            } else {
                file_put_contents('sms_log.txt', '短信发送失败,原因：' . $smsRet);
                $err = '短信发送失败，请联系管理员。';
            }
        }
    }

    if (!empty($err)) {
        $ret['flg'] = false;
        $ret['err'] = $err;
    }

    echo json_encode($ret);
}

exit;