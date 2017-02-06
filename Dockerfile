FROM ruby:2.3.3
# run update
RUN apt-get update -qq
# install dependencies
RUN apt-get install -y build-essential libpq-dev nodejs mysql-client
# add rails
RUN gem install rails

# make working directory and move files over
RUN mkdir /home/imageads
WORKDIR /home/imageads
ADD . /home/imageads
RUN cd /home/imageads
RUN bundle install

#FROM djdaviswvu/ruby_node

# run update
#RUN apt-get update -qq

# Configure the main working directory. This is the base
# directory used in any further RUN, COPY, and ENTRYPOINT
# commands.
#RUN mkdir -p /app
#VOLUME ["/app"]
#WORKDIR /app

#ADD . /app
#RUN bundle install

# Clean up APT when done.
RUN apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# The main command to run when the container starts. Also
# tell the Rails dev server to bind to all interfaces by
# default.
ENTRYPOINT ["bundle", "exec", "rails", "server", "-p", "3000", "-b", "0.0.0.0"]
