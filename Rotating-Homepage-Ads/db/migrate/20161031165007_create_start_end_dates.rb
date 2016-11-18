class CreateStartEndDates < ActiveRecord::Migration[5.0]
  def up
    create_table :start_end_dates do |t|
      t.datetime :start_date
      t.datetime :end_date
      t.references :ad, foreign_key: true

      t.timestamps
    end
  end

  def down
    drop_table :start_end_dates
  end
end
