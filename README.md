# Image-ads

[![Build Status](https://travis-ci.org/wvulibraries/image-ads.svg?branch=master)](https://travis-ci.org/wvulibraries/image-ads)![Coverage Status](/image-ads/coverage/coverage.png?raw=true)

Ad manager for rotating image ads.

This application was developed to create a place for users to be able to manage image ads that are typically used in rotating or carousel fashion on the Libraries home page.  

## Dependencies
  - Docker / Docker-Compose (For development)
  - Ruby Version (2.3.3)
  - Rails -v (5.0.0.1)
  - MySQL
  - Bundler
---

## Setup
  - Clone the repo

## Environment and Database settings
  To set your environment you need to create .env.db and .env.web these will hold all your setting like password for your database and your secrets key.

  In your .env.db if you are only doing development you can simply just create a file with just one line. I set mine to take empty passwords but if you want to set a password you can set it as you would in production with the instructions below.

      MYSQL_ALLOW_EMPTY_PASSWORD=true

  In production you will want to set more we used the following

      MYSQL_ROOT_PASSWORD=root
      MYSQL_DATABASE=image-ads_production
      MYSQL_USER=<username>
      MYSQL_PASSWORD=<password>

  In your .env.web you will want to set your timezone for example I have set mine to the US Eastern.

      TZ=America/New_York

  Also you will need to set the RAILS_ENV for either development or production

      RAILS_ENV=development

  If in production you will also need to set your database username and password.

      IMAGE-ADS_DATABASE_USERNAME=<username>
      IMAGE-ADS_DATABASE_PASSWORD=<password>

    Note: Be sure to update your config/database.yml to match.

  Also to generate your secret key run "rake secret" and copy the result

      SECRET_KEY_BASE=<key>

## Running in Docker

  - Use the terminal to change directory into the cloned repo and do the command `docker-compose up`
  - This will provision the container, but will not do everything you need to completed the setup.

## Setup Databases
Run the following commands to setup your database. Only use seed if you are just testing.

    docker-compose run image-ads rails db:create
    docker-compose run image-ads rails db:migrate
    docker-compose run image-ads rails db:seed

## SSH into the container
To work directly in the container you can run "docker-compose run web /bin/bash"

## Capistrano Notes

check
docker-compose run web cap staging deploy:check

to deploy
docker-compose run web cap staging deploy

# Deployment

** Manage Ads

The Manage state is used to see all currently active images and get a general overview of what images are stored and the data that they are going to have with them.  When look at the manage state you will see the following information,

 - Enabled : is the image going to show
 - Priority : Low or High Priority will be determine order and if limited if it will show.  
 - Link : The link that the image will go to when clicked
 - Display Dates : The dates that the image will show
 - Display Times : The times that the image will show
 - Display Weekdays : The days in specific that the image will show

** Input Areas (Create Update Delete)

The areas will allow you to use forms to create update or delete images you have already stored.  The bulk of the users time will be spent in these sections.

## Front End Use

The application is setup to send a JSON file to the html browser by using an AJAX Call. The following JQuery will display the images in a list file.   

``` javascript
<script type='text/javascript'>
      $(document).ready(function() {
          // make it slide
          var slider = $('.slider').bxSlider({
            mode: 'fade',
            captions: true
          });

          $.ajax({
              dataType:'json',
              url:'/ajax/getads.json',
              data: '',
              success:function() {
                  console.log('success');
              },
              error: function (jqXHR, textStatus, errorThrown) {
                  console.log(textStatus + ': ' + errorThrown);
              },
              complete: function(data){
                  var jsonData = data.responseJSON;

                  for (var i = 0; i < jsonData.length; i++) {
                      var buildList = '<li><a href="' + jsonData[i].actionUrl +'">';
                      buildList += '<img src="'+ jsonData[i].imageAd +'" alt="' + jsonData[i].altText + '" title="' + jsonData[i].name + '" />';
                      buildList += '</a></li>';

                      $('.slider').append(buildList);
                  }

                  slider.reloadSlider();
              },
          });


          $(window).on('load resize', function(){
            console.log('sizing');
            var tallest = 0;
            $('.equalHeight').each(function(){
              $('.equal').each(function(){
                  $(this).css('height','auto');
                  if($(this).height() > tallest){
                    tallest = $(this).height() + 72;
                  }
              });
              $('.equal', this).height(tallest);
            });
          });
      });
  </script>
 ```


If the number of images received needs to be limited this can be done by adding a query string to the end of the url.  An example of this would be if the images need to be limited to 7.  

	http://localhost:3000/ajax/getads.json?limit=7

## PUMA Using SSL (Needed for CAS)

1. Starts puma using custom configs.
  - `bundle exec puma -C config/puma.rb`
2. Be sure that in the `config/application.rb` the line `config.forced_ssl = true` is present and un-commented.
3. Have some self signed ssl tickets generated and placed in the /etc/httpd/ssl folders.
  - You may have to make the SSL tickets and make the ssl directory
  - `sudo mkdir /etc/httpd/ssl`
  - Replace ${APP_NAME} with your applications name, or web address.  Ex: imageads.mysite.com or imageads.lib.wvu.edu
  - `sudo openssl req -x509 -nodes -days 365 -newkey rsa:2048 -keyout /etc/httpd/ssl/${APP_NAME}.key -out /etc/httpd/ssl/${APP_NAME}.crt -subj '/CN=localhost/O=My Company Name LTD./C=US'`
