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
    return convert_time(self.start_time.to_s)
  end

  # hr_end_time
  # ==================================================
  # Name : David Davis
  # Date : 11/29/2016
  #
  # Description:
  # Uses convert_time method to return a human readable start time
  def hr_end_time
    return convert_time(self.end_time.to_s)
  end

  # check_time_ranges
  # ==================================================
  # Name : Tracy McCormick
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
    now = Time.now.strftime( "%H:%M" )
    start_time = self.start_time.strftime( "%H:%M" )
    end_time =  self.end_time.strftime( "%H:%M" )
    return now.between?(start_time, end_time)
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
  def convert_time(datetime)
    time = Time.parse(datetime)
    return time.strftime("%l:%M %p").strip
  end
end
