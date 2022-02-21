#!/bin/bash

MESSAGE="Hello Good Morning : `whoami`"
MESSAGE+=" \nToday is `date`"
MESSAGE+=" \nLet me Open everything for you"
echo -e "$MESSAGE"

. menu.sh

echo "System Update"
apt-get update &
apt-get upgrade &

