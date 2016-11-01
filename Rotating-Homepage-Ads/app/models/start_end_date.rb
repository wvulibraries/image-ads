class StartEndDate < ApplicationRecord
  belongs_to :ad
  validates_presence_of :ad

  def hr_start_date
    return convert_date(self.start_date.to_s)
  end

  def hr_end_date
    return convert_date(self.end_date.to_s)
  end

  private
  def convert_date(datetime)
    #time = Time.parse(datetime).in_time_zone('Eastern Time (US & Canada)')
    date = Time.parse(datetime)
    return date.strftime("%m-%d-%Y").strip
  end
end
