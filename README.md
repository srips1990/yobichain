Yobichain
=========

> WARNING: Yobichain is intended for experimenting and learning, NOT for a production environment.

![Image of Yobi](http://www.primechain.in/img/github_yobichain.png)

YobiChain is your very own private blockchain ecosystem preloaded with development tools, database, web & FTP servers and the following 4 blockchain applications:

1. HashChain, a simple blockchain powered drag n drop solution for authenticating and verifying electronic records.

2. PrimeVault, a simple blockchain powered document storage and retrieval system.

3. PrimeContract, a simple blockchain powered system for digitally signng contracts.

4. YobiWallet, a simple blockchain powered wallet for Yobicoins, a smart asset.

Yobichain is maintained by [Primechain Technologies Pvt. Ltd.](http://www.primechain.in). This version of Yobichain runs on [Multichain](https://github.com/MultiChain).

YobiChain is ideal for

* Startups looking to quickly build a blockchain powered prototype, PoC or MVP.
* Serious researchers / enthusiasts looking to experiment with a live blockchain.
* In-house teams experimenting with blockchain technology.


System Requirements
-------------------

One server (min 2 GB RAM, 2 CPUs) running Ubuntu 16.04.2 x64 and php 7.0.

You can set this up using Digtal Ocean's one-click app  `PhpMyAdmin on 16.04` or `LAMP on 16.04`. Use this link to get a free $10 credit from Digital Ocean: https://m.do.co/c/dc0df9a8a187 

Installation
------------

This section presumes that you have root permission and want to install Yobichain for all users on the system.

**Step 1.** Install git and clone the yobichain repository

    sudo apt-get install git
    sudo git clone https://github.com/Primechain/yobichain.git

**Step 2.** Harden the base operating system (Ubuntu 16.04.2 x64). This will also create a new user called yobiuser with the password entered by you below.

    cd yobichain
    sudo bash -e hardening.sh <password>

**Step 3.** Install the FTP server. This will set up the FTP server. For logging in, use the IP address of your server as the `host`. The username and password are as entered by you below. The connection is `SFTP`.

    sudo bash -e ftp.sh <username> <password>


**Step 4.** Install, configure and run the Multichain blockchain, Multichain web-demo and Multichain Exporer. This also sets up HashChain, PrimeVault, PrimeContract and YobiWallet. The RPC port will be set as `15590` and the Network port will be set as `61172`

    sudo bash -e multichain.sh <chain-name> <rpc-username> <rpc-password>
		
To access Multichain web-demo, visit `http://<IP Address>/multichain-web-demo`

To access Multichain Exporer, visit `http://<IP Address>:2750`

To use hashchain, see the instructions at [https://github.com/Primechain/hashchain/blob/master/README.md](https://github.com/Primechain/hashchain/blob/master/README.md)

To use PrimeVault, see the instructions at [https://github.com/Primechain/yobiapps/blob/master/README.md#primevault](https://github.com/Primechain/yobiapps/blob/master/README.md#primevault)

To use PrimeContract, see the instructions at [https://github.com/Primechain/yobiapps/blob/master/README.md#primecontract](https://github.com/Primechain/yobiapps/blob/master/README.md#primecontract)


To use YobiWallet, see the instructions at [https://github.com/Primechain/yobiapps/blob/master/README.md#yobiwallet](https://github.com/Primechain/yobiapps/blob/master/README.md#yobiwallet)


Notes
-----

This will:
1. harden the base operating system against cyber attacks

2. set up a Multichain blockchain using a pre-defined configuration

3. set up an FTP server

4. set up Multichain web demo

5. set up Multichain Explorer

6. set up HashChain, a simple blockchain powered drag n drop solution for authenticating and verifying electronic records.

7. set up PrimeVault, a simple blockchain powered document storage and retrieval system.

8. set up PrimeContract, a simple blockchain powered system for digitally signng contracts.

9. set up YobiWallet, a simple blockchain powered wallet for Yobicoins, a smart asset.

In case something goes wrong, you can roll back the multichain installation using

    bash rollback_multichain.sh 

Live demo
---------
* To access a live Multichain web-demo, visit http://139.59.27.186/multichain-web-demo

* To access a live Multichain Exporer, visit http://139.59.27.186:2750

* To authenticate a file using hashchain, visit http://139.59.27.186/hashchain/hashchain_authenticator.php and to verify a file using hashchain, visit http://139.59.27.186/hashchain/

* To access the yobiapps, visit: http://139.59.27.186/yobiapps


Planned roadmap
-----
+ ~~[ ] Installation of PrimeVault~~ **done**
+ ~~[ ] Installation of PrimeContract~~ **done**
+ ~~[ ] Installation of YobiWallet~~ **done**
+ [ ] Installation of MEAN (MongoDB, Express, AngularJS, Node.js)


Contributors
-------------
A non-exhaustive list of contributors:
* Sripathi Srinivasan
* Rohas Nagpal
* Sudin Baraokar
* Shinam Arora
