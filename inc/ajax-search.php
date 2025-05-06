<?php
/**
 * AJAX city search
 */

add_action('wp_ajax_search_cities', 'handle_ajax_search_cities');
add_action('wp_ajax_nopriv_search_cities', 'handle_ajax_search_cities');

function handle_ajax_search_cities() {
    if (!isset($_POST['search']) || !check_ajax_referer('search_cities_nonce', 'nonce', false)) {
        wp_send_json_error('Invalid request');
    }

    global $wpdb;
    $search = sanitize_text_field($_POST['search']);

    $cities = $wpdb->get_results($wpdb->prepare("
        SELECT p.ID, p.post_title, pm1.meta_value as latitude, pm2.meta_value as longitude
        FROM {$wpdb->posts} p
        LEFT JOIN {$wpdb->postmeta} pm1 ON p.ID = pm1.post_id AND pm1.meta_key = '_latitude'
        LEFT JOIN {$wpdb->postmeta} pm2 ON p.ID = pm2.post_id AND pm2.meta_key = '_longitude'
        WHERE p.post_type = 'city'
          AND p.post_status = 'publish'
          AND p.post_title LIKE %s
    ", '%' . $wpdb->esc_like($search) . '%'));

    ob_start();
    foreach ($cities as $city) {
        $title = esc_html($city->post_title);
        $country_terms = get_the_terms($city->ID, 'country');
        $country_name = $country_terms && !is_wp_error($country_terms) ? esc_html($country_terms[0]->name) : '—';

        $lat = esc_attr($city->latitude);
        $lng = esc_attr($city->longitude);
        $temp = '—';

        if ($lat && $lng) {
            $api_key = defined('OPENWEATHER_API_KEY') ? OPENWEATHER_API_KEY : '';
            $response = wp_remote_get("https://api.openweathermap.org/data/2.5/weather?lat={$lat}&lon={$lng}&units=metric&appid={$api_key}");
            if (!is_wp_error($response)) {
                $data = json_decode(wp_remote_retrieve_body($response));
                if (!empty($data->main->temp)) {
                    $temp = round($data->main->temp);
                }
            }
        }

        echo "<tr><td>{$title}</td><td>{$country_name}</td><td>{$temp}</td></tr>";
    }

    $html = ob_get_clean();
    wp_send_json_success($html);
}
