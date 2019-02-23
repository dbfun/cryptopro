#!/bin/bash

#
# Подписание документа
#

tmp=`mktemp`
cat - > "$tmp"
cryptcp -sign -thumbprint "$1" -nochain -pin "$2" "$tmp" "$tmp.sig" > /dev/null 2>&1
signResult=$?
if [ "$signResult" != "0" ]; then
  rm -f "$tmp" "$tmp.sig"
  exit $signResult
fi
cat "$tmp.sig"
rm -f "$tmp" "$tmp.sig"
