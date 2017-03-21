#!/bin/bash
export TERM=xterm-color

NC='\033[0m' # No Color
RED='\033[0;31m'
LIGHTGREEN='\033[1;32m'
CYAN='\033[0;36m'
LIGHTYELLOW='\033[1;33m'
bold=$(tput bold)
normal=$(tput sgr0)

chainname=$1
rpcuser=$2
rpcpassword=$3
protocol=10007
networkport=15590
rpcport=61172
explorerport=2750
adminNodeName=$chainname'_Admin'
explorerDisplayName=$chainname
phpinipath='/etc/php/7.0/apache2/php.ini'
username='yobiuser'

echo '----------------------------------------'
echo -e ${CYAN}${bold}'INSTALLING PREREQUISITES.....'${normal}${LIGHTYELLOW}
echo '----------------------------------------'

sudo apt-get --assume-yes update
sudo apt-get --assume-yes install jq git vsftpd aptitude apache2-utils php-curl sqlite3 libsqlite3-dev python-dev python-pip
sudo pip install --upgrade pip
#sudo pip install pycrypto

wget https://pypi.python.org/packages/60/db/645aa9af249f059cc3a368b118de33889219e0362141e75d4eaf6f80f163/pycrypto-2.6.1.tar.gz
tar -xvzf pycrypto-2.6.1.tar.gz
cd pycrypto*
sudo python setup.py install
cd ..

## Configuring PHP-Curl
sudo sed -ie 's/;extension=php_curl.dll/extension=php_curl.dll/g' $phpinipath

sudo service apache2 restart

echo ''
echo ''
echo '----------------------------------------'
echo ''
echo ''
echo ''
echo ''

sleep 3
echo '----------------------------------------'
echo -e ${CYAN}${bold}'CONFIGURING FIREWALL.....'${normal}${LIGHTYELLOW}
echo '----------------------------------------'

sudo ufw allow $networkport
sudo ufw allow $rpcport
sudo ufw allow $explorerport
sudo ufw allow 21

echo ''
echo ''
echo '----------------------------------------'
echo ''
echo ''
echo ''
echo ''

echo -e ${LIGHTGREEN}${bold}'----------------------------------------'
echo -e 'FIREWALL SUCCESSFULLY CONFIGURED!'
echo -e '----------------------------------------'${normal}${NC}

echo '----------------------------------------'
echo -e ${CYAN}${bold}'INSTALLING & CONFIGURING MULTICHAIN.....'${normal}${LIGHTYELLOW}
echo '----------------------------------------'

sudo bash -c 'chmod -R 777 /var/www/html'
wget --no-verbose http://www.multichain.com/download/multichain-latest.tar.gz
sudo bash -c 'tar xvf multichain-latest.tar.gz'
sudo bash -c 'cp multichain-1.0-alpha*/multichain* /usr/local/bin/'

su -l $username -c  'multichain-util create '$chainname $protocol

su -l $username -c "sed -ie 's/.*root-stream-open =.*\#/root-stream-open = false     #/g' /home/"$username"/.multichain/$chainname/params.dat"
su -l $username -c "sed -ie 's/.*mining-requires-peers =.*\#/mining-requires-peers = true     #/g' /home/"$username"/.multichain/$chainname/params.dat"
su -l $username -c "sed -ie 's/.*initial-block-reward =.*\#/initial-block-reward = 0     #/g' /home/"$username"/.multichain/$chainname/params.dat"
su -l $username -c "sed -ie 's/.*first-block-reward =.*\#/first-block-reward = -1     #/g' /home/"$username"/.multichain/$chainname/params.dat"
su -l $username -c "sed -ie 's/.*target-adjust-freq =.*\#/target-adjust-freq = 172800     #/g' /home/"$username"/.multichain/$chainname/params.dat"
su -l $username -c "sed -ie 's/.*max-std-tx-size =.*\#/max-std-tx-size = 100000000     #/g' /home/"$username"/.multichain/$chainname/params.dat"
su -l $username -c "sed -ie 's/.*max-std-op-returns-count =.*\#/max-std-op-returns-count = 1024     #/g' /home/"$username"/.multichain/$chainname/params.dat"
su -l $username -c "sed -ie 's/.*max-std-op-return-size =.*\#/max-std-op-return-size = 8388608     #/g' /home/"$username"/.multichain/$chainname/params.dat"
su -l $username -c "sed -ie 's/.*max-std-op-drops-count =.*\#/max-std-op-drops-count = 100     #/g' /home/"$username"/.multichain/$chainname/params.dat"
su -l $username -c "sed -ie 's/.*max-std-element-size =.*\#/max-std-element-size = 32768     #/g' /home/"$username"/.multichain/$chainname/params.dat"
su -l $username -c "sed -ie 's/.*default-network-port =.*\#/default-network-port = '$networkport'     #/g' /home/"$username"/.multichain/$chainname/params.dat"
su -l $username -c "sed -ie 's/.*default-rpc-port =.*\#/default-rpc-port = '$rpcport'     #/g' /home/"$username"/.multichain/$chainname/params.dat"
su -l $username -c "sed -ie 's/.*chain-name =.*\#/chain-name = '$chainname'     #/g' /home/"$username"/.multichain/$chainname/params.dat"
su -l $username -c " sed -ie 's/.*protocol-version =.*\#/protocol-version = '$protocol'     #/g' /home/"$username"/.multichain/$chainname/params.dat"

su -l $username -c 'echo rpcuser='$rpcuser' > /home/'$username'/.multichain/'$chainname'/multichain.conf'
su -l $username -c 'echo rpcpassword='$rpcpassword' >> /home/'$username'/.multichain/'$chainname'/multichain.conf'
su -l $username -c 'echo rpcport='$rpcport' >> /home/'$username'/.multichain/'$chainname'/multichain.conf'

echo ''
echo ''
echo '----------------------------------------'
echo ''
echo ''
echo ''
echo ''

echo '----------------------------------------'
echo -e ${CYAN}${bold}'RUNNING BLOCKCHAIN.....'${normal}${LIGHTYELLOW}
echo '----------------------------------------'

su -l $username -c 'multichaind '$chainname' -daemon'

echo ''
echo ''
echo '----------------------------------------'
echo ''
echo ''
echo ''
echo ''

echo '----------------------------------------'
echo -e ${CYAN}${bold}'LOADING CONFIGURATION.....'${normal}${LIGHTYELLOW}
echo '----------------------------------------'

sleep 6

addr=`curl --user $rpcuser:$rpcpassword --data-binary '{"jsonrpc": "1.0", "id":"curltest", "method": "getaddresses", "params": [] }' -H 'content-type: text/json;' http://127.0.0.1:$rpcport | jq -r '.result[0]'`


echo ''
echo ''
echo '----------------------------------------'
echo ''
echo ''
echo ''
echo ''

#sleep 3

echo '----------------------------------------'
echo -e ${CYAN}${bold}'CREATING AND CONFIGURING STREAMS.....'${normal}${LIGHTYELLOW}
echo '----------------------------------------'


# CREATE STREAMS
# ------ -------
su -l $username -c "multichain-cli $chainname createrawsendfrom $addr '{}' '[{\"create\":\"stream\",\"name\":\"proof_of_existence\",\"open\":false,\"details\":{\"purpose\":\"Stores hashes of files\"}}]' send"


# SUBSCRIBE STREAMS
# --------- -------
su -l $username -c "multichain-cli "$chainname" subscribe proof_of_existence"


echo ''
echo ''
echo '----------------------------------------'
echo ''
echo ''
echo ''
echo ''

echo -e ${LIGHTGREEN}${bold}'----------------------------------------'
echo -e 'BLOCKCHAIN SUCCESSFULLY SET UP!'
echo -e '----------------------------------------'${normal}${LIGHTYELLOW}

#sleep 2

echo '----------------------------------------'
echo -e ${CYAN}${bold}'SETTING UP APPLICATIONS.....'${normal}${LIGHTYELLOW}
echo '----------------------------------------'

cd /var/www/html

###
## INSTALLING & CONFIGURING HASHCHAIN
###
git clone https://github.com/Primechain/hashchain.git
# sleep 3
sudo sed -ie 's/RPC_USER =.*;/RPC_USER = "'$rpcuser'";/g' /var/www/html/hashchain/resources.php
sudo sed -ie 's/RPC_PASSWORD =.*;/RPC_PASSWORD = "'$rpcpassword'";/g' /var/www/html/hashchain/resources.php
sudo sed -ie 's/RPC_PORT =.*;/RPC_PORT = "'$rpcport'";/g' /var/www/html/hashchain/resources.php
sudo sed -ie 's/MANAGER_ADDRESS =.*;/MANAGER_ADDRESS = "'$addr'";/g' /var/www/html/hashchain/resources.php

###
## INSTALLING & CONFIGURING MULTICHAIN WEB DEMO
###
git clone https://github.com/MultiChain/multichain-web-demo.git
# sleep 3
sudo bash -c 'cp /var/www/html/multichain-web-demo/config-example.txt /var/www/html/multichain-web-demo/config.txt'
sudo sed -ie 's/default.name=.*\#/default.name='$adminNodeName'       \#/g' /var/www/html/multichain-web-demo/config.txt
sudo sed -ie 's/default.rpcuser=.*\#/default.rpcuser='$rpcuser'       \#/g' /var/www/html/multichain-web-demo/config.txt
sudo sed -ie 's/default.rpcpassword=.*\#/default.rpcpassword='$rpcpassword'       \#/g' /var/www/html/multichain-web-demo/config.txt
sudo sed -ie 's/default.rpcport=.*\#/default.rpcport='$rpcport'       \#/g' /var/www/html/multichain-web-demo/config.txt


###
## INSTALLING & CONFIGURING MULTICHAIN EXPLORER
###

git clone https://github.com/MultiChain/multichain-explorer.git
# sleep 3
sudo bash -c 'cp /var/www/html/multichain-explorer/chain1.example.conf /var/www/html/multichain-explorer/'$chainname'.conf'
sudo sed -ie 's/MultiChain chain1/'$explorerDisplayName'/g' /var/www/html/multichain-explorer/$chainname.conf
#sudo sed -ie 's/2750/'$explorerPort'/g' /var/www/html/multichain-explorer/$chainname.conf
sudo sed -ie 's/chain1/'$chainname'/g' /var/www/html/multichain-explorer/$chainname.conf
sudo sed -ie 's/host localhost.*\#/host  localhost 	#/g' /var/www/html/multichain-explorer/$chainname.conf
sudo sed -ie 's/host localhost/host 0.0.0.0/g' /var/www/html/multichain-explorer/$chainname.conf
sudo sed -ie 's/chain1.explorer.sqlite/'$chainname'.explorer.sqlite/g' /var/www/html/multichain-explorer/$chainname.conf

cd /var/www/html/multichain-explorer
su -l $username -c 'python -m Mce.abe --config '$chainname'.conf --commit-bytes 100000 --no-serve'
sleep 5
echo -ne '\n' | su -l $username -c 'nohup python -m Mce.abe --config '$chainname'.conf > /dev/null 2>/dev/null &'

# Restarting Apache to load the changes
# sudo service apache2 restart
sudo service apache2 stop
su -l $username -c 'service apache2 restart'

echo ''
echo ''
echo '----------------------------------------'
echo ''
echo ''
echo ''
echo ''

echo -e ${LIGHTGREEN}${bold}'----------------------------------------'
echo -e 'APPLICATIONS SUCCESSFULLY SET UP!'
echo -e '----------------------------------------'${normal}${NC}
echo ''
echo ''
echo ''
echo ''