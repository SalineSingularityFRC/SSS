#!/usr/local/bin/bash

chars=ABCDEFGHJKLMNP23456789
for i in {1..4} ; do
    s="${s}${chars:RANDOM%${#chars}:1}"
done

echo $s > /usr/local/www/sss/key.txt
