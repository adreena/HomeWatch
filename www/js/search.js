/** Graph manager. */
require(['jquery',
        'underscore',
        'search/defines',
        'search/GraphManager',
        'spiffy/spiffy.min',
        'vendor/json2'],

function ($, _, D, GraphManager) {
    "use strict";

    var makeCategories;

    /**
     * Takes that category array and converts it into things
     * that can be converted into optgroups for x and y.
     *
     * Also, creates an object that maps arbitrary value IDs to a
     * metadata object that contains the information needed to
     * send to process.php.
     *
     * Returns {
     *      x: { 'group1' : { "Display Name": "1" },
     *      y: { 'group1' : { "Display Name": "1" },
     *      values: { "1": {type: 'sensorarray', values: ["One value"]} }
     * }
     *
     */
    makeCategories = function (categories) {
        var x = {}, y = {}, values = {};


        /* Parse the category names. */
        _.each(categories, function (elements, catName) {
            var valueType = D.categoryNameToType[catName];

            /* Initialize the category. */
            x[catName] = {};
            y[catName] = {};

            _.each(elements, function (info, name) {
                var displayName, value, valueID, forX, forY;

                /* Assume the value is applicable for both axes. */
                forX = true;
                forY = true;

                if (_.isString(info)) {
                    /* Use the defaults with this as the display name. */
                    displayName = info;
                    value = [name];

                } else {
                    /* It's a big scary object. */

                    displayName = info.displayName;

                    /* Check if it has multiple values, else just use the
                     * name as the value. */
                    value = (info.hasOwnProperty('multiple'))
                        ? info.multiple
                        : [name];

                    if (info.hasOwnProperty('applicableAxes')) {
                        forX = /x/.test(info.applicableAxes);
                        forY =  /y/.test(info.applicableAxes);
                    }
                }

                /* Create a value ID for the value and insert it into
                 * the value array thing. */
                valueID = _.uniqueId();
                values[valueID] = {
                    type: valueType,
                    values: value
                };

                /* Make sure we add to the applicable axes! */
                if (forX) {
                    x[catName][displayName] = valueID;
                }

                if (forY) {
                    y[catName][displayName] = valueID;
                }

            });

        });

        return { x: x, y: y, values: values };

    };



    /**
     * ON DOCUMENT LOAD
     */

    $(function () {

        /* THIS IS ALL DEBUG! */
        var cats = makeCategories(D.exampleCategories),
            graphMan = new GraphManager(D.sel.graphList, cats),
            grrid = graphMan.add(undefined),
            theOneGraph = $('#' + grrid),
            dump = theOneGraph.find('.debug-results');

        theOneGraph.find('[href=#DEBUG]').click(function () {
            var query;

            query = {
                /* Disable process.php's test thing... stuff. */
                notest: true,
                /* Serialize the example 'cause I ain't got notin' else. */
                graph: JSON.stringify(D.exampleProcessParameters)
            };

            $.ajax({
                url: D.uri.process,
                type: 'GET',
                data: query,

                success: function (graphInfo) {
                    var asText = JSON.stringify(graphInfo, null, true);
                    dump.text(asText);
                },

                error: function () {
                    dump.text('Something went wrong while contacting process.php');
                }

            });

            return false;

        });

        /* TEMPORARY: Trying out spiffy because why not? */
        $('.graph-controls').spiffy();
        $('.graph-controls > li > h3').addClass('spiffy-header');

    });

});
