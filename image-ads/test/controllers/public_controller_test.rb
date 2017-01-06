require 'test_helper'

class PublicControllerTest < ActionDispatch::IntegrationTest
  test "should get index" do
    get root_url
    assert_response :success
  end

  test "should get login" do
    get "/vagrantlogin"
    assert_redirected_to root_url
  end

  test "should get logout" do
    get "/logout"
    assert_redirected_to root_url
  end

  test "should delete the session for cas user" do
    get "/vagrantlogin"
    assert session['cas']['user'], "something is wrong no user in cas session"

    get "/logout"
    assert_not session['cas'], "something is wrong it didn't delete the session"
  end

  test "should login/logout from url" do
    get "/vagrantlogin"
    assert session['cas']['user'], "something is wrong no user in cas session"

    get "/logout"
    assert_not session['cas'], "something is wrong it didn't delete the session"

    assert_redirected_to root_url

    #assert_equal 'Logged Out!', flash[:notice]
  end

end
