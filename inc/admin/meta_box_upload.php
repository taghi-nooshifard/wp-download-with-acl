<?php
$wp_downlaod_file_name = get_post_meta(
    $post->ID,
    "wp_download_file_name",
    true);

include WP_DOWNLOADER_TPL.'admin'.DIRECTORY_SEPARATOR.'meta_box_upload.php';
