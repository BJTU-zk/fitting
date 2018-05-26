<?php
/**
 * Created by PhpStorm.
 * User: smsbao
 * Date: 2016/6/20
 * Time: 16:38
 */
add_action('admin_menu', 'create_admin_page');

function create_admin_page() {
    add_options_page('SmsBao', '短信宝', 'manage_options', 'smsbao', 'output_menu_page');
}

function output_menu_page() {

    if (isset($_POST['submit'])) {
        update_option('smsbao_name', $_POST['smsbao_name']);
        update_option('smsbao_password', $_POST['smsbao_password']);
        update_option('smsbao_sign', $_POST['smsbao_sign']);
        update_option('smsbao_register_tmp', $_POST['smsbao_register_tmp']);
    }

    $name = get_option('smsbao_name');
    $password = get_option('smsbao_password');
    $sign = get_option('smsbao_sign');
    $register = get_option('smsbao_register_tmp');

    if (empty($name)) {
        $name = 'smsbaouser';
        add_option('smsbao_name', $name);
    }

    if (empty($password)) {
        $password = '******';
        add_option('smsbao_password', $password);
    }

    if (empty($sign)) {
        $sign = '我的博客';
        add_option('smsbao_sign', $sign);
    }

    if (empty($register)) {
        $register = '用户您好，您的注册验证码为:{code}。';
        add_option('smsbao_register_tmp', $register);
    }


  print  <<< STR
    <h1>短信宝短信设置</h1>
    <form method="post">
        短信宝用户名：<input type="text" name="smsbao_name" value="$name" /> 没有账号？<a href="http://www.smsbao.com/reg">立即注册</a><br />
        <div style="height:10px;"></div>
        短信宝密码：　<input type="password" name="smsbao_password" value="$password" /> <br />
        <div style="height:10px;"></div>
        短信签名：　　<input type="text" name="smsbao_sign" value="$sign" /> <br />
        <div style="height:10px;"></div>
        注册验证模板：<textarea name="smsbao_register_tmp">$register</textarea><br />
        <div style="height:10px;"></div>
        <input type="submit" value="保 存" name="submit" /> 
    </form>
    
STR;

}



