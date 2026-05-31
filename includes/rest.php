<?php

if (!defined('ABSPATH')) {
    exit;
}

function jr_creator_media_register_rest_routes()
{
    register_rest_route('jr/v1', '/playlist/by-yt-id/(?P<yt_playlist_id>[a-zA-Z0-9_-]+)', array(
        'methods'             => WP_REST_Server::READABLE,
        'callback'            => 'jr_creator_media_rest_playlist_by_yt_id',
        'permission_callback' => 'jr_creator_media_rest_auth',
        'args'                => array(
            'yt_playlist_id' => array(
                'required'          => true,
                'sanitize_callback' => 'sanitize_text_field',
            ),
        ),
    ));

    register_rest_route('jr/v1', '/video/by-yt-id/(?P<yt_video_id>[a-zA-Z0-9_-]+)', array(
        'methods'             => WP_REST_Server::READABLE,
        'callback'            => 'jr_creator_media_rest_video_by_yt_id',
        'permission_callback' => 'jr_creator_media_rest_auth',
        'args'                => array(
            'yt_video_id' => array(
                'required'          => true,
                'sanitize_callback' => 'sanitize_text_field',
            ),
        ),
    ));

    register_rest_route('jr/v1', '/live-stream', array(
        'methods'             => WP_REST_Server::READABLE,
        'callback'            => 'jr_creator_media_rest_get_live_stream',
        'permission_callback' => '__return_true',
    ));

    register_rest_route('jr/v1', '/live-stream/activate', array(
        'methods'             => WP_REST_Server::CREATABLE,
        'callback'            => 'jr_creator_media_rest_activate_live_stream',
        'permission_callback' => 'jr_creator_media_rest_auth_manage',
        'args'                => array(
            'yt_video_id' => array(
                'required'          => true,
                'sanitize_callback' => 'sanitize_text_field',
                'validate_callback' => function ($value) {
                    return (bool) preg_match('/^[A-Za-z0-9_-]+$/', $value);
                },
            ),
            'title'       => array(
                'required'          => true,
                'sanitize_callback' => 'sanitize_text_field',
            ),
            'url'         => array(
                'required'          => true,
                'sanitize_callback' => 'esc_url_raw',
            ),
        ),
    ));

    register_rest_route('jr/v1', '/live-stream/deactivate', array(
        'methods'             => WP_REST_Server::CREATABLE,
        'callback'            => 'jr_creator_media_rest_deactivate_live_stream',
        'permission_callback' => 'jr_creator_media_rest_auth_manage',
    ));

    register_rest_route('jr/v1', '/channel/sync', array(
        'methods'             => WP_REST_Server::CREATABLE,
        'callback'            => 'jr_creator_media_rest_sync_channel',
        'permission_callback' => 'jr_creator_media_rest_auth_manage',
        'args'                => array(
            'handle'           => array(
                'required'          => true,
                'sanitize_callback' => 'sanitize_text_field',
            ),
            'url'              => array(
                'required'          => true,
                'sanitize_callback' => 'esc_url_raw',
            ),
            'subscriber_count' => array(
                'required'          => true,
                'sanitize_callback' => 'absint',
            ),
            'video_count'      => array(
                'required'          => true,
                'sanitize_callback' => 'absint',
            ),
        ),
    ));

    register_rest_route('jr/v1', '/channel', array(
        'methods'             => WP_REST_Server::READABLE,
        'callback'            => 'jr_creator_media_rest_get_channel',
        'permission_callback' => '__return_true',
    ));
}

function jr_creator_media_rest_auth()
{
    return current_user_can('edit_posts');
}

function jr_creator_media_rest_auth_manage()
{
    return current_user_can('manage_options');
}

function jr_creator_media_rest_video_by_yt_id(WP_REST_Request $request)
{
    $yt_video_id = $request->get_url_params()['yt_video_id'];

    $query = new WP_Query(array(
        'post_type'      => jr_creator_media_video_post_type(),
        'post_status'    => 'any',
        'posts_per_page' => 1,
        'no_found_rows'  => true,
        'meta_query'     => array(
            array(
                'key'   => 'yt_video_id',
                'value' => $yt_video_id,
            ),
        ),
    ));

    if (empty($query->posts)) {
        return new WP_Error('not_found', __('No video found with that YouTube ID.', 'jr-creator-media'), array('status' => 404));
    }

    $post_id = (int) $query->posts[0]->ID;

    if (!current_user_can('edit_post', $post_id)) {
        return new WP_Error('not_found', __('No video found with that YouTube ID.', 'jr-creator-media'), array('status' => 404));
    }

    return rest_ensure_response(array('post_id' => $post_id));
}

function jr_creator_media_rest_get_live_stream()
{
    $live = get_option('jr_live_stream');

    if (!is_array($live) || empty($live['active'])) {
        return rest_ensure_response(array('active' => false));
    }

    return rest_ensure_response($live);
}

function jr_creator_media_rest_activate_live_stream(WP_REST_Request $request)
{
    $yt_video_id = $request->get_param('yt_video_id');
    $title       = $request->get_param('title');
    $url         = $request->get_param('url');
    $embed_url   = 'https://www.youtube.com/embed/' . $yt_video_id;

    $data = array(
        'active'      => true,
        'yt_video_id' => $yt_video_id,
        'title'       => $title,
        'url'         => $url,
        'embed_url'   => $embed_url,
    );

    update_option('jr_live_stream', $data, false);

    $query = new WP_Query(array(
        'post_type'      => jr_creator_media_video_post_type(),
        'post_status'    => 'any',
        'posts_per_page' => 1,
        'no_found_rows'  => true,
        'meta_query'     => array(
            array(
                'key'   => 'yt_video_id',
                'value' => $yt_video_id,
            ),
        ),
    ));

    if (!empty($query->posts)) {
        update_post_meta($query->posts[0]->ID, 'yt_broadcast_status', 'live');
    }

    return rest_ensure_response(array('success' => true));
}

function jr_creator_media_rest_deactivate_live_stream()
{
    $live = get_option('jr_live_stream');

    delete_option('jr_live_stream');

    if (is_array($live) && !empty($live['yt_video_id'])) {
        $query = new WP_Query(array(
            'post_type'      => jr_creator_media_video_post_type(),
            'post_status'    => 'any',
            'posts_per_page' => 1,
            'no_found_rows'  => true,
            'meta_query'     => array(
                array(
                    'key'   => 'yt_video_id',
                    'value' => $live['yt_video_id'],
                ),
            ),
        ));

        if (!empty($query->posts)) {
            update_post_meta($query->posts[0]->ID, 'yt_broadcast_status', 'none');
        }
    }

    return rest_ensure_response(array('success' => true));
}

function jr_creator_media_rest_playlist_by_yt_id(WP_REST_Request $request)
{
    $yt_playlist_id = $request->get_url_params()['yt_playlist_id'];

    $query = new WP_Query(array(
        'post_type'      => jr_creator_media_playlist_post_type(),
        'post_status'    => 'any',
        'posts_per_page' => 1,
        'no_found_rows'  => true,
        'meta_query'     => array(
            array(
                'key'   => 'yt_playlist_id',
                'value' => $yt_playlist_id,
            ),
        ),
    ));

    if (empty($query->posts)) {
        return new WP_Error('not_found', __('No playlist found with that YouTube ID.', 'jr-creator-media'), array('status' => 404));
    }

    $post_id = (int) $query->posts[0]->ID;

    if (!current_user_can('edit_post', $post_id)) {
        return new WP_Error('not_found', __('No playlist found with that YouTube ID.', 'jr-creator-media'), array('status' => 404));
    }

    return rest_ensure_response(array('post_id' => $post_id));
}

function jr_creator_media_rest_sync_channel(WP_REST_Request $request)
{
    update_option('jr_yt_channel_handle', $request->get_param('handle'), false);
    update_option('jr_yt_channel_url', $request->get_param('url'), false);
    update_option('jr_yt_subscriber_count', $request->get_param('subscriber_count'), false);
    update_option('jr_yt_video_count', $request->get_param('video_count'), false);

    return rest_ensure_response(array('success' => true));
}

function jr_creator_media_rest_get_channel()
{
    return rest_ensure_response(array(
        'handle'           => get_option('jr_yt_channel_handle', ''),
        'url'              => get_option('jr_yt_channel_url', ''),
        'subscriber_count' => (int) get_option('jr_yt_subscriber_count', 0),
        'video_count'      => (int) get_option('jr_yt_video_count', 0),
    ));
}
