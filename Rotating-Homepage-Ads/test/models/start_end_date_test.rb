require 'test_helper'

class StartEndDateTest < ActiveSupport::TestCase
  ## setup the base model used for testing
  def setup
    @startEndDate = StartEndDate.new(
        start_date: 1.day.ago.to_datetime,
        end_date: 1.day.from_now.to_datetime,
        ad: Ad.find(1)
    )
  end

  test "should be a valid start end date" do
    assert @startEndDate.valid?, "the start end date was not a valid date"
  end
  
  test "should NOT be valid" do
    @startEndDate.ad = nil
    assert_not @startEndDate.valid?, "the start end date was not a valid date"
  end

  test "should not submit with out an ad" do
    @startEndDate.ad = nil;
    assert_not @startEndDate.save, "saved without an id this is bad"
  end

  test "should be not save an unvalid datetime on end" do
    @startEndDate.end_date = "something cool"
    assert_not @startEndDate.save, "should not save an invalid datetime"
  end

  test "should not be able to save an unvalid datetime on start" do
    @startEndDate.start_date = "something cool"
    assert_not @startEndDate.save, "should not save an invalid datetime"
  end

  test "do not assume that the start and end dates are different" do
    @startEndDate.start_date = 1.day.ago.to_datetime
    @startEndDate.end_date = 1.day.ago.to_datetime
    assert_not @startEndDate.save, "saved an invalid combination of dates"
  end
end
