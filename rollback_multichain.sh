#!/bin/bash

NC='\033[0m' # No Color
RED='\033[0;31m'
LIGHTGREEN='\033[1;32m'
CYAN='\033[0;36m'
LIGHTYELLOW='\033[1;33m'
bold=$(tput bold)
normal=$(tput sgr0)

username='yobiuser'

echo '----------------------------------------'
echo -e ${CYAN}${bold}'RESTORING.....'${normal}${NC}
echo '----------------------------------------'

ps axf | grep 'multichaind' | grep -v grep | awk '{print "kill -9 " $1}' | sh
rm -rf /home/$username/.multichain/*
rm -rf /var/www/html/hashchain
rm -rf /var/www/html/yobiapps
rm -rf /var/www/html/multichain-web-demo
ps axf | grep 'python -m Mce.abe --config' | grep -v grep | awk '{print "kill -9 " $1}' | sh
rm -rf /home/$username/multichain-explorer/
rm -rf /var/www/html/default_configs
rm -rf /var/www/html/multichain-1.0-alpha*

echo ''
echo ''
echo '----------------------------------------'
echo ''
echo ''
echo ''
echo ''
