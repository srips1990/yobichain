#!/bin/bash
# export TERM=xterm-color

# NC='\033[0m' # No Color
# RED='\033[0;31m'
# LIGHTGREEN='\033[1;32m'
# CYAN='\033[0;36m'
# LIGHTYELLOW='\033[1;33m'
# bold=$(tput bold)
# normal=$(tput sgr0)

chainname=$1
rpcuser=$2
rpcpassword=$3
assetName='yobicoin'
multichainVersion='1.0'
protocol=10009
networkport=61172
rpcport=15590
explorerport=2750
adminNodeName=$chainname'_Admin'
explorerDisplayName=$chainname
phpinipath='/etc/php/7.0/apache2/php.ini'
username='yobiuser'

echo '----------------------------------------'
echo -e 'INSTALLING PREREQUISITES.....'
echo '----------------------------------------'

cd .. 

sudo apt-get --assume-yes update
sudo apt-get --assume-yes install jq git vsftpd aptitude apache2-utils php-curl php7.0-curl sqlite3 libsqlite3-dev python-dev gcc python-pip
sudo pip install --upgrade pip

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
echo -e 'CONFIGURING FIREWALL.....'
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

echo -e '----------------------------------------'
echo -e 'FIREWALL SUCCESSFULLY CONFIGURED!'
echo -e '----------------------------------------'

echo '----------------------------------------'
echo -e 'INSTALLING & CONFIGURING MULTICHAIN.....'
echo '----------------------------------------'

sudo bash -c 'chmod -R 777 /var/www/html'
wget --no-verbose http://www.multichain.com/download/multichain-latest.tar.gz
sudo bash -c 'tar xvf multichain-latest.tar.gz'
sudo bash -c 'cp multichain-'$multichainVersion'*/multichain* /usr/local/bin/'

su -l $username -c  'multichain-util create '$chainname

su -l $username -c "sed -ie 's/.*root-stream-open =.*\#/root-stream-open = false     #/g' /home/"$username"/.multichain/$chainname/params.dat"
su -l $username -c "sed -ie 's/.*admin-consensus-admin =.*\#/admin-consensus-admin = 0.0     #/g' /home/"$username"/.multichain/$chainname/params.dat"
su -l $username -c "sed -ie 's/.*admin-consensus-activate =.*\#/admin-consensus-activate = 0.0     #/g' /home/"$username"/.multichain/$chainname/params.dat"
su -l $username -c "sed -ie 's/.*admin-consensus-mine =.*\#/admin-consensus-mine = 0.0     #/g' /home/"$username"/.multichain/$chainname/params.dat"
su -l $username -c "sed -ie 's/.*mining-requires-peers =.*\#/mining-requires-peers = true     #/g' /home/"$username"/.multichain/$chainname/params.dat"
su -l $username -c "sed -ie 's/.*initial-block-reward =.*\#/initial-block-reward = 0     #/g' /home/"$username"/.multichain/$chainname/params.dat"
su -l $username -c "sed -ie 's/.*first-block-reward =.*\#/first-block-reward = -1     #/g' /home/"$username"/.multichain/$chainname/params.dat"
su -l $username -c "sed -ie 's/.*target-adjust-freq =.*\#/target-adjust-freq = 172800     #/g' /home/"$username"/.multichain/$chainname/params.dat"
su -l $username -c "sed -ie 's/.*allow-min-difficulty-blocks =.*\#/allow-min-difficulty-blocks = true     #/g' /home/"$username"/.multichain/$chainname/params.dat"
su -l $username -c "sed -ie 's/.*max-std-tx-size =.*\#/max-std-tx-size = 100000000     #/g' /home/"$username"/.multichain/$chainname/params.dat"
su -l $username -c "sed -ie 's/.*max-std-op-returns-count =.*\#/max-std-op-returns-count = 1024     #/g' /home/"$username"/.multichain/$chainname/params.dat"
su -l $username -c "sed -ie 's/.*max-std-op-return-size =.*\#/max-std-op-return-size = 8388608     #/g' /home/"$username"/.multichain/$chainname/params.dat"
su -l $username -c "sed -ie 's/.*max-std-op-drops-count =.*\#/max-std-op-drops-count = 100     #/g' /home/"$username"/.multichain/$chainname/params.dat"
su -l $username -c "sed -ie 's/.*max-std-element-size =.*\#/max-std-element-size = 32768     #/g' /home/"$username"/.multichain/$chainname/params.dat"
su -l $username -c "sed -ie 's/.*default-network-port =.*\#/default-network-port = '$networkport'     #/g' /home/"$username"/.multichain/$chainname/params.dat"
su -l $username -c "sed -ie 's/.*default-rpc-port =.*\#/default-rpc-port = '$rpcport'     #/g' /home/"$username"/.multichain/$chainname/params.dat"
su -l $username -c "sed -ie 's/.*chain-name =.*\#/chain-name = '$chainname'     #/g' /home/"$username"/.multichain/$chainname/params.dat"
# su -l $username -c " sed -ie 's/.*protocol-version =.*\#/protocol-version = '$protocol'     #/g' /home/"$username"/.multichain/$chainname/params.dat"

su -l $username -c "echo rpcuser='$rpcuser' > /home/$username/.multichain/$chainname/multichain.conf"
su -l $username -c "echo rpcpassword='$rpcpassword' >> /home/$username/.multichain/$chainname/multichain.conf"
su -l $username -c 'echo rpcport='$rpcport' >> /home/'$username'/.multichain/'$chainname'/multichain.conf'

echo ''
echo ''
echo '----------------------------------------'
echo ''
echo ''
echo ''
echo ''

echo '----------------------------------------'
echo -e 'RUNNING BLOCKCHAIN.....'
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
echo -e 'LOADING CONFIGURATION.....'
echo '----------------------------------------'

sleep 6

addr=`curl --user $rpcuser:$rpcpassword --data-binary '{"jsonrpc": "1.0", "id":"curltest", "method": "getaddresses", "params": [] }' -H 'content-type: text/json;' http://127.0.0.1:$rpcport | jq -r '.result[0]'`

su -l $username -c  "multichain-cli "$chainname" issue "$addr" '{\"name\":\""$assetName"\", \"open\":true}' 1000000000000 0.01 0 '{\"description\":\"This is a smart asset for peer-to-peer transaction\"}'"


echo ''
echo ''
echo '----------------------------------------'
echo ''
echo ''
echo ''
echo ''


echo '----------------------------------------'
echo -e 'CREATING AND CONFIGURING STREAMS.....'
echo '----------------------------------------'


# CREATE STREAMS
# ------ -------
su -l $username -c "multichain-cli $chainname createrawsendfrom $addr '{}' '[{\"create\":\"stream\",\"name\":\"proof_of_existence\",\"open\":false,\"details\":{\"purpose\":\"Stores hashes of files\"}}]' send"

su -l $username -c  "multichain-cli "$chainname" createrawsendfrom "$addr" '{}' '[{\"create\":\"stream\",\"name\":\"users_credentials\",\"open\":false,\"details\":{\"purpose\":\"Stores Users Credentials\"}}]' send"
su -l $username -c  "multichain-cli "$chainname" createrawsendfrom "$addr" '{}' '[{\"create\":\"stream\",\"name\":\"users_details\",\"open\":false,\"details\":{\"purpose\":\"Stores Users Details\"}}]' send"
su -l $username -c  "multichain-cli "$chainname" createrawsendfrom "$addr" '{}' '[{\"create\":\"stream\",\"name\":\"users_addresses\",\"open\":false,\"details\":{\"purpose\":\"Stores addresses owned by users\"}}]' send"
su -l $username -c  "multichain-cli "$chainname" createrawsendfrom "$addr" '{}' '[{\"create\":\"stream\",\"name\":\"users_session\",\"open\":false,\"details\":{\"purpose\":\"Stores session history for users\"}}]' send"

su -l $username -c  "multichain-cli "$chainname" createrawsendfrom "$addr" '{}' '[{\"create\":\"stream\",\"name\":\"vault\",\"open\":false,\"details\":{\"purpose\":\"Stores documents uploaded by users\"}}]' send"

su -l $username -c  "multichain-cli "$chainname" createrawsendfrom "$addr" '{}' '[{\"create\":\"stream\",\"name\":\"contract_details\",\"open\":false,\"details\":{\"purpose\":\"Stores basic details of contracts\"}}]' send"
su -l $username -c  "multichain-cli "$chainname" createrawsendfrom "$addr" '{}' '[{\"create\":\"stream\",\"name\":\"contract_files\",\"open\":false,\"details\":{\"purpose\":\"Stores files related to contracts\"}}]' send"
su -l $username -c  "multichain-cli "$chainname" createrawsendfrom "$addr" '{}' '[{\"create\":\"stream\",\"name\":\"contract_signatures\",\"open\":false,\"details\":{\"purpose\":\"Stores signatures of contracts\"}}]' send"
su -l $username -c  "multichain-cli "$chainname" createrawsendfrom "$addr" '{}' '[{\"create\":\"stream\",\"name\":\"contracts_signed\",\"open\":false,\"details\":{\"purpose\":\"Stores the list of contracts signed by each user\"}}]' send"
su -l $username -c  "multichain-cli "$chainname" createrawsendfrom "$addr" '{}' '[{\"create\":\"stream\",\"name\":\"contract_invited_signees\",\"open\":false,\"details\":{\"purpose\":\"Stores the list of users invited to sign a contract\"}}]' send"


# SUBSCRIBE STREAMS
# --------- -------
su -l $username -c "multichain-cli "$chainname" subscribe proof_of_existence"

su -l $username -c  "multichain-cli "$chainname" subscribe users_credentials"
su -l $username -c  "multichain-cli "$chainname" subscribe users_details"
su -l $username -c  "multichain-cli "$chainname" subscribe users_addresses"
su -l $username -c  "multichain-cli "$chainname" subscribe users_session"

su -l $username -c  "multichain-cli "$chainname" subscribe vault"

su -l $username -c  "multichain-cli "$chainname" subscribe contract_details"
su -l $username -c  "multichain-cli "$chainname" subscribe contract_files"
su -l $username -c  "multichain-cli "$chainname" subscribe contract_signatures"
su -l $username -c  "multichain-cli "$chainname" subscribe contracts_signed"
su -l $username -c  "multichain-cli "$chainname" subscribe contract_invited_signees"



echo ''
echo ''
echo '----------------------------------------'
echo ''
echo ''
echo ''
echo ''

echo -e '----------------------------------------'
echo -e 'BLOCKCHAIN SUCCESSFULLY SET UP!'
echo -e '----------------------------------------'


echo '----------------------------------------'
echo -e 'SETTING UP APPLICATIONS.....'
echo '----------------------------------------'

cd /var/www/html	# Changing current directory to web server's root directory


###
## INSTALLING & CONFIGURING HASHCHAIN
###
git clone https://github.com/Primechain/hashchain.git

# Configuring Hashchain
sudo sed -ie 's/RPC_USER =.*;/RPC_USER = "'$rpcuser'";/g' /var/www/html/hashchain/resources.php
sudo sed -ie 's/RPC_PASSWORD =.*;/RPC_PASSWORD = "'$rpcpassword'";/g' /var/www/html/hashchain/resources.php
sudo sed -ie 's/RPC_PORT =.*;/RPC_PORT = "'$rpcport'";/g' /var/www/html/hashchain/resources.php
sudo sed -ie 's/MANAGER_ADDRESS =.*;/MANAGER_ADDRESS = "'$addr'";/g' /var/www/html/hashchain/resources.php


###
## INSTALLING & CONFIGURING MULTICHAIN WEB DEMO
###
git clone https://github.com/MultiChain/multichain-web-demo.git

# Configuring Web Demo
sudo bash -c 'cp /var/www/html/multichain-web-demo/config-example.txt /var/www/html/multichain-web-demo/config.txt'
sudo sed -ie 's/default.name=.*\#/default.name='$adminNodeName'       \#/g' /var/www/html/multichain-web-demo/config.txt
sudo sed -ie 's/default.rpcuser=.*\#/default.rpcuser='$rpcuser'       \#/g' /var/www/html/multichain-web-demo/config.txt
sudo sed -ie 's/default.rpcpassword=.*\#/default.rpcpassword='$rpcpassword'       \#/g' /var/www/html/multichain-web-demo/config.txt
sudo sed -ie 's/default.rpcport=.*\#/default.rpcport='$rpcport'       \#/g' /var/www/html/multichain-web-demo/config.txt


###
## INSTALLING & CONFIGURING MULTICHAIN EXPLORER
###

cd /home/$username
git clone https://github.com/MultiChain/multichain-explorer.git
cd multichain-explorer
sudo python setup.py install

sudo bash -c 'cp /home/'$username'/multichain-explorer/chain1.example.conf /home/'$username'/multichain-explorer/'$chainname'.conf'

sudo sed -ie 's/MultiChain chain1/'$explorerDisplayName'/g' /home/$username/multichain-explorer/$chainname.conf
sudo sed -ie 's/2750/'$explorerport'/g' /home/$username/multichain-explorer/$chainname.conf
sudo sed -ie 's/chain1/'$chainname'/g' /home/$username/multichain-explorer/$chainname.conf
sudo sed -ie 's/host localhost.*\#/host  localhost 	#/g' /home/$username/multichain-explorer/$chainname.conf
sudo sed -ie 's/host localhost/host 0.0.0.0/g' /home/$username/multichain-explorer/$chainname.conf
sudo sed -ie 's/chain1.explorer.sqlite/'$chainname'.explorer.sqlite/g' /home/$username/multichain-explorer/$chainname.conf

su -l $username -c "python -m Mce.abe --config /home/"$username"/multichain-explorer/"$chainname".conf --commit-bytes 100000 --no-serve"
sleep 5
su -l $username -c "echo -ne '\n' | nohup python -m Mce.abe --config /home/"$username"/multichain-explorer/"$chainname".conf > /dev/null 2>/dev/null &"

# Restarting Apache to load the changes
sudo service apache2 restart

echo ''
echo ''
echo '----------------------------------------'
echo ''
echo ''
echo ''
echo ''

echo -e '----------------------------------------'
echo -e 'APPLICATIONS SUCCESSFULLY SET UP!'
echo -e '----------------------------------------'
echo ''
echo ''
echo ''
echo ''