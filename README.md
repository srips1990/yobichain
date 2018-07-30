Yobichain: Build your own blockchain in minutes
=========

> WARNING: Yobichain is intended for experimenting and learning, NOT for a production environment.

![Image of Yobi](http://www.primechaintech.com/assets/base/img/content/github/github_yobichain.png)

YobiChain is your very own private blockchain ecosystem preloaded with database, web & FTP servers and HashChain, a simple blockchain powered drag n drop solution for authenticating and verifying electronic records.

Yobichain is maintained by Yobichain is maintained by Sripathi Srinivasan, Blockchain Engineer, Primechain Technologies Private Limited.
[Primechain Technologies Pvt. Ltd.](http://www.primechaintech.com). Yobichain runs on [Multichain](https://github.com/MultiChain).

YobiChain is ideal for

* Startups looking to quickly build a blockchain powered prototype, PoC or MVP.
* Serious researchers / enthusiasts looking to experiment with a live blockchain.
* In-house teams experimenting with blockchain technology.


System Requirements
-------------------

To set up Yobichain, you will need 1 server (min 2 GB RAM, 2 CPUs) running Ubuntu 16.04.2 x64. 

Installation
------------

This section presumes that you have root access to the server mentioned above and want to install Yobichain for all users on the system.

**Step 1.**  Install git and clone the yobichain repository

    sudo git clone https://github.com/Primechain/yobichain
	

**Step 2.** 

    cd yobichain/setup

**Step 3.** 

    sudo nano yobichain.conf
	
   Modify the values of the following parameters:
   sendinblue_api_key
   sendgrid_api_key
   
**Step 4.** 

    sudo bash -e master.sh

**Step 5.** 

    Press ENTER when the following prompt appears:
	"Press [ENTER] to continue or ctrl-c to cancel adding it"
   
**Step 6.** 

    Once the setup completes, the credentials for FTP, database & Multichain API are displayed on the screen.
	The output is also stored in yobichain.out under the home directory of the user.
	
**Step 7.** 

    Once the setup completes, the Yobichain Web interface can be accessed through the following URL:
    http://<ip_address>/yobichain-web
	
**Step 8.** 

    Click on "Create new user" and register a user.

**Step 9.** 

    You'll receive an activation link on the registered email ID. Click on it to receive login credentials via email.
-------------------------

To access Multichain Exporer, visit `http://<IP Address>:2750`

To access PHPMyAdmin, visit `http://<IP Address>/mysql_dashboard`

To use hashchain, see the instructions at [https://github.com/Primechain/hashchain/blob/master/README.md](https://github.com/Primechain/hashchain/blob/master/README.md)


Notes
-----

This will:
1. harden the base operating system against cyber attacks

2. set up a Multichain blockchain using a pre-defined configuration

3. set up an FTP server

4. set up Yobichain-Web, a web application for Smart Asset management, Secure storage & retrieval of files.

5. set up Multichain Explorer

6. set up HashChain, a simple blockchain powered drag n drop solution for authenticating and verifying electronic records.


Contributors
-------------
Yobichain is maintained by Sripathi Srinivasan, Blockchain Engineer, Primechain Technologies Private Limited.

