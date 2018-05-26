<?php
/*
Plugin Name: WordPress短信服务(SMS)
Author: 水脉烟香
Author URI: https://wptao.com/smyx
Plugin URI: https://wptao.com/wptao-sms.html
Description: 支持手机号注册/登录，重要事件短信通知等。
Version: 1.0.1
*/

define('WPTAO_SMS_V', '1.0.1');
define("WPTAO_SMS_URL", plugins_url('wptao-sms'));

function wptao_sms_add_page() {
	if (function_exists('add_menu_page')) {
		add_menu_page('短信服务', '短信服务', 'manage_options', 'wptao-sms', 'wptao_sms_do_page', 'dashicons-email-alt');
	} 
	if (function_exists('add_submenu_page')) {
		add_submenu_page('wptao-sms', '短信服务', '短信服务', 'manage_options', 'wptao-sms');
		//add_submenu_page('wptao-sms', '手机用户', '手机用户', 'manage_options', 'wptao-sms-user', 'wptao_sms_user_do_page');
	} 
} 

add_action('admin_menu', 'wptao_sms_add_page');
add_action('plugin_action_links_' . plugin_basename(__FILE__), 'wptao_sms_plugin_actions');
function wptao_sms_plugin_actions($links) {
    $new_links = array();
    $new_links[] = '<a href="admin.php?page=wptao-sms">' . __('Settings') . '</a>';
    return array_merge($new_links, $links);
}
// 设置 Setting
function wptao_sms_do_page() {
	echo '<div class="error"><p><strong>本插件为付费插件，此处仅作为后台展示，不能使用功能，如果您有需求，请【<a href="https://wptao.com/wptao-sms.html" target="_blank">点击这里</a>】购买插件，买后卸载本插件，<a href="https://wptao.com/download" target="_blank">重新下载</a>安装后使用。</strong></p></div>';
	wp_register_script("wptao-sms-admin", WPTAO_SMS_URL . "/js/admin.js", array("jquery"), WPTAO_SMS_V);
	wp_print_scripts('wptao-sms-admin');
?>
<style type="text/css">
.wptao-container{margin-top:15px}
.wptao-grid a{text-decoration:none}
.wptao-main{width:80%;float:left}
.wptao-sidebar{width:19%;float:right}
.wptao-sidebar ol{margin-left:10px}
.wptao-box{margin:10px 0px;padding:10px;border-radius:3px 3px 3px 3px;border-color:#cc99c2;border-style:solid;border-width:1px;clear:both}
.wptao-box.yellow{background-color:#FFFFE0;border-color:#E6DB55}
@media (max-width:782px){
.wptao-grid{display:block;float:none;width:100%}
}
</style>
<div class="wrap">
  <h2>短信服务<code>v1.0.1</code> <code><a target="_blank" href="https://wptao.com/wptao-sms.html">官网</a></code></h2>
  <div id="poststuff">
    <div id="post-body">
      <div class="nav-tab-wrapper">
		<a id="group-code-tab" class="nav-tab nav-tab-active" title="验证码" href="#group-code">验证码</a><a id="group-sms-tab" class="nav-tab" title="短信通知" href="#group-sms">短信通知</a><a id="group-expand-tab" class="nav-tab" title="插件拓展" href="#group-expand">插件拓展</a>	  </div>
      <div class="wptao-container">
        <div class="wptao-grid wptao-main">
          <form method="post" action="">
            <input type="hidden" id="_wpnonce" name="_wpnonce" value="d28d8bfac0"><input type="hidden" name="_wp_http_referer" value="/wp-admin/admin.php?page=wptao-sms">            <div id="group-code" class="group" style="display: block;">
              <div class="postbox">
                <h3 class="hndle">
                  <label for="title">手机号注册/登录设置</label>
                </h3>
                <div class="inside">
                  <table class="form-table">
                    <tbody>
					<tr><th scope="row">手机验证码注册/登陆</th><td><label><input type="checkbox" value="1" name="options[1][open]" id="options-1-open">开启</label><p class="description">开启后以下所有功能才能使用</p></td></tr><tr><th scope="row">手机号密码登录</th><td><label><input type="checkbox" value="1" name="options[1][login_pass]" id="options-1-login_pass">开启</label><p class="description">可以使用手机号+密码登录网站。在任意登录页面都支持，使用ajax的登录不一定支持，如果登录页面没有提示手机号密码登录，请自己修改文字描述</p></td></tr><tr><th scope="row">新用户注册必须验证手机号</th><td><label><input type="checkbox" value="1" name="options[1][verify]" id="options-1-verify">开启</label><p class="description">开启后，将在您原来的注册页面强制要求填写手机号并且验证。</p></td></tr><tr><th scope="row">手机号注册独立页面</th><td><label><input type="checkbox" value="1" name="options[1][disable_reg]" id="options-1-disable_reg">关闭</label><p class="description">如果您需要保留原来的注册页面，并且开启了【新用户注册必须验证手机号】建议关闭它，因为此注册页面为本插件创建的，需要填写资料比较少。如果您发现您原来的注册页面不显示手机号验证，请不要关闭。</p></td></tr><tr><th scope="row">注册时需要填写密码</th><td><label><input type="checkbox" value="1" name="options[1][pass]" id="options-1-pass">开启</label><p class="description">如果您的注册页面已经包括，请不要开启，免得重复</p></td></tr><tr><th scope="row">解绑手机号</th><td><label><input type="checkbox" value="1" name="options[1][disable_unbind]" id="options-1-disable_unbind">不允许</label><p class="description">勾选后用户不能解绑手机号，只能修改为新手机号</p></td></tr><tr><th scope="row">服务商</th><td><select name="options[1][service]" id="options-1-service"><option value="0">请选择</option><option value="1">阿里云</option></select><p class="description"><a target="_blank" href="https://wptao.com/help/sms.html">查看教程</a></p></td></tr><tr><th scope="row"><label for="options-1-key">App Key</label></th><td><input type="text" name="options[1][key]" value="" id="options-1-key" size="40"></td></tr><tr><th scope="row"><label for="options-1-secret">App Secret</label></th><td><input type="text" name="options[1][secret]" value="" id="options-1-secret" size="40"></td></tr><tr><th scope="row"><label for="options-1-sign">短信签名</label></th><td><input type="text" name="options[1][sign]" value="" id="options-1-sign" size="40"><p class="description">请与服务商备案的签名保持一致！</p></td></tr><tr><th scope="row"><label for="options-1-tpl_id">验证码模版ID</label></th><td><input type="text" name="options[1][tpl_id]" value="" id="options-1-tpl_id" size="40"></td></tr><tr><th scope="row"><label for="options-1-tpl_str">验证码模版内容</label></th><td><input type="text" name="options[1][tpl_str]" value="" id="options-1-tpl_str" size="40"><p class="description">将服务商备案的模版内容复制一份到此，方便作为备注及检查参数，如需修改内容，请在服务商修改。</p></td></tr><tr><th scope="row">模版内容参考</th><td>验证码：<code>${code}</code>，您正在进行身份验证，打死不告诉别人！</td></tr>                    </tbody>
                  </table>
                </div>
                <!-- end of inside -->
              </div>
              <!-- end of postbox -->
            </div>
            <div id="group-sms" class="group" style="display: none;">
              <div class="postbox">
                <h3 class="hndle">
                  <label for="title">短信通知设置</label>
                </h3>
                <div class="inside">
                  <table class="form-table">
                    <tbody>
					<tr><th scope="row">服务商</th><td><select name="options[2][service]" id="options-2-service"><option value="0">请选择</option><option value="1">阿里云</option></select><p class="description"><a target="_blank" href="https://wptao.com/help/sms.html">查看教程</a></p></td></tr><tr><th scope="row"><label for="options-2-key">App Key</label></th><td><input type="text" name="options[2][key]" value="" id="options-2-key" size="40"></td></tr><tr><th scope="row"><label for="options-2-secret">App Secret</label></th><td><input type="text" name="options[2][secret]" value="" id="options-2-secret" size="40"></td></tr><tr><th scope="row"><label for="options-2-sign">短信签名</label></th><td><input type="text" name="options[2][sign]" value="" id="options-2-sign" size="40"><p class="description">请与服务商备案的签名保持一致！</p></td></tr><tr><th scope="row"><label for="options-2-tpl_num">需要的模版数量</label></th><td><input type="text" value="2" onkeyup="value=value.replace(/[^\d]/g,'')" name="options[2][tpl_num]" id="options-2-tpl_num" size="40"><p class="description">填写后先保存，至少1个</p></td></tr><tr><th scope="row"><label for="options-2-tpl_id_1">短信模版ID[1]</label></th><td><input type="text" name="options[2][tpl_id_1]" value="" id="options-2-tpl_id_1" size="40"></td></tr><tr><th scope="row"><label for="options-2-tpl_str_1">短信模版内容[1]</label></th><td><input type="text" name="options[2][tpl_str_1]" value="" id="options-2-tpl_str_1" size="40"><p class="description">将服务商备案的模版内容复制一份到此，方便作为备注及检查参数，如需修改内容，请在服务商修改。</p></td></tr><tr><th scope="row"><label for="options-2-tpl_id_2">短信模版ID[2]</label></th><td><input type="text" name="options[2][tpl_id_2]" value="" id="options-2-tpl_id_2" size="40"></td></tr><tr><th scope="row"><label for="options-2-tpl_str_2">短信模版内容[2]</label></th><td><input type="text" name="options[2][tpl_str_2]" value="" id="options-2-tpl_str_2" size="40"><p class="description">将服务商备案的模版内容复制一份到此，方便作为备注及检查参数，如需修改内容，请在服务商修改。</p></td></tr><tr><th scope="row">模版内容参考</th><td><strong>参考1：</strong>亲，订单${a}，详情可进入网站（http://xxx）查看！<br><strong>参考2：</strong>您当前的${a}状态：${b}，敬请留意！</td></tr><tr><th scope="row">使用说明</th><td><strong>如果您正在使用 WooCommerce 和 Easy Digital Downloads 插件，请看【插件拓展】。</strong>
					<br>当前支持4个自定义参数：<code>${a}</code>、<code>${b}</code>、<code>${c}</code>、<code>${d}</code>. 您可以给他们任意赋值。
					<br>在您需要短信通知的地方，加上以下代码：
					<br><code>do_action("wptao_send_sms", $user_id, $tpl_id, $a, $b, $c, $d);</code>
					<br>其中<code>$user_id</code>是网站的用户ID，就是发给谁，改成你代码对应的参数名，必须传入。
					<br><code>短信模版ID[1]</code>，<code>$tpl_id</code>就写<code>1</code>，记得把<code>$tpl_id, $a, $b, $c, $d</code>改成具体的值，顺序不能变，如果没有值就写<code>""</code>。
					<br><strong>举个例子：</strong>
					<br>您在服务商备案的<code>短信模版ID[1]</code>：<code>亲，订单${a}，详情可进入网站（http://xxx）查看！</code>
					<br>您要在订单发货后通知用户，那么找到您订单发货的函数/代码，在发货完成的后面加上：
					<br><code>do_action("wptao_send_sms", $user_id, 1, "已发货");</code></td></tr><tr><th scope="row">测试一下</th><td><label>接收的手机号: <input name="options[test][phone]" type="text" value="" id="options-test--phone" size="40"></label><label><br>选择模版: <select name="options[test][tpl_id]" id="options-test--tpl_id"><option value="" selected="selected">选择模板</option><option value="1">短信模版ID[1]</option><option value="2">短信模版ID[2]</option></select>&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" name="submit-test" class="button-primary" value="点击测试" onclick="return confirm('确定测试吗？如果发送成功，服务商会扣您几分钱哦！ ')"></label><label><br>参数${a}: <input name="options[test][data][a]" type="text" value="" id="options-test--data--a" size="40"></label><label><br>参数${b}: <input name="options[test][data][b]" type="text" value="" id="options-test--data--b" size="40"></label><label><br>参数${c}: <input name="options[test][data][c]" type="text" value="" id="options-test--data--c" size="40"></label><label><br>参数${d}: <input name="options[test][data][d]" type="text" value="" id="options-test--data--d" size="40"></label></td></tr>                    </tbody>
                  </table>
                </div>
                <!-- end of inside -->
              </div>
              <!-- end of postbox -->
            </div>
            <div id="group-expand" class="group" style="display: none;">
              <div class="postbox">
                <h3 class="hndle">
                  <label for="title">常用插件拓展（短信通知）</label>
                </h3>
                <div class="inside">
				                  <table class="form-table">
                    <tbody>
					<tr><th scope="row"><strong>WooCommerce</strong></th><td></td></tr><tr><th scope="row">订单状态</th><td>当订单为以下状态时发短信给买家：<br><br><label><input type="checkbox" name="options[addons][woo][status][]" value="completed" id="options-addons-woo-status">已完成</label> <label><input type="checkbox" name="options[addons][woo][status][]" value="refunded" id="options-addons-woo-status">已退款</label> </td></tr><tr><th scope="row">发送到订单填写的手机号</th><td><label><input type="checkbox" value="1" name="options[addons][woo][to_order_phone]" id="options-addons-woo-to_order_phone">开启</label><p class="description">开启后，优先发送到订单填写的手机号，否则会发送到买家绑定的手机号！如果买家没有绑定，才发到订单手机号。</p></td></tr><tr><th scope="row">关联模版</th><td><select name="options[addons][woo][tpl_id]" id="options-addons-woo-tpl_id"><option value="" selected="selected">选择模板</option><option value="1">短信模版ID[1]</option><option value="2">短信模版ID[2]</option></select><p class="description">请在【短信通知】设置后选择。请使用已经定义的参数：<br><code>$a</code>: 订单号+订单状态<br><code>$b</code>: 订单号<br><code>$c</code>: 订单状态，模板参考：<code>亲，订单${a}，详情可进入网站（http://xxx）查看！</code></p></td></tr><tr><th scope="row"><strong>Easy Digital Downloads</strong></th><td></td></tr><tr><th scope="row">订单状态</th><td>当订单为以下状态时发短信给买家：<br><br><label><input type="checkbox" name="options[addons][edd][status][]" value="completed" id="options-addons-edd-status">已完成</label> <label><input type="checkbox" name="options[addons][edd][status][]" value="refunded" id="options-addons-edd-status">已退款</label> </td></tr><tr><th scope="row">关联模版</th><td><select name="options[addons][edd][tpl_id]" id="options-addons-edd-tpl_id"><option value="" selected="selected">选择模板</option><option value="1">短信模版ID[1]</option><option value="2">短信模版ID[2]</option></select><p class="description">请在【短信通知】设置后选择。请使用已经定义的参数：<br><code>$a</code>: 订单号+订单状态<br><code>$b</code>: 订单号<br><code>$c</code>: 订单状态，模板参考：<code>亲，订单${a}，详情可进入网站（http://xxx）查看！</code></p></td></tr>                    </tbody>
                  </table>
				                  </div>
                <!-- end of inside -->
              </div>
              <!-- end of postbox -->
            </div>
            <p class="submit">
              <input type="hidden" name="options[regid]" value="">
              <input type="submit" name="update_options" class="button-primary" value="保存更改">
            </p>
          </form>
        </div>
        <div class="wptao-grid wptao-sidebar">
          <div class="postbox" style="min-width: inherit;">
            <h3 class="hndle">
              <label for="title">联系作者</label>
            </h3>
            <div class="inside">
              <p>QQ群①：<a href="http://shang.qq.com/wpa/qunwpa?idkey=ad63192d00d300bc5e965fdd25565d6e141de30e4f6b714708486ab0e305f639" target="_blank">88735031</a></p>
              <p>QQ群②：<a href="http://shang.qq.com/wpa/qunwpa?idkey=c2e8566f2ab909487224c1ebfc34d39ea6d84ddff09e2ecb9afa4edde9589391" target="_blank">149451879</a></p>
              <p>QQ：<a href="http://wpa.qq.com/msgrd?v=3&amp;uin=3249892&amp;site=qq&amp;menu=yes" target="_blank">3249892</a></p>
			  <p><a href="https://wptao.com/wptao-sms.html" target="_blank">官方网站</a></p>
			</div>
          </div>
          <div class="postbox" style="min-width: inherit;">
            <h3 class="hndle">
              <label for="title">产品推荐</label>
            </h3>
            <div class="inside">
			  <?php $source = urlencode(home_url());?>
              <ol><li><a target="_blank" href="https://wptao.com/product-lists.html?source=<?php echo $source;?>">产品套餐（付费一次拥有以下所有插件，超级划算）</a></li><li><a target="_blank" href="https://wptao.com/wp-connect.html?source=<?php echo $source;?>">WordPress连接微博专业版（一键登录网站，同步到微博、博客，社会化评论）</a></li><li><a target="_blank" href="https://wptao.com/wechat.html?source=<?php echo $source;?>">WordPress连接微信(微信机器人)</a></li><li><a target="_blank" href="https://wptao.com/blog-optimize.html?source=<?php echo $source;?>">WordPress优化与增强插件：博客优化</a></li><li><a target="_blank" href="https://wptao.com/wp-taomall.html?source=<?php echo $source;?>">WordPress淘宝客主题：wp-taomall (自动获取商品信息和推广链接)</a></li><li><a target="_blank" href="https://wptao.com/wptao.html?source=<?php echo $source;?>">WordPress淘宝客插件 (一键获取及自动填充商品信息和推广链接)</a></li><li><a target="_blank" href="https://wptao.com/wp-user-center.html?source=<?php echo $source;?>">WordPress用户中心</a></li><li><a target="_blank" href="https://wptao.com/weixin-cloned.html?source=<?php echo $source;?>">WordPress微信分身（避免微信封杀网站域名）</a></li><li><a target="_blank" href="https://wptao.com/weixin-helper.html?source=<?php echo $source;?>">WordPress微信群发助手</a></li></ol>			</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php
} 