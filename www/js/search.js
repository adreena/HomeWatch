/**
 * Search page.
 */

/*jslint browser: true, nomen: true, white: true, indent: 4, maxlen: 120 */
/*global require, apartmentData, categoryData */

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

        var cats = D.exampleCategories, graphMan, data;

        /* Get the sensor/formula data embedded in the page. */
        if (categoryData !== undefined) {
            $.extend(true, cats, categoryData);
        } else {
            console.log("Could not find categoryData");
        }

        data = GraphManager.makeCategories(cats);

        /* Get the apartment data embedded in the page. */
        if (apartmentData !== undefined) {
            data.apartments = apartmentData;
        } else {
            // TODO: GET RID OF THIS DEBUG NON-SENSE
            console.log("Could not find apartments");
            data.apartments = _.range(1, 3); // Just like Python's range()...
        }

        graphMan = new GraphManager(D.sel.graphList, data);
        /* Have one initial graph control. */
        graphMan.add();

        /* Bind the "add new graph" button. */
        $(D.sel.addGraphButton).click(function (event) {
            event.preventDefault();
            graphMan.add();
        });

        /* Get rid of the "now loading..." placeholder. */
        $(D.sel.pageLoadingPlaceholder).remove();

    });

});
