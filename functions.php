<?php

add_action( 'wp_login_failed', 'custom_login_fail_redirect', 10 );
add_action( 'wp_authenticate', 'custom_login_empty_check', 10, 2 );

function custom_login_fail_redirect( $username ) {
    custom_login_redirect();
}

function custom_login_empty_check( $username, $pwd ) {
    if ( empty( $username ) || empty( $pwd ) ) {
        custom_login_redirect();
    }
}

function custom_login_redirect() {
    $referrer = $_SERVER['HTTP_REFERER'] ?? ''; // Fallback to empty string if not set
    if (!empty($referrer) && !strstr($referrer, 'wp-login') && !strstr($referrer, 'wp-admin')) {
        $referrer = esc_url(remove_query_arg('login', $referrer)); // Sanitize and remove existing query args
        wp_safe_redirect(add_query_arg('login', 'failed', $referrer));
        exit;
    }
}

function generate_login_fail_messaging(){
    if(isset($_GET['login']) && $_GET['login'] == 'failed'){
        return '<div class="message_login_fail">Oops! Looks like you have entered the wrong username or password. Please check your login details and try again.</div>';
    }
    return '';
}
add_shortcode('login_fail_messaging', 'generate_login_fail_messaging');
