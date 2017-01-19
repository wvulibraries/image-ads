FROM djdaviswvu/ruby_node

# run update
RUN apt-get update -qq

RUN mkdir /app
WORKDIR /app
ADD ./image-ads/Gemfile* /app/
RUN bundle install
ADD ./image-ads /app

EXPOSE 3000
