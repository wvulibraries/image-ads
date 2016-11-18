class StartEndTime < ApplicationRecord
  belongs_to :ad
  validates_presence_of :ad
  validates :start_time, presence: true
  validates :end_time, presence: true 

  def hr_start_time
    return convert_time(self.start_time.to_s)
  end

  def hr_end_time
    return convert_time(self.end_time.to_s)
  end

  private
  def convert_time(datetime)
    #time = Time.parse(datetime).in_time_zone('Eastern Time (US & Canada)')
    time = Time.parse(datetime)
    return time.strftime("%l:%M %p").strip
  end
end
