source 'https://rubygems.org'


# Rails, MySQL, Puma
gem 'rails', '~> 5.0.0', '>= 5.0.0.1'
gem 'mysql2', '>= 0.3.18', '< 0.5'
gem 'rake'
gem 'puma', '~> 3.0'

# Rails Dependencies
gem 'sass-rails', '~> 5.0'
gem 'uglifier', '>= 1.3.0'
gem 'coffee-rails', '~> 4.2'
gem 'jquery-rails'
gem 'turbolinks', '~> 5'
gem 'jbuilder', '~> 2.5'

# cas client
gem 'rubycas-client', :git => 'git://github.com/rubycas/rubycas-client.git'

# frontend
gem 'bourbon'
gem 'neat'
gem 'bitters'
gem 'normalize-scss'
gem 'font-awesome-sass'
gem 'pickadate-rails'

# Use Capistrano for deployment
gem 'capistrano-rails', '~> 1.2'

# TEST ONLY GEMS
group :test  do
  gem 'simplecov'
  gem 'simplecov-shield'
end

# DEVELOPMENT ONLY GEMS
group :development do
  gem 'web-console'
  gem 'listen', '~> 3.0.5'
  gem 'spring'
  gem 'spring-watcher-listen', '~> 2.0.0'
  gem 'byebug', platform: :mri
  gem 'rubocop', require: false
  gem 'brakeman', :require => false
  #gem 'capistrano', '~> 3.6'
end

# Windows does not include zoneinfo files, so bundle the tzinfo-data gem
gem 'tzinfo-data', platforms: [:mingw, :mswin, :x64_mingw, :jruby]
