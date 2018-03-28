#!/bin/sh

curl \
    --request POST \
    --user patrick:1234 \
    --header "Accept: application/hal+json" \
    --header "Content-type: application/hal+json" \
    --url http://localhost/thunder/entity/file?_format=hal_json \
    --data-binary @post-image.json

# curl \
#     --request GET \
#     --user patrick:1234 \
#     --url http://localhost/thunder/entity/file/24?_format=json

echo
