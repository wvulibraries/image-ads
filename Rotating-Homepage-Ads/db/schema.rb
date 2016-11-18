# This file is auto-generated from the current state of the database. Instead
# of editing this file, please use the migrations feature of Active Record to
# incrementally modify your database, and then regenerate this schema definition.
#
# Note that this schema.rb definition is the authoritative source for your
# database schema. If you need to create the application database on another
# system, you should be using db:schema:load, not running all the migrations
# from scratch. The latter is a flawed and unsustainable approach (the more migrations
# you'll amass, the slower it'll run and the greater likelihood for issues).
#
# It's strongly recommended that you check this file into your version control system.

ActiveRecord::Schema.define(version: 20161031192230) do

  create_table "ads", force: :cascade, options: "ENGINE=InnoDB DEFAULT CHARSET=utf8" do |t|
    t.string   "filename"
    t.string   "content_type"
    t.binary   "file_contents", limit: 65535
    t.string   "image_name"
    t.boolean  "displayed"
    t.integer  "priority"
    t.string   "alttext"
    t.string   "link"
    t.text     "selected_days", limit: 65535
    t.datetime "created_at",                  null: false
    t.datetime "updated_at",                  null: false
  end

  create_table "start_end_dates", force: :cascade, options: "ENGINE=InnoDB DEFAULT CHARSET=utf8" do |t|
    t.datetime "start_date"
    t.datetime "end_date"
    t.integer  "ad_id"
    t.datetime "created_at", null: false
    t.datetime "updated_at", null: false
    t.index ["ad_id"], name: "index_start_end_dates_on_ad_id", using: :btree
  end

  create_table "start_end_times", force: :cascade, options: "ENGINE=InnoDB DEFAULT CHARSET=utf8" do |t|
    t.datetime "start_time"
    t.datetime "end_time"
    t.integer  "ad_id"
    t.datetime "created_at", null: false
    t.datetime "updated_at", null: false
    t.index ["ad_id"], name: "index_start_end_times_on_ad_id", using: :btree
  end

  add_foreign_key "start_end_dates", "ads"
  add_foreign_key "start_end_times", "ads"
end
