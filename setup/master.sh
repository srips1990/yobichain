#!/bin/bash

outputfilepath=~/yobichain.out

rm -rf $outputfilepath

db_root_pass=`< /dev/urandom tr -dc A-Za-z0-9 | head -c20; echo`
db_admin_user=`< /dev/urandom tr -dc A-Za-z0-9 | head -c15; echo`
db_admin_pass=`< /dev/urandom tr -dc A-Za-z0-9 | head -c20; echo`

ftpusername=`< /dev/urandom tr -dc A-Za-z0-9 | head -c15; echo`
ftppasswd=`< /dev/urandom tr -dc A-Za-z0-9 | head -c20; echo`

rpcuser=`< /dev/urandom tr -dc A-Za-z0-9 | head -c15; echo`
rpcpassword=`< /dev/urandom tr -dc A-Za-z0-9 | head -c20; echo`

bash -e create_linux_admin_user.sh
bash -e hardening.sh
bash -e lamp.sh $db_root_pass
bash -e phpmyadmin.sh
bash -e ftp.sh $ftpusername $ftppasswd
bash -e core.sh $rpcuser $rpcpassword $db_admin_user $db_admin_pass
bash -e configure-yobichain.sh $db_root_pass $db_admin_user $db_admin_pass

echo -e \
'--------------------------------------------'"\n"\
'API CREDENTIALS'"\n"\
'--------------------------------------------'"\n"\
'rpcuser='$rpcuser"\n"\
'rpcpassword='$rpcpassword"\n\n"\
 > $outputfilepath

echo -e \
'--------------------------------------------'"\n"\
'MYSQL CREDENTIALS'"\n"\
'--------------------------------------------'"\n"\
'mysql_root_user_name=root'"\n"\
'mysql_root_pass='$db_root_pass"\n"\
'yobichain_db_admin_user='$db_admin_user"\n"\
'yobichain_db_admin_pass='$db_admin_pass"\n\n"\
 >> $outputfilepath

echo -e \
'--------------------------------------------'"\n"\
'FTP CREDENTIALS'"\n"\
'--------------------------------------------'"\n"\
'ftpusername='$ftpusername"\n"\
'ftppasswd='$ftppasswd"\n\n"\
 >> $outputfilepath

cat $outputfilepath
 
echo ''
echo ''

echo -e '========================================'
echo -e 'SET UP COMPLETED SUCCESSFULLY!'
echo -e '========================================'
echo ''
echo ''
echo ''