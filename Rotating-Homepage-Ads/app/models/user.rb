class User < ApplicationRecord
  # all the basic validations for this new record to be inserted
  validates :username, presence: true

end
