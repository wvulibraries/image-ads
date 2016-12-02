require "rack-cas-rails"
class AdminController < ApplicationController
  before_action :authenticate!
  before_action :check_secret_CAS

  private
  def check_secret_CAS
    secret = Digest::MD5.hexdigest("Library")
    if secret != session['cas']['secret']
      session.delete('cas')
      redirect_to root_path, notice: 'You have failed this Application with a faulty login!'
    end
  end
end
