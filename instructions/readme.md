Rails App Starter Kit
===================

The purpose of this starter kit is to provide a base to use going forward.  This may have to be adapted and updated as changes are made in the Ruby / Rails framework and document how to get up and running to development really quickly.  

Rails Commands
-------------
Restart and Serve the App on Port 3000

    bin/rails server

Changes to the App Server
-------------

 1. In vagrant the app server gets a private address of 127.0.0.1
 2. MySQL is used instead of SQLLite to do this we have add the following items done in the bootstrap.sh
	 - gem install
	 - `rails new /vagrant -d mysql`
	 - `gem install mysql2`
	 - ensure that `./Gemfile` uses `mysql2`

Manual Changes after Vagrant Box Is Running
-------------
In your config folder change the application.rb change the automated *Vagrant* application name to a new application name.  Use this application name to prepend the databases in the databases.yml file.  This will allow you to modify your databases and application name to match.  

SSH into your vagrant box first then run the command `bin/rails db:create` which reads the YML and modifies the databases.  DB Create will remove any other databases that are currently built.  It also doesn't modify your production database and instead wants that item to be created in the Production mode of the application.  


Running the Server
-------------
Currently the rails server is meant to run on 127.0.0.1, but that isn't the same IP as the local host.  I've tried configuring the vagrant box to a private server, but it doesn't seem to work properly.  **This requires you to be SSH'd into the vagrant box.**

To get around that run the server using this command, `bin/rails server -b 0.0.0.0`.  



Rails Items To Understand
===================

Active Record
-------------
Active Record facilitates the creation and use of business objects whose data requires persistent storage to a database. It is an implementation of the Active Record pattern which itself is a description of an Object Relational Mapping system.  

This means that our models can act as our database and we can avoid writing SQL unless we need a special use case.  

Tests
-------------
Tests are also created when you create a new model.  Ideally you should have written your tests before your model is made, but the goal is to make sure that each model has a test and that each model passes the tests before moving on to the next data model.  Tests give us an automated way to prove that our code is valid and does what we expect it to do.  Writing a verbose easy to read test is good practice and can help to eliminate a number of bugs.  
