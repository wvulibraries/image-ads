json.key_format! camelize: :lower
json.array! @ads do |ad|
   if ad.send_to_JSON
    json.partial! ad
   end
end
