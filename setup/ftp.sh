#!/bin/bash

# export TERM=xterm-color

# NC='\033[0m' # No Color
# RED='\033[0;31m'
# LIGHTGREEN='\033[1;32m'
# CYAN='\033[0;36m'
# LIGHTYELLOW='\033[1;33m'
# bold=$(tput bold)
# normal=$(tput sgr0)

# username=$(whoami)
source yobichain.conf

ftpusername=$1
ftppasswd=$2

echo '----------------------------------------'
echo -e 'INSTALLING PREREQUISITES.....'
echo '----------------------------------------'

sudo apt-get --assume-yes update
sudo apt-get --assume-yes install vsftpd

echo ''
echo ''
echo '----------------------------------------'
echo ''
echo ''
echo ''
echo ''


echo '----------------------------------------'
echo -e 'CONFIGURING FTP.....'
echo '----------------------------------------'

if ! id $ftpusername >/dev/null 2>&1; then
	sudo useradd -d $webServerActiveDirectory $ftpusername
	echo $ftpusername":"$ftppasswd | sudo chpasswd
	sudo sed -i 's/.*anonymous_enable=.*/anonymous_enable=NO/g' /etc/vsftpd.conf
	sudo sed -i 's/.*local_enable=.*/local_enable=YES/g' /etc/vsftpd.conf
	sudo sed -i 's/.*write_enable=.*/write_enable=YES/g' /etc/vsftpd.conf
	sudo sed -i 's/.*chroot_local_user=.*/chroot_local_user=YES/g' /etc/vsftpd.conf
	sudo sed -i 's/.*chroot_list_enable=.*/chroot_list_enable=YES/g' /etc/vsftpd.conf
	sudo sed -i 's/.*chroot_list_file=.*/chroot_list_file=\/etc\/vsftpd.chroot_list/g' /etc/vsftpd.conf
	sudo bash -c 'echo '$ftpusername' > /etc/vsftpd.chroot_list'
	sudo chown $ftpusername: $webServerActiveDirectory
	sudo chmod u+w $webServerActiveDirectory

	echo ''
	echo ''
	echo '----------------------------------------'
	echo ''
	echo ''
	echo ''
	echo ''

	echo -e '----------------------------------------'
	echo -e 'SECURE FTP SUCCESSFULLY SET UP!'
	echo -e '----------------------------------------'
	echo ''
	echo ''
fi
