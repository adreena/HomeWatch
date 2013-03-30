/** Graph manager. */
require(['jquery',
        'underscore',
        'search/defines',
        'search/GraphManager',
        'spiffy/spiffy.min'],

function ($, _, D, GraphManager) {
    "use strict";

    /**
     * On document load:
     */

    $(function () {

        // TODO: Need to get sensor, apartment, and formula info from
        // somewhere.

        var data = GraphManager.makeCategories(D.exampleCategories),
            graphMan;

        data.apartments = _.range(1, 6 + 1); // Just like Python's range()...
        graphMan = new GraphManager(D.sel.graphList, data);
        graphMan.add();


        /* Trying out spiffy because why not? */
        $('.graph-controls').spiffy();
        $('.graph-controls > li > h3').addClass('spiffy-header');

    });

});
