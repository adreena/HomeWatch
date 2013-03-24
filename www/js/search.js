/** Graph manager. */
define(['jquery',
        'underscore'],
        //'search/defines'],

    function ($, _, D) {
        
        /* Each of these things should go in their own modules,
         * probably. */
        var render,
            templateCache = {},
            compileTemplate,
            fetchTemplateText,

            parseGraphControls,
            parseGraphType,

            addGraph,
            genericControlCategory;


        /*
         * This stuff should be in a "template manager"
         * class.
         */

        /**
         * Given a graph HTML thing (ID? Element?) will 
         * parse its HTML controller and return the
         * data need to pass to process.php.
         */
        parseGraphControls = function (graph) {
            // TODO: write this...

            // By the way, I don't need to return
            // the graph type.
        };

        /**
         * Gets the graph type from the graph controls.
         */
        parseGraphType = function (graph) {
            // TODO: parse the graph type!
        };

        /**
         * Temporary. To be replaced with real renderers.
         */
        genericControlCategory = function () {
            return render('graph-controls-li', {
                header: 'Generic header',
                content: '<div>hello</div>'
            });
        };

        /**
         * Adds a graph to the page. Where?
         * It's appended to 'place' (jQuery element).
         * Data is { axes: {}, apartments: {} }.
         *
         * Returns the graph ID.
         */
        addGraph = function (place, data) {
            var elements,
                rendered,
                graphID = 'graph' + _.uniqueId(),
                renderedElements;
            
            elements = [genericControlCategory()];

            renderedElements = elements.join('');

            rendered = render('graph-group', {
                graphID: graphID,
                graphControls: renderedElements
            });

            place.append(rendered);

            return graphID;

        };

        /**
         * Gets template text from the page. Should be
         * a callback, but currently hard-coded.
         */
        fetchTemplateText = function (templateName) {
            return $('#_t-' + templateName).html();
        };

        /** Underscore template manager. */
        render = function (templateName, parameters, options) {
            var templateText, template;
            
            /* If the template has not be compiled yet, compile it. */
            if (typeof(templateCache[templateName] === "undefined")) {
                templateText = fetchTemplateText(templateName);
                templateCache[templateName] = _.template(templateText);
            }

            template = templateCache[templateName];

            return template(parameters, options);
        };


        $(function () {
            addGraph($('ul#graphs'), undefined);
        });

        return {
            render: render
        };

});
