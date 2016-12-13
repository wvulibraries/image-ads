# Load the Rails application.
require_relative 'application'

# Initialize the Rails application.
Rails.application.initialize!


# Basic CAS client configuration

require 'casclient'
require 'casclient/frameworks/rails/filter'

CASClient::Frameworks::Rails::Filter.configure(
  :cas_base_url => 'https://ssodev.wvu.edu/cas/login'
)


# More complicated configuration

#cas_logger = CASClient::Logger.new(Rails.root+'/log/cas.log')
#cas_logger.level = Logger::DEBUG
#
#CASClient::Frameworks::Rails::Filter.configure(
#  :cas_base_url  => "https://localhost:7778/",
#  :login_url     => "https://localhost:7778/login",
#  :logout_url    => "https://localhost:7778/logout",
#  :validate_url  => "https://localhost:7778/proxyValidate",
#  :session_username_key => :cas_user,
#  :session_extra_attributes_key => :cas_extra_attributes
#  :logger => cas_logger,
#  :authenticate_on_every_request => true
#)
