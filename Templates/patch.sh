#!/bin/sh

curl \
    --request PATCH \
    --user patrick:1234 \
    --header "Accept: application/json" \
    --header "Content-type: application/json" \
    --url http://localhost/thunder/node/152?_format=json \
    --data-binary @zzz-patch-post.json > zzz-error.json

cat zzz-error.json
echo

# curl http://localhost/thunder/default-seo-title-2018-04-16t1744240000?_format=json > zzz-abc.json
