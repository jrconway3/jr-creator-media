<?php

if (!defined('ABSPATH')) {
    exit;
}

function jr_creator_media_save_post($post_id, $post)
{
    if ($post->post_type !== jr_creator_media_social_post_type()) {
        return;
    }

    if (!isset($_POST['jr_creator_media_nonce'])) {
        return;
    }

    if (!wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['jr_creator_media_nonce'])), 'jr_creator_media_save')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (wp_is_post_revision($post_id)) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    $icon_id = isset($_POST['icon_image']) ? absint($_POST['icon_image']) : 0;
    $slug = isset($_POST['jrblog_social_slug']) ? jr_creator_media_sanitize_string(wp_unslash($_POST['jrblog_social_slug'])) : '';
    $url = isset($_POST['jrblog_social_url']) ? jr_creator_media_sanitize_string(wp_unslash($_POST['jrblog_social_url'])) : '';
    $username = isset($_POST['jrblog_social_name']) ? jr_creator_media_sanitize_string(wp_unslash($_POST['jrblog_social_name'])) : '';

    update_post_meta($post_id, 'icon_image', $icon_id);
    update_post_meta($post_id, 'jrblog_social_slug', $slug);
    update_post_meta($post_id, 'jrblog_social_full', !empty($_POST['jrblog_social_full']));
    update_post_meta($post_id, 'jrblog_social_sub', !empty($_POST['jrblog_social_sub']));
    update_post_meta($post_id, 'jrblog_social_url', $url);
    update_post_meta($post_id, 'jrblog_social_name', $username);
    update_post_meta($post_id, 'jrblog_social_share', !empty($_POST['jrblog_social_share']));
    update_post_meta($post_id, 'jrblog_social_sharing', !empty($_POST['jrblog_social_sharing']));
    update_post_meta($post_id, 'jrblog_social_follow', !empty($_POST['jrblog_social_follow']));
}
