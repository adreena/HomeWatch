/** Graph manager. */
require(['jquery',
        'underscore',
        'search/defines',
        'search/GraphManager',
        'spiffy/spiffy.min',
        'vendor/json2'],

function ($, _, D, GraphManager) {
    "use strict";

    /**
     * On document load:
     */

    $(function () {

        /* THIS IS ALL DEBUG! */

        var data = GraphManager.makeCategories(D.exampleCategories),
            graphMan,
            grrid,
            theOneGraph,
            dump;

        data.apartments = _.range(1, 6 + 1); // Just like Python's range()...
        graphMan = new GraphManager(D.sel.graphList, data),
        grrid = graphMan.add(),
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
