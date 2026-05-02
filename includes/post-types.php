<?php

if (!defined('ABSPATH')) {
    exit;
}

function jr_creator_media_register_post_types()
{
    register_post_type('social', array(
        'labels' => array(
            'name' => __('Social Links', 'jr-creator-media'),
            'singular_name' => __('Social', 'jr-creator-media'),
            'menu_name' => __('Social Links', 'jr-creator-media'),
            'add_new_item' => __('Add New Social Link', 'jr-creator-media'),
            'edit_item' => __('Edit Social Link', 'jr-creator-media'),
            'new_item' => __('New Social Link', 'jr-creator-media'),
            'view_item' => __('View Social Link', 'jr-creator-media'),
            'search_items' => __('Search Social Links', 'jr-creator-media'),
            'not_found' => __('No social links found.', 'jr-creator-media'),
            'not_found_in_trash' => __('No social links found in Trash.', 'jr-creator-media'),
        ),
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_rest' => true,
        'has_archive' => false,
        'rewrite' => false,
        'supports' => array('title'),
        'menu_icon' => 'dashicons-share',
    ));

    register_post_type('video', array(
        'labels' => array(
            'name' => __('Videos', 'jr-creator-media'),
            'singular_name' => __('Video', 'jr-creator-media'),
            'menu_name' => __('Videos', 'jr-creator-media'),
            'add_new_item' => __('Add New Video', 'jr-creator-media'),
            'edit_item' => __('Edit Video', 'jr-creator-media'),
            'new_item' => __('New Video', 'jr-creator-media'),
            'view_item' => __('View Video', 'jr-creator-media'),
            'search_items' => __('Search Videos', 'jr-creator-media'),
            'not_found' => __('No videos found.', 'jr-creator-media'),
            'not_found_in_trash' => __('No videos found in Trash.', 'jr-creator-media'),
        ),
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_rest' => true,
        'has_archive' => true,
        'rewrite' => array('slug' => 'videos'),
        'supports' => array('title', 'editor', 'thumbnail', 'author', 'revisions'),
        'menu_icon' => 'dashicons-video-alt3',
    ));
}
