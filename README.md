Yobichain
=========

> WARNING: Yobichain is intended for experimenting and learning, NOT for a production environment.

![Image of Yobi](http://www.primechaintech.com/assets/base/img/content/github/github_yobichain.png)

YobiChain is your very own private blockchain ecosystem preloaded with database, web & FTP servers and HashChain, a simple blockchain powered drag n drop solution for authenticating and verifying electronic records.

Yobichain is maintained by [Primechain Technologies Pvt. Ltd.](http://www.primechain.in). This version of Yobichain runs on [Multichain](https://github.com/MultiChain).

YobiChain is ideal for

* Startups looking to quickly build a blockchain powered prototype, PoC or MVP.
* Serious researchers / enthusiasts looking to experiment with a live blockchain.
* In-house teams experimenting with blockchain technology.


System Requirements
-------------------

To set up Yobichain, you will need 1 server (min 2 GB RAM, 2 CPUs) running Ubuntu 16.04.2 x64 and php 7.0. If you don't have access to an Ubuntu server, you can set it up using Digtal Ocean's one-click app  `PhpMyAdmin on 16.04` or `LAMP on 16.04`. Use this link to get a free $10 credit from Digital Ocean: https://m.do.co/c/dc0df9a8a187 

Installation
------------

This section presumes that you have root access to the server mentioned above and want to install Yobichain for all users on the system.

**Step 1.** Install git and clone the yobichain repository

    sudo apt-get install git
    sudo git clone https://github.com/Primechain/yobichain.git

**Step 2.** Harden the base operating system (Ubuntu 16.04.2 x64). This will also create a new user called yobiuser with the password entered by you below.

    cd yobichain
    sudo bash -e hardening.sh <password>

**Step 3.** Install the FTP server. This will set up the FTP server. For logging in, use the IP address of your server as the `host`. The username and password are as entered by you below. The connection is `SFTP`.

    sudo bash -e ftp.sh <username> <password>


**Step 4.** Install, configure and run the Multichain blockchain, Multichain web-demo and Multichain Exporer. This also sets up HashChain, PrimeVault, PrimeContract and YobiWallet. The RPC port will be set as `15590` and the Network port will be set as `61172`. If you get a "locale error" using Terminal on mac, go to Terminal -> Preferences -> Profiles and uncheck "Set locale environment variables on startup"

    sudo bash -e multichain.sh <chain-name> <rpc-username> <rpc-password>
		
To access Multichain web-demo, visit `http://<IP Address>/multichain-web-demo`

To access Multichain Exporer, visit `http://<IP Address>:2750`

To use hashchain, see the instructions at [https://github.com/Primechain/hashchain/blob/master/README.md](https://github.com/Primechain/hashchain/blob/master/README.md)


Notes
-----

This will:
1. harden the base operating system against cyber attacks

2. set up a Multichain blockchain using a pre-defined configuration

3. set up an FTP server

4. set up Multichain web demo

5. set up Multichain Explorer

6. set up HashChain, a simple blockchain powered drag n drop solution for authenticating and verifying electronic records.

In case something goes wrong, you can roll back the multichain installation using

    bash rollback_multichain.sh 


Contributors
-------------
A non-exhaustive list of contributors:
* Sripathi Srinivasan
* Rohas Nagpal
* Sudin Baraokar
* Shinam Arora
