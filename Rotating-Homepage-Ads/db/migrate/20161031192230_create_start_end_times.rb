class CreateStartEndTimes < ActiveRecord::Migration[5.0]
  def up
    create_table :start_end_times do |t|
      t.datetime :start_time
      t.datetime :end_time
      t.references :ad, foreign_key: true

      t.timestamps
    end
  end

  def down
    drop_table :start_end_times
  end
end
