source yobichain.conf

sudo apt-get -y install php"$phpversion"-mbstring php"$phpversion"-gettext php"$phpversion"-mcrypt
sudo phpenmod mcrypt
sudo phpenmod mbstring

sudo systemctl restart apache2

cd $webServerActiveDirectory
sudo rm -rf $archiveFileName		# Removing existing archive file
sudo wget $phpmyadminDownloadPath/$archiveFileName
sudo tar -xvzf $archiveFileName
sudo rm -rf $archiveFileName
sudo mv phpMyAdmin* mysql_dashboard
cd mysql_dashboard
sudo cp config.sample.inc.php config.inc.php
sudo chmod -R 755 $webServerActiveDirectory/mysql_dashboard
sudo sed -i 's/localhost/'$db_host_name'/g' config.inc.php
echo ''
echo ''
echo '----------'
echo ' Complete'
echo '----------'