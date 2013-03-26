/**
 * GraphManager. Manages a whole bunch of graphs.
 */
define([
    'jquery',
    './GraphControl'],

function ($, GraphControl) {

    /**
     * Instantiate a new GraphManager, using the
     * given element to place graphs in.
     */
    function GraphManager(element) {
        /** Graphs are appended in the given element. */
        this.masterGraphList = element;

        /** List of managed graphs. */
        this.graphs = {};
    }

    /** Adds a new graph and returns its ID. */
    GraphManager.prototype.add = function () {
        var newGraph = new GraphControl(),
            graphID = newGraph.id;

        /* Start tracking the given graph. */
        this.graphs[graphID] = newGraph;

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
