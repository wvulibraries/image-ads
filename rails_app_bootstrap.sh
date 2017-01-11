echo "Gem install bundler and rails ... "
echo " -------------------------------------------------------------------------- "
gem install bundler
gem install rails
gem install mysql2

echo "install rails app dependencies ... "
echo " -------------------------------------------------------------------------- "
cd /vagrant/image-ads
bundle install
