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
> 1. [Repair Log](#Repair Log)

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

**Description:** Database access and calculations will be handled by a page completely separate from the front-end pages.

**Rationale:** To facilitate a flexible design, the clients have requested that our data-level protocols be kept separate from the display of these protocols.

**Fit Criterion:** Whenever database access or calculations on this data (i.e., calculation of efficiency coefficients for heat loss) must be performed, this data will be sent from the front-end page to a different page which will do all calculations. This page will perform all necessary calculations and return the data to the front-end of the system, which will be responsible for the display of this data. 

**Customer Satisfaction:** 1		**Customer Dissatisfaction:** 6  
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

**Requirement Name:** Chart/Graph Visualization  
**Requirement #:** 7	**Requirement Type:** 1	**Use Cases:** 2.1, 3.1, 4.4, 4.5

**Description:** Data must be presented in some visual format: i.e. interactive graphs.

**Rationale:** To facilitate a flexible design, the clients have requested that our data-level protocols be kept separate from the display of these protocols.

**Fit Criterion:** Whenever database access or calculations on this data (i.e., calculation of efficiency coefficients for heat loss) must be performed, this data will be sent from the front-end page to the back-end web service through an http request. This service will perform all necessary calculations and return the data to the front-end of the system, which will be responsible for the display of this data.

**Customer Satisfaction:** 1		**Customer Dissatisfaction:** 8  
**Dependencies:** 3			**Conflicts:** None  
**Supporting Materials:** None

---

**Requirement Name:** Weather Data

**Requirement #:** 8 	**Requirement Type:** 5	**Use Cases:**

**Description:** Data is parsed from Environment Canada or a similar website, and presented side-by-side with sensor data.

**Rationale:** Heat loss and heating data is more useful within the context of that day's weather.

**Fit Criterion:** When selecting sensor data and apartments, the user should have the option of displaying the average daily temperature (alternatively, the daily high and daily low temperature) for that day, in addition to the other requested information.

**Customer Satisfaction:** 4		**Customer Dissatisfaction:** 3  
**Dependencies:** 3			**Conflicts:** None  
**Supporting Materials:** None

---

**Requirement Name:** Floor Plan Selection

**Requirement #:** 9  **Requirement Type:** 2	**Use Cases:**

**Description:** A list of floor plans will have to be maintainable, as apartments with new floor plans may be added to the system.

**Rationale:** If this system is extended to include more apartments, the floor plans of these new apartments must be supported.

**Fit Criterion:** The system will allow users to upload new floor plans for use in the system, whether through an upload tool or API.

**Customer Satisfaction:** 3		**Customer Dissatisfaction:** 7
**Dependencies:** 3			**Conflicts:** None  
**Supporting Materials:** None

<a name='user-stories'/>
<a name='User Stories'/>
## User Stories
<table>
  <tr><th>ID</th><th width='100%'>Description</th><th>Priority</th></tr>
  <tr>
    <td>1.1</td>
    <td>As a user, I want to be able to log in to my account.</td>
    <td>Medium</td>
  </tr>
  <tr>
    <td>1.2</td>
    <td>As a resident, I want to be able to register for the service.</td>
    <td>Medium</td>
  </tr>
  <tr>
    <td>1.3</td>
    <td>As a user, I want to be able to edit my account information.</td>
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
  <tr>
    <td>2.6</td>
    <td>As the building manager, I want to be able to register new residents and engineers to the service.</td>
    <td>Medium</td>
  </tr>
  <tr>
    <td>2.7</td>
    <td>As the building manager, I want to be able to specify what I am paying for a certain utility at a certain time.</td>
    <td>High</td>
  </tr>
  <tr>
    <td>2.8</td>
    <td>As the building manager, I want to be able to see a general overview of the status of residents.</td>
    <td>Low</td>
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
  <tr>
    <td>3.21</td>
    <td>As an Engineer, I want to view the values at any specific point on the graph by hovering over that point.</td>
    <td>Medium</td>
  </tr>
  <tr>
    <td>3.22</td>
    <td>As an Engineer, I want to be able to drill down into more specific time slices when viewing a graph.</td>
    <td>High</td>
  </tr>
  <tr>
    <td>3.23</td>
    <td>As an Engineer, I want to be able to drill up into less specific time slices when viewing a graph.</td>
    <td>High</td>
  </tr>
  <tr>
    <td>3.24</td>
    <td>As an Engineer, I want to be able to specify my own forumai to graph.</td>
    <td>High</td>
  </tr>
  <tr>
    <td>3.25</td>
    <td>As an Engineer, I want to be able to graph forumai defined by other engineers.</td>
    <td>Medium</td>
  </tr>
  <tr>
    <td>3.26</td>
    <td>As an Engineer, I want to be able to name the formula I have defined.</td>
    <td>Low</td>
  </tr>
  <tr>
    <td>3.27</td>
    <td>As an Engineer, I want to be able to view certain data trends as a floorplan visualization.</td>
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
    <td>As a resident, I want to be able to view and specify my current occupancy status.</td>
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

<pre>
ID:              1.2
Description:     Resident registers for the service
Preconditions:   Resident has an ID that can is tied to their apartment
Postconditions:  Resident can login to the system (UC1.1)
Success flow:
                 1.  Resident navigates to the SmartHome website.
                 2.  System shows a welcome page that prompts the resident to login or register.
                 3.  Resident selects the register option.
                 4.  System prompts the resident for their ID.
                 5.  Resident enters their ID.
                 6.  System validates the resident ID (may require manual intervention).
                 7.  System prompts the resident to enter a username, password, and email for the new 
                     account
                 8.  Resident enters their information.
                 9.  System validates the information and registers the resident.
                 10. System sends the resident an email to activate their account.
                 11. Resident opens their email, and clicks the link, bringing the resident back to
                     the SmartHome website.
                 12. System prompts the resident to login to their new account (UC1.1).
Exceptions:
                 6a.  Resident enters an invalid ID.
                      1. System indicates that the ID is invalid and logs the attempt.
                 6b.  Resident enters an ID of another resident.
                      1. System indicates that the ID is invalid and flags the attempt for
                         investigation.
                 11a. Resident does not receive an activation email.
                      1. Resident can attempt to register again.
</pre>

<pre>
ID:              1.3
Description:     User edits account information
Preconditions:   User is logged in
Postconditions:  System immediately reflects the user's new information
Success flow:
                 1. User navigates to the edit account information page.
                 2. User edits their existing account information and saves the changes.
                 3. System validates the changes.
                 4. System indicates that the changes have been saved.
Alternative flows:
                 2a. User cancels editing their changes by pressing a cancel button.
                     1. System cancels any changes and indicates that no changes have been made.
                 2b. User cancels editing their changes by navigating away from the page.
                     1. System cancels the changes.
Exceptions:
                 3a. User attempts to make invalid changes.
                     1. System rejects the changes, and indicates what is wrong.
</pre>

<a name="uc-building-manager" />
##### Building Manager
<pre>
ID:              2.1
Description:     Building manager requests visualization of utility usage data
Preconditions:   User is logged in as a Building Manager
Postconditions:  Building Manager sees visualization of results of selected query
Success Flow:
                 1. System displays all utility options
                 2. Building manager selects utilities for visualization
                 3. Building manager selects apartments of interest
                 4. Building manager selects time frame of interest
                 5. Building manager selects desired form of visualization
                 6. Building manager requests results
                 7. System client validates page
                 8. System displays consumption data of selected utilities for 
                    selected apartments over selected period in desired format

Variations:      2a. 
                    1. Electrical energy consumption (kWh)
                    2. Heating water energy consumption (Wh)
                    3. Total water consumption (gallons)
                    4. Hot water consumption (gallons)

                 3a.
                    1. Unit A
                    2. Unit B
                    3. Unit C
                    4. Unit D
                    5. Unit E

                 4a.
                    1. Single day
                    2. Single week
                    3. Singe month
                    4. Single year
                    5. Multiple days
                    6. Multiple weeks
                    5. Multiple months
                    8. Multiple years

                 5a.
                    1. Plain text
                    2. Histogram
                    3. Pie chart
                    4. Line graph 

Exceptions:      7a. System client validation detects problem
                     1. System informs building manager of invalid input
                 8a. System fails to execute query
                     1. System provides explanation for why query failed
                     2. System displays failure information to building manager                   
</pre>

<pre>
ID:              2.2
Description:     Building manager generates report of utility usage data
Preconditions:   User is logged in as a Building Manager
Postconditions:  Building manager receives compiled usage data as report
Success Flow:
                 1. System displays all utility options
                 2. Building manager selects generate report
                 3. System displays utility, apartment, time period and report type selectors
                 4. Building manager selects desired utilities for inclusion in report
                 5. Building manager selects desired apartments for inclusion in report
                 6. Building manager selects desired time period of report
                 7. Building manager selects format of report
                 8. Building manager requests report generation
                 9. System client validates page
                10. System compiles usage data and generates report in selected form
                11. Building manager selects output method
                12. System outputs report

Variations:     4a. Same as for use case 2.1(2a)
                5a. Same as for use case 2.1(3a)
                6a. Same as for use case 2.1(4a)
                7a. Same as for use case 2.1(5a)
               10a. 
                   1. Print report
                   2. Export report 

Exceptions:     9a. Same as for 2.1(7a)
                10a. Same as for 2.1(8a)
</pre>

<pre>
ID:              2.3
Description:     Building manager views utility cost data
Preconditions:   User is logged in as a Building Manager
Postconditions:  Building manager sees visualization of monetary cost of consumption
Success Flow:
                 1. System displays all utility options
                 2. Building manager selects cost analysis
                 3. System displays utility, apartment and time period selectors
                 4. Building manager selects utilities of interest
                 5. Building manager selects apartments of interest
                 6. Building manager selects time period of interest
                 7. Building manager requests financial analysis
                 8. System client validates page
                 9. System compiles and displays cost analysis for given utilities in 
                    given apartments over given time period

Variations:     4a. Same as for use case 2.1(2a)
                5a. Same as for use case 2.1(3a)
                6a. Same as for use case 2.1(4a)

Exceptions:     8a. Same as for 2.1(7a)
                9a. Same as for 2.1(8a)
</pre>

<pre>
ID:              2.4
Description:     Building manager registers new user
Preconditions:   User is logged in as a Building Manager
Postconditions:  A new user is uploaded to the database for loging in
Success Flow:
                 1. Manager enters Register User page
                 2. Page requests necessary data fields
                 3. Manager supplies required data fields
                 4. Manager submits registration request
                 5. Page validates data fields
                 6. Page sends registration request to database
                 7. Database responds with success or failure
                 8. Page notifies Manager with result

Exceptions:     Manager enters username or email address that is registered to another user
				Manager enters invalid data (password too short or email wihout at symbol)
				Database fails to respond
</pre>

<pre>
ID:              2.4
Description:     Building manager adds a new contract
Preconditions:   User is logged in as a Building Manager
Postconditions:  A new contract is available for cost calculations
Success Flow:
                 1. Manager enters Add Contract page
                 2. Page requests necessary data fields
                 3. Manager supplies required data fields
                 4. Manager submits registration request
                 5. Page validates data fields
                 6. Page sends registration request to database
                 7. Database responds with success or failure
                 8. Page notifies Manager with result

Exceptions:     Database fails to respond
				There already exists a contract for the specified utility over the specified date
</pre>

<pre>
ID:              2.4
Description:     Building manager views an overview of resident data
Preconditions:   User is logged in as a Building Manager
Postconditions:  A table containing relevent data is presented to the Manager
Success Flow:
                 1. Manager enters Resident View page
                 2. Page submits request to the database
                 3. Database responds with the relevent data
                 4. Page creates a table out of the data
                 5. Page presents table to the Manager

Exceptions:     Database fails to respond
				There already exists a contract for the specified utility over the specified date
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
                 1. Engineer selects the metrics they want visualize
                 2. Engineer selects a method of visualization
                 3. Engineer chooses to group the results
                 4. Engineer enters the time granularity they want to see the results
                 5. Engineer enters the date range for the results (default month)
                 6. Engineer requests the results
                 7. System client validates page
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
					 11. User defined function
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
                 7a. System client validation detects problem
                     1. System informs engineer of invalid input
                 8a. System fails to execute query
                     1. System provides explanation for why query failed
                     2. System displays failure information to engineer
</pre>

<pre>
ID:              3.2
Description:     Engineer requests to generate a text report of building data for set of apartment groups over a given time
                 range and granularity. 
Preconditions:   User is logged in as an Engineer
Postconditions:  Engineer sees results in plain text
Success Flow:
                 1. Engineer selects the metrics they want visualize
                 2. Engineer chooses to group the results
                 3. Engineer enters the time granularity they want to see the results
                 4. Engineer enters the date range for the results (default month)
                 5. Engineer requests the results
                 6. System client validates page
                 7. System returns results to the client based on the given query
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
					 11. User defined function
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
                 6a. System client validation detects problem
                     1. System informs engineer of invalid input
                 7a. System fails to execute query
                     1. System provides explanation for why query failed
                     2. System displays failure information to engineer
</pre>

<pre>
ID:              3.3
Description:     Engineer drills down to a more precise time slice.
Preconditions:   User is logged in as an Engineer
Postconditions:  Engineer sees a graph with a lower level of time separation.
Success Flow:
                 1. Engineer generates a graph
                 2. Engineer clicks a data point within the graph
                 3. Page redoes the calculations required to display the graph at a lower time separation
                 4. Page displays the new graph
Variations:
                 Years->Months
				 Months->Weeks
				 Weeks->Days
				 Days->Hours
Exceptions:
				Cannot drill down from hours
</pre>

<pre>
ID:              3.4
Description:     Engineer drills up to a less precise time slice.
Preconditions:   User is logged in as an Engineer
Postconditions:  Engineer sees a graph with a lower level of time separation.
Success Flow:
                 1. Engineer generates a graph
                 2. Engineer clicks a drill up option
                 3. Page redoes the calculations required to display the graph at a higher time separation
                 4. Page displays the new graph
Variations:
                 Hours->Days
				 Days->Weeks
				 Weeks->Months
				 Months->Years
Exceptions:
				Cannot drill up from years
</pre>

<pre>
ID:              3.5
Description:     Engineer registers a formula.
Preconditions:   User is logged in as an Engineer
Postconditions:  Engineer can select the new formula to graph.
Success Flow:
                 1. Engineer locates the Engineer config file
                 2. Engineer editsthe file, adding a line matching their formula to the required syntax
                 3. Engineer saves their edit
                 4. Service detects the change and commits it
				 5. Engineer goes to graphing page
				 6. Page detects a list of formulai
				 7. Page displays the list along with the other options for the Engineer
				 8. Engineer selects their formula for the data field they intend
				 9. Page uses the formula rather than the raw data along that axis
Exceptions:
				The formula does not match the syntax
				The formula is inteded to work in some way other than as an axis
</pre>

<a name="uc-resident" />
##### Resident
<pre>
ID:              4.1
Description:     Resident views their stats page
Preconditions:   Resident is logged in
Postconditions:  Resident is shown current stats page
Success Flow:
                 1. Resident navigates to stats page.
                 2. System loads resident's latest room data
                 3. System loads latest data for other rooms
                 4. System determines residents ranking
                 5. System displays resident's current room status screen, with raw numbers and position
                    (don't display other user data)
</pre>

<pre>
ID:              4.2  
Description:     Resident views the awards page
Preconditions:   Resident is logged in
Postconditions:  Resident is shown the awards page
Success Flow:
                 1. Resident requests awards history
                 2. System displays awards page; listing medals, prizes, badges and achievements which
                    have been won/possible to win.
</pre>

<pre>
ID:              4.3
Description:     Resident is informed of ways to improve their standing
Preconditions:   Resident is logged in
Postconditions:  Resident is shown useful suggestions
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
Postconditions:  Resident sees graphs of data on the room since the beginning
Success Flow:
                 1. Resident requests room history
                 2. System displays engineering options (restricted to this room)
                 3. System requests aspect of interest (restricted for privacy of previous tennant) and timeframe (restricted for privacy of previous tennant)
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
                 The current occupancy has changed as specified by the resident.
Success flow:
                 1. Resident clicks preferences.
                 2. System shows the resident his/her current expected occupancy.
                 3. Resident indicates he/she wants to change his/her current occupancy.
                 4. System asks residetwhat his/her actual current occupancy is.
                 5. Resident indicates what he/she expects the occupancy of his/her apartment will be most 
                    of the time.
                 6. System stores the information and displays the updated information.
</pre>

##### Overall Data Access
![](http://yuml.me/e6910974)
<!-- EDIT HERE: http://yuml.me/edit/e6910974 -->

<a name='User Interface'/>
## User Interface
* [See Storyboards](Storyboards)

<a name='Software License'/>
## Software License  
Our team has agreed to the software license we discussed in class, but we have not yet met with stakeholders and had them sign off on it. We will do so at our meeting this week.

<a name='Project Glossary'/>
## Project Glossary  
* **[WBHDC]:** The client, the Wood Buffalo Housing and Development Corporation.

**Building Terms**
* **Building:** The [Stony Mountain Plaza](http://www.wbhadc.ca/rg_stonymountain.html) owned by the [WBHDC].
* **Unit:** A rental unit in the building where a resident lives.
* **Stack:** Apartment units with the same floor plan that exist at the same location on each floor of the building.
* **Utility:** Any of electricity, hot/cold water, or natural gas.
* **Electricity:** Electrical power drawn from solar panels and geothermal fields, as well as from the main power lines.
* **Hot Water:** Hot water produced by geothermal fields or natural gas boilers.
* **Geothermal Field:** A set of pipes underneath the building collecting heat energy from the earth.
* **[BAS/BMS](http://www.kmccontrols.com/products/Understanding_Building_Automation_and_Control_Systems.aspx):** Building Automation/Management System. The building has an existing BMS that provides lower-level access to sensor data. Our application provides a more accessible front-end to this system.
* **[HVAC](http://en.wikipedia.org/wiki/HVAC):** Heating, Ventilation, and Air Conditioning
* **[ERV](http://en.wikipedia.org/wiki/Energy_recovery_ventilation):** Energy Recovery Ventilation
* **EDH:** Electrical Duct Heating
* **HWT:** Hot Water Tank

**Actors**
* **Building Manager:** A manager at the [Stony Mountain Plaza] interested in using aggregate sensor data to analyze financial aspects of the building.
* **Resident:** A resident at the [Stony Mountain Plaza] interested in using sensor data to reduce their bills and environmental impact.
* **Engineer:** A construction or research engineer.
* **Research engineer:** A privileged user interested in using sensor data to evaluate sustainability issues (ex. analyzing the sustainability of the building's solar/geothermal/natgas energy setup.) 
* **Construction Engineer:** A privileged user interested in analyzing sensor data to improve the physical design of the building (ex. wall thickness, insulation, and stud placement).
* **Privileged user:** A user with access to all sensor data.

**Resident Terms**
* **Achievement:** A sustainability goal achievable by residents to improve their score and flaunt status.
* **Sustainability Goal:** A goal based on reducing the consumption of utilities indicated by sensor data.
* **Score:** The total number of points a resident has accumulated based on their utility usage over a certain time period.
* **Scoreboard (ladder):** A webpage that shows the total score of every resident (by a chosen username) in the building.
* **Occupancy:** the status of the resident as being on vacation or presently occupying the unit.

**Graphing Terms**
* **Hovering:** to rest the cursor on top of an element of the webpage. Generally used to bring up additional information relating to that element.
* **Drill Up:** to request less specific information. Generally used to indicate using larger periods of time to average data.
* **Drill Down:** to request more specific information. Genearlly used to indicate using shorter periods of time to average data.

<a name='Competing Products'/>
## Competing Products

####[Honeywell Attune](https://buildingsolutions.honeywell.com/HBSCDMS/attune/)
**Overview**  
Honeywell Attune offers services to help building managers improve the efficiency of their buildings. Attune provides an [Energy Awareness Dashboard](https://buildingsolutions.honeywell.com/HBSCDMS/attune/css/images/popup-image02.jpg) that gives building managers easy access to information collected from their buildings, such as utility usage and cost. Attune also provides benchmarks, recommendations, and diagnostics for analyzing the sustainability and financial cost of the building.

**Analysis**  
Attune appears to be geared towards building managers, providing only aggregate information about the building. It does not appear to provide access to data on a room by room basis, and does not seem to support multiple types of users. The benchmarks, recommendations, and diagnostics it provide are interesting, but beyond the scope of our project.
***
####[DGLux](http://www.dglogik.com/dglux/features/overview)
**Overview**  
> DGLux is a "drag & drop" visualization platform that enables you to design real-time, data-driven applications and dashboards without ever writing a single line of code.

**Analysis**  
DGLux is solely a visualization platform. Its method of binding data to widgets appears very flexible. It is even possible to [visualize data on an image of a floorplan](http://www.dglogik.com/dgnews/company-news/117-zone-temperature-overlays-tutorial). DGLux works on mobile platforms as well. If it were possible, it would work great as a visualization component inside of our web application.

***
####[Lucid Building Dashboard](http://www.luciddesigngroup.com/products.php)
**Overview**  
Lucid Building Dashboard is used in many large buildings across North America (see http://www.luciddesigngroup.com/customers.php). The dashboard provides graphs and breakdowns of utility usage and cost by floor and end use. It allows users to compare their usage information with other users in the Dashboard Network, and is integrated with social networking sites. Lucid keeps their user base involved by hosting competitions and awarding medals. Lucid also provides a "Kiosk" version of the dashboard that can be used in public places.

**Analysis**  
With there large user base, there is no doubt they are doing something right. While the dashboard looks perfect for our building manager and resident users, it does not appear flexible enough to handle our engineer requirements, as the visualizations it provides are too basic. Nonetheless, we should take some notes from their design for our resident pages.

<a name='Other Resources'/>
## Other Resources
* [Client Website (WBHDC)](http://www.wbhadc.ca/)
* [Designing an Energy Consumption Visualization for an End User Home Automation Display](http://hci.rwth-aachen.de/materials/publications/tsoleridis2012.pdf)

<a name='See Also'/>
## See Also
* [[Project Plan]]
* [Requirements Document Specification](https://eclass.srv.ualberta.ca/mod/page/view.php?id=479345)

<a name='Repair Log'/>
## Repair Log
* Added use cases for registering residents and editing account information
* Clarified use case actors
* Improved glossary
* Added information about competing products
* Added a general use case diagram for overall data access
* Clarified each of the storyboards for residents
* Removed requirement for "multiple apartment search" as it was confusing and unnecessary
* Clarified requirement for data processing separation from front-end system
* Added requirement for floor plan selection 
* Added captions to storyboards.
* Changed storyboard table headers from "data" to more plausible titles.

[WBHDC]: http://www.wbhadc.ca/
[Stony Mountain Plaza]: http://www.wbhadc.ca/rg_stonymountain.html
