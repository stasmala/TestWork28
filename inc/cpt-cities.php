<?php
/**
 * Registers the "Cities" custom record type
 */

add_action('init', 'register_cpt_cities');

function register_cpt_cities() {
    register_post_type('city', [
        'labels' => [
            'name' => 'Cities',
            'singular_name' => 'City',
        ],
        'public' => true,
        'has_archive' => true,
        'supports' => ['title'],
        'menu_icon' => 'dashicons-location-alt',
        'show_in_rest' => true
    ]);
}
