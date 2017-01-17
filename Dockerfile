FROM ruby:2.3.3

# run update
RUN apt-get update -qq
RUN apt-get install -y build-essential libpq-dev nodejs

# install mysql-client
RUN apt-get install -y mysql-client

RUN mkdir /app
WORKDIR /app
ADD ./image-ads/Gemfile* /app/
RUN bundle install
ADD ./image-ads /app

EXPOSE 3000
