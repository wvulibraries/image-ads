class Display < ApplicationRecord
  belongs_to :ad
  validates_presence_of :ad
end
