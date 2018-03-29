#!/bin/sh

curl \
    --request POST \
    --user patrick:1234 \
    --header "Accept: application/json" \
    --header "Content-type: application/json" \
    --url http://localhost/thunder/entity/node?_format=json \
    --data-binary @article-template.json > article-log.json

cat article-log.json
echo

# curl http://localhost/thunder/seo-title?_format=json
