/*
 * Defines for the search page.
 *
 * Includes jQuery selectors (sel), AJAX URIs (uri), and other debug stuff.
 */

/*jslint indent: 4, maxlen: 120 */
/*global define */

define({

    /** Internal URIs to use for AJAX and alike. */
    uri: {
        /* URI to retrieve new graph data. */
        process: '/HomeWatch/search/process.php'
    },

    messages: {
        newGraph: 'Pick some axes, timeframe and apartments to graph',
        errorFetchingInfo: 'Error fetching info from the server :/',
        graphLoading: 'Loading data...',
        unusableResponse: 'Received unusable data from the server.'
    },

    /** jQuery selectors. It is prefered to put your selectors here, so that
     * in the future, when wee ne*/
    sel: {
        graphList: 'ul#graphs',
        addGraphButton: 'a[href=#add-graph]',
        pageLoadingPlaceholder: '.loading-placeholder',

        flotGraph: '.graph',

        graphControls: '.graph-controls',
        graphContainer: '.graph-container',

        graphMessages: '.graph-messages',
        graphLegend: '.graph-legend',

        graphVisibilityControls: '.graph-vis-controls',
        graphDestroyButton: 'a[href=#destroy-graph]',
        graphMinifyButton: 'a[href=#minify-graph]',
        graphHideButton: 'a[href=#hide-graph]'

    },

    /**
     * This is the minimum period between requests to process.
     * An HTTP request is not made within this period.
     */
    MinRequestDelay: 1500,

    /**
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

        "None":{"None":""},

        "Air Quality": {
        	"CO2": "",
        	"Relative_Humidity": "Relative Humidity",
        	"Temperature": "Indoor Temperature",
        	"Outside_Temperature": "Outside Temperature"
        },

        "Apartment Heating": {
        	"Total_Energy": "Heating Energy Consumed",
        	"Total_Volume": "Heating Flow",
        	"Current_Temperature_1": "Supply Temperature",
        	"Current_Temperature_2": "Return Temperature"
        },

        "Heat Flux": {
        	"HeatFlux_Stud": "Heat Flux Stud",
        	"HeatFlux_Insulation": "Heat Flux Insulation"
        },

        "Water": {
        	"Hot_Water": "Hot Water",
        	"Total_Water": "Total Water"
        },

        "Apartment Electricity": {
            "Mains (Phase A)": "",
            "Bedroom and hot water tank (Phase A)": "",
            "Oven (Phase A) and range hood": "",
            "Microwave": "",
            "Electrical duct heating": "",
            "Kitchen plugs (Phase A) and bathroom lighting": "",
            "Energy recovery ventilation": "",
            "Mains (Phase B)": "",
            "Kitchen plugs (Phase B) and kitchen counter": "",
            "Oven (Phase B)": "",
            "Bathroom": "",
            "Living room and balcony": "",
            "Hot water tank (Phase B)": "",
            "Refrigerator": ""

        },

        "Formulae": {
        },

		  "Utilities Costs": {
            "electricity": "HP_Electricity",
            "water": "Water"
        },

        "Alerts": {
        },

        "BAS Electricity": {
        	"Total_P1": "Total Geo Circ. Pump",
        	"Total_HP": "Total Heat Pumps",
        	"P11": "Geo Circ Pump 1 (P-1-1)",
        	"P12": "Geo Circ Pump 2 (P-1-2)",
        	"HP1": "Heat Pump 1",
        	"HP2": "Heat Pump 2",
        	"HP3": "Heat Pump 3",
        	"HP4": "Heat Pump 4"
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
    exampleProcessResponse: {
        "xaxis": "time",
        "yaxis": "Total_Water",
        "values": {
            "1": {
                "2012-01-01:0": {
                    "Total_Water": {
                        "y": "124",
                        "x": "900"
                    },
                    "Hot_Water": {
                        "y": "65",
                        "x": "900"
                    }
                },
                "2013-01-01:0": {
                    "Total_Water": {
                        "y": "120",
                        "x": "915"
                    },
                    "Hot_Water": {
                        "y": "60",
                        "x": "915"
                    }
                },
                "2014-01-01:0": {
                    "Total_Water": {

                        "y": "129",
                        "x": "925"
                    },
                    "Hot_Water": {
                        "y": "70",

                        "x": "925"
                    }
                }
            },
            "2": {
                "2012-01-01:0": {

                    "Total_Water": {
                        "y": "120",
                        "x": "900"
                    },
                    "Hot_Water": {

                        "y": "65",
                        "x": "900"
                    }
                },
                "2013-01-01:0": {
                    "Total_Water": {
                        "y": "108",
                        "x": "905.5"
                    },

                    "Hot_Water": {
                        "y": "77",
                        "x": "905.5"
                    }
                },

                "2014-01-01:0": {
                    "Total_Water": {
                        "y": "145",
                        "x": "910"
                    },
                    "Hot_Water": {
                        "y": "70",
                        "x": "910"
                    }
                }
            }
        },
        "granularity": "Yearly",
        "messages": ["No graph data received\n"]
    },

    /*
     * This one is kind of hacky. Turns the category display name into the type
     * that should be sent to process.php.
     */
    categoryNameToType: {
        "Time": "time",
        "None": "none",
        "Sensors": "sensorarray",
        "Air Quality": "sensorarray",
        "Apartment Heating": "sensorarray",
        "Heat Flux": "sensorarray",
        "Water": "sensorarray",
        "Apartment Electricity": "sensorarray",
        "BAS Electricity": "sensorarray",
        "Formulae": "formula",
        "BAS Energy": "energy",
        "Alerts": "alert",
        "Utilities Costs": "utility"
    },

    /* This is pretty much a hack. These sensors are not to be processed. */
    omissions: ['Ch1', 'Ch2', 'AUX1', 'AUX2', 'AUX3', 'AUX4', 'AUX5']

});
