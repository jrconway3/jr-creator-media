<?php
/**
 * Plugin Name: jr-creator-media
 * Plugin URI: https://www.jaidynreiman.net
 * Description: Creator and media compatibility layer for social links, video content, and legacy social metadata.
 * Version: 0.1.0
 * Author: JaidynReiman
 * Author URI: https://www.jaidynreiman.net
 * Text Domain: jr-creator-media
 * Requires Plugins: jr-content-core
 */

if (!defined('ABSPATH')) {
    exit;
}

define('JR_CREATOR_MEDIA_VERSION', '0.1.0');
define('JR_CREATOR_MEDIA_FILE', __FILE__);
define('JR_CREATOR_MEDIA_PATH', plugin_dir_path(__FILE__));
define('JR_CREATOR_MEDIA_URL', plugin_dir_url(__FILE__));

require_once JR_CREATOR_MEDIA_PATH . 'includes/dependencies.php';

if (!jr_creator_media_is_core_plugin_active()) {
    add_action('admin_notices', 'jr_creator_media_render_dependency_notice');
    return;
}

require_once JR_CREATOR_MEDIA_PATH . 'includes/helpers.php';
require_once JR_CREATOR_MEDIA_PATH . 'includes/post-types.php';
require_once JR_CREATOR_MEDIA_PATH . 'includes/meta.php';
require_once JR_CREATOR_MEDIA_PATH . 'includes/migrations.php';

function jr_creator_media_load()
{
    if (!jr_creator_media_has_dependencies()) {
        add_action('admin_notices', 'jr_creator_media_render_dependency_notice');
        return;
    }

    require_once JR_CREATOR_MEDIA_PATH . 'includes/assets.php';
    require_once JR_CREATOR_MEDIA_PATH . 'includes/metaboxes.php';
    require_once JR_CREATOR_MEDIA_PATH . 'includes/save-post.php';
    require_once JR_CREATOR_MEDIA_PATH . 'includes/rest.php';
    require_once JR_CREATOR_MEDIA_PATH . 'includes/bootstrap.php';

    jr_creator_media_bootstrap();
}

register_activation_hook(JR_CREATOR_MEDIA_FILE, 'jr_creator_media_activate');

add_action('plugins_loaded', 'jr_creator_media_load', 25);
