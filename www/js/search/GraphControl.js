/**
 * GraphControl. Manages interface and controls of one graph.
 */
define([
    'jquery',
    'underscore',
    './Graph',
    'utils/TemplateManager'],

function ($, _, Graph, TemplateManager) {
    // TODO: Copy stuff from search.js into here!

    var tman = new TemplateManager();

    /** Not documented. */
    function GraphControl() {

        this.id = _.uniqueId('graph');

    }

    /* Export the class. */
    return GraphControl;
});
