class Ad < ApplicationRecord
  serialize :selected_days


  ## example of creating a private method in sudo code
  # before_save :serialize
  #
  # private
  #   def serialize
  #     return "serialized array or something here"
  #   end
end
