# Be sure to restart your server when you modify this file.
require 'rack-cas/session_store/rails/active_record'

Rails.application.config.session_store :cookie_store, key: '_Rotating-Homepage-Ads_session'
Rails.application.config.session_store ActionDispatch::Session::RackCasActiveRecordStore
