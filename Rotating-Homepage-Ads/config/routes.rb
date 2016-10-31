Rails.application.routes.draw do
  root 'ads#index'
  #resources :schedules
  resources :ads do
    end
  # For details on the DSL available within this file, see http://guides.rubyonrails.org/routing.html
end
