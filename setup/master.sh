#!/bin/bash

source yobichain.conf

db_root_pass=`< /dev/urandom tr -dc A-Za-z0-9 | head -c20; echo`
db_admin_user=`< /dev/urandom tr -dc A-Za-z0-9 | head -c15; echo`
db_admin_pass=`< /dev/urandom tr -dc A-Za-z0-9 | head -c20; echo`

ftpusername=`< /dev/urandom tr -dc A-Za-z0-9 | head -c15; echo`
ftppasswd=`< /dev/urandom tr -dc A-Za-z0-9 | head -c20; echo`

rpcuser=`< /dev/urandom tr -dc A-Za-z0-9 | head -c15; echo`
rpcpassword=`< /dev/urandom tr -dc A-Za-z0-9 | head -c20; echo`

yobiweb_user_pass=`< /dev/urandom tr -dc A-Za-z0-9 | head -c20; echo`


## CHECKING IF HARDENING OPTION IS PROVIDED IN COMMAND-LINE ARGUMENT
if [[ $# -gt 0 ]] ; then
	if [[ $1 == true || $1 == false ]] ; then
		hardening_enabled=$1
	fi
fi

## CREATING A NEW LINUX USER
bash -e create_linux_admin_user.sh
homedir=`su -l $linux_admin_user -c 'echo ~'`

## SETTING THE PATH OF THE OUTPUT FILE WHICH WOULD CONTAIN YOBICHAIN RELATED CREDENTIALS
outputfilepath=$homedir/$output_file_name
cat > $outputfilepath.tmp << EOF

--------------------------------------------
API CREDENTIALS
--------------------------------------------
rpcuser=$rpcuser
rpcpassword=$rpcpassword


--------------------------------------------
MYSQL CREDENTIALS
--------------------------------------------
mysql_root_user_name=root
mysql_root_pass=$db_root_pass
yobichain_db_admin_user=$db_admin_user
yobichain_db_admin_pass=$db_admin_pass


--------------------------------------------
FTP CREDENTIALS
--------------------------------------------
ftpusername=$ftpusername
ftppasswd=$ftppasswd

--------------------------------------------
YOBICHAIN-WEB CREDENTIALS
--------------------------------------------
yobiweb_user_email=$yobiweb_user_email
yobiweb_user_pass=$yobiweb_user_pass

EOF

## HARDENS THE SYSTEM IF THE OPTION IS SET
if $hardening_enabled ; then
	bash -e hardening.sh
fi

bash -e lamp.sh $db_root_pass
bash -e phpmyadmin.sh
bash -e ftp.sh $ftpusername $ftppasswd
bash -e core.sh $rpcuser $rpcpassword $db_admin_user $db_admin_pass
bash -e configure-yobichain.sh $db_root_pass $db_admin_user $db_admin_pass $yobiweb_user_pass
bash -e reg_startup_script.sh

rm -rf $outputfilepath
cp $outputfilepath.tmp $outputfilepath
rm -f $outputfilepath.tmp
cat $outputfilepath
sudo chown $linux_admin_user: $outputfilepath
 
echo ''
echo ''

echo -e '========================================'
echo -e 'SET UP COMPLETED SUCCESSFULLY!'
echo -e '========================================'
echo ''
echo ''
echo ''