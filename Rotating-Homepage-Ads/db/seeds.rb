# This file should contain all the record creation needed to seed the database with its default values.
# The data can then be loaded with the rails db:seed command (or created alongside the database with db:setup).
#
# Examples:
#
#   movies = Movie.create([{ name: 'Star Wars' }, { name: 'Lord of the Rings' }])
#   Character.create(name: 'Luke', movie: movies.first)

def seed_image(file_name, file_type)
  File.open(File.join(Rails.root, "/app/assets/images/#{file_name}.#{file_type}"))
end

# filename -text
# content_type -text
# file_contents -text
# image_name - text
# displayed  boolean
# priority - int
# alttext - text
# link - text
# selected_days - array ["Days"]

ads = [
  [ "hollow.png", "image/png", seed_image('hollow', 'png'), 'Hollow Test Image', 'yes', 10, 'test image of hollow', 'http://www.google.com', ["", "Monday", "Tuesday"] ],
  [ "test.png", "image/png", seed_image('test', 'png'), 'Test Image', 'yes', 1, 'test image of something else', 'http://www.google.com', [""] ],
]

ads.each do |filename, mime, file_content, image_name, displayed, priority, alt, link, days|
  Ad.create(filename:filename, content_type:mime, file_contents:file_content, image_name: image_name, displayed: displayed, priority: priority, alttext: alt, link:link, selected_days: days)
end


# start and end dates
# start_date
# end_date
# ad_id
start_date = Date.today
end_date = 10.days.from_now

dates = [
  [1, start_date, end_date],
  [2, start_date, end_date]
]
dates.each do | id, st, et|
  StartEndDate.create(start_date: st, end_date: et, ad_id: id)
end
# start and end times
# start_time
# end_time
# ad_id

start_time = Time.now
end_time = Time.now + 10*60*60

times = [
  [1, start_time, end_time],
  [2, start_time, end_time]
]

times.each do | id, st, et|
  StartEndTime.create(start_time: st, end_time: et, ad_id: id)
end
