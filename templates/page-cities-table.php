<?php
/**
 * Template Name: Cities Table
 * Description: A page with a table of countries, cities and temperatures.
 */

get_header();

do_action('before_cities_table'); // Custom action hook in front of the table
?>

    <div class="cities-table-wrapper" style="padding: 2rem;">
        <h1>List of cities and temperatures</h1>

        <input type="text" id="city-search" placeholder="Search by city..." style="margin-bottom: 1rem; padding: 0.5rem; width: 100%; max-width: 400px;">

        <div id="cities-table">
            <?php get_template_part('templates/partials/cities-table'); ?>
        </div>
    </div>

<?php
do_action('after_cities_table'); // Custom action hook after the table
get_footer();
