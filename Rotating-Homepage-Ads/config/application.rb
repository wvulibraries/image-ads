require_relative 'boot'
require 'rails/all'
require 'rack-cas/session_store/active_record'

# Require the gems listed in Gemfile, including any gems
# you've limited to :test, :development, or :production.
Bundler.require(*Rails.groups)

module RotatingHomepageAds
  class Application < Rails::Application
    ## Set Configuration of Time Zone Here
    ## Although this should be handled by the server
    config.time_zone = 'Eastern Time (US & Canada)'
    config.active_record.default_timezone = :local

    ## Rails Cas Config
    @cas_server_url = "https://ssotest.wvu.edu"

    config.rack_cas.server_url = @cas_server_url
    config.rack_cas.protocol = 'p3'
    config.rack_cas.session_store = RackCAS::ActiveRecordStore
  end
end
