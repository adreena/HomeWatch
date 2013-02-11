## Contents
> 1. [Project Overview](#Project Overview)
> 1. [Requirements](#Requirements)
> 1. &nbsp; &nbsp; [User Stories](#User Stories)
> 1. &nbsp; &nbsp; [Use Cases](#Use Cases)
> 1. [User Interface](#User Interface)
> 1. [Software License](#Software License)
> 1. [Project Glossary](#Project Glossary)
> 1. [Competing Products](#Competing Products)
> 1. [Other Resources](#Other Resources)
> 1. [See Also](#See Also)

<a name='Project Overview'/>
## Project Overview
Our client ([WBHDC](http://www.wbhadc.ca/)) produces affordable apartments for low-income tenants. To this end, they would like to know how to maximize the efficiency of operating these apartments. They are currently tracking metrics such as heat lost through certain walls, heating required, and electricity consumed by the different appliances in each apartment. Identifying inefficiencies in these apartments will assist the engineers in constructing more efficient housing in the future; further, identifying what aspects of maintenance are most expensive will assist the building managers in planning, budgeting, and incentivizing tenants to reduce costs in these areas. Tenants may also be interested to see where and why they are spending the most money on heating and electricity. However, the current visualization of this data is insufficient for the needs of any of these stakeholders. Developing custom visualizations so that each of these stakeholders can quickly extract the information they are interested will make this job easier to perform and perhaps assist in future development of low-income housing.

<a name='Requirements'/>
## Requirements

**Overview**  
For this first pass, I have followed the Volere template for requirement analysis. I have devised the following list of requirement types and use cases. In general, cuts to requirements of categories 1, 2, or 3 indicate a failure to deliver the system as promised. I have allowed multiple use cases for a single requirement as different users will have different use cases but similar requirements.

 	
**Requirement Types**   	
- 1. System – These are back-end requirements which are necessary for the completion of the project. As they are the most likely candidates for bottlenecking our system development, they should be our first priority.
- 2. Mandatory – We cannot consider the project to be completed in any sense without fulfilling these requirements for the user.
- 3. Flexible – We must have something approximately equivalent to this requirement to consider the project completed, but the details need not be exactly as described. 	
- 4. Polish – While not strictly necessary for completion of this project, these requirements will add significant quality to our project and should be considered part of our deliverables unless the project falls well behind schedule.	 	
- 5. Optional – While desirable, these requirements can be cut if time is running low.

---

**Requirement Name:** Data Selection  
**Requirement #:** 1 	**Requirement Type:** 2	**Use Cases:** 2.1, 3.1

**Description:** The product will allow building managers and engineers to select what types of data they are interested in, and how they would like that data displayed.

**Rationale:** Building managers and engineers should be permitted access to all of the same data, as it is possible that either may be interested in data that is “usually” the domain of the other. However, providing all this data at once is not feasible. A system must exist to act as the “controller” for our product, and allow the user to choose what data they would like to see, and how they would like to see it.

**Fit Criterion:** The user will be able to provide as input to the product any collection of apartments outfitted with sensors and a time period, as well as visualization options and optional information such as financial analysis, efficiency analysis,  (either through a dropdown menu or by selecting a different search form). The product will then return the aggregate and average data as appropriate for the selected sensors, in the selected apartments, displayed in the selected format. In short, our system must be flexible in what data it displays, and how it displays it.

**Customer Satisfaction:** 4		**Customer Dissatisfaction:** 9  
**Dependencies:** 2, 4			**Conflicts:** None  
**Supporting Materials:** None

---

**Requirement Name:** Report Generation  
**Requirement #:** 2 	**Requirement Type:** 2	**Use Cases:** 2.2, 3.1

**Description:**  The product will produce plaintext reports, based on selection options, of sensor data for a given apartment.

**Rationale:** The raw numbers and data collected by the sensor are too numerous and small to be of use to any of our stakeholders. The aggregate data must be available in plaintext for use in stakeholder databases, reports, etc.

**Fit Criterion:**  When provided an apartment and time period by the user, the product will return, in plaintext, the aggregate and average data (as appropriate) for the selected apartment in the selected time period.

**Customer Satisfaction:** 3		**Customer Dissatisfaction:** 10  
**Dependencies:** 3			**Conflicts:** None  
**Supporting Materials:** None

---

**Requirement Name:** Data Processing  
**Requirement #:** 3 	**Requirement Type:** 1	**Use Cases:**   

**Description:** Database access and calculations will be handled by an http web service.

**Rationale:** To facilitate a flexible design, the clients have requested that our data-level protocols be kept separate from the display of these protocols.

**Fit Criterion:** Whenever database access or calculations on this data (i.e., calculation of efficiency coefficients for heat loss) must be performed, this data will be sent from the front-end page to the back-end web service through an http request. This service will perform all necessary calculations and return the data to the front-end of the system, which will be responsible for the display of this data.

**Customer Satisfaction:** 1		**Customer Dissatisfaction:** 8  
**Dependencies:** None			**Conflicts:** None  
**Supporting Materials:** None

---

**Requirement Name:** Visualization Options  
**Requirement #:** 4	**Requirement Type:** 1	**Use Cases:** 2.1, 2.3, 3.1, 4.4, 4.5 

**Description:** Data must be presented in some visual format: i.e. interactive graphs.

**Rationale:** While plaintext data is valuable for certain tasks, graphs and charts are easier to digest at a glance.

**Fit Criterion:** Whenever a user requests data from the system, they should have the choice of different visual formats.

**Customer Satisfaction:** 4		**Customer Dissatisfaction:** 6  
**Dependencies:** 1			**Conflicts:** None  
**Supporting Materials:** None

---

**Requirement Name:**  Apartment View  
**Requirement #:** 5 	**Requirement Type:** 2	**Use Cases:** 2.1, 3.1, 4.4, 4.5

**Description:** Data should be presented overlaid over the floor plan of the apartment from which the data was collected.

**Rationale:** Being able to glance at the floor plan of an apartment and immediately identify the areas of high and low energy loss will greatly simplify the work of the building manager and engineers, who are both interested in tracking down problem spots throughout the apartments.

**Fit Criterion:** Whenever a user requests data from the system, they should have the option to select Apartment View. This view should cause data to be represented in some fashion (dots, color-coded) as an overlay on the floor plan of the selected apartments.

**Customer Satisfaction:** 8		**Customer Dissatisfaction:** 4  
**Dependencies:** 4			**Conflicts:** None  
**Supporting Materials:** None

---

**Requirement Name:**  Graph View  
**Requirement #:** 6 	**Requirement Type:** 1	**Use Cases:** 2.1, 3.1, 4.4, 4.5

**Description:** Data must be presented in some visual format: i.e. interactive graphs.

**Rationale:** While plaintext data is valuable for certain tasks, graphs and charts are easier to digest at a glance.

**Fit Criterion:** Whenever a user requests data from the system, they should have the option of selecting from one or more Graph Views. These views will display the data in some visual format, such as a bar chart, pie chart, or line chart (where appropriate).

**Customer Satisfaction:** 1		**Customer Dissatisfaction:** 8  
**Dependencies:** 1, 2			**Conflicts:** None  
**Supporting Materials:** None

---

**Requirement Name:** Multiple Apartment Selection  
**Requirement #:** 7 	**Requirement Type:** 1	**Use Cases:** 2.1, 3.1, 4.4, 4.5

**Description:** Data must be presented in some visual format: i.e. interactive graphs.

**Rationale:** While plaintext data is valuable for certain tasks, graphs and charts are easier to digest at a glance.

**Fit Criterion:** Whenever a user requests data from the system, they should have the option of

**Customer Satisfaction:** 1		**Customer Dissatisfaction:** 8  
**Dependencies:** 1, 2			**Conflicts:** None  
**Supporting Materials:** None

---

**Requirement Name:** Chart/Graph Visualization  
**Requirement #:** 8 	**Requirement Type:** 1	**Use Cases:** 2.1, 3.1, 4.4, 4.5

**Description:** Data must be presented in some visual format: i.e. interactive graphs.

**Rationale:** To facilitate a flexible design, the clients have requested that our data-level protocols be kept separate from the display of these protocols.

**Fit Criterion:** Whenever database access or calculations on this data (i.e., calculation of efficiency coefficients for heat loss) must be performed, this data will be sent from the front-end page to the back-end web service through an http request. This service will perform all necessary calculations and return the data to the front-end of the system, which will be responsible for the display of this data.

**Customer Satisfaction:** 1		**Customer Dissatisfaction:** 8  
**Dependencies:** 3			**Conflicts:** None  
**Supporting Materials:** None

---

**Requirement Name:** Weather Data

**Requirement #:** 9 	**Requirement Type:** 5	**Use Cases:**

**Description:** Data is parsed from Environment Canada or a similar website, and presented side-by-side with sensor data.

**Rationale:** Heat loss and heating data is more useful within the context of that day's weather.

**Fit Criterion:** When selecting sensor data and apartments, the user should have the option of displaying the average daily temperature (alternatively, the daily high and daily low temperature) for that day, in addition to the other requested information.

**Customer Satisfaction:** 4		**Customer Dissatisfaction:** 3  
**Dependencies:** 3			**Conflicts:** None  
**Supporting Materials:** None

<a name='user-stories'/>
## User Stories
<table>
  <tr><th>ID</th><th width='100%'>Description</th><th>Priority</th></tr>
  <tr>
    <td>1.1</td>
    <td>As a user, I want to be able to log in to my account.</td>
    <td>Medium</td>
  </tr>
</table>

<a name='us-buidling-manager'/>
##### Building Manager
<table>
  <tr><th>ID</th><th width='100%'>Description</th><th>Priority</th></tr>
  <tr>
    <td>2.1</td>
    <td>As the building manager, I want to see the consumption pattern of a particular utility for an apartment unit over a given time period in an easily understandable form.</td>
    <td>Medium</td>
  </tr>
  <tr>
    <td>2.2</td>
    <td>As the building manager, I want to compare the consumption pattern of a particular utility over a given time period between unit X and unit Y.</td>
    <td>Medium</td>
  </tr>
  <tr>
    <td>2.3</td>
    <td>As the building manager, I want to generate a daily/weekly/monthly/yearly report with a pie chart that depicts the ratio of water to electricity to gas consumption for selected units.</td>
    <td>Medium</td>
  </tr>
  <tr>
    <td>2.4</td>
    <td>As the building manager, I want to see the consumption cost of a particular utility for an apartment over a given time period.</td>
    <td>High</td>
  </tr>
  <tr>
    <td>2.5</td>
    <td>As the building manager, I want to compare the cost of a particular utility over a given time period for unit X and unit Y.</td>
    <td>High</td>
  </tr>
</table>

<a name='us-engineer'/>
##### Engineer
<table>
  <tr><th>ID</th><th width='100%'>Description</th><th>Priority</th></tr>
  <tr>
    <td>3.1</td>
    <td>As an Engineer, I want to group results by apartment number, type, floor, facing direction, or any combination of those categories.</td>
    <td>Medium</td>
  </tr>
  <tr>
    <td>3.2</td>
    <td>As an Engineer, I want to specify a time range for results.</td>
    <td>Medium</td>
  </tr>
  <tr>
    <td>3.3</td>
    <td>As an Engineer, I want results reported on a granularity of hour, day, and month.</td>
    <td>Medium</td>
  </tr>
  <tr>
    <td>3.4</td>
    <td>As an Engineer, I want to visualize results on a graph.</td>
    <td>Medium</td>
  </tr>
  <tr>
    <td>3.5</td>
    <td>As an Engineer, I want to determine exact values from looking at results on a graph.</td>
    <td>High</td>
  </tr>
  <tr>
    <td>3.6</td>
    <td>As an Engineer, I want to know the hours in which a given metric reaches its maximum and minimum values.</td>
    <td>High</td>
  </tr>
  <tr>
    <td>3.7</td>
    <td>As an Engineer, I want to generate reports summarizing important information from a result set.</td>
    <td>Medium</td>
  </tr>
  <tr>
    <td>3.8</td>
    <td>As an Engineer, I want to visualize the average C02 concentration (ppm) for a set of apartment groups over a given time range and granularity.</td>
    <td>Medium</td>
  </tr>
  <tr>
    <td>3.9</td>
    <td>As an Engineer, I want to visualize the average relative humidity (%) for a set of apartment groups over a given time range and granularity.</td>
    <td>Medium</td>
  </tr>
  <tr>
    <td>3.10</td>
    <td>As an Engineer, I want to visualize the temperature (C) for a set of apartment groups over a given time range and granularity.</td>
    <td>Medium</td>
  </tr>
  <tr>
    <td>3.11</td>
    <td>As an Engineer, I want to visualize the heat flux through studs (W/m^2) for a set of apartment groups over a given time range and granularity.</td>
    <td>Medium</td>
  </tr>
  <tr>
    <td>3.12</td>
    <td>As an Engineer, I want to visualize the heat flux through insulation (W/m^2) for a set of apartment groups over a given time range and granularity.</td>
    <td>Medium</td>
  </tr>
  <tr>
    <td>3.13</td>
    <td>As an Engineer, I want to visualize the total energy (Wh) of water used to heat the building for a set of apartment groups over a given time range and granularity.</td>
    <td>Medium</td>
  </tr>
  <tr>
    <td>3.14</td>
    <td>As an Engineer, I want to visualize the total volume (L), mass (g), and current flow (L/s) of water used to heat the building for a set of apartment groups over a given time range and granularity.</td>
    <td>Medium</td>
  </tr>
  <tr>
    <td>3.15</td>
    <td>As an Engineer, I want to visualize the outdoor temperature over a given time range and granularity.</td>
    <td>Medium</td>
  </tr>
  <tr>
    <td>3.16</td>
    <td>As an Engineer, I want to compare how the outdoor temperature effects heating parameters for a set of apartment groups over a given time range and granularity.</td>
    <td>Medium</td>
  </tr>
  <tr>
    <td>3.17</td>
    <td>As an Engineer, I want to visualize the total electrical energy usage (KWh) for a set of apartment groups over a given time range and granularity.</td>
    <td>Medium</td>
  </tr>
  <tr>
    <td>3.18</td>
    <td>As an Engineer, I want to visualize the total electrical energy usage (KWh) on a given socket for a set of apartment groups over a given time range and granularity.</td>
    <td>Medium</td>
  </tr>
  <tr>
    <td>3.19</td>
    <td>As an Engineer, I want to visualize the total water usage (gallons) of a set of apartment groups over a given time range and granularity.</td>
    <td>Medium</td>
  </tr>
  <tr>
    <td>3.20</td>
    <td>As an Engineer, I want to visualize the total hot water usage (gallons) of a set of apartment groups over a given time range and granularity.</td>
    <td>Medium</td>
  </tr>
</table>

<a name='uc-resident'/>
##### Resident
<table>
  <tr><th>ID</th><th width='100%'>Description</th><th>Priority</th></tr>
  <tr>
    <td>4.1</td>
    <td>As a resident, I want to be shown my current standing and usage upon logging in.</td>
    <td>Medium</td>
  </tr>
  <tr>
    <td>4.2</td>
    <td>As a resident, I want to be able to view my awards history, with granularity on the level (gold/silver/bronze) and the type (co2,electricity etc.).</td>
    <td>Medium</td>
  </tr>
  <tr>
    <td>4.3</td>
    <td>As a resident, I want to be told ways to improve my standing.</td>
    <td>Low</td>
  </tr>
  <tr>
    <td>4.4</td>
    <td>As a resident, I want to see how the usage of the room has changed since the sensors were installed.</td>
    <td>Low</td>
  </tr>
  <tr>
    <td>4.5</td>
    <td>As a resident, I want to be able to see how the room's usage has changed since I moved in.</td>
    <td>Medium</td>
  </tr>
  <tr>
    <td>4.6</td>
    <td>As a resident, I want to be able to view and specify my current average occupancy.</td>
    <td>Low</td>
  </tr>
</table>

<a name='Use Cases'/>
## Use Cases

<a name="uc-general" />
<pre>
ID:              1.1
Description:     User logs in
Preconditions:   None
Postconditions:  User is logged in and sees his/her SmartHome profile page.
Success flow:
                 1. User inputs his/her username and password.
                 2. System validates the username and password.
                 3. System shows user his/her SmartHome profile page.
Alternative flows:
                 1a. User clicks "Forgot password".
                     1. System asks user to input his/her username and inform him/her that it will email a
                        temporary password to his/her associated email.
                     2. User inputs his/her username.
                     3. System sends an email to the username's corresponding email containing the
                        temporary password.
                     4. System returns to the log in page.
Exceptions:
                 2a. Username does not exist.
                     1. System lets the user know the username he/she entered does not exist.
                 2b. Password is incorrect.
                     1. System will let the user know his/her password is incorrect.
</pre>

<a name="uc-building-manager" />
##### Building Manager
<pre>
ID:              2.1
Description:     Building manager views utility usage data
Preconditions:   User is logged in as a Building Manager.
Postconditions:  
Success Flow:
                 1. System displays utility options
                 2. Building manager selects electricity option (water and gas represent alternative flows)
                 3. System displays electricity consumption for all apartments (default) for latest day
                    (default) as bar chart (default)
                 4. Building manager selects latest week (previous days/weeks/months/years represent
                    alternative flows)
                 5. System displays electricity consumption for all apartments for last week as bar chart
                 6. Building manager selects apartment 5 for display (other discreet apartment
                    units/combinations represent alternative flows)
                 7. System displays electricity consumption for apartment 5 for last week as bar chart
                 8. Building manager selects line graph (pie chart represents an alternative flow)
                 9. System displays electricity consumption for apartment 5 for last week as line graph
</pre>

<pre>
ID:              2.2
Description:     Building manager generates report of utility usage data
Preconditions:   User is logged in as a Building Manager
Postconditions:  
Success Flow:
                 1. System displays utility options
                 2. Building manager selects generate report (secondary function access: select report at
                    point 11 of use case #1)
                 3. System displays utility type selector, time period selector, unit selector and report
                    type selector
                 4. Building manager selects all utilities, previous year, all apartments, pie chart
                    options (plain text report represents alternative flow)
                 5. System displays pan-utility consumption for previous year for all units in pie chart
                    form
                 6. Building manager selects print (export report represents alternative flow)
                 7. System prints out report
</pre>

<pre>
ID:              2.3
Description:     Building manager views utility cost data
Preconditions:   User is logged in as a Building Manager
Postconditions:  
Success Flow:
                 1. System displays utility options
                 2. Building manager selects cost analysis
                 3. System displays cost analysis per utility type (in dollars) for all apartments
                    (default) for latest day (default)
                 4. Building manager selects cost analysis for natural gas
                 5. System displays cost analysis for natural gas for all apartments for latest day
                    (electricity and water represent alternative flows)
                 6. Building manager selects apartment 12 (other apartments represent alternative flows)
                 7. System displays cost analysis for natural gas for apartment 12 for latest day
                 8. Building manager selects time period as latest week (previous days/weeks/months/years
                    represent alternative flows)
                 9. System displays cost analysis for natural gas for apartment 12 for latest week
</pre>

<a name="uc-engineer" />
##### Engineer
<pre>
ID:              3.1
Description:     Engineer requests to visualize building data for set of apartment groups over a given time
                 range and granularity. 
Preconditions:   User is logged in as an Engineer
Postconditions:  Engineer sees results in their selected visualization method
Success Flow:
                 1. User selects the metrics they want visualize
                 2. User selects a method of visualization
                 3. User chooses to group the results
                 4. User enters the time granularity they want to see the results
                 5. User enters the date range for the results (default month)
                 6. User requests the results
                 7. Client validates page
                 8. System returns results to the client based on the given query
Variations:
                 1a. Metrics:
                     1.  CO2 (ppm)
                     2.  Humidity (%)
                     3.  Temperature (C)
                     4.  Heat flux through studs (W/m^2)
                     5.  Heat flux through insulation (W/m^2)
                     6.  Heating water energy consumption (Wh)
                     7.  Heating water volume (L), mass (g), flow (L/s)
                     8.  Electrical energy consumption (KWh)
                     9.  Total water consumption (gallons)
                     10. Hot water consumption (gallons)
                 2a. Visualization Methods:
                     1. Graph
                     2. Report
                     3. Building view
                 3a. Grouping Methods:
                     1. Apartment number (default)
                     2. Apartment floor
                     3. Apartment type
                     4. Apartment facing direction
                 4a. Time granularity:
                     1. Hour (default)
                     2. Day
                     3. Month
Exceptions:
                 7a. Client validation detects problem
                     1. Client informs user of invalid input
                 8a. System fails to execute query
                     1. System provides explanation for why query failed
                     2. Client displays failure information to user
</pre>

<a name="uc-resident" />
##### Resident
<pre>
ID:              4.1
Description:     Resident views their stats page
Preconditions:   Resident is logged in
Postconditions:  Resident is shown current stats page
Success Flow:
                 1. User navigates to stats page.
                 2. System loads resident's latest room data
                 3. System loads latest data for other rooms
                 4. System determines residents ranking
                 5. System displays user's current room status screen, with raw numbers and position
                    (don't display other user data)
</pre>

<pre>
ID:              4.2  
Description:     Resident views the awards page
Preconditions:   Resident is logged in
Postconditions:  User is shown the awards page
Success Flow:
                 1. Resident requests awards history
                 2. System displays awards page; listing medals, prizes, badges and achievements which
                    have been won/possible to win.
</pre>

<pre>
ID:              4.3
Description:     Resident is informed of ways to improve their standing
Preconditions:   Resident is logged in
Postconditions:  User is shown useful suggestions
Success Flow:
                 1. Resident requests suggestions
                 2. System analyzes current room status against optimal settings
                 3. System loads suggestions based on analysis
                 4. System displays suggestions page
</pre>

<pre>
ID:              4.4
Description:     Resident views historical sensor data for their room
Preconditions:   Resident is logged in
                 Manager doesn't terribly mind someone seeing info from before
Postconditions:  User sees graphs of data on the room since the beginning
Success Flow:
                 1. Resident requests room history
                 2. System displays engineering options (restricted to this room)
                 3. System requests aspect of interest (heating/electricity/humidity/co2) and timeframe
                 4. Resident provides settings
                 5. System loads requested data from database (this room only)
                 6. System uses engineering requirement graphs to display data
</pre>

<pre>
ID:              4.5
Description:     Resident views personal historical sensor data for their room
Preconditions:   Resident is logged in
Postconditions:  Resident is shown contrasting graphs that hopefully get better
Success Flow:
                 1. Resident requests personal history
                 2. System displays engineering options (this room and only since date moved in)
                 3. System requests aspect of interest
                 4. Resident provides setting
                 5. System loads requested data from database
                 6. System uses engineering requirements graph to display data
</pre>

<pre>
ID:              4.6
Description:     Resident views/changes occupancy information.
Preconditions:   Resident is logged in.
Postconditions:  Resident sees his/her current occupancy.
                 The current occupancy has changed as specified by the user.
Success flow:
                 1. User clicks preferences.
                 2. System shows the user his/her current expected occupancy.
                 3. User indicates he/she wants to change his/her current occupancy.
                 4. System asks user what his/her actual current occupancy is.
                 5. User indicates what he/she expects the occupancy of his/her apartment will be most of
                    the time.
                 6. System stores the information and displays the updated information.
</pre>

<a name='user-interface'/>
## User Interface
* [See Storyboards](Storyboards)

<a name='software-license'/>
## Software License  
Our team has agreed to the software license we discussed in class, but we have not yet met with stakeholders and had them sign off on it. We will do so at our meeting this week.

<a name='project-glossary'/>
## Project Glossary  
* [BAS/BMS](http://www.kmccontrols.com/products/Understanding_Building_Automation_and_Control_Systems.aspx): Building Automation/Management System
* [HVAC](http://en.wikipedia.org/wiki/HVAC): Heating, Ventilation, and Air Conditioning
* [ERV](http://en.wikipedia.org/wiki/Energy_recovery_ventilation): Energy Recovery Ventilation
* EDH: Electrical Duct Heating
* HWT: Hot Water Tank

<a name='competing-products'/>
## Competing Products
* [Honeywell Attune](https://buildingsolutions.honeywell.com/HBSCDMS/attune/)
* [DGLux](http://www.dglogik.com/dglux/features/overview)
* [Lucid Building Dashboard](http://www.luciddesigngroup.com/products.php)

<a name='other-resources'/>
## Other Resources
* [Client Website (WBHDC)](http://www.wbhadc.ca/)
* [Designing an Energy Consumption Visualization for an End User Home Automation Display](http://hci.rwth-aachen.de/materials/publications/tsoleridis2012.pdf)

<a name='see-also'/>
## See Also
* [[Project Plan]]
* [Requirements Document Specification](https://eclass.srv.ualberta.ca/mod/page/view.php?id=479345)
