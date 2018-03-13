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
multichainVersion='1.0.4'
protocol=10010
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
sudo apt-get --assume-yes install jq git vsftpd aptitude apache2-utils php7.0-curl sqlite3 libsqlite3-dev python-dev gcc python-pip
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
wget --no-verbose http://www.multichain.com/download/multichain-$multichainVersion.tar.gz
sudo bash -c 'tar xvf multichain-'$multichainVersion'.tar.gz'
sudo bash -c 'cp multichain-'$multichainVersion'*/multichain* /usr/local/bin/'

su -l $username -c  'multichain-util create '$chainname #$protocol

su -l $username -c "sed -ie 's/.*root-stream-open =.*\#/root-stream-open = false     #/g' /home/"$username"/.multichain/$chainname/params.dat"
su -l $username -c "sed -ie 's/.*admin-consensus-admin =.*\#/admin-consensus-admin = 0.0     #/g' /home/"$username"/.multichain/$chainname/params.dat"
su -l $username -c "sed -ie 's/.*admin-consensus-activate =.*\#/admin-consensus-activate = 0.0     #/g' /home/"$username"/.multichain/$chainname/params.dat"
su -l $username -c "sed -ie 's/.*admin-consensus-mine =.*\#/admin-consensus-mine = 0.0     #/g' /home/"$username"/.multichain/$chainname/params.dat"
su -l $username -c "sed -ie 's/.*mining-requires-peers =.*\#/mining-requires-peers = true     #/g' /home/"$username"/.multichain/$chainname/params.dat"
su -l $username -c "sed -ie 's/.*initial-block-reward =.*\#/initial-block-reward = 0     #/g' /home/"$username"/.multichain/$chainname/params.dat"
su -l $username -c "sed -ie 's/.*first-block-reward =.*\#/first-block-reward = -1     #/g' /home/"$username"/.multichain/$chainname/params.dat"
su -l $username -c "sed -ie 's/.*skip-pow-check =.*\#/skip-pow-check = true     #/g' /home/"$username"/.multichain/$chainname/params.dat"
su -l $username -c "sed -ie 's/.*pow-minimum-bits =.*\#/pow-minimum-bits = 4     #/g' /home/"$username"/.multichain/$chainname/params.dat"
su -l $username -c "sed -ie 's/.*target-adjust-freq =.*\#/target-adjust-freq = -1     #/g' /home/"$username"/.multichain/$chainname/params.dat"
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

echo -e '----------------------------------------'
echo -e 'BLOCKCHAIN SUCCESSFULLY SET UP!'
echo -e '----------------------------------------'
echo ''
echo ''
echo ''
echo ''
