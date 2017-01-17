require 'test_helper'

class AdminControllerTest < ActionDispatch::IntegrationTest
  setup do
    CASClient::Frameworks::Rails::Filter.fake("username1", {:role => "user", :email => "homer@test.foo"})
  end

  # called after every single test
  teardown do
    # when controller is using cache it may be a good idea to reset it afterwards
    Rails.cache.clear
  end

  test "should get index" do
    get admin_url
    assert_redirected_to ads_path
  end

  test "should fail get index" do
    CASClient::Frameworks::Rails::Filter.fake("username")
    get admin_url
    assert_redirected_to root_path
  end
end
