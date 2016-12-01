class PublicController < ApplicationController
  def index
  end

  def logout
    session.delete('cas')
    redirect_to root_path, notice: 'Logged Out!'
  end

  def set_vagrant_user
      session['cas'] = {
        'user' => 'vagrant',
        'extra_attributes' => {},
        'secret' => Digest::MD5.hexdigest("Library")
      }

      redirect_to root_path, notice: 'Logged In!'
  end

  def fail_vagrant_user
      session['cas'] = {
        'user' => 'vagrant',
        'extra_attributes' => {},
        'secret' => Digest::MD5.hexdigest("asdkfja")
      }

      redirect_to root_path, notice: 'Purposeful failed Log In!'
  end
end
