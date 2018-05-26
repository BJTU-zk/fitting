<?php

define(WYSJ_THEME_VER, "4.2.4");

if ( isset($_GET['wysj-theme-dismiss']) && $_GET['wysj-theme-dismiss'] == '1') {
  $_COOKIE["wysj-theme-dismiss"] = 'hidden';
  setcookie("wysj-theme-dismiss", "hidden", time()+3600*120, "/");
}

function theme_get_url_content($url, $data){
  if(function_exists("curl_init")){
    $ch = curl_init();
    $timeout = 30;
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    $file_contents = curl_exec($ch);
    curl_close($ch);
  }else{
  $is_auf=ini_get('allow_url_fopen')?true:false;
    if($is_auf){
      $file_contents = file_get_contents($url);
    }
  }
    return $file_contents;
}

//获取升级信息
function theme_updateNotice(){
  $info = theme_get_url_content("http://update.wysujian.com/5Usujian/plugin_update_stable.php",array ("plugin_name" => "5Usujian", "plugin_version" => WYSJ_THEME_VER, "alipayid" => avia_get_option('alipayid'), "themekey" => avia_get_option('themekey')));
  if ( $info != '' ) {//有新版本时
    return $info;
  }else if ( $info == false ){
  	$notice = '升级密钥验证失败，这会导致无法收到主题升级提醒，请检查密钥填写是否有误，马上<a href=admin.php?page=avia#goto_themekey>检查密钥</a>';
  	return $notice;
  }
}
function theme_showUpdateNotice(){
  if ( !isset($_COOKIE["wysj-theme-dismiss"]) && $_COOKIE["wysj-theme-dismiss"] != 'hidden' && $_COOKIE["wysj-theme-checked"] != 'newest') {//未忽略时或无新版本时
  	echo '<div class="notice notice-warning is-dismissible"><p><strong>'.$_COOKIE["wysj-theme-checked"].'</strong>，或<a href="admin.php?page=avia&wysj-theme-dismiss=1">忽略</a></p></div>';
  }
}

if ( !isset($_COOKIE["wysj-theme-checked"]) && !isset($_COOKIE["wysj-theme-dismiss"]) ) {
	$_COOKIE["wysj-theme-checked"] = theme_updateNotice();
	setcookie("wysj-theme-checked", theme_updateNotice(), time()+3600*120, "/");
}
add_action( 'admin_notices','theme_showUpdateNotice');
?>