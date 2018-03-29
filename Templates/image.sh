#!/bin/sh

curl \
    --request POST \
    --user patrick:1234 \
    --header "Accept: application/hal+json" \
    --header "Content-type: application/hal+json" \
    --url http://localhost/thunder/entity/file?_format=hal_json \
    --data-binary @image-upload-template.json

echo

# curl http://localhost/thunder/file/30?_format=hal_json
