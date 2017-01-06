# StartEnd Date CLASS
# ==================================================
# AUTHORS : Tracy McCormick, David Davis
# Description:
# The data layer for start and end dates.  Start and end dates only affect ads.

class StartEndDate < ApplicationRecord
  belongs_to :ad
  validates_presence_of :ad
  validates :start_date, presence: true
  validates :end_date, presence: true
  validate :dates_not_same

  # hr_dates
  # ==================================================
  # Name : David Davis
  # Date : 11/29/2016
  #
  # Description:
  # Both start_date and end date go to conver date, which takes a date/time string and modifies it to a Month - Day - Year sring.
  # @return (string)

  def hr_start_date
    convert_date(start_date.to_s)
  end

  def hr_end_date
    convert_date(end_date.to_s)
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
    today.between?(start_date, end_date)
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

  def convert_date(datetime)
    date = Time.parse(datetime)
    date.strftime('%m/%d/%Y').strip
  end

  # dates not same
  # ==================================================
  # Name : David Davis
  # Date : 11/29/2016
  #
  # Description:
  # keeps the start and end date from being equal and submitting
  
  def dates_not_same
    if start_date == end_date
      errors.add(:start_date, 'start date can not equal end date')
      errors.add(:end_date, 'end date can not equal start date')
    end
  end
end
