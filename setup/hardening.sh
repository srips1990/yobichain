#!/bin/bash
# export TERM=xterm-color

# NC='\033[0m' # No Color
# RED='\033[0;31m'
# LIGHTGREEN='\033[1;32m'
# CYAN='\033[0;36m'
# LIGHTYELLOW='\033[1;33m'
# bold=$(tput bold)
# normal=$(tput sgr0)

source yobichain.conf


# Update the system
echo '----------------------------------------'
echo -e 'UPDATING THE SYSTEM:'
echo '----------------------------------------'
sudo apt-get -y update
sudo apt-get -y upgrade
sudo apt-get -y autoremove
sudo apt-get -y autoclean

sleep 3
# Enable automatic security updates
# echo '----------------------------------------'
# echo -e 'ENABLING AUTOMATIC SECURITY UPDATES:'
# echo '----------------------------------------'
# echo 'unattended-upgrades unattended-upgrades/configure boolean true' | debconf-set-selections

# sudo apt-get --assume-yes install unattended-upgrades
# sleep 4
# sudo dpkg-reconfigure -plow unattended-upgrades

sleep 3

# Setting up swap partition
echo '----------------------------------------'
echo -e 'SETTING UP SWAP PARTITION:'
echo '----------------------------------------'

sudo dd if=/dev/zero of=/swapfile bs=4M count=500
sudo mkswap /swapfile
sudo swapon /swapfile
sudo swapon -s
echo '/swapfile swap swap defaults 10 10' >> /etc/fstab
sudo echo 20 >> /proc/sys/vm/swappiness
sudo echo vm.swappiness = 20 >> /etc/sysctl.conf


sleep 3
echo '----------------------------------------'
echo -e 'DISABLING IPV6:'
echo '----------------------------------------'

echo 'net.ipv6.conf.all.disable_ipv6 = 1' >> /etc/sysctl.conf
echo 'net.ipv6.conf.default.disable_ipv6 = 1' >> /etc/sysctl.conf
echo 'net.ipv6.conf.lo.disable_ipv6 = 1' >> /etc/sysctl.conf
sudo sysctl -p


# sleep 3
# echo '----------------------------------------'
# echo -e 'DISABLING IRQ BALANCE:'
# echo '----------------------------------------'

# sudo sed -i 's/ENABLED=.*/ENABLED="0"/g' /etc/default/irqbalance


sleep 3
echo '----------------------------------------'
echo -e 'VERIFYING OPENSSL VERSION:'
echo '----------------------------------------'

sudo apt-get -y update
sudo apt-get -y upgrade openssl libssl-dev
sudo apt-cache policy openssl libssl-dev


sleep 3
echo '----------------------------------------'
echo -e 'SECURING SHARED MEMORY:'
echo '----------------------------------------'

echo 'tmpfs     /run/shm    tmpfs     ro,noexec,nosuid        0       0' >> /etc/fstab
sudo mount -a


sleep 3
echo '----------------------------------------'
echo -e 'SECURING /tmp:'
echo '----------------------------------------'

sudo dd if=/dev/zero of=/usr/tmpDSK bs=1024 count=1024000
sudo mkfs.ext4 /usr/tmpDSK
sudo cp -avr /tmp /tmpbackup

#Mount the new /tmp partition, and set the right permissions.
sudo mount -t tmpfs -o loop,noexec,nosuid,rw /usr/tmpDSK /tmp
sudo chmod 1777 /tmp

#Copy the data from the backup folder, and remove the backup folder.
sudo cp -avr /tmpbackup/* /tmp/
sudo rm -rf /tmpbackup

# Set the /tmp in the fbtab.
echo '/usr/tmpDSK /tmp tmpfs loop,nosuid,noexec,rw 0 0' >> /etc/fstab

#Test fstab entry.
sudo mount -a


sleep 3
echo '----------------------------------------'
echo -e 'SECURING /var/tmp:'
echo '----------------------------------------'

#create a symbolic link that makes /var/tmp point to /tmp.
sudo mv /var/tmp /var/tmpold
sudo ln -s /tmp /var/tmp
sudo cp -avr /var/tmpold/* /tmp/


sleep 3
echo '----------------------------------------'
echo -e 'SET SECURITY LIMITS:'
echo '----------------------------------------'

echo 'user1 hard nproc 100' >> /etc/security/limits.conf


sleep 3
echo '----------------------------------------'
echo -e 'SECURING SERVER AGAINST BASH VULNERABILITY:'
echo '----------------------------------------'

sudo apt-get -y update
sudo apt-get -y install --only-upgrade bash

echo ''
echo ''
echo -e '----------HARDENING SUCCESSFUL----------'
echo ''
echo ''
echo ''
echo ''