<?php
/**
 * Created by PhpStorm.
 * User: phpDev
 * Date: 4/3/2019
 * Time: 2:38 PM
 */
if(!current_user_can('wp_download')){
    $content_page = " 
    <div id=\"mainDialog\" class=\"main_dialog\">
    <h2 id=\"message_board\"></h2>
    <div id=\"mainTab\" >
    <ul class=\"\">
        <li id='login_tab'><a href=\"#tab1\">ورود</a></li>
        <li id='register_tab'><a href=\"#tab2\">ثبت نام</a></li>
    </ul>
        ".generateLoginForm()."
        ".generateRegisterForm()."
    </div>
</div> ";
    echo $content_page;
}
//Login Form
function getLoginMobile(){
    $mobileTag = getMobileOption();
    if(is_LoginWithMobile() and  strlen($mobileTag)>0) {
        return str_replace("register","login",$mobileTag);
    }
    return "";

}
function getLoginFormTitle(){

    $loginTitle =  !empty(get_option('wp_download_login_title'))? get_option('wp_download_login_title') : "ورود به سایت";

    return $loginTitle;
}
function generateLoginForm(){
    $loginTitle = getLoginFormTitle();
    $mobileTag = getLoginMobile();
    $emailTag = "<label>ایمیل :</label>
        <input type=\"email\" name=\"email_login\" id=\"email_login\">";
    $loginID = strlen($mobileTag)>0?$mobileTag:$emailTag;
    $loginContent = "<div id=\"tab1\" class=\"\">
<form>
            <h2>".$loginTitle."</h2>
            ".$loginID."
<label>کلمه عبور :</label>
<input type=\"password\" name=\"password_login\" id=\"password_login\">
<hr>
<input type=\"button\" name=\"login_button\" id=\"login_button\" value=\"ورود\">
<input type=\"button\" class=\"close\" id=\"close1\" value=\"خروج \">
</form>
        </div>";
    return $loginContent;
}
//Register Form
function getRegisterFormTitle(){

    $registerTitle =  !empty(get_option('wp_download_register_title'))? get_option('wp_download_register_title') : "ثبت نام در سایت";

    return $registerTitle;
}
function getMobileOption(){
    if(hasMobileOption()){
        return "<label>شماره همراه :</label>
<input type=\"text\" name=\"mobile_register\" id=\"mobile_register\">
";
    }
    return "";

}
function getPhoneOption(){
    if(hasPhoneOption()){
        return "<label>شماره تلفن :</label>
<input type=\"text\" name=\"phone_register\" id=\"phone_register\">
";
    }
    return "";

}
function getAddressOption(){
    if(hasAddressOption()){
        return "<label>آدرس :</label>
<textarea type=\"text\" name=\"address_register\" id=\"address_register\"></textarea>";
    }
    return "";

}

function generateRegisterForm(){
    $registerTitle = getRegisterFormTitle();

    $registerContent = "        <div id=\"tab2\" class=\"\">
<form>
            <h2>".$registerTitle."</h2>
            <label>نام ونام خانوادگی :</label>
<input type=\"text\" name=\"name_register\" id=\"name_register\">
<label>ایمیل :</label>
<input type=\"email\" name=\"email_register\" id=\"email_register\">
".getMobileOption()."
".getPhoneOption()."
".getAddressOption()."
<label>کلمه عبور :</label>
<input type=\"password\" name=\"password_register1\" id=\"password_register\">
<label>تکرار کلمه عبور :</label>
<input type=\"password\" name=\"cpassword_register1\" id=\"cpassword_register\">
<hr>
<input type=\"button\" name=\"register_button\" id=\"register_button\" value=\"ثبت نام\">
<input type=\"button\" class=\"close\" id=\"close2\" value=\"خروج \">
</form>            
        </div>
           
";
    return $registerContent;
}