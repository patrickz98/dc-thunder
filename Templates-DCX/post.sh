#!/bin/sh

# curl \
#     -v \
#     --url "http://192.168.18.131/dcx/atom/documents" \
#     --user "testuser:dc" \
#     --header "Content-Type: application/atom+xml;type=entry" \
#     --data-binary @post.xml > zzz.txt

curl \
    -v \
    --url "http://192.168.18.131/dcx/api/document" \
    --user "testuser:dc" \
    --header "Content-Type: application/json" \
    --data-binary @story-post.json > zzz.txt
    # --data-binary @post.xml > zzz.txt

cat zzz.txt

# cookie-jar = "cookies.txt"
# cookie = "cookies.txt"
