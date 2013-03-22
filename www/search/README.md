# `process.php` parameter guide

## GET parameters

A parameter whose name ends in empty brackets (`[]`) indicates an array
parameter. Multiple values can be sent for this value.

### sensors[]

Sensors. Names correspond directly to the name of the sensors in the
database.

Note that electricity sensors exist in phase{A,B}sensors[].


### apartments[]

Unit numbers. IDs correspond directly to the unit names in the
database.

### phaseAsensors[] and phaseBsensors[]

Electricity sensors.


### period

Mislabeled granularity. Possible values:

 * Hourly
 * Daily
 * Weekly
 * Monthly
 * Yearly

### startdate and enddate

Dates denoting the date ranges for the query.For ranged queries, the
start date.

### finances

Boolean. Its appearance in the query string indicates whether or not
to send financial information.

### {phaseA,phaseB,elec}Sum, waterTempDiff

Boolean. Their presence indicates whether or not to send their
respective statistics.

