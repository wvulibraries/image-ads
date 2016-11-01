Rails.application.routes.draw do
  root 'ads#index'
  #resources :schedules
  resources :ads do
    resources :start_end_dates
    resources :start_end_times
  end
  # For details on the DSL available within this file, see http://guides.rubyonrails.org/routing.html
end
