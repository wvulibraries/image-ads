#json.extract! ad, :id, :filename, :content_type, :base64_encode, :image_name, :displayed, :priority, :alttext, :link, :created_at, :updated_at
json.extract! ad, :image_name, :displayed, :priority, :alttext, :link, :created_at, :updated_at

# json.name @ad.image_name
#json.imageAd
# json.priority @ad.priority
# json.altText @ad.alttext
# json.actionURL @ad.link

# json.key_format! camelize: :lower
# json.content_type @ad.content_type
# json.base64_encode @ad.base64_encode

#json.url ad_url(ad, format: :json)
