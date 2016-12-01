Rails.application.routes.draw do

  # root
  root 'public#index'

  # admin
  get '/admin', to: 'ads#index'

  # vagrant only
  get '/vagrantlogin', to:"public#set_vagrant_user"
  get '/vlogout', to:"public#logout"
  get '/vfail', to: "public#fail_vagrant_user"

  # forces the controllers to use the admin name space
  # this is going to allow for the addition of a function to restrict access
  # resources generates all routes for crud of libraries, departments, users, etc.

  #scope '/admin' do
  #  resources :libraries, :departments, :users, :normal_hours, :special_hours, module: 'admin'
  #end

  #get '/admin/departments/list', to: 'departments#index'





  get 'public/index'

  get 'ajax/getads'

  #root 'ads#index'

  get 'display/:id' => 'display#show'

  resources :ads do
    resources :start_end_dates
    resources :start_end_times
  end


  # For details on the DSL available within this file, see http://guides.rubyonrails.org/routing.html
end
