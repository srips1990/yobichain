#!/bin/bash

sudo apt-get --assume-yes install jq git curl

username=$1			# User name
chainname=$2		# Chain name
webserver_activeDirectory='/var/www/html'
assetName='yobicoin'

homedir=`su -l $username -c 'cd ~ && pwd'`

source $homedir/.multichain/$chainname/multichain.conf

addr=`curl --user $rpcuser:$rpcpassword --data-binary '{"jsonrpc": "1.0", "id":"curltest", "method": "getaddresses", "params": [] }' -H 'content-type: text/json;' http://127.0.0.1:$rpcport | jq -r '.result[0]'`

su -l $username -c  "multichain-cli "$chainname" issue "$addr" '{\"name\":\""$assetName"\", \"open\":true}' 1000000000000 0.01 0 '{\"description\":\"This is a smart asset for peer-to-peer transaction\"}'"


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


###
## INSTALLING & CONFIGURING YOBIAPPS
###
cd $webserver_activeDirectory
git clone https://github.com/Primechain/yobiapps.git

# Configuring Yobiapps
sudo sed -ie 's/$CHAIN_NAME =.*;/$CHAIN_NAME = "'$chainname'";/g' $webserver_activeDirectory/yobiapps/config.php
sudo sed -ie 's/RPC_USER =.*;/RPC_USER = "'$rpcuser'";/g' $webserver_activeDirectory/yobiapps/config.php
sudo sed -ie 's/RPC_PASSWORD =.*;/RPC_PASSWORD = "'$rpcpassword'";/g' $webserver_activeDirectory/yobiapps/config.php
sudo sed -ie 's/RPC_PORT =.*;/RPC_PORT = "'$rpcport'";/g' $webserver_activeDirectory/yobiapps/config.php
sudo sed -ie 's/MANAGER_ADDRESS =.*;/MANAGER_ADDRESS = "'$addr'";/g' $webserver_activeDirectory/yobiapps/config.php
