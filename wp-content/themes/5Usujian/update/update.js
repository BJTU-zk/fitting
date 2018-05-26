jQuery(function($) {
	//手动验证密钥
	$("#check-themekey").on('click', function(event) {
		event.preventDefault();
		var alipayid = jQuery("#alipayid").val();
    	var themekey = jQuery("#themekey").val();
    	$(this).attr('disabled', 'disabled').addClass('avia_button_inactive').html("正在验证");
		wysjTheme.check_key(alipayid,themekey);
	});
    $("#alipayid,#themekey").on('change input', function(event) {
        wysjTheme.setCookie("wysj-theme-checked", "", -1, "/");
        wysjTheme.setCookie("wysj-theme-dismiss", "", -1, "/");
    });
})

var wysjTheme = {

    //验证密钥方法
    check_key:function(alipayid,themekey){
        jQuery.ajax({
            type: "POST",
            url: "http://update.wysujian.com/theme_key/check_key.php",
            data: {
                alipayid: alipayid,
                themekey: themekey
            },
            success: function(msg){
                if(msg){
                    alert('验证通过，请点击右下角保存所有按钮保存密钥信息');
                    jQuery("#check-themekey").html('验证通过');
                }else{
                    alert('验证失败，请检查淘宝账号和密钥填写是否有误');
                    jQuery("#check-themekey").removeAttr('disabled').removeClass('avia_button_inactive').html('重新验证');
                }
            }
        })
    },
    setCookie:function (c_name,value,expiredays,path){
        var exdate=new Date();
        exdate.setDate(exdate.getDate()+expiredays);
        document.cookie= c_name+ "=" +escape(value)+((expiredays==null) ? "" : ";expires="+exdate.toGMTString())+((path==null) ? "" : ";path="+path);
    },
    getCookie:function(c_name){
        if (document.cookie.length>0){
            c_start=document.cookie.indexOf(c_name + "=");
            if (c_start!=-1){ 
                c_start=c_start + c_name.length+1;
                c_end=document.cookie.indexOf(";",c_start);
                if (c_end==-1) 
                    c_end=document.cookie.length;
                    return unescape(document.cookie.substring(c_start,c_end));
            } 
        }
        return "";
    }

}
