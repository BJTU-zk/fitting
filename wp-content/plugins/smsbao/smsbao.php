<?php
/**
 * Created by PhpStorm.
 * User: smsbao
 * Date: 2016/6/20
 * Time: 11:23
 */
/*
Plugin Name: 短信宝短信插件
Plugin URI: http://www.smsbao.com/plugin/
Description: 短信宝短信插件。专注提供最好用的短信服务。稳定，快速是我们不变的追求。该插件提供用户注册时的短信验证功能。
Author: smsbao
Version: 1.0
Author URI: http://www.smsbao.com
*/
if (!isset($_SESSION)) {
    session_start();
    session_regenerate_id(TRUE);
}
global $wpdb;
$tabelName = $wpdb->prefix . 'user_active';
$sql = "CREATE TABLE IF NOT EXISTS `$tabelName` (
  `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `phone` varchar(20) NOT NULL DEFAULT '',
  `active_num` varchar(20) NOT NULL DEFAULT '',
  `active_time` INT NOT NULL DEFAULT 0,
  `is_active` INT NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8;";

$wpdb->query($sql);

define('SMSBAO_PLUGIN_URL', plugin_dir_url( __FILE__ ));
wp_enqueue_script("jquery");

require(dirname(__FILE__) . '/includes/menu_ui.php');
require(dirname(__FILE__) . '/includes/new_register.php');
