$(document).on "turbolinks:load ready", ->
  if $('.datepicker').length
    $('.datepicker').pickadate
      format: 'mmmm dd, yyyy',
      labelMonthNext: 'Next month',
      labelMonthPrev: 'Previous month',
      labelMonthSelect: 'Pick a month from the dropdown',
      labelYearSelect: 'Pick a year from the dropdown',
      selectMonths: true,
      selectYears: true
