/**
 * Definitions for search. Includes URIs and selectors.
 *
 * For use with RequireJS.
 */
define({

    /* URIs -- such as that for AJAX calls. */
    uri: {
        // Pedantic note: this is the model, not the controller...
        controller: '/HomeWatch/search/process.php',
        // Mock data for database-less debugging.
        mockdata: '/HomeWatch/search/mockdata.json' 
     },

    /* jQuery selectors. */
    sel: {
        searchForm: 'form.sensor-search',
        resultBox: '#results',

    }

});
