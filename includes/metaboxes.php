<?php

if (!defined('ABSPATH')) {
    exit;
}

function jr_creator_media_register_metaboxes()
{
    add_meta_box(
        'jr-creator-media-social-icon',
        __('Icon', 'jr-creator-media'),
        'jr_creator_media_render_social_icon_metabox',
        jr_creator_media_social_post_type(),
        'side',
        'default'
    );

    add_meta_box(
        'jr-creator-media-social-url',
        __('URL', 'jr-creator-media'),
        'jr_creator_media_render_social_url_metabox',
        jr_creator_media_social_post_type(),
        'side',
        'default'
    );

    add_meta_box(
        'jr-creator-media-social-options',
        __('Options', 'jr-creator-media'),
        'jr_creator_media_render_social_options_metabox',
        jr_creator_media_social_post_type(),
        'side',
        'default'
    );
}

function jr_creator_media_render_nonce()
{
    wp_nonce_field('jr_creator_media_save', 'jr_creator_media_nonce');
}

function jr_creator_media_render_social_icon_metabox($post)
{
    jr_creator_media_render_nonce();

    $icon_id = absint(get_post_meta($post->ID, 'icon_image', true));
    $icon_src = $icon_id ? wp_get_attachment_image_url($icon_id, 'thumbnail') : '';

    echo '<div class="jr-creator-media-image-picker">';
    echo '<input type="hidden" id="icon_image" name="icon_image" value="' . esc_attr((string) $icon_id) . '">';
    echo '<div class="jr-creator-media-image-preview">';
    if ($icon_src) {
        echo '<img src="' . esc_url($icon_src) . '" alt="">';
    } else {
        echo '<span class="description">' . esc_html__('No image selected.', 'jr-creator-media') . '</span>';
    }
    echo '</div>';
    echo '<p><button type="button" class="button jr-creator-media-select-image">' . esc_html__('Select image', 'jr-creator-media') . '</button> ';
    echo '<button type="button" class="button-link jr-creator-media-clear-image">' . esc_html__('Clear image', 'jr-creator-media') . '</button></p>';
    echo '</div>';
}

function jr_creator_media_render_social_url_metabox($post)
{
    jr_creator_media_render_nonce();

    $full = !empty(get_post_meta($post->ID, 'jrblog_social_full', true));
    $slug = get_post_meta($post->ID, 'jrblog_social_slug', true);
    if ($slug === '') {
        $slug = $post->post_name;
    }
    $sub = !empty(get_post_meta($post->ID, 'jrblog_social_sub', true));
    $url = get_post_meta($post->ID, 'jrblog_social_url', true);
    $name = get_post_meta($post->ID, 'jrblog_social_name', true);

    echo '<p><label><input type="checkbox" name="jrblog_social_full" value="1"' . checked($full, true, false) . '> ' . esc_html__('Use full social media URL', 'jr-creator-media') . '</label></p>';
    echo '<p><label><input type="checkbox" name="jrblog_social_sub" value="1"' . checked($sub, true, false) . '> ' . esc_html__('Use social media URL as subdomain', 'jr-creator-media') . '</label></p>';
    echo '<p><label for="jrblog_social_slug"><strong>' . esc_html__('Slug', 'jr-creator-media') . '</strong></label><input class="widefat" type="text" id="jrblog_social_slug" name="jrblog_social_slug" value="' . esc_attr($slug) . '"></p>';
    echo '<p><label for="jrblog_social_url"><strong>' . esc_html__('Social Media URL', 'jr-creator-media') . '</strong></label><input class="widefat" type="text" id="jrblog_social_url" name="jrblog_social_url" value="' . esc_attr($url) . '"></p>';
    echo '<p><label for="jrblog_social_name"><strong>' . esc_html__('Social Media Username', 'jr-creator-media') . '</strong></label><input class="widefat" type="text" id="jrblog_social_name" name="jrblog_social_name" value="' . esc_attr($name) . '"></p>';
}

function jr_creator_media_render_social_options_metabox($post)
{
    jr_creator_media_render_nonce();

    $share = !empty(get_post_meta($post->ID, 'jrblog_social_share', true));
    $sharing = !empty(get_post_meta($post->ID, 'jrblog_social_sharing', true));
    $follow = !empty(get_post_meta($post->ID, 'jrblog_social_follow', true));

    echo '<p><label><input type="checkbox" name="jrblog_social_share" value="1"' . checked($share, true, false) . '> ' . esc_html__('Supports sharing for this platform', 'jr-creator-media') . '</label></p>';
    echo '<p><label><input type="checkbox" name="jrblog_social_sharing" value="1"' . checked($sharing, true, false) . '> ' . esc_html__('Display share button', 'jr-creator-media') . '</label></p>';
    echo '<p><label><input type="checkbox" name="jrblog_social_follow" value="1"' . checked($follow, true, false) . '> ' . esc_html__('Display follow button', 'jr-creator-media') . '</label></p>';
}
