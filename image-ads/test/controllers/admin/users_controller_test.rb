require 'test_helper'

class Admin::UsersControllerTest < ActionDispatch::IntegrationTest
  ## setup the base model used for testing
  def setup
    @user = User.find(1)
    #get '/vagrantlogin'
    CASClient::Frameworks::Rails::Filter.fake(@user.username, {:sn => "Admin", :mail => "username1@nowhere.com"})
  end

  test "should get index" do
    get users_url
    assert_response :success
  end

  test "should get new" do
    get new_user_url
    assert_response :success
  end

  test "should get edit" do
    get edit_user_url(@user)
    assert_response :success
  end

  test "should create user" do
    assert_difference('User.count') do
      post users_url, params: {
                  user: {
                    username: 'jdoe',
                    lastname: 'Doe',
                    firstname: 'John' }
                  }
    end

    assert_redirected_to user_url(User.last)
  end

  test "user shouldn't save without a username" do
    @user.username = nil
    assert_not @user.save, "ad saved without a username"
  end

  test "should update user" do
    patch user_url(@user), params: { user: { firstname: "Jane" } }
    assert_redirected_to user_url(@user), "this did not redirect properly"
    @user.reload
    assert_equal "Jane", @user.firstname, "firstname was not equal for update of firstname"
  end

  # test "should fail update user" do
  #   patch user_url(@user), params: { user: { username: nil } }
  #   assert_equal nil, @user.username, "firstname was not equal for update of firstname"
  #  end

  test "should destroy user" do
    assert_difference('User.count', -1) do
      delete "/admin/users/1"
    end

    assert_no_difference('User.count') do
      # assume nothing has changed
    end

    assert_redirected_to users_url
  end

end
