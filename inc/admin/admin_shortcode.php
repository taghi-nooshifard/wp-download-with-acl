<?php


add_shortcode("wp_download_file","wp_download_file_short_code_handler");
function wp_download_file_short_code_handler($args,$content){
    if(current_user_can('wp_download')){

        global $post;
        $fileName = get_post_meta($post->ID,"wp_download_file_name",true);
        $download_link =  "<button class=\"button-primary wp_download_file\"  data-in=\"{$post->ID}\" data-value=\"{$fileName}\" >دریافت فایل </button>";
        return $download_link;


    }
    else{

        include_once WP_DOWNLOADER_INC.'admin'.DIRECTORY_SEPARATOR.'form_generator.php';

        $wp_download_url = get_post_permalink();

        $content_page =  "<button class=\"button-primary wp_download_login_or_register\" data-value=\"{$wp_download_url}\" >دریافت فایل </button>";

        return $content_page;

    }

}

