require 'test_helper'

class StartEndTimeTest < ActiveSupport::TestCase
  ## setup the base model used for testing
  def setup
    @startEndTime = StartEndTime.new(
        start_time: Time.now.midnight.strftime("%l:%M %p").strip,
        end_time: Time.now.beginning_of_day.strftime("%l:%M %p").strip,
        ad: Ad.find(1)
    )
  end

  test "should be a valid start end time" do
    assert @startEndTime.valid?, "the start end time was not a valid time"
  end

  test "should NOT be valid" do
    @startEndTime.ad = nil
    assert_not @startEndTime.valid?, "the start end time was not a valid time"
  end

  test "should not submit with out an ad" do
    @startEndTime.ad = nil;
    assert_not @startEndTime.save, "saved without an id this is bad"
  end

  test "should be not save an unvalid time on end" do
    @startEndTime.end_time = "something cool"
    assert_not @startEndTime.save, "should not save an invalid time"
  end

  test "should not be able to save an unvalid time on start" do
    @startEndTime.start_time = "something cool"
    assert_not @startEndTime.save, "should not save an invalid time"
  end

  test "do not allow an empty start time with a valid end time" do
    @startEndTime.start_time = nil
    @startEndTime.end_time = Time.now.midnight
    assert_not @startEndTime.save,  "saved with an invalid start time and valid end time"
  end

  test "do not allow an empty end time with a valid start time" do
    @startEndTime.start_time = Time.now.midnight
    @startEndTime.end_time = nil
    assert_not @startEndTime.save, "saved with an invalid end time and a valid start time"
  end
end
