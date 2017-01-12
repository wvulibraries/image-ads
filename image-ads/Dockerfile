FROM ruby:2.3.3

# run update
RUN apt-get update -qq
RUN apt-get install -y build-essential libpq-dev nodejs

# install mysql-client
RUN apt-get install -y mysql-client

RUN mkdir /app
WORKDIR /app
ADD Gemfile* /app/
RUN bundle install
ADD . /app

EXPOSE 3000
CMD ["rails", "server", "-b", "0.0.0.0"]
