class AdminController < ApplicationController
  # tell rails which view layout to use with this controller
  layout "admin"

  # perform filter before action
  before_filter CASClient::Frameworks::Rails::Filter

   def index
     @username = session[:cas_user]
     @extra_attributes = session[:cas_extra_attributes]
   end

   def logout
     CASClient::Frameworks::Rails::Filter.logout(self)
   end
end
