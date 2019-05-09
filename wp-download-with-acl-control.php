<?php
/*
Plugin Name: دانلود فایل با مجوز
Plugin URI: http://txtzoom.com/
Description: دانلود فایل با قابلیت تعیین دسترسی برای اعضای سایت
Version: 1.0
Author: Taghi Nooshifard
Author URI: http://txtzoom.com/
License: GPLv2 or later
Text Domain: wp_download_with_acl
*/

// Constants Defines
define('WP_DOWNLOADER_INC',plugin_dir_path(__FILE__).DIRECTORY_SEPARATOR.'inc'.DIRECTORY_SEPARATOR);
define('WP_DOWNLOADER_TPL',plugin_dir_path(__FILE__).DIRECTORY_SEPARATOR.'tpl'.DIRECTORY_SEPARATOR);
define('WP_DOWNLOADER_ASSETS',plugin_dir_url(__FILE__).'/assets/');
define('WP_DOWNLOADER_ROOT',plugin_dir_path(__FILE__));

include WP_DOWNLOADER_INC.'admin'.DIRECTORY_SEPARATOR.'admin_shortcode.php';
include plugin_dir_path(__FILE__)."functions.php";

//Plugin Constructor
register_activation_hook(__FILE__,'wp_download_activation_hook');
function wp_download_activation_hook(){
    add_role('wp_downloader_with_acl',
        'Wordpress Downloader',
        [
            'read'=>true,
            'wp_download'
        ]);


}


// Add Menu Admin
add_action('admin_menu','wp_downloader_admin_menu');
function wp_downloader_admin_menu(){
    add_menu_page('دانلود با قابلیت تعیین دسترسی',
        'دانلود فایل با مجوز',
        'manage_options',
        'wp_download_with_acl',
        'wp_downloader_admin_main_handler','' );

}

function wp_downloader_admin_main_handler(){
    $userList = getUserList();
    include  WP_DOWNLOADER_INC.'admin'.DIRECTORY_SEPARATOR.'main_menu.php';
}

//Add scripts and styles
add_action('admin_enqueue_scripts','wp_download_admin_enqueue_scripts_handler');
add_action('wp_enqueue_scripts','wp_download_admin_enqueue_scripts_handler');
function wp_download_admin_enqueue_scripts_handler(){
    if(is_admin()){
        wp_register_script('wp_download_admin_jquery',WP_DOWNLOADER_ASSETS.'admin/js/wp_admin_jquery.js',['jquery','jquery-ui-core','jquery-ui-dialog','jquery-ui-tabs','jquery-ui-button','jquery-effects-core','jquery-effects-fade','jquery-effects-explode'],'1.0',true);
        wp_enqueue_script('wp_download_admin_jquery');

        wp_register_style('jquery-ui-smoothness', WP_DOWNLOADER_ASSETS.'admin/css/jquery-ui.css', null, '1.11.4');
        wp_enqueue_style('jquery-ui-smoothness');

        wp_register_style('wp_download_admin_css',WP_DOWNLOADER_ASSETS.'admin/css/wp_admin.css',null,'1.0');
        wp_enqueue_style('wp_download_admin_css');


    }
    else
    {
        wp_register_style('jquery-ui-smoothness', WP_DOWNLOADER_ASSETS.'admin/css/jquery-ui.css', null, '1.11.4');
        wp_enqueue_style('jquery-ui-smoothness');

        wp_register_script(
            'wp_download_front_jquery',
            WP_DOWNLOADER_ASSETS.'front/js/wp_front_jquery.js',
            ['jquery','jquery-ui-core','jquery-ui-dialog','jquery-ui-tabs','jquery-ui-button','jquery-effects-core','jquery-effects-fade','jquery-effects-explode'],
            '1.0',
            true);
        wp_enqueue_script('wp_download_front_jquery');

        wp_register_style('wp_download_front_css',WP_DOWNLOADER_ASSETS.'front/css/wp_front.css',null,'1.0');
        wp_enqueue_style('wp_download_front_css');

    }

}

// Ajax for Saving download path
add_action('wp_ajax_wp_download_save_path','wp_download_save_path');
function wp_download_save_path(){

    $directory_path = stripslashes($_POST['wp_download_path']);
    $result = is_Valid_Upload_Path($directory_path);
    if($result["is_valid"]) {
        update_option('wp_download_path',$directory_path);
        wp_send_json(['wp_download_path'=>$directory_path,
            "message"=>$result["message"]],200);
    }
    else{
        wp_send_json(["message"=>$result["message"]],400);
    }

}

// Ajax for Saving Login Form Settings
add_action('wp_ajax_wp_download_login_setting','wp_download_login_setting');
function wp_download_login_setting(){


    $result = is_Empty_Text($_POST['wp_download_login_title']);
    if($result["is_valid"]) {
        update_option('wp_download_login_title',$_POST['wp_download_login_title']);
    }
    else{
        wp_send_json(["message"=>$result["message"]],400);
    }
    if((bool)$_POST['wp_download_mobile_login']){
        update_option('wp_download_mobile_login',$_POST['wp_download_mobile_login']);
    }
    else{
        delete_option('wp_download_mobile_login');
    }

    wp_send_json(["message"=>$result["message"]],200);
}

// Ajax for Edit User Mobile and Phone and Address
add_action('wp_ajax_user_list_edit_dialog_button_edit','user_list_edit_dialog_button_edit');
function user_list_edit_dialog_button_edit(){



    if(isset($_POST['user_id']) and isset($_POST['user_meta']) and isset($_POST['user_meta_value'])){
        update_user_meta(intval($_POST['user_id']),$_POST['user_meta'],$_POST['user_meta_value']);

        wp_send_json(["message"=>"تغییرات انجام شد"],200);

    }
    else{
        wp_send_json(["message"=>"خطا در اجرای عملیات"],400);

    }

}


// Ajax for Changing User Access to Download File
add_action('wp_ajax_wp_download_user_access','wp_download_user_access');
function wp_download_user_access(){

    if(isset($_POST['user_id'])){
        $btn_title="دارد";

        $wp_user = get_user_by('ID',intval($_POST['user_id']));
        if ( $wp_user->has_cap( 'wp_download') ) {
            $wp_user->remove_cap('wp_download');
            $btn_title = "ندارد";
        }
        else{
            $wp_user->add_cap('wp_download');
        }
        wp_send_json(["message"=>"تغییرات انجام شد","has_access"=>$btn_title],200);

    }
    else{
        wp_send_json(["message"=>"خطا در اجرای عملیات"],400);

    }

}

// Ajax for Saving Register Form Settings
add_action('wp_ajax_wp_download_register_setting','wp_download_register_setting');
function wp_download_register_setting(){


    $result = is_Empty_Text($_POST['wp_download_register_title']);
    if($result["is_valid"]) {
        update_option('wp_download_register_title',$_POST['wp_download_register_title']);
    }
    else{
        wp_send_json(["message"=>$result["message"]],400);
    }
    if((bool)$_POST['wp_download_mobile_register']){
        update_option('wp_download_mobile_register',$_POST['wp_download_mobile_register']);
    }
    else{
        delete_option('wp_download_mobile_register');
    }
    if((bool)$_POST['wp_download_phone_register']){
        update_option('wp_download_phone_register',$_POST['wp_download_phone_register']);
    }
    else{
        delete_option('wp_download_phone_register');
    }
    if((bool)$_POST['wp_download_address_register']){
        update_option('wp_download_address_register',$_POST['wp_download_address_register']);
    }
    else{
        delete_option('wp_download_address_register');
    }

    wp_send_json(["message"=>$result["message"]],200);
}



// Ajax for Register User
add_action('wp_ajax_nopriv_wp_download_register_user','wp_download_register_user');
function wp_download_register_user(){
    $result = registerFormValidation();
    if(!$result["is_valid"])
        wp_send_json(['message'=>$result["message"],400]);

    $display_name = apply_filters("pre_user_display_name",$_POST['name']);
    $user_email = apply_filters("pre_user_email",$_POST['email']);
    $user_name = apply_filters("pre_user_login",str_replace("@","_",$user_email).rand(100,9999));
    $user_pass = apply_filters("pre_user_pass",$_POST['password']);
    $mobile =  $_POST['mobile'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];


    // Check for Repeated Email
    $user = get_user_by('email', $user_email);
    if (!empty($user)){
        wp_send_json(['message'=>"ایمیل وارد شده تکراری است. لطفا ایمیل دیگری وارد کنید."],400);
    }
// Check for Repeated Mobile
    if(hasMobileOption()){
        $ValidUserList = get_users(array('meta_key'=>'mobile','meta_value'=>$mobile));
        if (!empty($ValidUserList)){
            wp_send_json(['message'=>"موبایل وارد شده تکراری است. لطفا موبایل دیگری وارد کنید."],400);
        }

    }


    //Insert User
    $user_data = array('user_login'=>$user_name,
                        'display_name'=>$display_name,
                        'user_email'=>$user_email,
                        'user_pass'=>$user_pass);
    $user_id = wp_insert_user($user_data);

    //Check for Insert User
    if(is_wp_error($user_id)){
        wp_send_json(['message'=>"خطا در ذخیره سازی اطلاعات کاربر جدید".$user_id->get_error_message()],400);
    }

    // Add Meta Data User
    if(hasMobileOption()) update_user_meta($user_id,"mobile",$mobile);
    if(hasPhoneOption()) update_user_meta($user_id,"phone",$phone);
    if(hasAddressOption()) update_user_meta($user_id,"address",$address);

    //Set wp_download Role to User
    $user = new WP_User($user_id);
    $user->set_role('wp_download');

    //Send Success Message to User
    wp_send_json(['message'=>"ثبت نام موفقیت آمیز بود. می توانید  به سایت وارد شوید","success"=>true],200);

}

// Ajax for Login User
add_action('wp_ajax_nopriv_wp_download_login_user','wp_download_login_user');
function wp_download_login_user(){

    $result = loginFormValidation();
    if(!$result["is_valid"])
        wp_send_json(['message'=>$result["message"],400]);

    $user_email = apply_filters("pre_user_email",$_POST['email']);
    $user_pass = apply_filters("pre_user_pass",$_POST['password']);
    $mobile = sanitize_text_field($_POST['mobile']);
    $redirect_url = $_POST['redirect'];

    $user = null;
    if(is_LoginWithMobile()){
        $ValidUserList = get_users(array('meta_key'=>'mobile','meta_value'=>$mobile));
        if (empty($ValidUserList)){
            wp_send_json(['message'=>"برای ورود از نام کاربری و کلمه عبور مجاز استفاده کنید"],
                400);
        }

        $user = $ValidUserList[0];
    }
    else{
        // Check for  Email
        $user = wp_authenticate_email_password(null,$user_email,$user_pass);
        if (is_wp_error($user)){
            wp_send_json(['message'=>"برای ورود از نام کاربری و کلمه عبور مجاز استفاده کنید"],
                400);

        }
    }


    // Login With New Registered User
    $user_name = $user->user_login;

    $cred = array('user_login' => $user_name,
        'user_password' => $user_pass);

    $user = wp_signon( $cred, false );

    if (is_wp_error($user)){
        wp_send_json(['message'=>"امکان ورود به سایت وجود ندارد"],
            400);

    }
    wp_send_json(['message'=>$user->display_name.' خوش آمدید'],
        200);


}

// Ajax for Download file
add_action('wp_ajax_wp_download_file','wp_download_file');
function wp_download_file(){

    if(current_user_can('wp_download')){

        $fileName = get_post_meta($_POST['post_id'],"wp_download_file_name",true);
        $wp_download_directory = get_option('wp_download_path');

        if(!($wp_download_directory[strlen($wp_download_directory)-1] ==DIRECTORY_SEPARATOR ))
            $wp_download_directory = $wp_download_directory.DIRECTORY_SEPARATOR;

        $file = $wp_download_directory.$fileName;




        if (file_exists($file)) {
            header ("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header('Content-Type: application/octet-stream;');
            header("Content-Transfer-Encoding: Binary");
            header("Content-length: ".filesize($file));
            header("Content-disposition: attachment; filename=\"".basename($file)."\"");
            ob_clean(); //<--- add this line

            flush(); //<--- add this line

            $data = file_get_contents($file);
            print($data);
            exit;
        }

    }




}



//Add MetaBox Upload File To Post
add_action('add_meta_boxes_post','wp_download_add_meta_box');
function wp_download_add_meta_box($post){
    add_meta_box(
        "wp_download_file",
        "فایل پیوست",
        "wp_download_meta_handler",
        'post',
        'normal',
        'default');
}
function wp_download_meta_handler($post){
    include WP_DOWNLOADER_INC.'admin'.DIRECTORY_SEPARATOR.'meta_box_upload.php';
}
add_action('save_post','wp_download_meta_save');
function wp_download_meta_save($post_id){

    //get wp_download_path from plugin option
    $wp_download_directory = get_option('wp_download_path');
    if(empty($wp_download_directory) or !is_dir($wp_download_directory))
        wp_die("مسیر ذخیره سازی را در قسمت تنظیمات پلاگین اصلاح کنید."."<a href=".get_admin_url(null,"admin.php?page=wp_download_with_acl").">صفحه تنظیمات</a>","خطای مسیر ذخیره سازی");

    if (isset($_FILES['wp_uploaded_file']) and ($_FILES['wp_uploaded_file']['name']!="")){
        // Where the file is going to be stored
        $target_dir = $wp_download_directory;
        $file = $_FILES['wp_uploaded_file']['name'];
        $path = pathinfo($file);
        $filename = $path['filename'];
        $ext = $path['extension'];
        $temp_name = $_FILES['wp_uploaded_file']['tmp_name'];
        $path_filename_ext = $target_dir.DIRECTORY_SEPARATOR.$filename.".".$ext;

// Check if file already exists
        if (file_exists($path_filename_ext)) {
            wp_die("یک فایل با همین نام موجود است، لطفا نام فایل را تغییر داده و دوباره انتحان کنید."."<a href=".get_admin_url(null,"post.php?post={$post_id}&action=edit").">صفحه ویرایش</a>","نام فایل تکراری");
        }else{

            move_uploaded_file($temp_name,$path_filename_ext);
            //Delete prev file
            if(!empty(get_post_meta($post_id,"wp_download_file_name",true)))
                unlink($target_dir.DIRECTORY_SEPARATOR.get_post_meta($post_id,"wp_download_file_name",true));

            update_post_meta($post_id,'wp_download_file_name',$filename.".".$ext);
            wp_die("بارگذاری انجام شد"."<a href=".get_admin_url(null,"post.php?post={$post_id}&action=edit").">صفحه ویرایش</a>","موفقیت در بارگذاری فایل");
        }
    }


}
/**
 * Add functionality for file upload.
 */
function update_edit_form() {
    echo ' enctype="multipart/form-data"';
}
add_action( 'post_edit_form_tag', 'update_edit_form' );