<?php

if (!defined('ABSPATH')) {
    exit;
}

function jr_creator_media_bootstrap()
{
    add_action('init', 'jr_creator_media_register_post_types', 8);
    add_action('init', 'jr_creator_media_register_meta', 9);
    add_action('init', 'jr_creator_media_maybe_upgrade', 20);
    add_action('add_meta_boxes', 'jr_creator_media_register_metaboxes');
    add_action('admin_enqueue_scripts', 'jr_creator_media_enqueue_admin_assets');
    add_action('save_post', 'jr_creator_media_save_post', 10, 2);
    add_action('rest_api_init', 'jr_creator_media_register_rest_routes');
}
