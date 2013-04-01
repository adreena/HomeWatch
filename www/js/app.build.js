({
    appDir: "../",
    baseUrl: "js/",
    // This will copy the ENTIRE webapp and minify it.
    dir: "../../build",

    paths: {
        // jQuery is included in require.
        "jquery": "empty:",
        "underscore": "vendor/underscore",
        'flot': 'flot/jquery.flot',
        'flot-orderbars': 'flot-orderbars/jquery.flot.orderBars',
        'flot-axislabels': 'flot-axislabels/jquery.flot.axislabels',
        'flot-time': 'flot/jquery.flot.time',
        'flot-navigate': 'flot/jquery.flot.navigate',
        'flot-pie': 'flot/jquery.flot.pie'
    },

    modules: [
        //Optimize the application files. jQuery is not 
        //included since it is already in require-jquery.js
        {
            name: "search"
        }
    ]
})
