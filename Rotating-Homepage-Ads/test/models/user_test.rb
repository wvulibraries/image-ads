require 'test_helper'

class UserTest < ActiveSupport::TestCase
  ## setup the base model used for testing
  def setup
    @user = User.new(
            username: 'jdoe',
            lastname: 'Doe',
            firstname: 'John',
            created_at: Date.today,
            updated_at: 10.days.from_now
          )
  end

  ## write tests
  test "valid user" do
    assert @user.valid?, "user was not valid"
  end

  test "User shouldn't save without a username" do
    @user.username = nil
    assert_not @user.save, "user saved without a username"
  end
end
