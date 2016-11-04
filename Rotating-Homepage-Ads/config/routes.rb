Rails.application.routes.draw do
  root 'ads#index'

  get 'display/:id' => 'display#show'

  resources :ads do
    resources :start_end_dates
    resources :start_end_times
  end
  # For details on the DSL available within this file, see http://guides.rubyonrails.org/routing.html
end
