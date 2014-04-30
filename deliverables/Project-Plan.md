## Introduction

**Preliminary Requirements:**  
See [[Requirements Document]]

**Technical issues:**  
We need to set up a LAMP server VM to run our web application. We need to have an HTTP web service running to receive queries from our application and send data in response to these queries. We need to handle the large data set in our MySQL database. We will need to do extensive testing of our queries as we are taking a modular approach to search and display on our database. Deployment should not be too complicated beyond setting up the web service. We need to parse Environment Canada or somewhere similar to provide weather data for the system and run this script daily.

**Personnel issues:**  
Two members of our group have never worked with HTML/PHP/JavaScript and one member is not overly familiar with these languages. The floor engineers have not been available so far and have sent us no requirements so as yet they are not included in the project. The “super-client”, that is the building manager for whom we are developing this project (with Ioannis as the intermediary and our official client) has not provided us any requirements so we are already working from a second-hand requirement analysis.

**Resources required:**  
All tools (servers, data sets) have already been provided for us. Engineering formulas for efficiency calculations and the like are still missing and need to be provided by our engineering clients, else they will not be implemented. It is not feasible at this time to do a man-hours estimate but it is likely that we will need a bare minimum of 6 hours/week/person or 42 manhours/week to complete this project on time. The actual number is likely to be around twice that.

**Dependencies:**  
Dependencies between requirements are covered in our requirements document. At this time all dependencies outside of requirements have been met (i.e. we have everything we need to begin working on our requirements). Testing will obviously have to wait until requirements are completed.

**Risks**  
Requirements are fairly flexible which gives us a lot of rope with which to hang ourselves. UI design in particular lacks structure at this time. There are always risks of code loss, personnel loss, and so forth but if these occur they are unavoidable. Risk mitigation at this point consists of trusting in backups for GitHub, ensuring communication between the team members to make sure everyone knows what is being worked on by who, etc.

**Task Breakdown**  
Please take a look at issues and milestones for our current task assignments.

## Risk Assessment

<table>
  <tr>
    <th>Risk</th>
    <th>Impact</th>
    <th>Likelihood</th>
    <th>Indicators</th>
    <th>Mitigation/Contingency Plan</th>
  </tr>

  <tr>
    <td>Unknown construction engineering requirements</td>
    <td>Medium</td>
    <td>Medium</td>
    <td></td>
    <td>We need to meet with the construction engineers soon (next week) to elicit requirements.</td>
  </tr>

  <tr>
    <td>Haven't met building management</td>
    <td>Medium</td>
    <td>Medium</td>
    <td></td>
    <td>At minimum, the building management should give us feedback on our project plan.</td>
  </tr>

  <tr>
    <td>Slipping deadlines</td>
    <td>Medium</td>
    <td>Medium</td>
    <td>Low velocity, missing or buggy functionality</td>
    <td>
      <ul>
        <li>Meet at least once a week</li>
        <li>Balance workload</li>
        <li>Use instant messaging. Share phone numbers.</li>
        <li>Talk openly and honestly to clear obstacles and anxiety</li>
        <li>Accommodate team member commitments to other course work</li>
      </ul>
    </td>
  </tr>

  <tr>
    <td>Browser compatibility</td>
    <td>Medium</td>
    <td>Medium</td>
    <td>Browser compatbility tests fail</td>
    <td>
      <ul>
        <li>Consider using something like PhoneGap that takes care of this</li>
        <li>Use <a href="http://www.1stwebdesigner.com/design/tools-browser-compatibility-check/">automated compatibility tests</a></li>
        <li>Run tests with every commit</li>
      </ul>
    </td>
  </tr>

  <tr>
    <td>Floorplan visualization</td>
    <td>Medium</td>
    <td>Medium</td>
    <td></td>
    <td>We need to find a library that allows us to do this in a browser.</td>
  </tr>

  <tr>
    <td>Size of dataset</td>
    <td>Medium</td>
    <td>Medium</td>
    <td>Queries take too long</td>
    <td>Test some intensive queries on an expanded dataset so that we know whether we need to look into something like Hadoop.</td>
  </tr>

  <tr>
    <td>Web development expertise</td>
    <td>Medium</td>
    <td>Medium</td>
    <td>Poor quality, non-functioning code; browser incompatibilities</td>
    <td>
      <ul>
        <li>Have knowledgeable members write tips and guidelines pages</li>
        <li>Do code reviews</li>
      </ul>
    </td>
  </tr>

  <tr>
    <td>Accessing utility cost data</td>
    <td>Medium</td>
    <td>Medium</td>
    <td></td>
    <td>We need to speak with the building management about this. If we can't get access to this data on the web, then we will have to provide a way for the building management to enter it.</td>
  </tr>

  <tr>
    <td>LAMP server management problems</td>
    <td>Low</td>
    <td>Low</td>
    <td></td>
    <td>Have multiple people verify large changes. Consider consulting someone more knowledgeable.</td>
  </tr>

  <tr>
    <td>Accessing weather data (from Environment Canada)</td>
    <td>Low</td>
    <td>Low</td>
    <td></td>
    <td>If we can't find a way to parse weather data into our system, then our system will not be able to display weather data side-by-side with other information, and that requirement will be abandoned.</td>
  </tr>

</table>

## Project Macro-Structure
We will be following the Scrum development methodology for this project. Project iterations have been divided into two-week sprints, each sprint ending in a working increment of the product. We will not be able to do daily standup meetings, but the team is encouraged to talk about their issues over email, GitHub, and instant messaging. Progress will be reviewed at the Friday meeting each week, and monitored using burn-down charts and issue velocity. **(We need to set this up on our server, since GitHub doesn't provide it out of the box.)** 

We have _tentatively_ divided the team into the following roles:
 * Project Lead: Devin
 * Database Lead: Ahmed
 * Front-End Lead: Eddie
 * Management Requirements: Brent
 * Engineering Requirements: Steven
 * Tenant Requirements: Seth, Tsung
 * Testing: Devin, Tsung _(402 members)_

These roles developed naturally based on expressed interest, past experience, and what work each member has contributed so far. The lead programmers in each section are responsible for providing direction and maintaining consistency and standards. We would still like to be as egoless as possible, dividing work fairly, and promoting honest reviews of code.

## Major Phases and Milestones
For more details, take a look at our milestones here on github.
 1. Monday, February 11th – Requirements document finished. Begin coding
 * Monday, February 18h – Must have met with client by this time to discuss requirements document.
 * Friday, February 24th – UI design (first pass) complete. All database-related methods in place. Basic functionality using small test data set in place for single apartment search; report view.
 * Friday, March 8th – UI design (second pass) complete. Larger data set in place. Graph views in place. 
 * Friday, March 22nd – Apartment view implemented. Feature freeze. Testing and polishing until release.
 * Monday, April 1st – Code submission and acceptance test and user manual due.
 * Monday, April 8th – Complete Documentation due
 * Wednesday, April 10th - Demos

##### Sprint 1: Monday, February 11 -- Sunday, February 24
<table>
  <tr>
    <th>Activity</th>
    <th colspan="3">Planned/Estimated</th>
    <th colspan="3">Actual</th>
  </tr>
  <tr>
    <td></td>
    <td>Start Date</td> <td>End Date</td> <td>Effort (person hours)</td>
    <td>Start Date</td> <td>End Date</td> <td>Effort (person hours)</td>
  </tr>
  <tr>
    <td>Client Meeting - Requirements Document</td>
    <td>Undetermined</td> <td>February 18th</td> <td>8</td>
    <td></td> <td></td> <td></td>
  </tr>
  <tr>
    <td>Requirements Document - Review & Revision</td>
    <td>Undetermined</td> <td>February 21st</td> <td>4</td>
    <td></td> <td></td> <td></td>
  </tr>
  <tr>
    <td>UI Mockups - First Pass</td>
    <td>February 11th</td> <td>February 18th</td> <td>16</td>
    <td></td> <td></td> <td></td>
  </tr>
  <tr>
    <td>Database Connection Methods & System</td>
    <td>February 11th</td> <td>February 18th</td> <td>8</td>
    <td></td> <td></td> <td></td>
  </tr>
  <tr>
    <td>Test Data Set in Place</td>
    <td>February 11th</td> <td>February 18th</td> <td>4</td>
    <td></td> <td></td> <td></td>
  </tr>
  <tr>
    <td>Single Apartment Search</td>
    <td>February 18th</td> <td>February 24th</td> <td>4</td>
    <td></td> <td></td> <td></td>
  </tr>
  <tr>
    <td>Report View</td>
    <td>February 18th</td> <td>February 24th</td> <td>3</td>
    <td></td> <td></td> <td></td>
  </tr>
</table>
---
##### Sprint 2: Monday, February 25 -- Sunday, March 10
<table>
  <tr>
    <th>Activity</th>
    <th colspan="3">Planned/Estimated</th>
    <th colspan="3">Actual</th>
  </tr>
  <tr>
    <td></td>
    <td>Start Date</td> <td>End Date</td> <td>Effort (person hours)</td>
    <td>Start Date</td> <td>End Date</td> <td>Effort (person hours)</td>
  </tr>
  <tr>
    <td>UI Design - Second Pass</td>
    <td>February 24th</td> <td>March 10th</td> <td>12</td>
    <td></td> <td></td> <td></td>
  </tr>
  <tr>
    <td>Real Data (Parsing, Normalization)</td>
    <td>February 24th</td> <td>March 1st</td> <td>8</td>
    <td></td> <td></td> <td></td>
  </tr>
  <tr>
    <td>Graph Views</td>
    <td>March 1st</td> <td>March 10th</td> <td>6</td>
    <td></td> <td></td> <td></td>
  </tr>
</table>
---
##### Sprint 3: Monday, March 11 -- Sunday, March 24
<table>
  <tr>
    <th>Activity</th>
    <th colspan="3">Planned/Estimated</th>
    <th colspan="3">Actual</th>
  </tr>
  <tr>
    <td>Activity</td>
    <td>Start Date</td> <td>End Date</td> <td>Effort (person hours)</td>
    <td>Start Date</td> <td>End Date</td> <td>Effort (person hours)</td>
  </tr>
  <tr>
    <td>HTTP Web Service / Socket Connections</td>
    <td>March 11th</td> <td>March 24th</td> <td>12</td>
    <td></td> <td></td> <td></td>
  </tr>
  <tr>
    <td>Apartment View</td>
    <td>March 11th</td> <td>March 24th</td> <td>15</td>
    <td></td> <td></td> <td></td>
  </tr>
  <tr>
    <td>Weather Data Parsing</td>
    <td>March 11th</td> <td>March 24th</td> <td>15</td>
    <td></td> <td></td> <td></td>
  </tr>
</table>
---
##### Sprint 4: Monday, March 25 -- Wednesday, April 10
<table>
  <tr>
    <th>Activity</th>
    <th colspan="3">Planned/Estimated</th>
    <th colspan="3">Actual</th>
  </tr>
  <tr>
    <td></td>
    <td>Start Date</td> <td>End Date</td> <td>Effort (person hours)</td>
    <td>Start Date</td> <td>End Date</td> <td>Effort (person hours)</td>
  </tr>
  <tr>
    <td>Testing and Polishing</td>
    <td>March 25th</td> <td>April 10th</td> <td>20</td>
    <td></td> <td></td> <td></td>
  </tr>
  <tr>
    <td>Acceptance Test</td>
    <td>March 25th</td> <td>April 10th</td> <td>20</td>
    <td></td> <td></td> <td></td>
  </tr>
  <tr>
    <td>User Manual</td>
    <td>March 25th</td> <td>April 10th</td> <td>10</td>
    <td></td> <td></td> <td></td>
  </tr>
  <tr>
    <td>Complete Documentation </td>
    <td>March 25th</td> <td>April 10th</td> <td>10</td>
    <td></td> <td></td> <td></td>
  </tr>
</table>

## Personal Logs
* [[Ahmed|Ahmed Weekly]]
* [[Brent|Brent's Dear Diary]]
* [[Devin|hanchar]]
* [[Eddie|Eddie's Dev Diary]]
* [[Seth|Seth's Personal Wiki]]
* [[Steven|Smaschmeyer Weekly]]
* [[Tsung|Tsung's Personal Wiki]]

## Resource Usage

<table>
  <tr> <th> Activity </th> <th colspan="7"> Actual Effort (person hours) </th> </tr>
  <tr>
    <td></td>
    <td>Ahmed</td><td>Brent</td><td>Devin</td><td>Eddie</td><td>Seth</td><td>Steven</td><td>Tsung</td>
  </tr>
  <tr>
    <td></td>
    <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td>
  </tr>
  <tr>
    <td>Total</td>
    <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td>
  </tr>
  <tr>
    <td>Average Per Week</td>
    <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td>
  </tr>
</table>

## See Also
* [Project Plan Specification](https://eclass.srv.ualberta.ca/mod/page/view.php?id=479346)
* [[Requirements Document]]