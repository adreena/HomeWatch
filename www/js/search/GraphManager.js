/**
 * GraphManager. Manages a whole bunch of graphs.
 */
define([
    'jquery',
    './GraphControl'],

function ($, GraphControl) {
    "use strict";

    /**
     * Instantiate a new GraphManager, using the given element to place graphs
     * in, and the data (x, y, values, apartments, etc.).
     */
    function GraphManager(element, data) {
        /** Graphs are appended in the given element. */
        this.masterGraphList = $(element);
        this.data = data;
        this.values = data.values;

        /** List of managed graphs. */
        this.graphs = {};
    }

    /** Adds a new graph and returns its ID. */
    GraphManager.prototype.add = function () {
        var newGraph = new GraphControl(this, this.data),
            graphID = newGraph.id;

        /* Start tracking the given graph. */
        this.graphs[graphID] = newGraph;

        /* Append the element to the div. */
        this.masterGraphList.append(newGraph.element);

        return graphID;
    };

    /**
     * Stops tracking the given Graph ID. Intended to be used by a
     * GraphController to signify that it has been destroyed.
     */
    GraphManager.prototype.untrack = function (id) {
        delete this.graphs[id];
    };

    /** Request to remove the given graph from the manager. */
    GraphManager.prototype.remove = function (id) {
        this.graphs[id].destroy();
    };


    /* Export the class. */
    return GraphManager;
});
