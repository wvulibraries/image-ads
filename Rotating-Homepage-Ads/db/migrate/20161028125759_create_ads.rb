class CreateAds < ActiveRecord::Migration[5.0]
  def change
    create_table :ads do |t|
      t.string  :filename
      t.string  :content_type
      t.binary  :file_contents

      t.string  :image_name
      t.string  :displayed
      t.integer :priority
      t.string  :alttext
      t.string  :link
      t.text    :selected_days

      t.timestamps
    end
  end
end
