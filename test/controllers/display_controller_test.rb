require 'test_helper'

class DisplayControllerTest < ActionDispatch::IntegrationTest
  setup do
    @ad = Ad.find(1)
  end

  # called after every single test
  teardown do
    # when controller is using cache it may be a good idea to reset it afterwards
    Rails.cache.clear
  end

  test "should show image of ad" do
    get "/display/" + @ad.id.to_s
    assert_response :success
  end
end
