#!/bin/bash

cd "$(dirname "$0")"

docker build --tag required/cryptopro .
