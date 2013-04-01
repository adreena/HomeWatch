/*
 * TEMPORARY: testing for the date picker widgets in the search page.
 */
require(['jquery', 'vendor/jquery.jdpicker'],

function ($) {
    var start = $('input[name=start]'),
        end = $('input[name=end]'),
        today = new Date();

    // Aw yeah, prototypes!
    Date.prototype.toShortISOString = function () {
        return this.getFullYear() + '-' +
            (this.getMonth() + 1) + '-' +
            this.getDate();
    };

    start.jdPicker({
        date_format: "YYYY-mm-dd",
        select_week: true,
        start_of_week: 0 // Means to start with Sunday.
    });

    end.jdPicker({
        date_format: "YYYY-mm-dd",
        date_max: today.toShortISOString()
    });
});
