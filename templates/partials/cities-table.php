<?php
/**
 * Таблица городов с температурой.
 */

global $wpdb;

// Получаем все города
$cities = $wpdb->get_results("
    SELECT p.ID, p.post_title, pm1.meta_value as latitude, pm2.meta_value as longitude
    FROM {$wpdb->posts} p
    LEFT JOIN {$wpdb->postmeta} pm1 ON p.ID = pm1.post_id AND pm1.meta_key = '_latitude'
    LEFT JOIN {$wpdb->postmeta} pm2 ON p.ID = pm2.post_id AND pm2.meta_key = '_longitude'
    WHERE p.post_type = 'city' AND p.post_status = 'publish'
");

if ($cities): ?>
    <table class="table-cities" border="1" cellpadding="8" cellspacing="0">
        <thead>
        <tr>
            <th>City</th>
            <th>Country</th>
            <th>Temperature (°C)</th>
        </tr>
        </thead>
        <tbody>
        <?php
        foreach ($cities as $city):
            $title = esc_html($city->post_title);
            $country_terms = get_the_terms($city->ID, 'country');
            $country_name = $country_terms && !is_wp_error($country_terms) ? esc_html($country_terms[0]->name) : '—';

            // Температура через API
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
            ?>
            <tr>
                <td><?php echo $title; ?></td>
                <td><?php echo $country_name; ?></td>
                <td><?php echo $temp; ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>Cities not found.</p>
<?php endif; ?>
