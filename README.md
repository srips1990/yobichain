# Yobichain: Build your own blockchain in minutes

> WARNING: Yobichain is intended for experimenting and learning, NOT for a production environment.

![Image of Yobi](yobichain-web/images/yobi1.png?raw=true)


YobiChain is your very own private blockchain ecosystem preloaded with a database server, web server, FTP server, D.A.V.E. (Data Authentication & Verification) and S.A.M. (Smart Asset Management). Yobichain runs on the [Multichain](https://github.com/MultiChain) blockchain framework.

YobiChain is ideal for

* Startups looking to quickly build a blockchain powered prototype, PoC or MVP.
* Serious researchers / enthusiasts looking to experiment with a live blockchain.
* In-house teams experimenting with blockchain technology.

## Installation

To set up Yobichain, you will need 1 server (min 2 GB RAM, 2 CPUs) running Ubuntu 18.04 x64. This section presumes that you have root access to the server mentioned above and want to install Yobichain for all users on the system.

**Step 1.**  Install git and clone the yobichain repository

    sudo git clone https://github.com/srips1990/yobichain
	
**Step 2.** Navigate to the relevant directory.

    cd yobichain/setup

**Step 3.** Modify the values of the sendinblue_api_key and sendgrid_api_key parameters. Send-in-blue is used for transactional SMS (optional) and sendgrid is used for transactional emails.

    sudo nano yobichain.conf
	
**Step 4.** Harden the operating system, install and configure multichain, phpmyadmin and the FTP server.

    sudo bash -e master.sh

**Step 5.** Press ENTER when the following prompt appears:

	"Press [ENTER] to continue or ctrl-c to cancel adding it"
   
**Step 6.**  Once the setup completes, the credentials for FTP, database, Multichain API & Yobichain-Web are displayed on the screen. Copy and save this on your machine. The output is also stored in *yobichain.out* under the home directory of the user.

**Step 7.** Once the setup completes, the Yobichain Web interface can be accessed through the following URL:
    
    http://<ip_address>/yobichain-web

**Step 8.** You can login using the default login credentials generated for Yobichain-Web during the setup.
	
**Step 9.** To create a new user, you may click on "Create new user" and register a user.

**Step 10.** You'll receive an activation link on the registered email ID. Click on it to receive login credentials via email.
    
    To access the Yobichain web application, visit 'http://<IP Address>yobichain-web'

    To access Multichain Exporer, visit 'http://<IP Address>:2750'

    To access PHPMyAdmin, visit 'http://<IP Address>/mysql_dashboard'
    
    To access hashchain visit 'http://<IP Address>/hashchain'

## Contributors

Yobichain is maintained by Sripathi Srinivasan, Former Head of Engineering and Chief Cyber Security Officer, [Primechain Technologies Pvt. Ltd.](http://www.primechaintech.com). 
