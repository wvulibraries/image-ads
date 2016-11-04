Rails.application.routes.draw do
  get 'ajax/getAds'

  #root 'display#index'

  root 'ads#index'

  resources :ads do
    resources :start_end_dates
    resources :start_end_times
  end
  # For details on the DSL available within this file, see http://guides.rubyonrails.org/routing.html
end
