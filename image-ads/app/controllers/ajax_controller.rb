class AjaxController < ApplicationController
  def getads
    @ads = Ad.ads_shown.sorted_priority
  end

  def ajax_param
    params.permit(:limit)
  end
end
