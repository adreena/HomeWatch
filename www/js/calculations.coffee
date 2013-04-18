###
Calculations page!

Do calculations for engineers or something.

###

require ['jquery', 'underscore', 'vendor/jquery.jdpicker'], ($, _) ->
  "use strict"

  deboog = true
  CALCULATE_URL = if deboog
    '/engineer/samplecalc.json'
  else
    '/engineer/calculate.php'

  window.calculate = ->

    # 'DD' appears to mean 'drop down'

    calcDD = $ "#calculations"
    calculation = calcDD.val()
    calcName = calcDD.children(':selected').text()

    energyDD = $ '#energies'
    energy = energyDD.val()
    energyName = energyDD.children(':selected').text()

    startdate = $('#startdate').val()
    enddate = $('#enddate').val()
    starthour = $('#starthour').val()
    endhour = $('#endhour').val()

    calculateButton = $('#calculateButton')
      .text('calculating...')
      .attr('disbled', true)

    $.getJSON(CALCULATE_URL,
      name: calcName
      energyname: energyName
      calculation: calculation
      energy: energy
      startdate: startdate
      enddate: enddate
      starthour: starthour
      endhour: endhour

    ).done((data) ->
      # Should actually do something a bit more clever like
      # render a template or something...
      resultsdiv = $('#results').html(renderResults data)

    ).fail((data) ->
      alert "Error Doing Calculations: #{data.statusText}"

    ).always ->
      calculateButton.removeAttr 'disabled'
      calculateButton.text 'Calculate'

    false

  ###
  Toggles the display of the energies subtype drop-down.
  ###
  window.showhideEnergiesOnChange = (dropdown) ->

    calcType = $(dropdown).val()
    subDropDown = $ '#energies'

    subDropDown.attr 'disabled', (calcType isnt 'eq1')


  __renderer = _.template "
  <ul>
    <% _.each(results, function (pair) { %>
    <li><strong> <%= pair.key %> </strong> = <%= pair.val %></li>
    <% }) %>
  </ul>
  "

  renderResults = (results) ->
    __renderer
      results: results

  # On document ready... 
  $ ->
    datePickers = $ '#startdate, #enddate'
    
    # jdPicker gives hidden type inputs a full calendar display. 
    datePickers.attr 'type', 'hidden'
    
    # Bind the date selectors with jdPicker. 
    datePickers.jdPicker
      date_format: 'YYYY-mm-dd'
      start_of_week: 0


