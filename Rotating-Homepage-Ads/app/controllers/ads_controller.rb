class AdsController < ApplicationController
  def new
  end

  def create
    render plain: params[:title].inspect
  end
end
