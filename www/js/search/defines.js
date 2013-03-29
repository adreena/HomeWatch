/*
 * Defines for the search page.
 *
 * Includes jQuery selectors (sel), AJAX URIs (uri), and other debug stuff.
 */
define({

    uri: {
        /* URI to retrieve new graph data. */
        process: '/search/process.php'
    },

    sel: {
        graphList: 'ul#graphs'
     },
    
    /*
     * This is an example of how the sensors will look. It's in simple
     * JSON so that we can move this to a simple file if need be. If
     * the value is a string, that is its display name. Otherwise,
     * the value is an object that must contain the display name. It
     * may also contain any axis constraints (by default, 'xy'), in
     * "applicableAxes". Multiple values are specified as an array of
     * value strings.
     */
    exampleCategories: {
        "Time": {
            "time": {
                "displayName": "Time",
                "applicableAxes": "x"
            }
        },

        "Sensors": {
            "CO2": "Carbon Dioxide (PPM)",
            "all_electricity": {
                "multiple": [
                    "Mains (Phase A)",
                    "Bedroom and hot water tank (Phase A)",
                    "Oven (Phase A) and range hood",
                    "Microwave and ERV controller",
                    "Electrical duct heating",
                    "Kitchen plugs (Phase A) and bathroom lighting",
                    "Energy recovery ventilation",
                    "Mains (Phase B)",
                    "Kitchen plugs (Phase B) and kitchen counter",
                    "Oven (Phase B)",
                    "Bathroom",
                    "Living room and balcony",
                    "Hot water tank (Phase B)",
                    "Refrigerator"],
                "applicableAxes": "y",
                "displayName": "All electricity"
            }
        },

        "Formulae": {
            "waffles": "Waffles"
        }
    },

    /*
     * This data structure is what process.php takes in as its
     * "graph" parameter, serialized as JSON.
     */
    exampleProcessParameters: {
        "startdate": "2012-03-01", /* Includes this! */
        "enddate": "2012-03-02", /* Goes up to this! */
        /* This is the display name of the independent variable. */
        "xaxis": "CO2 (ppm)",
        /* This is either the formula name or the sensor name. This field
         * doesn't matter when xtype is time. */
        "x": "CO2",
        /* Type of the independent variable. Can be "time", "sensorarray", or
         * "formula". */
        "xtype": "sensorarray",
        /* This is the display name...? of the dependent variables. */
        "yaxis": "Water_Usage",
        /* This is the list of depenedent variables. These can be a list of
         * sensor names or a list of formulas (maybe?). */
        "y": ["Total_Water", "Hot_Water"],
        /* Can be "sensorarray" or "formula" */
        "ytype": "sensorarray", 
        /* The granularity. */
        "period": "Daily",
        /* The selected apartments. */
        "apartments": [1, 2]
    },

    /**
     * This is an example of what process.php currently spits out.
     */
    /*exampleProcessResponse: {
        "xaxis": "time",
        "yaxis": "Water_Usage",
        "values": {
            "1": {
                "2012-03-01:0": {
                    "Total_Water": {
                        "y": "124",
                        "x": "2012-03-01:0"
                    },
                    "Hot_Water": {
                        "y": "65",
                        "x": "2012-03-01:0"
                    }
                }
            },
            "2": {
                "2012-03-01:0": {
                    "Total_Water": {
                        "y": "16",
                        "x": "2012-03-01:0"
                    },
                    "Hot_Water": {
                        "y": "9",
                        "x": "2012-03-01:0"
                    }
                }
            }
        },
        "granularity": "Daily",
        "messages": ["No graph data received\n"]
    },*/

    exampleProcessResponse: {"x-axis":"Time","y-axis":"Total_Water","values":{"1":{"2012-02-29:0":{"Total_Water":{"y":"100","x":"2012-02-29:0"}},"2012-02-29:1":{"Total_Water":{"y":"105","x":"2012-02-29:1"}},"2012-02-29:2":{"Total_Water":{"y":"101","x":"2012-02-29:2"}},"2012-02-29:3":{"Total_Water":{"y":"99","x":"2012-02-29:3"}},"2012-02-29:4":{"Total_Water":{"y":"100","x":"2012-02-29:4"}},"2012-02-29:5":{"Total_Water":{"y":"95","x":"2012-02-29:5"}},"2012-02-29:6":{"Total_Water":{"y":"110","x":"2012-02-29:6"}},"2012-02-29:7":{"Total_Water":{"y":"115","x":"2012-02-29:7"}},"2012-02-29:8":{"Total_Water":{"y":"112","x":"2012-02-29:8"}},"2012-02-29:9":{"Total_Water":{"y":"120","x":"2012-02-29:9"}},"2012-02-29:10":{"Total_Water":{"y":"118","x":"2012-02-29:10"}}}}, "granularity":"Hourly", "messages": ["No graph data received\n"]},

    /*
     * This one is kind of hacky. Turns the category display name into the type
     * that should be sent to process.php.
     */
    categoryNameToType: {
        "Time": "time",
        "Sensors": "sensorarray",
        "Formulae": "formula"
    }


});
