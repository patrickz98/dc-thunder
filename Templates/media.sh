#!/bin/sh

curl \
    --request POST \
    --user patrick:1234 \
    --header "Accept: application/json" \
    --header "Content-type: application/json" \
    --url http://localhost/thunder/entity/media?_format=json \
    --data-binary @media-tmplate.json > zzz-log-media.json

cat zzz-log-media.json
echo

# curl http://localhost/thunder/media/67?_format=json
