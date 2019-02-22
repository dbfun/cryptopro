#!/bin/bash

# сборка образа

cd "$(dirname "$0")"

docker build --tag required/cryptopro .
