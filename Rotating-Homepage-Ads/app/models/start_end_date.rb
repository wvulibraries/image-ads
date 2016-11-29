class StartEndDate < ApplicationRecord
  belongs_to :ad
  validates_presence_of :ad
  validates :start_date, presence: true
  validates :end_date, presence: true

  # hr_dates
  # ==================================================
  # Name : David Davis
  # Date : 11/29/2016
  #
  # Description:
  # Both start_date and end date go to conver date, which takes a date/time string and modifies it to a Month - Day - Year sring.
  # @return (string)

  def hr_start_date
    return convert_date(self.start_date.to_s)
  end

  def hr_end_date
    return convert_date(self.end_date.to_s)
  end

  # check date ranges
  # ==================================================
  # Name : David Davis
  # Date : 11/29/2016
  #
  # Description:
  # Looks to see if today is between ranges and returns a boolean
  # @return (boolean)

  def check_date_ranges
    today = Date.today
    return today.between?(self.start_date, self.end_date)
  end


  # convert_date
  # ==================================================
  # Name : David Davis
  # Date : 11/29/2016
  #
  # @param datetime (string)
  # @return Month-Day-Year (string)
  #
  # Description:
  # Returns a string from the Month Day Year as human readable.
  private
  def convert_date(datetime)
    date = Time.parse(datetime)
    return date.strftime("%m-%d-%Y").strip
  end
end
