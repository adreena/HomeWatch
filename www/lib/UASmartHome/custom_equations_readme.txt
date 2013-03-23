How to use:

database variables:
    $air_co2$
    $air_humidity$
    $air_temperature$
    $elec_ch1$
    $elec_ch2$
    $elec_aux1$
    $elec_aux2$
    $elec_aux3$
    $elec_aux4$
    $elec_aux5$
    $heat_energy$
    $heat_volume$
    $heat_mass$
    $heat_flow$
    $heat_temp1$
    $heat_temp2$
    $heatflux_stud$
    $heatflux_insul$
    $water_hot$
    $water_total$
    $weather_temp$
    $weather_humidity$
    $weather_windspeed$
    $weather_winddirection$

constants:
    e = 2.71...
    pi = 3.14...

built-in functions:
    'sin','sinh','arcsin','asin','arcsinh','asinh',
    'cos','cosh','arccos','acos','arccosh','acosh',
    'tan','tanh','arctan','atan','arctanh','atanh',
    'sqrt','abs','ln','log'

example function:
    9 * (3+pi) * \$air_temperature$ + \$air_co2$ / 4

    Based on what you selected in the search for start date, end date,
    granularity, and apartment, it will show time on the x axis and
    the evaluated function values on the y axis.
