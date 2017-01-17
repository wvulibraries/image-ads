require 'test_helper'

class Admin::StartEndDatesControllerTest < ActionDispatch::IntegrationTest
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

  test "should be able to create new dates" do
    assert_difference('StartEndDate.count') do
      post "/admin/ads/#{@ad.id}/start_end_dates", params: {
          start_end_date: {
            start_date: 1.day.ago,
            end_date: 1.day.from_now
          }
      }
    end

    assert_no_difference('StartEndDate.count') do
      # assume nothing has changed
    end

    assert_redirected_to ad_url(@ad),  "not being redirected the proper ad url"
  end

  test "should destroy date range" do

    assert_difference('StartEndDate.count', -1) do
      delete "/admin/ads/#{@ad.id}/start_end_dates/1"
    end

    assert_no_difference('StartEndDate.count') do
      # assume nothing has changed
    end

    assert_redirected_to ad_url(@ad)
  end

end
