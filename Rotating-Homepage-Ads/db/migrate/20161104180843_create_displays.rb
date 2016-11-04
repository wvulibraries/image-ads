class CreateDisplays < ActiveRecord::Migration[5.0]
  def change
    create_table :displays do |t|

      t.timestamps
    end
  end
end
