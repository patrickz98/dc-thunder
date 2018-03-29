#!/bin/sh

curl \
    --request POST \
    --user patrick:1234 \
    --header "Accept: application/json" \
    --header "Content-type: application/json" \
    --url http://localhost/thunder/entity/media?_format=json \
    --data-binary @media-tmplate.json > media-log.json

cat media-log.json
echo

# curl http://localhost/thunder/media/27?_format=json
