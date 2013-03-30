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

        // TODO: Need to get sensor, apartment info from somewhere...

        var cats = D.exampleCategories, graphMan, data;

        /* Get data embedded in the page. */
        if (categoryData !== undefined) {
            $.extend(true, cats, categoryData);
        }

        data = GraphManager.makeCategories(cats);

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
