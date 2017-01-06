#!/bin/bash

## Variables
RUBY_VERSION='2.3.0'
APP_NAME="image-ads"

## SERVER CONFIGURATION
echo "Updating OS and YUM"
echo " -------------------------------------------------------------------------- "

yum -y update

echo "Installing Developer Tools ... "
echo " -------------------------------------------------------------------------- "

yum groupinstall -y "Development Tools"
yum install -y kernel-devel kernel-headers
yum install -y openssl openssl-devel
yum install -y libcurl-devel
yum install -y gcc gcc-c++ make
yum install -y zlib-devel
yum install -y readline-devel
yum install -y ntp git vim emacs

echo "Installing Apache ... "
echo " -------------------------------------------------------------------------- "
yum install -y httpd
yum install -y curl-devel httpd-devel

echo "Setup Apache ... "
echo " -------------------------------------------------------------------------- "
chkconfig httpd on

echo "Enable EPEL for Yum Utilitis  ... "
echo " -------------------------------------------------------------------------- "
yum install -y epel-release yum-utils
yum-config-manager --enable epel

echo "Install Passenger for Rails to use apache  ... "
echo " -------------------------------------------------------------------------- "
yum install -y pygpgme curl
curl --fail -sSLo /etc/yum.repos.d/passenger.repo https://oss-binaries.phusionpassenger.com/yum/definitions/el-passenger.repo
yum install -y mod_passenger

echo "Restarting Apache ... "
echo " -------------------------------------------------------------------------- "
systemctl restart httpd
yum -y update

echo "Installing mysql ... "
echo " -------------------------------------------------------------------------- "
rpm -Uvh http://repo.mysql.com/mysql-community-release-el7-5.noarch.rpm
yum -y install mysql-community-server mysql-community-client mysql-community-devel mysql-community-common mysql-community-libs mysql-community-release

echo "Starting mysql"
echo " -------------------------------------------------------------------------- "
systemctl enable mysqld
systemctl restart mysqld

echo "Installing redis ... "
echo " -------------------------------------------------------------------------- "

yum --enablerepo=epel -y install redis

echo "Installing memcached ... "
echo " -------------------------------------------------------------------------- "

rpm -Uvh http://apt.sw.be/redhat/el5/en/x86_64/rpmforge/RPMS/rpmforge-release-0.5.2-2.el5.rf.x86_64.rpm
yum install memcached

echo "installing Ruby and RVM ... "
echo " -------------------------------------------------------------------------- "

gpg --keyserver hkp://keys.gnupg.net --recv-keys 409B6B1796C275462A1703113804BB82D39DC0E3
curl -sSL https://get.rvm.io | bash -s stable --ruby

echo "Installing Ruby Environment using the RVM ... "
echo " -------------------------------------------------------------------------- "

if [ ! -e ~/.rbenv ]; then
  git clone https://github.com/sstephenson/rbenv.git ~/.rbenv
  git clone https://github.com/sstephenson/ruby-build.git ~/.rbenv/plugins/ruby-build

  echo 'export PATH="$HOME/.rbenv/bin:$PATH"' >> ~/.bash_profile
  echo 'eval "$(rbenv init -)"' >> ~/.bash_profile

  source ~/.bash_profile
  echo "rbenv(`rbenv --version`) installed"
fi

source /home/vagrant/.rvm/scripts/rvm

yum -y install libffi-devel
rbenv install ${RUBY_VERSION}
rbenv global ${RUBY_VERSION}
rbenv rehash

echo "installing node"
echo " -------------------------------------------------------------------------- "

curl --silent --location https://rpm.nodesource.com/setup_4.x | bash -
yum -y install nodejs

echo " Change ownership so vagrant user can install gems and such for non-privaledged use"
echo " -------------------------------------------------------------------------- "

chown -R vagrant /usr/local/
chown -R vagrant /usr/local/bin
chown -R vagrant /usr/local/lib/ruby/gems/2.3.0

echo "Generate a ssl ticket (self signed default information)"
echo " -------------------------------------------------------------------------- "

mkdir /etc/httpd/ssl
openssl req -x509 -nodes -days 365 -newkey rsa:2048 -keyout /etc/httpd/ssl/${APP_NAME}.key -out /etc/httpd/ssl/${APP_NAME}.crt -subj '/CN=localhost/O=My Company Name LTD./C=US'
