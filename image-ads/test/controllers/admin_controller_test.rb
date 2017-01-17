require 'test_helper'

class AdminControllerTest < ActionDispatch::IntegrationTest
  setup do
    CASClient::Frameworks::Rails::Filter.fake("username1", {:sn => "Admin", :mail => "username1@nowhere.com"})
  end

  # called after every single test
  teardown do
    # when controller is using cache it may be a good idea to reset it afterwards
    Rails.cache.clear
  end

  test "should get index" do
    get admin_url
    assert_response :success
  end

  test "should get logout" do
    get "/logout"
    assert_response :success
  end

  test "should fail get index" do
    CASClient::Frameworks::Rails::Filter.fake("username")
    get admin_url
    assert_response :success
  end
end
