require 'test_helper'

class AdTest < ActiveSupport::TestCase
  # Returns the Binary for a test image or seed image.
  # only used to get the information for the test and for the basic db seed
  def seed_image(file_name, file_type)
    file = File.open(File.join(Rails.root, "/app/assets/images/#{file_name}.#{file_type}"), 'rb') { |f| f.read }
    return file
  end

  ## setup the base model used for testing
  def setup
    @ad = Ad.new(
            filename: 'test filename',
            content_type: 'image/png',
            file_contents: self.seed_image('hollow', 'png'),
            image_name: 'some image',
            displayed: true,
            priority: 1,
            alttext: "something crazy",
            link: "http://www.google.com",
            selected_days: ['', 'Sunday', 'Monday', 'Tuesday'],
            created_at: Date.today,
            updated_at: 10.days.from_now
          )
  end

  ## write tests
  test "valid ad" do
    assert @ad.valid?, "ad was not valid"
  end

  test "file shouldn't save without a filename" do
    @ad.filename = nil
    assert_not @ad.save, "ad saved without a filename"
  end

  test "ad should not save without a file" do
    @ad.file_contents = nil
    assert_not @ad.save, "ad saved without a file"
  end

  test "ad should not save without a mimetype" do
    @ad.content_type = nil
    assert_not @ad.save, "ad saved without a mime type"
  end

  test "mime type is a valid mime type" do
    @ad.content_type = "image/monkey"
    assert_not @ad.save,  "ad saved not having a valid mime type"
  end

  test "base 64 encoding is properly exporting" do
    ad = Ad.find(1)
    decode_base64_content = Base64.decode64(ad.base64_encode)
    assert_equal decode_base64_content.to_s, "Hello World" , "base 64 encoding isn't equal, message doesn't say hello world"
  end

  # notable caveat, there are always at least 1 empty string in the array
  test "are there days of the week, if not return true" do
    ad = Ad.find(1)
    ad.selected_days = [""] # remove the days
    ad_length = ad.selected_days.length - 1
    assert_equal ad_length, 0, "there are no days but this is not returning equal"
  end

  test "check days of the week function that it equals today" do
    today = Date.today.strftime("%A")
    ad = Ad.find(1)
    ad.selected_days = ["", today]
    assert ad.checkDayOfWeek, "this should come back true because we changed the selected days of the week to be today"
  end

  test "check that tomorrow fails and doesn't return a true for todays selected day of the week" do
    tomorrow = 1.day.from_now.strftime("%A")
    ad = Ad.find(1)
    ad.selected_days = ["", tomorrow]
    assert_not ad.checkDayOfWeek, "this should come back false"
  end
end
