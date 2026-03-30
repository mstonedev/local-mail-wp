<?php
/**
 * Plugin Name: Local Mail — Mailpit
 * Description: Routes all WordPress mail through Mailpit for local dev.
 */
add_action( 'phpmailer_init', function( $phpmailer ) {
    $phpmailer->isSMTP();
    $phpmailer->Host       = '127.0.0.1';
    $phpmailer->Port       = 1025;
    $phpmailer->SMTPAuth   = false;
    $phpmailer->SMTPSecure = '';
});