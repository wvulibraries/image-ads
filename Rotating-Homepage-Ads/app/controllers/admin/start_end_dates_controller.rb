class Admin::StartEndDatesController < AdminController
  def create
    @ad = Ad.find(params[:ad_id])
    @start_end_date = @ad.start_end_dates.create(start_end_date_params)
    redirect_to ad_path(@ad)
  end

  def destroy
    @ad = Ad.find(params[:ad_id])
    @start_end_date = @ad.start_end_dates.find(params[:id])
    @start_end_date.destroy
    redirect_to ad_path(@ad)
  end

  private
  def start_end_date_params
    params.require(:start_end_date).permit(:start_date, :end_date)
  end
end
