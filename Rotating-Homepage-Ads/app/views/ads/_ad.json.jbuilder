#json.extract! ad, :id, :filename, :content_type, :base64_encode, :image_name, :displayed, :priority, :alttext, :link, :created_at, :updated_at
#json.extract! ad, :image_name, :displayed, :priority, :alttext, :link, :created_at, :updated_at

json.key_format! camelize: :lower
json.name ad.image_name
json.image_ad request.base_url + url_for(controller: 'display', action: 'show')
json.priority ad.priority
json.alt_text ad.alttext
json.action_URL ad.link

# json.key_format! camelize: :lower
# json.content_type @ad.content_type
# json.base64_encode @ad.base64_encode

#json.url ad_url(ad, format: :json)
