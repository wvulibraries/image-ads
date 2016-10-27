Rails.application.routes.draw do

  # root
  root 'public#index'

  # admin
  get  '/admin', to: 'admin#index'

  # forces the controllers to use the admin name space
  # this is going to allow for the addition of a function to restrict access
  # resources generates all routes for crud of libraries, departments, users, etc.

  scope '/admin' do
    resources :ads
  end

  get '/admin/ads/list', to: 'ads#index'

end
