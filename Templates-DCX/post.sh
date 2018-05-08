#!/bin/sh

curl \
    -v \
    --url "http://192.168.18.131/dcx/api/document" \
    --user "testuser:dc" \
    --header "Content-Type: application/json" \
    --data-binary @story-post.json > zzz.txt

cat zzz.txt

# cookie-jar = "cookies.txt"
# cookie = "cookies.txt"
