class DisplayController < ApplicationController
  def show
    @ad = Ad.find(params[:id])

    format_type = @ad.content_type.split('/', 2)
    symbol_format_type = format_type[1].parameterize.underscore.to_sym
    send_data = format_type[1].chomp('+xml')

    respond_to do |format|
      format.send(send_data) { render symbol_format_type }
      send_data @ad.file_contents, type: @ad.content_type, disposition: 'inline'
      return
    end
    end
end
