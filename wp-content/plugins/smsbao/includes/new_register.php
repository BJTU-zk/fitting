<?php
/**
 * Created by PhpStorm.
 * User: smsbao
 * Date: 2016/6/23
 * Time: 14:51
 */

if ( !function_exists('wp_new_user_notification') ) {
    /**
     * Notify the blog admin of a new user, normally via email.
     *
     * @since 2.0
     *
     * @param int $user_id User ID
     * @param string $plaintext_pass Optional. The user's plaintext password
     */
    function wp_new_user_notification($user_id, $plaintext_pass = '', $flag = '')
    {
        if (func_num_args() > 1 && $flag !== 1)
            return;

        $user = new WP_User($user_id);

        $user_login = stripslashes($user->user_login);
        $user_email = stripslashes($user->user_email);

        // The blogname option is escaped with esc_html on the way into the database in sanitize_option
        // we want to reverse this for the plain text arena of emails.
        $blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);

        $message = sprintf(__('New user registration on your site %s:'), $blogname) . "\r\n\r\n";
        $message .= sprintf(__('Username: %s'), $user_login) . "\r\n\r\n";
        $message .= sprintf(__('E-mail: %s'), $user_email) . "\r\n";

        @wp_mail(get_option('admin_email'), sprintf(__('[%s] New User Registration'), $blogname), $message);

        if (empty($plaintext_pass))
            return;

        $message = sprintf(__('Username: %s'), $user_login) . "\r\n";
        $message .= sprintf(__('Password: %s'), $plaintext_pass) . "\r\n";
        $message .= '登陆网址: ' . wp_login_url() . "\r\n";
        wp_mail($user_email, sprintf(__('[%s] Your username and password'), $blogname), $message);
    }
}

function new_register_form() {
    $code_url = constant('SMSBAO_PLUGIN_URL') . '/captcha/captcha.php';

?>
    <script>
        jQuery(document).ready(function($) {
            if ($("#get_code").length > 0) {
                var flg = false;
                var node = $("#get_code");
                var text = node.text();
                var setT = 60;
                var clear = null;

                function okSet() {
                    --setT;
                    node.text(setT + "秒后提交");
                    if (0 == setT) {
                        node.attr("disabled", false);
                        node.text(text);
                        setT = 60;
                        clearTimeout(clear);
                    } else {
                        node.attr("disabled", "disabled");
                        clearTimeout(clear);
                        clear = setTimeout(function(){
                            okSet();
                        }, 1000);
                    }
                }

                $("#get_code").on("click", function () {
                    var phone = $("#user_phone").val();
                    var captcha = $("#CAPTCHA").val();
                    var tmp = "<?php echo get_option('smsbao_register_tmp', null)?>";
                    var username = "<?php echo get_option('smsbao_name', null)?>";
                    var password = "<?php echo md5(get_option('smsbao_password', null)) ?>";
                    var sign = "<?php echo get_option('smsbao_sign', null) ?>";
                    var dburl = "<?php echo str_replace('\\', '/', ABSPATH . 'wp-load.php')?>";

                    if (null != sign) {
                        sign = '【' + sign + '】';
                    }

                    var data =  {"phone" : phone, "captcha" : captcha, "tmp" : tmp, "username" : username, "password" : password, "sign" : sign, "dburl" : dburl};

                    if (false == flg) {
                        flg = true;

                        $.ajax({
                            "url" : "<?php echo constant('SMSBAO_PLUGIN_URL') . '/includes/get_code.php'?>",
                            "type" : "post",
                            "data" : data,
                            "dataType" : "json",
                            "success" : function (msg) {
                                var errorMsg = null;

                                if (true == msg.flg) {
                                    $("#captcha_img").click();
                                    alert('发送成功');
                                    okSet();
                                } else {
                                    alert(msg.err);
                                }

                                flg = false;
                            }
                        });
                    }
                });
            }

        });

        function setCode() {

        }

    </script>
    <style>
        #reg_passmail {display: none;}
    </style>

<p>
	<label for="user_phone">手机号码<br/>
		<input id="user_phone" class="woocommerce-Input woocommerce-Input--text input-text" type="text" size="25" value="<?php echo empty($_POST['user_phone']) ? '':$_POST['user_phone']; ?>" name="user_phone" />
	</label>
</p>


<p>
	<label for="CAPTCHA">验证码:<br />
		<input id="CAPTCHA" style="width:50%;*float:left;" class="woocommerce-Input woocommerce-Input--text input-text" type="text" size="10" value="" name="captcha_code" />
		看不清？<a href="javascript:void(0)" onclick="document.getElementById('captcha_img').src='<?php echo constant("SMSBAO_PLUGIN_URL"); ?>/captcha/captcha.php?'+Math.random();document.getElementById('CAPTCHA').focus();return false;">点击更换</a>
	</label>
</p>
<p>
	<label>
        <img id="captcha_img" src="<?php echo constant("SMSBAO_PLUGIN_URL"); ?>/captcha/captcha.php" title="看不清?点击更换" alt="看不清?点击更换" onclick="document.getElementById('captcha_img').src='<?php echo constant("SMSBAO_PLUGIN_URL"); ?>/captcha/captcha.php?'+Math.random();document.getElementById('CAPTCHA').focus();return false;" />
	</label>
</p>

<p>
	<label for="user_activation_key">短信验证码:<br />
		<input id="user_active" style="width:50%;*float:left;" class="woocommerce-Input woocommerce-Input--text input-text" type="text" size="10" value="" name="user_active" />
        <button type="button" class="woocommerce-Button button" id="get_code" style="display:inline;">获取验证码</button>
    </label>
</p>

<input type="hidden" name="user_role"  value="customer" />

<?php
}

function check_fields($login, $email, $errors) {
    global $wpdb;

    if(strlen($_POST['user_pass']) < 6)
        $errors->add('password_length', "<strong>错误</strong>：密码长度至少6位");
    elseif($_POST['user_pass'] != $_POST['user_pass2'])
        $errors->add('password_error', "<strong>错误</strong>：两次输入的密码必须一致");

    if($_POST['user_role'] != 'contributor')
        $errors->add('role_error', "<strong>错误</strong>：不存在的用户身份");

    $table = $wpdb->prefix . 'user_active';
    $key = 0;
    $is_phone = preg_match('/^1[34578]{1}\d{9}$/', $_POST['user_phone']);

    if (empty($_POST['user_phone'])) {
        $errors->add('phone_error', "<strong>错误</strong>：手机号码必填");
    } else if (false == $is_phone) {
        $errors->add('phone_error', "<strong>错误</strong>：手机号码格式不正确");
    } else {
        $sql = "select id, active_num, active_time, is_active from {$table} where phone='{$_POST['user_phone']}'";
        $obj =  $wpdb->get_row($sql);

        if (empty($obj)) {
            $errors->add('phone_error', "<strong>错误</strong>：请先手机获取激活码！");
        } else {
            if (empty($_POST['user_active'])) {
                $errors->add('user_active_error', "<strong>错误</strong>：请填写短信验证码！");
            } else if ($obj->active_num != $_POST['user_active']) {
                $errors->add('user_active_error', "<strong>错误</strong>：短信验证码不匹配！");
            } else {
                $currentTime = time();
                $getTime = $obj->active_time;
                $expireTime = $getTime + (3600 * 24);

                if ($currentTime >= $expireTime) {
                    $errors->add('user_active_error', "<strong>错误</strong>：短信验证码已过期，请重新获取！");
                }

                if (!empty($obj->is_active)) {
                    $errors->add('user_active_error', "<strong>错误</strong>：该短信验证码已经被用于注册，请重新获取！");
                }
            }
        }
    }

    if (empty($errors->errors)) {
        $wpdb->update($table, array('is_active'=>1), array('id'=>$obj->id));
    }

}

function save_register_data($user_id, $password="", $meta=array()) {
    $userdata = array();
    $userdata['ID'] = $user_id;
    $userdata['user_pass'] = $_POST['user_pass'];
    $userdata['role'] = $_POST['user_role'];

    wp_new_user_notification($user_id, $_POST['user_pass'], 1);
    wp_update_user($userdata);
}

add_action('register_form','new_register_form');
add_action('admin_header', 'setJquery');
add_action('register_post','check_fields', 10, 3);
add_action('user_register', 'save_register_data');
?>
