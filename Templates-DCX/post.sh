#!/bin/sh

curl \
    -v \
    --request POST \
    --url "http://192.168.18.131/dcx/api/document" \
    --user "testuser:dc" \
    --header "Content-Type: application/json" \
    --data-binary @img-backupafSD.json > zzz.txt
    # --data-binary @story-post.json > zzz.txt

cat zzz.txt

# curl --user "testuser:dc" http://192.168.18.131/dcx/api/document/doc6wz8agmeguu1guvk11i9l
# cookie-jar = "cookies.txt"
# cookie = "cookies.txt"
