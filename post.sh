#!/bin/sh

curl \
-b cookie.txt \
--header 'Accept: application/vnd.api+json' \
--header 'Content-type: application/vnd.api+json' \
--request POST http://h2758593.stratoserver.net/thunder/jsonapi/node/article \
--data-binary @thunder-article-template.json
