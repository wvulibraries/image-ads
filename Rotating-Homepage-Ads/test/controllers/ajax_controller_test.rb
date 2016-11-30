require 'test_helper'

class AjaxControllerTest < ActionDispatch::IntegrationTest

  test "should get getads" do
    get ajax_getads_url, xhr: true
    assert_response :success
  end

end
