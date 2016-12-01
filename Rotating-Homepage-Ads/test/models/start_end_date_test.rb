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

  test "do not allow an empty start date with a valid end date" do
    @startEndDate.start_date = nil
    @startEndDate.end_date = 1.day.ago.to_datetime
    assert_not @startEndDate.save,  "saved with an invalid start date and valid end date"
  end

  test "do not allow an empty end date with a valid start date" do
    @startEndDate.start_date = 1.day.ago.to_datetime
    @startEndDate.end_date = nil
    assert_not @startEndDate.save, "saved with an invalid end date and a valid start date"
  end

  test "do not allow both to be nil values if both are nil values, then a date doesn't need to be made" do
    @startEndDate.start_date = nil
    @startEndDate.end_date = nil
    assert_not @startEndDate.save, "saved with nil values"
  end
end
