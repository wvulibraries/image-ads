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

private
  def sanitize_filename(filename)
    # Get only the filename, not the whole path (for IE)
    # Thanks to this article I just found for the tip: http://mattberther.com/2007/10/19/uploading-files-to-a-database-using-rails
    return File.basename(filename)
  end

  NUM_BYTES_IN_MEGABYTE = 1048576
  def file_size_under_one_mb
    if (@file.size.to_f / NUM_BYTES_IN_MEGABYTE) > 1
      errors.add(:file, 'File size cannot be over one megabyte.')
    end
  end
end
