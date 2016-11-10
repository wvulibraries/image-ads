class AjaxController < ApplicationController
  def getads
    @ads = Ad.all
  end
end
