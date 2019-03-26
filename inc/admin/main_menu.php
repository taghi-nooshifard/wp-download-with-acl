<?php
// Public Section
$wp_download_directory = get_option('wp_download_path');

//Login Section
$wp_download_login_title = get_option('wp_download_login_title');
$wp_download_mobile_login = get_option('wp_download_mobile_login');


//Register Section
$wp_download_register_title = get_option('wp_download_register_title');
$wp_download_mobile_register = get_option('wp_download_mobile_register');
$wp_download_phone_register = get_option('wp_download_phone_register');
$wp_download_address_register = get_option('wp_download_address_register');


include WP_DOWNLOADER_TPL.'admin'.DIRECTORY_SEPARATOR.'main_menu.php';