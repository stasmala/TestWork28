jQuery(document).ready(function ($) {
    $('#city-search').on('input', function () {
        var search = $(this).val();
        $.post(citySearch.ajax_url, {
            action: 'search_cities',
            search: search,
            nonce: citySearch.nonce
        }, function (response) {
            if (response.success) {
                $('#cities-table tbody').html(response.data);
            } else {
                $('#cities-table tbody').html('<tr><td colspan="3">Request error</td></tr>');
            }
        });
    });
});
