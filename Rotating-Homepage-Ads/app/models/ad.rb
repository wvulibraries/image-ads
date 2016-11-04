class Ad < ApplicationRecord
has_many :start_end_dates, dependent: :destroy
has_many :start_end_times, dependent: :destroy
accepts_nested_attributes_for :start_end_dates, :start_end_times

validates :image_name, presence: true,
                  length: { minimum: 5 }

validate :file_size_under_one_mb

serialize :selected_days, Array

def initialize(params = {})
  @file = params.delete(:image)
  super
  if @file
    self.filename = sanitize_filename(@file.original_filename)
    self.content_type = @file.content_type
    self.file_contents = @file.read
  end
end

def base64_encode
  return Base64.encode64(self.file_contents)
end

def checkDisplayed
  if self.displayed = 'Yes'
   return true
  else
    return false
  end
end

def checkDayOfWeek
  # if day of week matches with current day of week
  return true
  #else  return false
end

def checkTimes
  # if time range is between current time range or no time range is added return true
  # else return false
end

def checkDates
  # if current date is between the date range return true
  # ELSE RETURN FALSE
end

def priorityLevel
  # reorder the array / hash based on priority level
end

def sendToJSON
  # check times, dates, day of week
  # if any are false don't include them in hash
  # send hash to priority
  # return hash to JSON page to parse JSON
end

private
  def sanitize_filename(filename)
    # Get only the filename, not the whole path (for IE)
    # Thanks to this article I just found for the tip: http://mattberther.com/2007/10/19/uploading-files-to-a-database-using-rails
    return File.basename(filename)
  end

  def file_size_under_one_mb
    num_bytes = 1048576
    if (@file.size.to_f / num_bytes) > 1
      errors.add(:file, 'File size cannot be over one megabyte.')
    end
  end

end
