# TestWork28 — WeatherCity Theme (Child Theme for Storefront)

Тестовое задание: реализация пользовательского типа записи **“Cities”**, таксономии **“Countries”**, метаполей координат, AJAX-поиска, вывода погоды и таблицы на кастомной странице. Вся реализация выполнена без использования плагинов, в дочерней теме Storefront.

## 📁 Структура

Проект включает только **дочернюю тему**, которую нужно установить в `/wp-content/themes/`.


---

## 🚀 Как запустить

1. Установите WordPress.
2. Установите тему **Storefront**.
3. Поместите содержимое этой папки в `/wp-content/themes/weathercity/`.
4. Активируйте тему **WeatherCity** через админку WordPress.

---

## 🔑 API-ключ OpenWeather

Чтобы получать температуру, добавьте свой API-ключ OpenWeather в файл `wp-config.php`:

```php
define('OPENWEATHER_API_KEY', 'ваш_ключ_сюда');
