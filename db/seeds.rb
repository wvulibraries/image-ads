def seed_image(file_name, file_type)
  file = File.open(File.join(Rails.root, "/app/assets/images/seed_images/#{file_name}.#{file_type}"), 'rb')
  return file.read
end

weekday = Hash.new
weekday[0] = ["", "Tuesday", "Thursday"];
weekday[1] = ["", "Monday", "Wednesday", "Friday"];
weekday[3] = [""];


(1..4).to_a.each do |i|
  link = 'http://www.google.com';
  Ad.create(filename:"file_#{i}.png", content_type:"image/png", file_contents:seed_image( i, 'png'), image_name: "Seed Image #{i}", displayed: true, priority: i, alttext: "Sample Alt Text #{i}", link:link, selected_days: weekday[rand(0..2)])
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


# Create Users
User.create(username: 'djdavis', firstname: 'David', lastname: 'Davis')
User.create(username: 'tam0013', firstname: 'Tracy', lastname: 'McCormick')
