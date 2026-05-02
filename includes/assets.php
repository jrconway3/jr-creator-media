<?php

if (!defined('ABSPATH')) {
    exit;
}

function jr_creator_media_enqueue_admin_assets($hook_suffix)
{
    if (!in_array($hook_suffix, array('post.php', 'post-new.php'), true)) {
        return;
    }

    $screen = function_exists('get_current_screen') ? get_current_screen() : null;
    if (!$screen || !in_array($screen->post_type, jr_creator_media_supported_admin_post_types(), true)) {
        return;
    }

    wp_enqueue_media();

    wp_enqueue_style(
        'jr-creator-media',
        JR_CREATOR_MEDIA_URL . 'assets/css/admin.css',
        array(),
        JR_CREATOR_MEDIA_VERSION
    );

    wp_enqueue_script(
        'jr-creator-media',
        JR_CREATOR_MEDIA_URL . 'assets/js/admin.js',
        array('jquery'),
        JR_CREATOR_MEDIA_VERSION,
        true
    );

    wp_localize_script('jr-creator-media', 'jrCreatorMedia', array(
        'selectImage' => __('Select icon image', 'jr-creator-media'),
        'useImage' => __('Use icon image', 'jr-creator-media'),
        'emptyPreview' => __('No image selected.', 'jr-creator-media'),
    ));
}
