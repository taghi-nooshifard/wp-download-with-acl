<?php
add_shortcode("wp_download_file","wp_download_file_short_code_handler");
function wp_download_file_short_code_handler($args,$content){
    if(current_user_can('wp_download')){

        global $post;
        $fileName = get_post_meta($post->ID,"wp_download_file_name",true);
        $download_link =  "<button class=\"button-primary\" id=\"wp_download_file\" name=\"wp_download_file\" data-in=\"{$post->ID}\" data-value=\"{$fileName}\" >دریافت فایل </button>";
        return $download_link;


    }
    else{

        $wp_download_url = get_post_permalink();

        $login_page =  "<button class=\"button-primary\" id=\"wp_download_login_or_register\" name=\"wp_download_login_or_register\" data-value=\"{$wp_download_url}\" >دریافت فایل </button>";

        $login_page = $login_page." "." 
    <div id=\"mainDialog\" class=\"main_dialog\">
    <ul class=\"tabs\">
        <li id='login_tab'><a href=\"#tab1\">ورود</a></li>
        <li id='register_tab'><a href=\"#tab2\">ثبت نام</a></li>
    </ul>
    <div class=\"tab_container\">
        <div id=\"tab1\" class=\"tab_content\">
            <h2>ورود به سایت</h2>
            <label>ایمیل :</label>
<input type=\"text\" name=\"email_login\" id=\"email_login\">
<label>کلمه عبور :</label>
<input type=\"password\" name=\"password_login\" id=\"password_login\">
<input type=\"button\" name=\"login_button\" id=\"login_button\" value=\"ورود\">
<input type=\"button\" class=\"close\" id=\"close1\" value=\"خروج \">

        </div>
        <div id=\"tab2\" class=\"tab_content\">
            
            <h2>ثبت نام در سایت</h2>
<label>نام ونام خانوادگی :</label>
<input type=\"text\" name=\"name_register\" id=\"name_register\">
<label>ایمیل :</label>
<input type=\"text\" name=\"email_register\" id=\"email_register\">
<label>شماره همراه :</label>
<input type=\"text\" name=\"mobile_register\" id=\"mobile_register\">
<label>کلمه عبور :</label>
<input type=\"password\" name=\"password_register1\" id=\"password_register\">
<label>تکرار کلمه عبور :</label>
<input type=\"password\" name=\"cpassword_register1\" id=\"cpassword_register\">
<input type=\"button\" name=\"register_button\" id=\"register_button\" value=\"ثبت نام\">
<input type=\"button\" class=\"close\" id=\"close2\" value=\"خروج \">
        </div>
        
    </div>
</div> ";

        return $login_page;

    }
}