require 'test_helper'

class Admin::StartEndTimesControllerTest < ActionDispatch::IntegrationTest
  ## setup the base model used for testing
  def setup
    @ad = Ad.find(1)
    #get '/vagrantlogin'
    CASClient::Frameworks::Rails::Filter.fake("username1", {:sn => "Admin", :mail => "username1@nowhere.com"})
  end

  # called after every single test
  teardown do
    # when controller is using cache it may be a good idea to reset it afterwards
    Rails.cache.clear
  end

  test "should be able to create new times" do
    assert_difference('StartEndTime.count') do
      post "/admin/ads/#{@ad.id}/start_end_times", params: {
          start_end_time: {
            start_time: Time.now.midnight.strftime("%l:%M %p").strip,
            end_time: Time.now.beginning_of_day.strftime("%l:%M %p").strip
          }
      }
    end

    assert_no_difference('StartEndTime.count') do
      # assume nothing has changed
    end

    assert_redirected_to ad_url(@ad),  "not being redirected the proper ad url"
  end

  test "should destroy time range" do

    assert_difference('StartEndTime.count', -1) do
      delete "/admin/ads/#{@ad.id}/start_end_times/1"
    end

    assert_no_difference('StartEndTime.count') do
      # assume nothing has changed
    end

    assert_redirected_to ad_url(@ad)
  end

end
