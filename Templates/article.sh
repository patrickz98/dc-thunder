#!/bin/sh

curl \
    --request POST \
    --user patrick:1234 \
    --header "Accept: application/json" \
    --header "Content-type: application/json" \
    --url http://localhost/thunder/entity/node?_format=json \
    --data-binary @article-template.json > zzz-log-article.json

cat zzz-log-article.json
echo

# curl http://localhost/thunder/seo-title?_format=json
