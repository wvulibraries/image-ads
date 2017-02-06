$(document).on "turbolinks:load ready", ->
  # If the timepicker exsists, fire the function
  # Makes the class run on all pages that would need it
  if $('.timepicker').length
    $('.timepicker').pickatime
      interval: 60
      format: 'h:i A'
