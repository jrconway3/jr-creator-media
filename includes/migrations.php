<?php

if (!defined('ABSPATH')) {
    exit;
}

function jr_creator_media_activate()
{
    jr_creator_media_register_post_types();
    jr_creator_media_register_meta();
    jr_creator_media_seed_default_social_profiles();

    update_option('jr_creator_media_version', JR_CREATOR_MEDIA_VERSION);

    flush_rewrite_rules();
}

function jr_creator_media_maybe_upgrade()
{
    $installed_version = get_option('jr_creator_media_version');

    if ($installed_version !== JR_CREATOR_MEDIA_VERSION) {
        jr_creator_media_seed_default_social_profiles();
        update_option('jr_creator_media_version', JR_CREATOR_MEDIA_VERSION);
    }
}

function jr_creator_media_seed_default_social_profiles()
{
    foreach (jr_creator_media_social_defaults() as $slug => $config) {
        $existing = get_posts(array(
            'name' => $slug,
            'post_type' => jr_creator_media_social_post_type(),
            'post_status' => array('publish', 'future', 'draft', 'pending', 'private', 'trash', 'auto-draft'),
            'numberposts' => 1,
            'fields' => 'ids',
        ));

        if (!empty($existing)) {
            continue;
        }

        $post_id = wp_insert_post(array(
            'post_name' => $slug,
            'post_title' => $config['name'],
            'post_status' => 'publish',
            'post_type' => jr_creator_media_social_post_type(),
            'ping_status' => 'closed',
            'comment_status' => 'closed',
        ));

        if (is_wp_error($post_id) || !$post_id) {
            continue;
        }

        update_post_meta($post_id, 'jrblog_social_slug', $slug);
        update_post_meta($post_id, 'jrblog_social_url', isset($config['url']) ? $config['url'] : '');
        update_post_meta($post_id, 'jrblog_social_share', !empty($config['share']));
        update_post_meta($post_id, 'jrblog_social_sub', !empty($config['sub']));
    }
}
