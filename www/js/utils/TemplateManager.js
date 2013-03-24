/*
 * Template managment and rendering class.
 */
define(['jquery', 'underscore'], function ($, _) {
    var TemplateManager,
        defaultFetchTemplateText;

    TemplateManager = function (templateTextFetcher) {
        /* Start with an empty template cache. */
        this.templateCache = {},

        /* templateTextFetcher is an optional parameter.
         * Set its default value here. */
        this.fetchTemplate = (typeof templateTextFetcher === "undefined")
            ? defaultFetchTemplateText
            : templateTextFetcher;
    };

    /**
     * Gets template text from the page. Should be
     * a callback, but currently hard-coded.
     */
    defaultFetchTemplateText = function (templateName) {
        return $('#_t-' + templateName).html();
    };

    /** Underscore template manager. */
    TemplateManager.prototype.render = function (templateName, parameters, options) {
        var templateText, template;

        /* If the template has not been compiled yet, compile it
         * and add it to the cache. */
        if (!this.templateCache.hasOwnProperty(templateName)) {
            templateText = this.fetchTemplate(templateName);

            template =  _.template(templateText);
            this.templateCache[templateName] = template;

        } else {
            template = this.templateCache[templateName];
        }

        return template(parameters, options);
    };


    /* This module exports one public member -- the class itself. */
    return TemplateManager;

});
