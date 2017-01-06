class ChangeToLongBlob < ActiveRecord::Migration[5.0]
  def change
    remove_column :ads, :file_contents 
    add_column :ads, :file_contents, :binary, limit: 16777215
  end
end
