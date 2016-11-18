class AjaxController < ApplicationController
  def getads
    @ads = Ad.where(:displayed = "yes");
    logger.info @ads
  end
end
