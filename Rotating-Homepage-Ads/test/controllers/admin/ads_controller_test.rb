require 'test_helper'

class Admin::AdsControllerTest < ActionDispatch::IntegrationTest
  include CasHelper

  setup do
    @ad = Ad.find(1)
    set_current_user('vagrant')
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

  test "should show ad" do
    get ad_url(@ad)
    assert_response :success
  end

  test "should get edit" do
    get edit_ad_url(@ad)
    assert_response :success
  end

  test "should create ad" do
    assert_difference('Ad.count') do
      test_image = "#{Rails.root}/app/assets/images/hollow.png"
      test_file = Rack::Test::UploadedFile.new(test_image, "image/png")
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


  test "should update ad" do
    patch ad_url(@ad), params: { ad: { alttext: "updated" } }
    assert_redirected_to ad_url(@ad), "this did not redirect properly"
    @ad.reload
    assert_equal "updated", @ad.alttext, "message was not equal for update message"
  end

  test "should destroy ad" do
    assert_difference('Ad.count', -1) do
      delete ad_url(@ad)
    end

    assert_redirected_to ads_url
  end

end
