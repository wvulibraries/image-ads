json.key_format! camelize: :lower
json.array! @ads do |ad|
  # if ad.sendToJSON
  #  json.partial! ad
  # end

  json.partial! ad
end
