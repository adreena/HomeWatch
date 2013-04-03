============================================================================
Alerts

    Alerts can be specified by a manager or engineer by clicking "Configuration" on the top of the page.

----------------------------------------------------------------------------

Adding or editing alerts:

    1. Click "edit" on an existing alert to edit it.  If you are editing an existing alert, "Alert Editor" will appear in red and indicate which alert you are editing. 

    2. Insert a name for the alert.  The name will show up on the graphs page.  The value can be of two different formats:

        a) equation1 <comparison> equation2
        b) variable <comparison1> constant1 [AND|OR] variable <comparison2> constant2

        a) can be any equation separated by a single comparison operator.  Supported comparison operators include:

            <:  less than
            >:  greater than
            !=: not equal
            ==: equal

    Boolean operators AND and OR are also supported for simple equations.  Ie, only one variable is allowed, and both sides of the boolean operator must contain only one variable which must be the same on both sides.  No additional equation-specific symbols (eg. +, *, pi) is allowed when using boolean operators; the comparison must be done to a constant.

    Examples of valid alert values include:

        $air_co2$ < 1000
        $air_temperature$ > 25 AND $air_temperature < 17
        $heat_temp1$ + $air_temperature$ < 35

    Add a description to the alert so you remember what it is showing.

    3. Click "cancel" to stop editing and remove all changes you have made so far to the alert, or click "submit" to save all changes.

----------------------------------------------------------------------------

Deleting alerts:

    1. Click "Delete" on the row of the alert you wish to delete.

----------------------------------------------------------------------------

Favorite alerts:

    1. To add an alert to your "favorites" list, check the box on the row of the alert.

    2. Click "Update Favorites".

----------------------------------------------------------------------------

Show all or favorite alerts:

    1. Click Toggle All/Favorites.

    2. This will hide alerts which are not favorites, or show all the alerts.

----------------------------------------------------------------------------

Graphing alerts:

    1. Click "Graphs" on the top of the page as an engineer or manager.

    2. Choose your alert name from the Y Axis dropdown menu.

    3. Choose your dates, apartment(s), and graph type.

    4. Wait for the graph to appear.  The graph will show all hourly points which matched your alert between the dates you chose, for the selected apartment(s).  The granularity for alerts will always be hourly.
        Note: the value graphed will be the result of the equation containing the variable you supplied for the alert.  If there are variables on both sides of a comparison operator, the value on the RIGHT side will be graphed.  If you used a boolean operator (AND or OR), only the variable will be graphed.

----------------------------------------------------------------------------

Known bugs:

    1) When graphing alerts, the number of points is not always checked correctly when granularities other than "Hourly" is selected, which may result in the web application attempting to graph too many points and slowing down significantly.

----------------------------------------------------------------------------

Future work:

    - Check inputs for alert "value" to make sure the format is correct and that it is a valid alert.
    - Extend alerts to allow more complex equations when using boolean operators.
    - Allow other boolean operators.
    - Graph multiple alerts on the same graph.
    - Show a line for thresholds on the graph.
    - Disable granularities from being chosen when the user tries to graph an alert.
