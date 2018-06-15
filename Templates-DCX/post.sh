#!/bin/sh

curl \
    -v \
    --request POST \
    --url "http://192.168.18.131/dcx/api/job" \
    --user "testuser:dc" \
    --header "Content-Type: application/json" \
    --data-binary @job.json > zzz.txt
    # --data-binary @img-backupafSD.json > zzz.txt
    # --data-binary @story-post.json > zzz.txt
    # --url "http://192.168.18.131/dcx/api/document" \

cat zzz.txt

# curl --user "testuser:dc" http://192.168.18.131/dcx/api/file/file6wz8s43sjookde4n1rfa
# curl --user "testuser:dc" http://192.168.18.131/dcx/api/document/doc6wzbf4o3b5ylnmlm8c6
# curl --user "testuser:dc" http://192.168.18.131/dcx/api/job/job6wzbf4j9z3q17felg1rfc
# cookie-jar = "cookies.txt"
# cookie = "cookies.txt"
