# image-ads

[![Build Status](https://travis-ci.org/wvulibraries/image-ads.svg?branch=master)](https://travis-ci.org/wvulibraries/image-ads)![Coverage Status](/Rotating-Homepage-Ads/coverage/coverage.png?raw=true)

Ad manager for rotating image ads.

This application was developed to create a place for users to be able to manage image ads that are typically used in rotating or carousel fashion on the Libraries home page.  

## Dependencies
  - CentOS 7.2
  - Vagrant / Virtual Box (For development)
  - Ruby Version (2.3.3)
  - Rails -v (5.0.0.1)
  - MySQL
  - Bundler

---

# Running in Vagrant Box

  - Clone the repo
  - Use the terminal to change directory into the cloned repo and do the command `vagrant up`
  - This will provision the box, but will not do everything you need to completed the setup so you will need to be inside of the box to continue.

### Vagrant SSH

  The following commands need to be done after entering `vagrant ssh` be sure that you are inside of your vagrant box.  
  - gem install bundler
  - gem install rails
  - gem install mysql

### Build the dependencies needed for the project
  - change directory in your vagrant box through vagrant ssh
  - bundle install

### Setup Databases
  - cd into /vagrant/bin/
  - run the command `rails db:reset`
  - followed by  `rails db:migrate`

### Run the Server
  - use `rails server` to boot server with vagrant by adding to the `config/boot.rb` file

  ```ruby
  require 'rails/commands/server'
  module Rails
    class Server
      def default_options
        super.merge(Host:  '0.0.0.0', Port: 3000)
      end
    end
  end
  ```

  - `rails server -b 0.0.0.0` this runs the rails server on an ip and helps to work with vagrant

# Deployment

# Customization

Things you may want to cover:

* Ruby version

* System dependencies

* Configuration

* Database creation

* Database initialization

* How to run the test suite

* Services (job queues, cache servers, search engines, etc.)

* Deployment instructions

* ...


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

**Front End Use**

The application is setup to send a JSON file to the html browser by using an AJAX Call. The following JQuery will display the images in a list file.   

``` javascript
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>

    <script type='text/javascript'>
        $(document).ready(function() {

            $.ajax({
                dataType:'json',
                url:'http://localhost:8090/admin/image_manager/includes/ajax/getImages.php',
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
                        var buildList = '<li class="slider-object"><a href="' + jsonData[i].actionURL +'">';
                        buildList += '<img src="'+ jsonData[i].imageAd +'" alt="' + jsonData[i].altText + '" title="' + jsonData[i].name + '" />';
                        buildList += '</a></li>';

                        $('.slider').append(buildList);
                    }
                },
            });

        });
    </script>
 ```


If the number of images recieved needs to be limited this can be done by adding a querystring to the end of the url.  An example of this would be if the images need to be limited to 7.  

	http://localhost:8090/admin/image_manager/includes/ajax/getImages.php?limit=7
