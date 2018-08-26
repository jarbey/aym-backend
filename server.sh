#!/bin/sh

BASEDIR=$(dirname "$0")
echo "$BASEDIR"

export $(cat $BASEDIR/.env | grep -v ^# | xargs)

/usr/bin/php $BASEDIR/bin/console aym:websocket:server -vv