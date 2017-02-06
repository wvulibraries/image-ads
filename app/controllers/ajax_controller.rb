class AjaxController < ApplicationController
  def getads
    @ads = Ad.ads_shown.sorted_priority
  end
end
