/*
 * jSearch/graphs
 *
 * Uses flot to do graphing.
 *
 */

define([
    'jquery',                   // Using the require+jquery combo
    'underscore',               // Underscore for data manipulation
    'flot/jquery.flot',         // Flot charts
    'flot/jquery.flot.time',    // Flot time plugin
    'flot-axislabels/jquery.flot.axislabels', // Extra flot plugins
    'flot-orderbars/jquery.flot.orderBars'],
    
    function ($, _) {

        var 
            /* Utility functions. */
            devinDateToUTC,
            preprocessData,
            mapKeys;

        /*
         * Err... nothing's in here yet...
         */

        /*
         * UTILITIES!
         */

        /* Converts a date in YYYY-MM-DD:hh format into milliseconds since the
         * UNIX epoch. Assumes everything is using the same timezone.  If the
         * input cannot be parsed, returns undefined.
         */
        devinDateToUTC = function (dateString) {
            var dateRegex = /(\d+)-(\d+)-(\d+):(\d+)/,
                m, // m for match
                UTCTime;

            m = dateString.match(dateRegex);

            // Return undefined if we could not match the regex.
            if (!m) {
                return;
            }

            UTCTime = Date.UTC(
                    m[1],     // Year
                    m[2] - 1, // Month (WHY IS THIS ZERO-INDEXED?!)
                    m[3],     // Day
                    m[4]);    // Hour

            return UTCTime;
        };

        /* Map, but on the keys of an object. Uses Underscore. */
        mapKeys = function (obj, keyFunc) {
            var func = function (value, key) {
                return [keyFunc(key), value];
            };

            return _
                .chain(obj)
                .map(func)
                .object()
                .value();
        };

        // TODO: Export this into its own underscore module?
        /* Uselessly creates an underscore extension to map something
         * to an object's keys. */
        _.mixin({ mapKeys: mapKeys });

        /* Preprocesses the data from process.php.
         *
         * For example:
         *  - converts Devin's date format (ISO 8601 with concatenated
         *    hour) to UTC time in milliseconds.
         *  - That's it...
         *
         * Returns the cleaned object.
         */
        preprocessData = function (data) {

            /* Assumes date is nested within apartment. */
            return _
                .chain(data)
                .map(function (apartment, number) {
                    var newApartment =
                        _.mapKeys(apartment, devinDateToUTC);

                    return [number, newApartment];
                })
                .object()
                .value();
        };
