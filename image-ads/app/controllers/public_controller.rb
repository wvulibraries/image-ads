class PublicController < ApplicationController
  layout 'application'

  def index; end

  def logout
    session.delete('cas')
    redirect_to root_path, notice: 'Logged Out!'
  end
  
end
