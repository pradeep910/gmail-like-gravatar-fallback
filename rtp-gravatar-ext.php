<?php

/* /
  Plugin Name: Gmail Like Gravatar Fallback
  Plugin URI: http://impradeep.in/gmail-like-gravatar-fallback-wordpress-plugin/
  Description: WordPress plugin for Gmail Like Gravatar Fallback.
  Version: 1.1
  Author: Pradeep Sonawane
  Author URI: impradeep.in
  bbPress Compatible: no
  WordPress Compatible: yes 
  BuddyPress Compatible: no
  / */

function rtp_gmail_like_gravatar_activate() {
    $avatar_default = get_option('avatar_default'); // Get current avatar setting
    update_option('avatar_default_old', $avatar_default); // Resetting to blank on activation
    update_option('avatar_default', 'blank'); // Resetting to blank
}

register_activation_hook(__FILE__, 'rtp_gmail_like_gravatar_activate');

function rtp_gmail_like_gravatar_deactivate() {
    $avatar_default_old = get_option('avatar_default_old'); // Get old avatar setting
    update_option('avatar_default', $avatar_default_old); // Resetting to Old setting on deactivation
}

register_deactivation_hook(__FILE__, 'rtp_gmail_like_gravatar_deactivate');

add_filter('get_avatar', 'rtp_gmail_like_gravatar', 99, 5); // Filtering get_avatar() to add custom div.default-avatar 
function rtp_gmail_like_gravatar($avatar, $id_or_email, $size, $default, $alt) {
    $gravatar_md5 = "d=blank";
    $pos = strpos($avatar, $gravatar_md5);
    if ($pos) {
        $title = '';
        if (is_object($id_or_email) && isset($id_or_email->comment_author)) { // IF COMMENTS OBJECT
            $title = '<span>' . substr($id_or_email->comment_author, 0, 1) . '</span>';
        }
        $avatar = str_replace('d=blank', 'd=404', $avatar);
        $avatar = str_replace('src=', 'data-src=', $avatar);
        $avatar = $avatar . '<div class="default-gravatar">' . $title . '</div>';
    }
    return $avatar;
}

add_action('wp_head', 'rtp_extra_script'); // Including JS And CSS
function rtp_extra_script($param) {
    if (!is_admin()) {
        wp_enqueue_script('rtp-gravatar-ext', plugin_dir_url(__FILE__) . '/js/rtp-plugin-script.js', 'jquery');
        echo '<style type="text/css">
            img.avtar {display: none;}
            div.default-gravatar { display: none; margin: 0 auto; text-align: center; 
                                   background: #da2728; color: white; overflow: hidden;  }
            div.default-gravatar span {display: table-cell; vertical-align: middle; font-size: 1em; text-transform: uppercase;}
        </style>';
    }
}
