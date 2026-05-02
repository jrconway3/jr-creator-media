<?php

if (!defined('ABSPATH')) {
    exit;
}

function jr_creator_media_meta_auth_callback($allowed, $meta_key, $post_id, $user_id)
{
    if ($post_id > 0) {
        return user_can($user_id, 'edit_post', $post_id);
    }

    return user_can($user_id, 'edit_posts');
}

function jr_creator_media_register_meta()
{
    $social_type = jr_creator_media_social_post_type();

    register_post_meta($social_type, 'icon_image', array(
        'single' => true,
        'type' => 'integer',
        'show_in_rest' => true,
        'sanitize_callback' => 'absint',
        'auth_callback' => 'jr_creator_media_meta_auth_callback',
    ));

    register_post_meta($social_type, 'jrblog_social_slug', array(
        'single' => true,
        'type' => 'string',
        'show_in_rest' => true,
        'sanitize_callback' => 'jr_creator_media_sanitize_string',
        'auth_callback' => 'jr_creator_media_meta_auth_callback',
    ));

    register_post_meta($social_type, 'jrblog_social_full', array(
        'single' => true,
        'type' => 'boolean',
        'show_in_rest' => true,
        'sanitize_callback' => 'jr_creator_media_sanitize_bool',
        'auth_callback' => 'jr_creator_media_meta_auth_callback',
    ));

    register_post_meta($social_type, 'jrblog_social_sub', array(
        'single' => true,
        'type' => 'boolean',
        'show_in_rest' => true,
        'sanitize_callback' => 'jr_creator_media_sanitize_bool',
        'auth_callback' => 'jr_creator_media_meta_auth_callback',
    ));

    register_post_meta($social_type, 'jrblog_social_url', array(
        'single' => true,
        'type' => 'string',
        'show_in_rest' => true,
        'sanitize_callback' => 'jr_creator_media_sanitize_string',
        'auth_callback' => 'jr_creator_media_meta_auth_callback',
    ));

    register_post_meta($social_type, 'jrblog_social_name', array(
        'single' => true,
        'type' => 'string',
        'show_in_rest' => true,
        'sanitize_callback' => 'jr_creator_media_sanitize_string',
        'auth_callback' => 'jr_creator_media_meta_auth_callback',
    ));

    register_post_meta($social_type, 'jrblog_social_share', array(
        'single' => true,
        'type' => 'boolean',
        'show_in_rest' => true,
        'sanitize_callback' => 'jr_creator_media_sanitize_bool',
        'auth_callback' => 'jr_creator_media_meta_auth_callback',
    ));

    register_post_meta($social_type, 'jrblog_social_sharing', array(
        'single' => true,
        'type' => 'boolean',
        'show_in_rest' => true,
        'sanitize_callback' => 'jr_creator_media_sanitize_bool',
        'auth_callback' => 'jr_creator_media_meta_auth_callback',
    ));

    register_post_meta($social_type, 'jrblog_social_follow', array(
        'single' => true,
        'type' => 'boolean',
        'show_in_rest' => true,
        'sanitize_callback' => 'jr_creator_media_sanitize_bool',
        'auth_callback' => 'jr_creator_media_meta_auth_callback',
    ));
}
