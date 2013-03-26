/*
 * Graph class skeleton.
 */
define([
    'jquery',
    'underscore'
    // TODO: add all of the Flot includes here!
    ],
function ($, _) {

    /**
     * Constructor for a graph.
     */
    function Graph(element, _clickCallback, initialData) {
        // initialData will have .graphType appended to it.
        this.clickCallback = _clickCallback;
        this.element = element;

        this.graphType = initialData.graphType;

        this.update(initialData);
    }



    /** Update method. Provide new data to update the graph. */
    Graph.prototype.update = function (newData) {
        // Actually graphs the data!
        
    };

    Graph.prototype.method = function (vars) {

    };


    /* This module exports one public member -- the class itself. */
    return Graph;

});
