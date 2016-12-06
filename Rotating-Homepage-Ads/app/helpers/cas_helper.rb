module CasHelper
  def set_current_user(user)
    session['cas'] = { 'user' => user.username, 'extra_attributes' => {} }
  end
end
