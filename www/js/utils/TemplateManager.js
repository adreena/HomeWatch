/*
 * Template managment and rendering class.
 */
define("utils/TemplateManager", ['jquery', 'underscore'], function ($, _) {
    "use strict";

    var defaultFetchTemplateText;

    /**
     * Construct a template manager with an optional function
     * that will fetch templates.
     */
    function TemplateManager(templateTextFetcher) {
        /* Start with an empty template cache. */
        this.templateCache = {};

        /* templateTextFetcher is an optional parameter.
         * Set its default value here. */
        this.fetchTemplate = (templateTextFetcher === undefined)
            ? defaultFetchTemplateText
            : templateTextFetcher;
    }

    /**
     * Gets template text from the page. Should be
     * a callback, but currently hard-coded.
     */
    defaultFetchTemplateText = function (templateName) {
        return $('#_t-' + templateName).html();
    };

    /**
     * Renders the template given by templateName.
     * Optional template parameters can be used, as well
     * as any underscore template settings.
     * */
    TemplateManager.prototype.render = function (templateName, parameters, settings) {
        var templateText, template;

        /* If the template has not been compiled yet, compile it
         * and add it to the cache. */
        if (!this.templateCache.hasOwnProperty(templateName)) {
            templateText = this.fetchTemplate(templateName);

            template = _.template(templateText);
            this.templateCache[templateName] = template;

        } else {
            template = this.templateCache[templateName];
        }

        return template(parameters, settings);
    };


    /* This module exports one public member -- the class itself. */
    return TemplateManager;

});
