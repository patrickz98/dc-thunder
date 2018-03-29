#!/bin/sh

curl \
    --request POST \
    --user patrick:1234 \
    --header "Accept: application/json" \
    --header "Content-type: application/json" \
    --url http://localhost/thunder/entity/paragraph?_format=json \
    --data-binary @paragraph-template.json > log-paragraph.json

cat log-paragraph.json
echo
