# TestWork28 — WeatherCity Theme (Child Theme for Storefront)

Test case: implementation of custom **"Cities "** record type, taxonomy **"Countries "**, coordinate meta fields, AJAX search, weather and table output on a custom page. All implementation is done without using plugins, in Storefront child theme.

## 📁 Structure

The project only includes the **doc theme**, which needs to be installed in `/wp-content/themes/`.


---

## 🚀 How to run

1. Install WordPress.
2. Install the **Storefront** theme.
3. Place the contents of this folder in `/wp-content/themes/weathercity/`.
4. Activate the **WeatherCity** theme through the WordPress admin area.

---

## 🔑  OpenWeather API key

To get the temperature, add your OpenWeather API key to the `wp-config.php` file:
```php
define('OPENWEATHER_API_KEY', 'your_key_here');
