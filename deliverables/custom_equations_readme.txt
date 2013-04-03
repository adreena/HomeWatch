============================================================================
Equations

    Equations can be specified by a manager or engineer by clicking $Configuration$ on the top of the page.
How to use:

database variables (you must include at least one of these in your equation!):
    $air_co2$
    $air_humidity$
    $air_temperature$
    $elec_ch1_phasea$
    $elec_ch2_phasea$
    $elec_aux1_phasea$
    $elec_aux2_phasea$
    $elec_aux3_phasea$
    $elec_aux4_phasea$
    $elec_aux5_phasea$
    $elec_ch1_phaseb$
    $elec_ch2_phaseb$
    $elec_aux1_phaseb$
    $elec_aux2_phaseb$
    $elec_aux3_phaseb$
    $elec_aux4_phaseb$
    $elec_aux5_phaseb$
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

----------------------------------------------------------------------------

Future work:

    - extend custom equations to allow averages and other statistical analyses
