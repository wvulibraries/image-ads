class Admin::StartEndTimesController < AdminController
  def create
    @ad = Ad.find(params[:ad_id])
    @start_end_time = @ad.start_end_times.create(start_end_time_params)
    redirect_to ad_path(@ad)
  end

  def destroy
    @ad = Ad.find(params[:ad_id])
    @start_end_time = @ad.start_end_times.find(params[:id])
    @start_end_time.destroy
    redirect_to ad_path(@ad)
  end

  private
  def start_end_time_params
    params.require(:start_end_time).permit(:start_time, :end_time)
  end
end
