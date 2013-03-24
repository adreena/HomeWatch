/** Graph manager. */
define(['jquery',
        'underscore',
        'search/defines'],

    function ($, _, D) {
        
        var render,
            templateCache = {},
            compileTemplate,

            parseGraphControls,
            parseGraphType;

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
         * 
         */
        parseGraphType = function (graph) {
            // TODO: parse the graph type!
        };

        /** Underscore template manager. */
        render = function (templateName, parameters, options) {
            var templateText, template;
            
            /* If the template has not be compiled yet, compile it. */
            if (typeof(templateCache[templateName] === "undefined") {
                templateText = fetchTemplateText(templateName);
                templateCache[templateName] = compileTemplate(templateText);
            }

            template = templateCache[templateName];

            return template(parameters, options);
        };


        return {
            render: render
        };

});
