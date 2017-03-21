#!/bin/bash
export TERM=xterm-color

NC='\033[0m' # No Color
RED='\033[0;31m'
LIGHTGREEN='\033[1;32m'
CYAN='\033[0;36m'
LIGHTYELLOW='\033[1;33m'
bold=$(tput bold)
normal=$(tput sgr0)

username='yobiuser'
passwd=$1

echo '----------------------------------------'
echo -e ${CYAN}${bold}'UPDATING THE SYSTEM:'${normal}${LIGHTYELLOW}
echo '----------------------------------------'

sudo apt-get -y update
sudo apt-get -y upgrade
sudo apt-get -y autoremove
sudo apt-get -y autoclean

sleep 3
echo '----------------------------------------'
echo -e ${CYAN}${bold}'ENABLING AUTOMATIC SECURITY UPDATES:'${normal}${LIGHTYELLOW}
echo '----------------------------------------'

echo 'unattended-upgrades unattended-upgrades/enable_auto_updates boolean true' | debconf-set-selections

sudo apt-get --assume-yes install unattended-upgrades
sleep 4
sudo dpkg-reconfigure -plow unattended-upgrades


echo '----------------------------------------'
echo -e ${CYAN}${bold}'SETTING UP $username USER ACCOUNT:'${normal}${LIGHTYELLOW}
echo '----------------------------------------'

sudo useradd -d /home/$username -s /bin/bash -m $username
sudo usermod -a -G sudo $username
echo $username":"$passwd | sudo chpasswd
#sudo passwd $username

echo '$username ALL=(ALL) NOPASSWD: ALL' >> /etc/sudoers


sleep 3
echo '----------------------------------------'
echo -e ${CYAN}${bold}'SETTING UP SWAP PARTITION:'${normal}${LIGHTYELLOW}
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
echo -e ${CYAN}${bold}'DISABLING IPV6:'${normal}${LIGHTYELLOW}
echo '----------------------------------------'

echo 'net.ipv6.conf.all.disable_ipv6 = 1' >> /etc/sysctl.conf
echo 'net.ipv6.conf.default.disable_ipv6 = 1' >> /etc/sysctl.conf
echo 'net.ipv6.conf.lo.disable_ipv6 = 1' >> /etc/sysctl.conf
sudo sysctl -p


sleep 3
echo '----------------------------------------'
echo -e ${CYAN}${bold}'DISABLING IRQ BALANCE:'${normal}${LIGHTYELLOW}
echo '----------------------------------------'

sudo sed -ie 's/ENABLED=.*/ENABLED="0"/g' /etc/default/irqbalance


sleep 3
echo '----------------------------------------'
echo -e ${CYAN}${bold}'VERIFYING OPENSSL VERSION:'${normal}${LIGHTYELLOW}
echo '----------------------------------------'

sudo apt-get -y update
sudo apt-get -y upgrade openssl libssl-dev
sudo apt-cache policy openssl libssl-dev


sleep 3
echo '----------------------------------------'
echo -e ${CYAN}${bold}'SECURING SHARED MEMORY:'${normal}${LIGHTYELLOW}
echo '----------------------------------------'

echo 'tmpfs     /run/shm    tmpfs     ro,noexec,nosuid        0       0' >> /etc/fstab
sudo mount -a


sleep 3
echo '----------------------------------------'
echo -e ${CYAN}${bold}'SECURING /tmp:'${normal}${LIGHTYELLOW}
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
echo -e ${CYAN}${bold}'SECURING /var/tmp:'${normal}${LIGHTYELLOW}
echo '----------------------------------------'

#create a symbolic link that makes /var/tmp point to /tmp.
sudo mv /var/tmp /var/tmpold
sudo ln -s /tmp /var/tmp
sudo cp -avr /var/tmpold/* /tmp/


sleep 3
echo '----------------------------------------'
echo -e ${CYAN}${bold}'SET SECURITY LIMITS:'${normal}${LIGHTYELLOW}
echo '----------------------------------------'

echo 'user1 hard nproc 100' >> /etc/security/limits.conf


sleep 3
echo '----------------------------------------'
echo -e ${CYAN}${bold}'SECURING SERVER AGAINST BASH VULNERABILITY:'${normal}${LIGHTYELLOW}
echo '----------------------------------------'

sudo apt-get -y update
sudo apt-get -y install --only-upgrade bash

echo ''
echo ''
echo -e ${CYAN}${bold}'----------HARDENING SUCCESSFUL----------'${normal}${NC}
echo ''
echo ''
echo ''
echo ''