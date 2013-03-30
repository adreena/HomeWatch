/** Graph manager. */
require(['jquery',
        'underscore',
        'search/defines',
        'search/GraphManager',
        'spiffy/spiffy.min'],

function ($, _, D, GraphManager) {
    "use strict";

    /**
     * On document load for the mangineer search page.
     */

    $(function () {

        // TODO: Need to get sensor, apartment, and formula info from
        // somewhere.

        var data = GraphManager.makeCategories(D.exampleCategories),
            graphMan;

        data.apartments = _.range(1, 6 + 1); // Just like Python's range()...
        graphMan = new GraphManager(D.sel.graphList, data);
        /* Have one initial graph control. */
        graphMan.add();

        /* Have spiffy menus for graph controls.? */
        $(D.sel.graphControls).spiffy();

        /* Herp derp derp. */
        $(D.sel.addGraphButton).click(function (event) {
            event.preventDefault();
            graphMan.add();
        });

    });

});
