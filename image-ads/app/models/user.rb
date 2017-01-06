# User Class
# ==================================================
# AUTHORS : Tracy McCormick
# Description:
# Fairly simple, just makes sure that a username is present.
class User < ApplicationRecord
  # all the basic validations for this new record to be inserted
  validates :username, presence: true
end
