class Ad < ApplicationRecord
  # dependent records of start_end_dates and start_end_times
  # this creates a many-to-many database relationship
  has_many :start_end_dates, dependent: :destroy
  has_many :start_end_times, dependent: :destroy

  accepts_nested_attributes_for :start_end_dates, :start_end_times

  # all the basic validations for this new record to be inserted
  validates :image_name, presence: true, length: { minimum: 5 }
  validates :filename, presence: true
  validates :file_contents, presence:true
  validates :content_type, presence: true, format: { with: /[image\/]+(svg|png|gif|jpg|svg\+xml)/i }
  validate :file_size_under_one_mb

  # putting the selected days into an array in the database
  serialize :selected_days, Array

  # Scope is used in the view to get only ads of with these items
  # example: Ads.ads_shown.sorted_priority
  scope :sorted_priority, -> { order("priority DESC") }
  scope :ads_shown, -> { where(:displayed => true) }


  # initialize
  # ==================================================
  # Name : Tracy McCormick
  # Date : 11/28/2016
  #
  # Description:
  # Overrides the initizle function in ActiveRecord to gather file information the file information is then stored in various instance name which relate to the datbase.
  def initialize(params = {})
    @file = params.delete(:file)
    super
    if @file
      self.filename = sanitize_filename(@file.original_filename)
      self.content_type = @file.content_type
      self.file_contents = @file.read
    end
  end

  # update
  # ==================================================
  # Name : Tracy McCormick
  # Date : 11/28/2016
  #
  # Description:
  # Overrides the update function in ActiveRecord to gather file information the file information
  def update(params = {})
    file = params.delete(:file)
    if file
      self.filename = sanitize_filename(file.original_filename)
      self.content_type = file.content_type
      self.file_contents = file.read
    end
    super
  end

  # base 64 encode
  # ==================================================
  # Name : Tracy McCormick
  # Date : 11/28/2016
  #
  # Description:
  # Returns a base 64 encoded string for displaying the images
  def base64_encode
    return Base64.encode64(self.file_contents)
  end

  # check day of week
  # ==================================================
  # Name : Tracy McCormick
  # Date : 11/28/2016
  #
  # Description:
  # Checks to see if anything in the array, if not assume that it displays on all days
  # Otherwise the day of the week if it matches today will reveal true
  def check_day_of_week
    if (self.selected_days.size-1 != 0)
      d = Date.today
      return self.selected_days.include?(Date::DAYNAMES[d.wday])
    else
      return true
    end
  end

  # check times
  # ==================================================
  # Name : Tracy McCormick
  # Modified By: David Davis
  # Date : 11/29/2016
  #
  # Description:
  # Loops through all the times and sees if today is between the ranges
  def check_times
    if (self.start_end_times.count != 0)
      self.start_end_times.each { |t| return t.check_time_ranges }
    else
      return true
    end
  end

  # check dates
  # ==================================================
  # Name : Tracy McCormick
  # Modified By: David Davis
  # Date : 11/29/2016
  #
  # Description:
  # Loops through all the dates and sees if today is between the ranges
  def check_dates
    if (self.start_end_dates.count != 0)
      self.start_end_dates.each { |d| return d.check_date_ranges }
    else
      return true
    end
  end

  # send to JSON
  # ==================================================
  # Name : Tracy McCormick
  # Modified By: David Davis
  # Date : 11/29/2016
  #
  # Description:
  # uses other methods to check if all values are true, if so the item can move into the JSON array.
  def send_to_JSON
    evaluate_checks = [self.check_times, self.check_dates, self.check_day_of_week]
    return evaluate_checks.all?
  end

  private
    # file size under one mb
    # ==================================================
    # Name : Tracy McCormick
    # Date : 12/01/2016
    #
    # Description:
    # The File.basename function returns the last component of the filename given in filename, which must
    # be formed using forward slashes (“/”) regardless of the separator used on
    # the local file system. If suffix is given and present at the end of
    # filename, it is removed.
    def sanitize_filename(filename)
      return File.basename(filename)
    end

    # file size under one mb
    # ==================================================
    # Name : Tracy McCormick
    # Date : 12/01/2016
    #
    # Description:
    # Checks to see if the image file is less than one meg.
    # if it is over 1 mb the function will send a error message stating this
    # Otherwise it will return true if it is less than 1 mb.
    def file_size_under_one_mb
      num_bytes = 1048576
      if @file && @file.size.to_f > num_bytes
        errors.add(:file, 'File size cannot be over one megabyte.')
      end
      return true
    end
end
