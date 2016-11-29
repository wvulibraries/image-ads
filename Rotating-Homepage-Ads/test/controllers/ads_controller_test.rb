require 'test_helper'

class AdsControllerTest < ActionDispatch::IntegrationTest
  # Returns the Binary for a test image or seed image.
  # only used to get the information for the test and for the basic db seed
  def seed_image(file_name, file_type)
    File.open(File.join(Rails.root, "/app/assets/images/#{file_name}.#{file_type}"))
  end

  setup do
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

  # called after every single test
  teardown do
    # when controller is using cache it may be a good idea to reset it afterwards
    Rails.cache.clear
  end

  test "should get index" do
    get ads_url
    assert_response :success
  end

  test "should get new" do
    get new_ad_url
    assert_response :success
  end

  # test "image upload" do
  #   test_image = "#{Rails.root}/app/assets/images/hollow.png"
  #   file = Rack::Test::UploadedFile.new(test_image, "image/png")
  #   post "/create", :user => { :avatar => file }
  #   assert_response :success
  # end




  test "should create ad" do
    assert_difference('Ad.count') do
      test_image = "#{Rails.root}/app/assets/images/ad-sample.gif"
      test_file = Rack::Test::UploadedFile.new(test_image, "image/gif")
      post ads_url, params: {
                  ad: {
                    file: test_file,
                    image_name: 'some image',
                    displayed: true,
                    priority: 1,
                    alttext: "something crazy",
                    link: "http://www.google.com",
                    selected_days: ['', 'Sunday', 'Monday', 'Tuesday'] }
                  }
    end

    assert_redirected_to ad_url(Ad.last)
  end
  #
  # test "should show ad" do
  #   get ad_url(@ad)
  #   assert_response :success
  # end
  #
  # test "should get edit" do
  #   get edit_ad_url(@ad)
  #   assert_response :success
  # end
  #
  # test "should update ad" do
  #   patch ad_url(@ad), params: { ad: {
  #               alttext: "updated",
  #               updated_at: Date.now } }
  #   assert_redirected_to ad_url(@ad)
  # end
  #
  # test "should destroy ad" do
  #   assert_difference('Ad.count', -1) do
  #     delete ad_url(@ad)
  #   end
  #
  #   assert_redirected_to ads_url
  # end


end
