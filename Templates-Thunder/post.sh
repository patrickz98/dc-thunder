#!/bin/sh

server="localhost"

#
# Authentication
#

# curl \
#     --request POST \
#     --header "Content-type: application/json" \
#     -c cookie.txt \
#     --url "http://${server}/thunder/user/login?_format=json" \
#     --data '{"name":"patrick", "pass":"1234"}'
#
# echo

#
# Madia
#

curl \
    --request POST \
    --user patrick:1234 \
    --header "Accept: application/json" \
    --header "Content-type: application/json" \
    --url http://${server}/thunder/entity/media?_format=json \
    --data-binary @media-tmplate.json > error.json

cat error.json
echo


#
# Article
#

# curl \
#     --request GET \
#     --user patrick:1234 \
#     --url http://${server}/thunder/seo-title?_format=json > xxx.json

# curl \
#     --request GET \
#     --url http://${server}/thunder/seo-title?_format=hal_json > xxx-hal.json




#
# Paragraphs
#

# curl \
#     --request GET \
#     --url http://${server}/thunder/entity/paragraph/19?_format=hal_json > paragraph-std.json

# curl \
#     --request GET \
#     --url http://${server}/thunder/entity/paragraph/83?_format=hal_json > paragraph-pz.json

# curl http://localhost/thunder/entity/paragraph/83?_format=json

# curl \
#     --request POST \
#     --user patrick:1234 \
#     --header "Accept: application/json" \
#     --header "Content-type: application/json" \
#     --url http://${server}/thunder/entity/paragraph?_format=json \
#     --data-binary @thunder-template-paragraphs.json > error.json
#
# cat error.json
# echo

# curl \
#     --request PATCH \
#     --user patrick:1234 \
#     --header "Accept: application/json" \
#     --header "Content-type: application/json" \
#     --url http://${server}/thunder/entity/paragraph/26?_format=json \
#     --data-binary @paragraph-pz-patch.json > error.json
#
# cat error.json
# echo



#
# Stuff
#

# http://localhost/thunder/entity/node/12
# --request POST http://h2758593.stratoserver.net/thunder/jsonapi/node/article \

# curl --include \
#   --request POST \
#   --user patrick:1234 \
#   --header 'Content-type: application/hal+json' \
#   --url http://${server}/thunder/entity/node?_format=hal_json \
#   --data-binary @${file}
#
# echo
