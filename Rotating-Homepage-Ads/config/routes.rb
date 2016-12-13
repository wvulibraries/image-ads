Rails.application.routes.draw do
  # root
  root 'public#index'

  # admin
  get '/admin', to: 'admin#index'

  # vagrant only
  if Rails.env.development? || Rails.env.test?
    get '/vagrantlogin', to:"public#set_vagrant_user"
    get '/vagrantlogout', to:"public#logout"
  end

  get '/login', to: "public#login"
  get '/logout', to:"public#logout"
  get 'ajax/getads'
  get 'display/:id' => 'display#show'

  # forces the controllers to use the admin name space
  # this is going to allow for the addition of a function to restrict access
  # resources generates all routes for crud of libraries, departments, users, etc.

  scope '/admin' do
      resources :users, module:'admin'
      resources :ads, module:'admin' do
        resources :start_end_dates, :start_end_times
      end
  end

  # For details on the DSL available within this file, see http://guides.rubyonrails.org/routing.html
end
