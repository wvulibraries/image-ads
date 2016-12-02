Rails.application.routes.draw do

  namespace :admin do
    get 'users/index'
  end

  # root
  root 'public#index'

  # admin
  get '/admin', to: 'admin#index'

  # vagrant only
  get '/vagrantlogin', to:"public#set_vagrant_user"
  get '/vlogout', to:"public#logout"
  get '/vfail', to: "public#fail_vagrant_user"

  get 'ajax/getads'

  get 'display/:id' => 'display#show'

  # forces the controllers to use the admin name space
  # this is going to allow for the addition of a function to restrict access
  # resources generates all routes for crud of libraries, departments, users, etc.

  scope '/admin' do
      resources :ads, module:'admin' do
        resources :start_end_dates, :start_end_times
      end
      resources :users, module:'admin'
  end

  # For details on the DSL available within this file, see http://guides.rubyonrails.org/routing.html
end
