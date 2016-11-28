require 'test_helper'

class AdTest < ActiveSupport::TestCase
  # Returns the Binary for a test image or seed image.
  # only used to get the information for the test and for the basic db seed
  def seed_image(file_name, file_type)
    File.open(File.join(Rails.root, "/app/assets/images/#{file_name}.#{file_type}"))
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


end
