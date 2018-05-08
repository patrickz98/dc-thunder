#!/bin/sh

curl \
    -v \
    --url "http://192.168.18.131/dcx/api/document" \
    --user "testuser:dc" \
    --header "Content-Type: application/json" \
    --data-binary @story-post.json > zzz.txt

cat zzz.txt

# curl --user "testuser:dc" http://192.168.18.131/dcx/api/document/doc6wxj23xrnq9ucu831m40
# cookie-jar = "cookies.txt"
# cookie = "cookies.txt"
