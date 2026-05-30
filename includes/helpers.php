<?php

if (!defined('ABSPATH')) {
    exit;
}

function jr_creator_media_social_post_type()
{
    return 'social';
}

function jr_creator_media_video_post_type()
{
    return 'video';
}

function jr_creator_media_social_defaults()
{
    return array(
        'skype' => array(
            'name' => 'Skype',
            'url' => '',
            'share' => false,
        ),
        'facebook' => array(
            'name' => 'Facebook',
            'url' => 'http://www.facebook.com/',
            'share' => true,
        ),
        'twitter' => array(
            'name' => 'Twitter',
            'url' => 'http://www.twitter.com/',
            'share' => true,
        ),
        'googleplus' => array(
            'name' => 'Google+',
            'url' => 'http://plus.google.com/',
            'share' => true,
        ),
        'linkedin' => array(
            'name' => 'LinkedIn',
            'url' => 'http://www.linkedin.com/pub/',
            'share' => true,
        ),
        'tumblr' => array(
            'name' => 'Tumblr',
            'url' => 'http://www.tumblr.com',
            'share' => false,
            'sub' => true,
        ),
    );
}

function jr_creator_media_sanitize_count($value)
{
    $int = filter_var($value, FILTER_VALIDATE_INT, array('options' => array('min_range' => 0)));
    return $int !== false ? (string) $int : '0';
}

function jr_creator_media_sanitize_string($value)
{
    if (is_array($value) || is_object($value)) {
        return '';
    }

    return sanitize_text_field((string) $value);
}

function jr_creator_media_sanitize_bool($value)
{
    return empty($value) ? false : true;
}

function jr_creator_media_supported_admin_post_types()
{
    return array(jr_creator_media_social_post_type(), jr_creator_media_video_post_type());
}
