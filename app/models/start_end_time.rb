# StartEndTime CLASS
# ==================================================
# AUTHORS : Tracy McCormick, David Davis
# Description:
# The data layer for start and end times. Only affects ads.  Needs presence of an Ad.

class StartEndTime < ApplicationRecord
  belongs_to :ad
  validates_presence_of :ad
  validates :start_time, presence: true
  validates :end_time, presence: true

  # hr_start_time
  # ==================================================
  # Name : David Davis
  # Date : 11/29/2016
  #
  # Description:
  # Uses convert_time method to return a human readable start time
  #
  # Ex:
  #  Takes a datetime stamp and returns HH:MM AM/PM  [6:00 == 6:00 AM] [14:00 == 2:00 PM]
  def hr_start_time
    convert_time(start_time.to_s)
  end

  # hr_end_time
  # ==================================================
  # Name : David Davis
  # Date : 11/29/2016
  #
  # Description:
  # Uses convert_time method to return a human readable start time
  def hr_end_time
    convert_time(end_time.to_s)
  end

  # check_time_ranges
  # ==================================================
  # Name : Tracy McCormick
  # Modified By: David Davis
  # Date : 11/29/2016
  #
  # @return (boolean)
  #
  # Description:
  # looks to see if the start_time and end time is between the current time
  #
  # Modified By: David Davis 11/29/16
  # removed if condition ot return true or false from bewteen method
  def check_time_ranges
    now = Time.current.strftime('%H:%M')
    s_time = start_time.strftime('%H:%M')
    e_time = end_time.strftime('%H:%M')
    now.between?(s_time, e_time)
  end

  private

  # convert_time
  # ==================================================
  # Name : David Davis
  # Date : 11/29/2016
  #
  # @param datetime (string)
  # @return (string formatted HH:MM AM/PM)
  #
  # Description:
  # Parses the time string into a time object and then modifies the time string to be formatted HH:MM AM/PM
  #
  # Ex:
  #  Takes a datetime stamp and returns HH:MM AM/PM  [6:00 == 6:00 AM] [14:00 == 2:00 PM]

  private

  def convert_time(datetime)
    time = Time.parse(datetime)
    time.strftime('%l:%M %p').strip
  end
end
