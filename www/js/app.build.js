({
    appDir: "../",
    baseUrl: "js/",
    // This will copy the ENTIRE webapp and minify it.
    dir: "../../build",

    paths: {
        // jQuery is included in require.
        "jquery": "empty:",
        "underscore": "vendor/underscore"
    },

    modules: [
        //Optimize the application files. jQuery is not 
        //included since it is already in require-jquery.js
        {
            name: "jSearch"
        },
        {
            name: "search"
        }
    ]
})
