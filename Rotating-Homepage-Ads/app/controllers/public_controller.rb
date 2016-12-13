class PublicController < ApplicationController
  layout "application"

  def index
  end

  # def logout
  #   session.delete('cas')
  #   redirect_to root_path, notice: 'Logged Out!'
  # end
  #
  # def set_vagrant_user
  #     session['cas'] = {
  #       'user' => 'vagrant',
  #       'extra_attributes' => {},
  #     }
  #     redirect_to root_path, notice: 'Logged In!'
  # end
end
