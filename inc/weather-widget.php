<?php
/**
 * Widget for displaying city and temperature by OpenWeatherMap API
 */

class City_Weather_Widget extends WP_Widget {
    public function __construct() {
        parent::__construct('city_weather_widget', 'City Weather', [
            'description' => 'Показывает текущую температуру в выбранном городе'
        ]);
    }

    public function form($instance) {
        $city_id = !empty($instance['city_id']) ? $instance['city_id'] : '';
        $cities = get_posts(['post_type' => 'city', 'numberposts' => -1]);
        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('city_id')); ?>">Выберите город:</label>
            <select name="<?php echo esc_attr($this->get_field_name('city_id')); ?>" id="<?php echo esc_attr($this->get_field_id('city_id')); ?>">
                <?php foreach ($cities as $city): ?>
                    <option value="<?php echo $city->ID; ?>" <?php selected($city_id, $city->ID); ?>>
                        <?php echo esc_html($city->post_title); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </p>
        <?php
    }

    public function update($new_instance, $old_instance) {
        return ['city_id' => intval($new_instance['city_id'])];
    }

    public function widget($args, $instance) {
        $city_id = $instance['city_id'] ?? 0;
        if (!$city_id) return;

        $lat = get_post_meta($city_id, '_latitude', true);
        $lng = get_post_meta($city_id, '_longitude', true);
        $title = get_the_title($city_id);

        $api_key = defined('OPENWEATHER_API_KEY') ? OPENWEATHER_API_KEY : '';
        $response = wp_remote_get("https://api.openweathermap.org/data/2.5/weather?lat={$lat}&lon={$lng}&units=metric&appid={$api_key}");

        if (!is_wp_error($response)) {
            $data = json_decode(wp_remote_retrieve_body($response));
            $temp = round($data->main->temp);
            echo $args['before_widget'] . "<p><strong>{$title}</strong>: {$temp}°C</p>" . $args['after_widget'];
        }
    }
}

add_action('widgets_init', function() {
    register_widget('City_Weather_Widget');
});
