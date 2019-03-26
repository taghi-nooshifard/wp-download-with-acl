<?php
function hasMobileOption(){
    return get_option('wp_download_mobile_register')=="true"?true:false;
}
function hasPhoneOption(){
    return get_option('wp_download_phone_register')=="true"?true:false;
}
function hasAddressOption(){
    return get_option('wp_download_address_register')=="true"?true:false;
}
function is_LoginWithMobile(){
    return get_option('wp_download_mobile_login')=="true"?true:false;
}

function registerFormValidation(){
    $result = ["message"=>"مقدار ورودی تایید شد",
        "is_valid"=>true];

    $display_name =  $_POST['name'];
    $user_email = $_POST['email'];
    $mobile = $_POST['mobile'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $user_pass = $_POST['password'];
    $user_cpass = $_POST['cpassword'];

    if(empty($display_name) or
        empty($user_email) or
        (hasMobileOption() and empty($mobile)) or
        (hasPhoneOption() and empty($phone)) or
        (hasAddressOption() and empty($address)) or
        empty($user_pass)) {

        $result = ["message" => "مقدار ورودی نمی تواند خالی باشد",
            "is_valid" => false];
        return $result;
    }
    if(strlen($user_pass)<5){
        $result = ["message"=>"طول کلمه عبور حداقل باید 5 باشد",
                "is_valid"=>false];
        return $result;
    }
    if($user_pass != $user_cpass){
        $result = ["message"=>"کلمات عبور وارد شده، یکسان نیستند. دوباره تلاش کتید.",
            "is_valid"=>false];
        return $result;
    }


    return $result;

}
function loginFormValidation(){
    $result = ["message"=>"مقدار ورودی تایید شد",
        "is_valid"=>true];

    $user_email = $_POST['email'];
    $mobile = $_POST['mobile'];
    $user_pass = $_POST['password'];

    if((!is_LoginWithMobile() and empty($user_email)) or
        (is_LoginWithMobile() and empty($mobile)) or
        empty($user_pass)) {

        $result = ["message" => "مقدار ورودی نمی تواند خالی باشد",
            "is_valid" => false];
        return $result;
    }


    return $result;

}

function is_Valid_Upload_Path($wp_download_path){
    $result = ["message"=>"مسیر ذخیره سازی فایل ها تایید شد",
        "is_valid"=>true];
    if(empty($wp_download_path)){

        $result = ["message"=>"مسیر ذخیره سازی فایل ها را تعیین کنید",
            "is_valid"=>false];
        return $result;

    }
    if(!is_dir($wp_download_path)){

        $result = ["message"=>"مسیر ذخیره سازی باید یک پوشه باشد",
            "is_valid"=>false];
        return $result;

    }
    if(!is_writable($wp_download_path)) {
        $result = ["message"=>"مسیر ذخیره سازی باید مجوز نوشتن برای کاربر جاری را ندارد",
            "is_valid"=>false];
        return $result;

    }
    return $result;
}
function is_Empty_Text($wp_setting_value){
    $result = ["message"=>"مقدار ورودی تایید شد",
        "is_valid"=>true];
    if(empty($wp_setting_value)){

        $result = ["message"=>"مقدار ورودی نمی تواند خالی باشد",
            "is_valid"=>false];
        return $result;

    }
    return $result;
}
function getUserList(){
    global $wpdb;
    $userList = $wpdb->get_results("SELECT * FROM {$wpdb->users}");
    return $userList;
}

