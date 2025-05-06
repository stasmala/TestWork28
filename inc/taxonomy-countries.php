<?php
/**
 * Registers the taxonomy "Countries" for the record type "Cities"
 */

add_action('init', 'register_tax_countries');

function register_tax_countries() {
    register_taxonomy('country', 'city', [
        'labels' => [
            'name' => 'Countries',
            'singular_name' => 'Country'
        ],
        'hierarchical' => true,
        'public' => true,
        'show_in_rest' => true
    ]);
}
