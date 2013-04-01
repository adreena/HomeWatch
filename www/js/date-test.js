/*
 * TEMPORARY: testing for search.js
 */
require(['jquery', 'underscore', 'vendor/jquery.jdpicker'],

function ($, _) {
    var start = $('input[name=start]'),
        end = $('input[name=end]');

    start.jdPicker({
        date_format: "YYYY-mm-dd",
        select_week: true,
        start_of_week: 0
    });

    end.jdPicker({
        date_format: "YYYY-mm-dd",
    });
});
