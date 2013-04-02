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
        process: '/search/process.php'
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

        graphVisibilityControls: '.graph-vis-controls',
        graphDestroyButton: 'a[href=#destroy-graph]',
        graphMinifyButton: 'a[href=#minify-graph]',
        graphHideButton: 'a[href=#hide-graph]'

    },

    /**
     * This is the minimum period between requests to process.
     * An HTTP request is not made within this period.
     */
    MinRequestDelay: 750,

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

        "Sensors": {
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
        },

        "Alerts": {
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
                },
		"2012-03-01:1": {
                    "Total_Water": {
                        "y": "120",
                        "x": "2012-03-01:1"
                    },
                    "Hot_Water": {
                        "y": "60",
                        "x": "2012-03-01:1"
                    }
                },
		"2012-03-01:2": {
                    "Total_Water": {
                        "y": "129",
                        "x": "2012-03-01:2"
                    },
                    "Hot_Water": {
                        "y": "70",
                        "x": "2012-03-01:2"
                    }
                },
		"2012-03-01:3": {
                    "Total_Water": {
                        "y": "100",
                        "x": "2012-03-01:3"
                    },
                    "Hot_Water": {
                        "y": "55",
                        "x": "2012-03-01:3"
                    }
                },
		"2012-03-01:4": {
                    "Total_Water": {
                        "y": "115",
                        "x": "2012-03-01:4"
                    },
                    "Hot_Water": {
                        "y": "72",
                        "x": "2012-03-01:4"
                    }
                },
		"2012-03-01:5": {
                    "Total_Water": {
                        "y": "96",
                        "x": "2012-03-01:5"
                    },
                    "Hot_Water": {
                        "y": "60",
                        "x": "2012-03-01:5"
                    }
                },
		"2012-03-01:6": {
                    "Total_Water": {
                        "y": "115",
                        "x": "2012-03-01:6"
                    },
                    "Hot_Water": {
                        "y": "45",
                        "x": "2012-03-01:6"
                    }
                },
		"2012-03-01:7": {
                    "Total_Water": {
                        "y": "113",
                        "x": "2012-03-01:7"
                    },
                    "Hot_Water": {
                        "y": "48",
                        "x": "2012-03-01:7"
                    }
                },
		"2012-03-01:8": {
                    "Total_Water": {
                        "y": "82",
                        "x": "2012-03-01:8"
                    },
                    "Hot_Water": {
                        "y": "36",
                        "x": "2012-03-01:8"
                    }
                },
		"2012-03-01:9": {
                    "Total_Water": {
                        "y": "129",
                        "x": "2012-03-01:9"
                    },
                    "Hot_Water": {
                        "y": "99",
                        "x": "2012-03-01:9"
                    }
                },
		"2012-03-01:10": {
                    "Total_Water": {
                        "y": "116",
                        "x": "2012-03-01:10"
                    },
                    "Hot_Water": {
                        "y": "77",
                        "x": "2012-03-01:10"
                    }
                },
		"2012-03-01:11": {
                    "Total_Water": {
                        "y": "129",
                        "x": "2012-03-01:11"
                    },
                    "Hot_Water": {
                        "y": "68",
                        "x": "2012-03-01:11"
                    }
                },
		"2012-03-01:12": {
                    "Total_Water": {
                        "y": "144",
                        "x": "2012-03-01:12"
                    },
                    "Hot_Water": {
                        "y": "76",
                        "x": "2012-03-01:12"
                    }
                },
		"2012-03-01:13": {
                    "Total_Water": {
                        "y": "89",
                        "x": "2012-03-01:13"
                    },
                    "Hot_Water": {
                        "y": "56",
                        "x": "2012-03-01:13"
                    }
                },
		"2012-03-01:14": {
                    "Total_Water": {
                        "y": "102",
                        "x": "2012-03-01:14"
                    },
                    "Hot_Water": {
                        "y": "63",
                        "x": "2012-03-01:14"
                    }
                },
		"2012-03-01:15": {
                    "Total_Water": {
                        "y": "136",
                        "x": "2012-03-01:15"
                    },
                    "Hot_Water": {
                        "y": "37",
                        "x": "2012-03-01:15"
                    }
                },
		"2012-03-01:16": {
                    "Total_Water": {
                        "y": "144",
                        "x": "2012-03-01:16"
                    },
                    "Hot_Water": {
                        "y": "100",
                        "x": "2012-03-01:16"
                    }
                },
		"2012-03-01:17": {
                    "Total_Water": {
                        "y": "108",
                        "x": "2012-03-01:17"
                    },
                    "Hot_Water": {
                        "y": "100",
                        "x": "2012-03-01:17"
                    }
                },
		"2012-03-01:18": {
                    "Total_Water": {
                        "y": "166",
                        "x": "2012-03-01:18"
                    },
                    "Hot_Water": {
                        "y": "32",
                        "x": "2012-03-01:18"
                    }
                },
		"2012-03-01:19": {
                    "Total_Water": {
                        "y": "150",
                        "x": "2012-03-01:19"
                    },
                    "Hot_Water": {
                        "y": "54",
                        "x": "2012-03-01:19"
                    }
                },
		"2012-03-01:20": {
                    "Total_Water": {
                        "y": "111",
                        "x": "2012-03-01:20"
                    },
                    "Hot_Water": {
                        "y": "90",
                        "x": "2012-03-01:20"
                    }
                },
		"2012-03-01:21": {
                    "Total_Water": {
                        "y": "109",
                        "x": "2012-03-01:21"
                    },
                    "Hot_Water": {
                        "y": "60",
                        "x": "2012-03-01:21"
                    }
                },
		"2012-03-01:22": {
                    "Total_Water": {
                        "y": "170",
                        "x": "2012-03-01:22"
                    },
                    "Hot_Water": {
                        "y": "105",
                        "x": "2012-03-01:22"
                    }
                },
		"2012-03-01:23": {
                    "Total_Water": {
                        "y": "115",
                        "x": "2012-03-01:23"
                    },
                    "Hot_Water": {
                        "y": "78",
                        "x": "2012-03-01:23"
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
                },
		"2012-03-01:1": {
                    "Total_Water": {
                        "y": "25",
                        "x": "2012-03-01:1"
                    },
                    "Hot_Water": {
                        "y": "59",
                        "x": "2012-03-01:1"
                    }
                },
		"2012-03-01:2": {
                    "Total_Water": {
                        "y": "110",
                        "x": "2012-03-01:2"
                    },
                    "Hot_Water": {
                        "y": "70",
                        "x": "2012-03-01:2"
                    }
                },
		"2012-03-01:3": {
                    "Total_Water": {
                        "y": "105",
                        "x": "2012-03-01:3"
                    },
                    "Hot_Water": {
                        "y": "50",
                        "x": "2012-03-01:3"
                    }
                },
		"2012-03-01:4": {
                    "Total_Water": {
                        "y": "99",
                        "x": "2012-03-01:4"
                    },
                    "Hot_Water": {
                        "y": "86",
                        "x": "2012-03-01:4"
                    }
                },
		"2012-03-01:5": {
                    "Total_Water": {
                        "y": "90",
                        "x": "2012-03-01:5"
                    },
                    "Hot_Water": {
                        "y": "77",
                        "x": "2012-03-01:5"
                    }
                },
		"2012-03-01:6": {
                    "Total_Water": {
                        "y": "108",
                        "x": "2012-03-01:6"
                    },
                    "Hot_Water": {
                        "y": "47",
                        "x": "2012-03-01:6"
                    }
                },
		"2012-03-01:7": {
                    "Total_Water": {
                        "y": "118",
                        "x": "2012-03-01:7"
                    },
                    "Hot_Water": {
                        "y": "55",
                        "x": "2012-03-01:7"
                    }
                },
		"2012-03-01:8": {
                    "Total_Water": {
                        "y": "80",
                        "x": "2012-03-01:8"
                    },
                    "Hot_Water": {
                        "y": "25",
                        "x": "2012-03-01:8"
                    }
                },
		"2012-03-01:9": {
                    "Total_Water": {
                        "y": "121",
                        "x": "2012-03-01:9"
                    },
                    "Hot_Water": {
                        "y": "106",
                        "x": "2012-03-01:9"
                    }
                },
		"2012-03-01:10": {
                    "Total_Water": {
                        "y": "110",
                        "x": "2012-03-01:10"
                    },
                    "Hot_Water": {
                        "y": "86",
                        "x": "2012-03-01:10"
                    }
                },
		"2012-03-01:11": {
                    "Total_Water": {
                        "y": "134",
                        "x": "2012-03-01:11"
                    },
                    "Hot_Water": {
                        "y": "69",
                        "x": "2012-03-01:11"
                    }
                },
		"2012-03-01:12": {
                    "Total_Water": {
                        "y": "147",
                        "x": "2012-03-01:12"
                    },
                    "Hot_Water": {
                        "y": "72",
                        "x": "2012-03-01:12"
                    }
                },
		"2012-03-01:13": {
                    "Total_Water": {
                        "y": "91",
                        "x": "2012-03-01:13"
                    },
                    "Hot_Water": {
                        "y": "54",
                        "x": "2012-03-01:13"
                    }
                },
		"2012-03-01:14": {
                    "Total_Water": {
                        "y": "110",
                        "x": "2012-03-01:14"
                    },
                    "Hot_Water": {
                        "y": "69",
                        "x": "2012-03-01:14"
                    }
                },
		"2012-03-01:15": {
                    "Total_Water": {
                        "y": "100",
                        "x": "2012-03-01:15"
                    },
                    "Hot_Water": {
                        "y": "15",
                        "x": "2012-03-01:15"
                    }
                },
		"2012-03-01:16": {
                    "Total_Water": {
                        "y": "122",
                        "x": "2012-03-01:16"
                    },
                    "Hot_Water": {
                        "y": "62",
                        "x": "2012-03-01:16"
                    }
                },
		"2012-03-01:17": {
                    "Total_Water": {
                        "y": "110",
                        "x": "2012-03-01:17"
                    },
                    "Hot_Water": {
                        "y": "100",
                        "x": "2012-03-01:17"
                    }
                },
		"2012-03-01:18": {
                    "Total_Water": {
                        "y": "90",
                        "x": "2012-03-01:18"
                    },
                    "Hot_Water": {
                        "y": "45",
                        "x": "2012-03-01:18"
                    }
                },
		"2012-03-01:19": {
                    "Total_Water": {
                        "y": "175",
                        "x": "2012-03-01:19"
                    },
                    "Hot_Water": {
                        "y": "88",
                        "x": "2012-03-01:19"
                    }
                },
		"2012-03-01:20": {
                    "Total_Water": {
                        "y": "91",
                        "x": "2012-03-01:20"
                    },
                    "Hot_Water": {
                        "y": "178",
                        "x": "2012-03-01:20"
                    }
                },
		"2012-03-01:21": {
                    "Total_Water": {
                        "y": "115",
                        "x": "2012-03-01:21"
                    },
                    "Hot_Water": {
                        "y": "78",
                        "x": "2012-03-01:21"
                    }
                },
		"2012-03-01:22": {
                    "Total_Water": {
                        "y": "199",
                        "x": "2012-03-01:22"
                    },
                    "Hot_Water": {
                        "y": "55",
                        "x": "2012-03-01:22"
                    }
                },
		"2012-03-01:23": {
                    "Total_Water": {
                        "y": "126",
                        "x": "2012-03-01:23"
                    },
                    "Hot_Water": {
                        "y": "70",
                        "x": "2012-03-01:23"
                    }
                }
            }
        },
        "granularity": "Hourly",
        "messages": ["No graph data received\n"]
    },*/

    /*exampleProcessResponse: {
        "xaxis": "time",
        "yaxis": "Total_Water",
        "values": {
            "1": {
                "2012-12-01:0": {
                    "Total_Water": {
                        "y": "124",
                        "x": "2012-12-01:0"
                    },
                    "Hot_Water": {
                        "y": "65",
                        "x": "2012-12-01:0"
                    }
                },
		"2012-12-08:0": {
                    "Total_Water": {
                        "y": "120",
                        "x": "2012-12-08:0"
                    },
                    "Hot_Water": {
                        "y": "60",
                        "x": "2012-12-08:0"
                    }
                },
		"2012-12-15:0": {
                    "Total_Water": {
                        "y": "129",
                        "x": "2012-12-15:0"
                    },
                    "Hot_Water": {
                        "y": "70",
                        "x": "2012-12-15:0"
                    }
                },
		"2012-12-22:0": {
                    "Total_Water": {
                        "y": "100",
                        "x": "2012-12-22:0"
                    },
                    "Hot_Water": {
                        "y": "55",
                        "x": "2012-12-22:0"
                    }
                }
	    },
            "2": {
                "2012-12-01:0": {
                    "Total_Water": {
                        "y": "120",
                        "x": "2012-12-01:0"
                    },
                    "Hot_Water": {
                        "y": "65",
                        "x": "2012-12-01:0"
                    }
                },
		"2012-12-08:0": {
                    "Total_Water": {
                        "y": "108",
                        "x": "2012-12-08:0"
                    },
                    "Hot_Water": {
                        "y": "77",
                        "x": "2012-12-08:0"
                    }
                },
		"2012-12-15:0": {
                    "Total_Water": {
                        "y": "145",
                        "x": "2012-12-15:0"
                    },
                    "Hot_Water": {
                        "y": "70",
                        "x": "2012-12-15:0"
                    }
                },
		"2012-12-22:0": {
                    "Total_Water": {
                        "y": "85",
                        "x": "2012-12-22:0"
                    },
                    "Hot_Water": {
                        "y": "40",
                        "x": "2012-12-22:0"
                    }
                }
	    }
        },
        "granularity": "Weekly",
        "messages": ["No graph data received\n"]
    },*/

    /*exampleProcessResponse: {
        "xaxis": "time",
        "yaxis": "Total_Water",
        "values": {
            "1": {
                "2012-01-01:0": {
                    "Total_Water": {
                        "y": "124",
                        "x": "2012-01-01:0"
                    },
                    "Hot_Water": {
                        "y": "65",
                        "x": "2012-01-01:0"
                    }
                },
		"2012-02-01:0": {
                    "Total_Water": {
                        "y": "120",
                        "x": "2012-02-01:0"
                    },
                    "Hot_Water": {
                        "y": "60",
                        "x": "2012-02-01:0"
                    }
                },
		"2012-03-01:0": {
                    "Total_Water": {
                        "y": "129",
                        "x": "2012-03-01:0"
                    },
                    "Hot_Water": {
                        "y": "70",
                        "x": "2012-03-01:0"
                    }
                },
		"2012-04-01:0": {
                    "Total_Water": {
                        "y": "100",
                        "x": "2012-04-01:0"
                    },
                    "Hot_Water": {
                        "y": "55",
                        "x": "2012-04-01:0"
                    }
                },
		"2012-05-01:0": {
                    "Total_Water": {
                        "y": "115",
                        "x": "2012-05-01:0"
                    },
                    "Hot_Water": {
                        "y": "72",
                        "x": "2012-05-01:0"
                    }
                },
		"2012-06-01:0": {
                    "Total_Water": {
                        "y": "96",
                        "x": "2012-06-01:0"
                    },
                    "Hot_Water": {
                        "y": "60",
                        "x": "2012-06-01:0"
                    }
                },
		"2012-07-01:0": {
                    "Total_Water": {
                        "y": "200",
                        "x": "2012-07-01:0"
                    },
                    "Hot_Water": {
                        "y": "95",
                        "x": "2012-07-01:0"
                    }
                },
		"2012-08-01:0": {
                    "Total_Water": {
                        "y": "175",
                        "x": "2012-08-01:0"
                    },
                    "Hot_Water": {
                        "y": "100",
                        "x": "2012-08-01:0"
                    }
                },
		"2012-09-01:0": {
                    "Total_Water": {
                        "y": "105",
                        "x": "2012-09-01:0"
                    },
                    "Hot_Water": {
                        "y": "78",
                        "x": "2012-09-01:0"
                    }
                },
		"2012-10-01:0": {
                    "Total_Water": {
                        "y": "135",
                        "x": "2012-10-01:0"
                    },
                    "Hot_Water": {
                        "y": "65",
                        "x": "2012-10-01:0"
                    }
                },
		"2012-11-01:0": {
                    "Total_Water": {
                        "y": "112",
                        "x": "2012-11-01:0"
                    },
                    "Hot_Water": {
                        "y": "50",
                        "x": "2012-11-01:0"
                    }
                },
		"2012-12-01:0": {
                    "Total_Water": {
                        "y": "100",
                        "x": "2012-12-01:0"
                    },
                    "Hot_Water": {
                        "y": "30",
                        "x": "2012-12-01:0"
                    }
                }
	    },
            "2": {
                "2012-01-01:0": {
                    "Total_Water": {
                        "y": "120",
                        "x": "2012-01-01:0"
                    },
                    "Hot_Water": {
                        "y": "65",
                        "x": "2012-01-01:0"
                    }
                },
		"2012-02-01:0": {
                    "Total_Water": {
                        "y": "108",
                        "x": "2012-02-01:0"
                    },
                    "Hot_Water": {
                        "y": "77",
                        "x": "2012-02-01:0"
                    }
                },
		"2012-03-01:0": {
                    "Total_Water": {
                        "y": "145",
                        "x": "2012-03-01:0"
                    },
                    "Hot_Water": {
                        "y": "70",
                        "x": "2012-03-01:0"
                    }
                },
		"2012-04-01:0": {
                    "Total_Water": {
                        "y": "85",
                        "x": "2012-04-01:0"
                    },
                    "Hot_Water": {
                        "y": "40",
                        "x": "2012-04-01:0"
                    }
                },
		"2012-05-01:0": {
                    "Total_Water": {
                        "y": "125",
                        "x": "2012-05-01:0"
                    },
                    "Hot_Water": {
                        "y": "70",
                        "x": "2012-05-01:0"
                    }
                },
		"2012-06-01:0": {
                    "Total_Water": {
                        "y": "100",
                        "x": "2012-06-01:0"
                    },
                    "Hot_Water": {
                        "y": "54",
                        "x": "2012-06-01:0"
                    }
                },
		"2012-07-01:0": {
                    "Total_Water": {
                        "y": "110",
                        "x": "2012-07-01:0"
                    },
                    "Hot_Water": {
                        "y": "49",
                        "x": "2012-07-01:0"
                    }
                },
		"2012-08-01:0": {
                    "Total_Water": {
                        "y": "150",
                        "x": "2012-08-01:0"
                    },
                    "Hot_Water": {
                        "y": "67",
                        "x": "2012-08-01:0"
                    }
                },
		"2012-09-01:0": {
                    "Total_Water": {
                        "y": "122",
                        "x": "2012-09-01:0"
                    },
                    "Hot_Water": {
                        "y": "30",
                        "x": "2012-09-01:0"
                    }
                },
		"2012-10-01:0": {
                    "Total_Water": {
                        "y": "95",
                        "x": "2012-10-01:0"
                    },
                    "Hot_Water": {
                        "y": "58",
                        "x": "2012-10-01:0"
                    }
                },
		"2012-11-01:0": {
                    "Total_Water": {
                        "y": "109",
                        "x": "2012-11-01:0"
                    },
                    "Hot_Water": {
                        "y": "50",
                        "x": "2012-11-01:0"
                    }
                },
		"2012-12-01:0": {
                    "Total_Water": {
                        "y": "118",
                        "x": "2012-12-01:0"
                    },
                    "Hot_Water": {
                        "y": "15",
                        "x": "2012-12-01:0"
                    }
                }
            }
        },
        "granularity": "Monthly",
        "messages": ["No graph data received\n"]
    },*/

    /*exampleProcessResponse: {
    "xaxis": "CO2",
        "yaxis": "Total_Water",
        "values": {
            "1": {
                "2012-03-01:0": {
                    "Total_Water": {
                        "y": "124",
                        "x": "900"
                    },
                    "Hot_Water": {
                        "y": "65",
                        "x": "900"
                    }
                },
		"2012-03-02:0": {
                    "Total_Water": {
                        "y": "120",
                        "x": "905.5"
                    },
                    "Hot_Water": {
                        "y": "60",
                        "x": "905.5"
                    }
                },
		"2012-03-03:0": {
                    "Total_Water": {
                        "y": "129",
                        "x": "910"
                    },
                    "Hot_Water": {
                        "y": "70",
                        "x": "910"
                    }
                },
		"2012-03-04:0": {
                    "Total_Water": {
                        "y": "100",
                        "x": "902.6"
                    },
                    "Hot_Water": {
                        "y": "55",
                        "x": "902.6"
                    }
                },
		"2012-03-05:0": {
                    "Total_Water": {
                        "y": "115",
                        "x": "918"
                    },
                    "Hot_Water": {
                        "y": "72",
                        "x": "918"
                    }
                },
		"2012-03-06:0": {
                    "Total_Water": {
                        "y": "96",
                        "x": "895.3"
                    },
                    "Hot_Water": {
                        "y": "60",
                        "x": "895.3"
                    }
                },
		"2012-03-07:0": {
                    "Total_Water": {
                        "y": "115",
                        "x": "892"
                    },
                    "Hot_Water": {
                        "y": "45",
                        "x": "892"
                    }
                }
	    },
            "2": {
                "2012-03-01:0": {
                    "Total_Water": {
                        "y": "120",
                        "x": "900"
                    },
                    "Hot_Water": {
                        "y": "65",
                        "x": "900"
                    }
                },
		"2012-03-02:0": {
                    "Total_Water": {
                        "y": "108",
                        "x": "905.5"
                    },
                    "Hot_Water": {
                        "y": "77",
                        "x": "905.5"
                    }
                },
		"2012-03-03:0": {
                    "Total_Water": {
                        "y": "145",
                        "x": "910"
                    },
                    "Hot_Water": {
                        "y": "70",
                        "x": "910"
                    }
                },
		"2012-03-04:0": {
                    "Total_Water": {
                        "y": "85",
                        "x": "902.6"
                    },
                    "Hot_Water": {
                        "y": "40",
                        "x": "902.6"
                    }
                },
		"2012-03-05:0": {
                    "Total_Water": {
                        "y": "125",
                        "x": "918"
                    },
                    "Hot_Water": {
                        "y": "70",
                        "x": "918"
                    }
                },
		"2012-03-06:0": {
                    "Total_Water": {
                        "y": "100",
                        "x": "895.3"
                    },
                    "Hot_Water": {
                        "y": "54",
                        "x": "895.3"
                    }
                },
		"2012-03-07:0": {
                    "Total_Water": {
                        "y": "110",
                        "x": "892"
                    },
                    "Hot_Water": {
                        "y": "49",
                        "x": "892"
                    }
                }
            }
        },
        "granularity": "Daily",
        "messages": ["No graph data received\n"]
    },*/

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
        "Sensors": "sensorarray",
        "Formulae": "formula",
        "Energy": "energy",
        "Alerts": "alert"
    }


});
