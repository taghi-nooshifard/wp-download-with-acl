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

include WP_DOWNLOADER_INC.'admin'.DIRECTORY_SEPARATOR.'admin_shortcode.php';

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
    include  WP_DOWNLOADER_INC.'admin'.DIRECTORY_SEPARATOR.'main_menu.php';
}

//Add scripts and styles
add_action('admin_enqueue_scripts','wp_download_admin_enqueue_scripts_handler');
add_action('wp_enqueue_scripts','wp_download_admin_enqueue_scripts_handler');
function wp_download_admin_enqueue_scripts_handler(){
    if(is_admin()){
        wp_register_script('wp_download_admin_jquery',WP_DOWNLOADER_ASSETS.'admin/js/wp_admin_jquery.js',['jquery','jquery-ui-core','jquery-ui-dialog'],'1.0',true);
        wp_enqueue_script('wp_download_admin_jquery');

        wp_register_style('wp_download_admin_css',WP_DOWNLOADER_ASSETS.'admin/css/wp_admin.css',null,'1.0');
        wp_enqueue_style('wp_download_admin_css');


    }
    else
    {
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
    $directory_path = $_POST['wp_download_path'];
    $directory_path = stripslashes($directory_path);
    if(!empty($directory_path) and is_dir($directory_path) and is_writable($directory_path)) {
        update_option('wp_download_path',$directory_path);
        wp_send_json(['wp_download_path'=>$directory_path]);
    }
    else{
        wp_send_json_error("خطا در ذخیره سازی داده. مقدار معتبر را وارد کنید...");
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
        wp_die("مسیر ذخیره سازی را در قسمت تنظیمات پلاگین اصلاح کنید.","خطای مسیر ذخیره سازی");

    if (($_FILES['wp_uploaded_file']['name']!="")){
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
            wp_die("یک فایل با همین نام موجود است، لطفا نام فایل را تغییر داده و دوباره انتحان کنید.","نام فایل تکراری");
        }else{

            move_uploaded_file($temp_name,$path_filename_ext);
            //Delete prev file
            if(!empty(get_post_meta($post_id,"wp_download_file_name",true)))
                unlink($target_dir.DIRECTORY_SEPARATOR.get_post_meta($post_id,"wp_download_file_name",true));

            update_post_meta($post_id,'wp_download_file_name',$filename.".".$ext);
            wp_die("بارگذاری انجام شد","موفقیت در بارگذاری فایل");
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

// Ajax for Register User
add_action('wp_ajax_wp_download_register_user','wp_download_register_user');
function wp_download_register_user(){

    $display_name = $_POST['name'];
    $user_email = $_POST['email'];
    $user_name = str_replace("@","_",$user_email);
    $mobile = $_POST['mobile'];
    $user_pass = $_POST['password'];
    $redirect_url = $_POST['redirect'];


    // Check for Repeated Email
    $user = get_user_by('email', $user_email);
    if (!empty($user)){
        wp_send_json(['message'=>"ایمیل وارد شده تکراری است. لطفا ایمیل دیگری وارد کنید."]);
        return;
    }

    //Insert User
    $user_data = array('user_login'=>$user_name,
                        'display_name'=>$display_name,
                        'user_email'=>$user_email,
                        'user_pass'=>$user_pass);
    $user_id = wp_insert_user($user_data);

    //Check for Insert User
    if(is_wp_error($user_id)){
        wp_send_json(['message'=>"خطا در ذخیره سازی اطلاعات کاربر جدید".$user_id->get_error_message()]);
        return;
    }

    // Add Meta Mobile User
    update_user_meta($user_id,"mobile",$mobile);

    //Set wp_download Role to User
    $user = new WP_User($user_id);
    $user->set_role('wp_download');

    //Send Success Message to User
    wp_send_json(['message'=>"ثبت نام موفقیت آمیز بود. می توانید با ایمیل خود به سایت وارد شوید","success"=>true]);


    // Login With New Registered User
//    $cred = array('user_login' => $user_name,
//        'user_password' => $user_pass);
//    $user = wp_signon( $cred, false );
//    wp_send_json(['message'=>$user->ID." redirect ".$redirect_url]);

//    ob_start();
//    wp_redirect($redirect_url);
//    header("Location: ".$redirect_url);
//    die();
}

// Ajax for Login User
add_action('wp_ajax_wp_download_login_user','wp_download_login_user');
function wp_download_login_user(){

    $user_email = $_POST['email'];
    $user_name = str_replace("@","_",$user_email);
    $user_pass = $_POST['password'];
    $redirect_url = $_POST['redirect'];


    // Check for Repeated Email
    $user = get_user_by('email', $user_email);
    if (empty($user)){
        wp_send_json(['message'=>"برای ورود از نام کاربری و کلمه عبور مجاز استفاده کنید"]);
        return;
    }



    // Login With New Registered User
    $cred = array('user_login' => $user_name,
        'user_password' => $user_pass);
    $user = wp_signon( $cred, false );

//    wp_send_json(['message'=>$user->ID." current user is ".wp_get_current_user()->ID]);

    wp_send_json(['message'=>$user->display_name.' خوش آمدید']);

//    wp_redirect($redirect_url);
//    exit();
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


        if (is_file($file)) {

            header("Expires: 0");
            header("Cache-Control: no-cache, no-store, must-revalidate");
            header('Cache-Control: pre-check=0, post-check=0, max-age=0', false);
            header("Pragma: no-cache");
            header("Content-Disposition:attachment; filename=".basename($file));
            header("Content-Type: application/force-download");
            readfile($file);
            exit();
        }

    }




}