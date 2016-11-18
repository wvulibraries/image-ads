class Ad < ApplicationRecord
  has_many :start_end_dates, dependent: :destroy
  has_many :start_end_times, dependent: :destroy

  accepts_nested_attributes_for :start_end_dates, :start_end_times

  validates :image_name, presence: true,
                    length: { minimum: 5 }

  validate :file_size_under_one_mb

  serialize :selected_days, Array

  scope :sorted_priority, -> { order("priority DESC") }
  scope :ads_shown, -> { where(:displayed => true) }

  def initialize(params = {})
    @file = params.delete(:file)
    super
    if @file
      self.filename = sanitize_filename(@file.original_filename)
      self.content_type = @file.content_type
      self.file_contents = @file.read
    end
  end

  def update(params = {})
    file = params.delete(:file)
    if file
      self.filename = sanitize_filename(file.original_filename)
      self.content_type = file.content_type
      self.file_contents = file.read
    end
    super
  end

  def base64_encode
    return Base64.encode64(self.file_contents)
  end

  def checkDayOfWeek
    # checks if there are any days entered in selected_days
    if (self.selected_days.size-1 != 0)
      # if days exist check and see if today is in the list
      d = Date.today
      return self.selected_days.include?(Date::DAYNAMES[d.wday])
    else
      #returns true if no days are selected we assume they want all days
      return true
    end
  end

  def checkTimes
    # if time range is between current time range or no time range is added return true
    # else return false
    if (self.start_end_times.count != 0)
      # check for vaild time
      now = Time.zone.now.strftime('%H:%M')

      self.start_end_times.each do |t|
        if (t.start_time.strftime( "%H:%M" ) <= now) and (t.end_time.strftime( "%H:%M" ) >= now)
          return true
        end
      end
      return false
    else
      #returns true if no time ranges are set we assume they want times
      return true
    end
  end

  def checkDates
    if (self.start_end_dates.count != 0)
      # check for vaild day
      today = Date.today

      self.start_end_dates.each do |d|
        if d.start_date.between?(today, d.end_date)
          return true
        end
      end
      return false
    else
      #returns true if no date ranges are set we assume they want all days
      return true
    end
  end

  def sendToJSON
    if self.checkDayOfWeek and self.checkTimes and self.checkDates
     return true
    else
     return false
    end
  end

  private
    def sanitize_filename(filename)
      # Get only the filename, not the whole path (for IE)
      # Thanks to this article I just found for the tip: http://mattberther.com/2007/10/19/uploading-files-to-a-database-using-rails
      return File.basename(filename)
    end

    def file_size_under_one_mb
      num_bytes = 1048576
      if @file
        if @file.size.to_f > num_bytes
          errors.add(:file, 'File size cannot be over one megabyte.')
        end
      else
        return true
      end
    end

end
