How to use:

format:
    equation1 <comparison> equation2

    equations: see custom_equations_readme.txt
    comparisons:
        <:  less than
        >:  greater than
        !=: not equal
        ==: equal

examples:
    $air_co2$ > 1000
    $heat_mass$ * 30 != $air_co2$

The comparison is checked for every hour of the graph you chose, even if you chose a different granularity (eg. Daily or Monthly).  If any of these hourly data points matches your comparison, it will return the value calculated on the left or right side of the equation, depending on which one has the database variable.  If both sides have the database variable, it returns the calculated values on the right side.
