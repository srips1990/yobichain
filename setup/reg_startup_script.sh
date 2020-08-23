#!/bin/bash

source yobichain.conf

homedir=`su -l $linux_admin_user -c 'cd ~ && pwd'`

### Creating Startup Script

cat > $homedir/$startup_script_name << EOF

sudo su -l $linux_admin_user -c "multichaind $chainname -daemon"

sleep 6

sudo su -l $linux_admin_user -c "python -m Mce.abe --config $homedir/multichain-explorer/$chainname.conf --commit-bytes 100000 --no-serve"

sleep 5

sudo su -l $linux_admin_user -c "echo -ne '\n' | nohup python -m Mce.abe --config $homedir/multichain-explorer/$chainname.conf > /dev/null 2>/dev/null &"

EOF

chmod ugo+x $homedir/$startup_script_name

### Creating Startup Service

cat > /etc/systemd/system/$startup_service_name.service << EOF
[Unit]
Description=yobichain script

[Service]
ExecStart=/bin/bash $homedir/$startup_script_name

[Install]
WantedBy=multi-user.target
 
EOF

systemctl enable $startup_service_name