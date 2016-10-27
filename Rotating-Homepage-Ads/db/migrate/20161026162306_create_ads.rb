class CreateAds < ActiveRecord::Migration[5.0]
  def change
    create_table :ads do |t|
      t.string  :filename
      t.string  :content_type
      t.binary  :file_contents

      t.string  :title
      t.integer :priority
      t.string  :desired_attribute
      t.string  :alttext
      t.string  :link
      t.text    :selected_days
    end

    create_table :tasks do |t|
      t.belongs_to :ads, index: true
      t.datetime :start_date
      t.datetime :end_date
      t.datetime :start_time
      t.datetime :end_time
      t.timestamps
    end
  end
end
