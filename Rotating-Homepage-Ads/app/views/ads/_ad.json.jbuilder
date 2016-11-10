json.key_format! camelize: :lower
json.name ad.image_name
json.sendtojson ad.sendToJSON
json.image_ad request.base_url + url_for(:controller => "display", :action => "show", :id => ad.id)
json.priority ad.priority
json.alt_text ad.alttext
json.action_URL ad.link
