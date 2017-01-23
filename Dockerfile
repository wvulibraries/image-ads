FROM djdaviswvu/ruby_node

# run update
RUN apt-get update -qq

RUN apt-get -y install ssh

WORKDIR /app

ADD image-ads /app
RUN bundle install

# Clean up APT when done.
RUN apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*
