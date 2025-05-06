<?php
/**
 * Adds a metabox for latitude and longitude
 */

add_action('add_meta_boxes', 'add_city_coordinates_metabox');
function add_city_coordinates_metabox() {
    add_meta_box('city_coordinates', 'Coordinates', 'render_city_coordinates_metabox', 'city');
}

function render_city_coordinates_metabox($post) {
    $lat = get_post_meta($post->ID, '_latitude', true);
    $lng = get_post_meta($post->ID, '_longitude', true);
    wp_nonce_field('save_city_coordinates', 'city_coordinates_nonce');
    ?>
    <p><label>Latitude: <input type="text" name="latitude" value="<?php echo esc_attr($lat); ?>"></label></p>
    <p><label>Longitude: <input type="text" name="longitude" value="<?php echo esc_attr($lng); ?>"></label></p>
    <?php
}

add_action('save_post', 'save_city_coordinates');
function save_city_coordinates($post_id) {
    if (!isset($_POST['city_coordinates_nonce']) || !wp_verify_nonce($_POST['city_coordinates_nonce'], 'save_city_coordinates')) {
        return;
    }
    update_post_meta($post_id, '_latitude', sanitize_text_field($_POST['latitude']));
    update_post_meta($post_id, '_longitude', sanitize_text_field($_POST['longitude']));
}
