/** Test the Graph class stuff, yo. */
require(['jquery', 'underscore', 'search/Graph', 'search/defines'],

function ($, _, Graph, D) {
    var container = $('#graph'),
        data,
        graph;

    /* Get a clone of the example parameters and add a graph type. */
    data = _.clone(D.exampleProcessResponse);
    data.graphType = 'line';

    /* Make the graph. Click events are simply printed by conosle.log. */
    graph = new Graph(container, console.log, data);

});
