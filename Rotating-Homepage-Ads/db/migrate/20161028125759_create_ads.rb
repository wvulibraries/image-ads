class CreateAds < ActiveRecord::Migration[5.0]
  def up
    create_table :ads do |t|
      t.string  :filename
      t.string  :content_type
      t.binary  :file_contents

      t.string  :image_name
      t.boolean  :displayed
      t.integer :priority
      t.string  :alttext
      t.string  :link
      t.text    :selected_days

      t.timestamps
    end
  end

  def down
    drop_table :ads
  end
end
