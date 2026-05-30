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

    $video_type = jr_creator_media_video_post_type();

    $video_string_fields = array(
        'yt_video_id',
        'yt_playlist_id',
        'yt_published_at',
        'yt_thumbnail_url',
        'yt_duration',
        'yt_broadcast_status',
        'yt_scheduled_start',
        'yt_import_source',
    );
    foreach ($video_string_fields as $key) {
        register_post_meta($video_type, $key, array(
            'single'            => true,
            'type'              => 'string',
            'show_in_rest'      => true,
            'sanitize_callback' => 'jr_creator_media_sanitize_string',
            'auth_callback'     => 'jr_creator_media_meta_auth_callback',
        ));
    }

    // Stored as strings to avoid 32-bit integer truncation on large counts.
    $video_count_fields = array('yt_view_count', 'yt_like_count');
    foreach ($video_count_fields as $key) {
        register_post_meta($video_type, $key, array(
            'single'            => true,
            'type'              => 'string',
            'show_in_rest'      => true,
            'sanitize_callback' => 'jr_creator_media_sanitize_count',
            'auth_callback'     => 'jr_creator_media_meta_auth_callback',
        ));
    }

    register_post_meta($video_type, 'wp_playlist_id', array(
        'single'            => true,
        'type'              => 'integer',
        'show_in_rest'      => true,
        'sanitize_callback' => 'absint',
        'auth_callback'     => 'jr_creator_media_meta_auth_callback',
    ));
}
