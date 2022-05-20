<?php
	use src\multichain\MultichainClient as MultichainClient;
	use src\multichain\MultichainHelper as MultichainHelper;
	include_once __DIR__.'/'.'src/MultichainHelper.php';
	include_once __DIR__.'/'.'src/MultichainClient.php';
	include_once __DIR__.'/'.'config.php';
	include_once __DIR__.'/'.'resources.php';
	//include_once 'helperFunctions.php';


	class blockchainEngine
	{		
		protected $mcObj;
		protected $mcHelper;
		protected $sessionId;
		protected $server;

		public function __construct()
		{
			$this->mcObj = new MultichainClient("http://".MultichainParams::HOST_NAME.":".MultichainParams::RPC_PORT, MultichainParams::RPC_USER, MultichainParams::RPC_PASSWORD, 30);
			$this->mcHelper = new MultichainHelper($this->mcObj);
		}


/*******************************************************************************************************************
Generate key pair and public address, on the blockchain, for a user and then enters it into the db
*******************************************************************************************************************/

		public function createPublicAddress()
		{
			$user_public_address = $this->mcObj->setDebug(true)->getNewAddress();
			$user_public_key = $this->getUserPublicKeyFromUserAddress($user_public_address);

			return array('address'=>$user_public_address, 'pubkey'=>$user_public_key);
		}

/*******************************************************************************************************************
Gets user's Public Key from Blockchain using public address
*******************************************************************************************************************/

		public function getUserPublicKeyFromUserAddress($userAddress)
		{
			$validateAddressResponse = $this->mcObj->setDebug(true)->validateAddress($userAddress);
			return $validateAddressResponse['pubkey'];
		}

/*******************************************************************************************************************
Gets user's Public Key from Blockchain using public address
*******************************************************************************************************************/

		public function getUserPrivateKeyFromUserAddress($userAddress)
		{
			$privateKey = $this->mcObj->setDebug(true)->dumpPrivKey($userAddress);
			return $privateKey;
		}



/*******************************************************************************************************************
Grants permissions to user
*******************************************************************************************************************/

		public function grantPermissions($address, $permissions)
		{
			try
			{
				$txId = $this->mcObj->setDebug(true)->grant($address, $permissions);
			}
			catch (Exception $e) {
				throw $e;
			}	
		}

/*******************************************************************************************************************
Which of these are needed ??
*******************************************************************************************************************/

		/**
		 *  Get admin address
		 */
		public function getAdminAddress()
		{
			$permissionsInfo = $this->mcObj->setDebug(true)->listPermissions("admin");

			foreach ($permissionsInfo as $permissionItem) {
				$validationInfo = $this->mcObj->setDebug(true)->validateAddress($permissionItem['address']);
				if ($validationInfo['ismine']) {
					return $permissionItem['address'];
				}
			}

			throw new Exception("There is no address with admin privileges in your node!");
		}


		/**
		 *  Get data from the data object of a transaction
		 */
		public function getDataFromDataItem($dataItem)
		{
			if (is_string($dataItem)) {
				$dataHex = $dataItem;
			}
			else{
				$vOut_n = $dataItem['vout'];
				$txId = $dataItem['txid'];
				$dataHex = $this->mcObj->setDebug(true)->getTxOutData($txId, $vOut_n);
			}

			return $dataHex;
		}


		/**
		 *  Get metadata for transaction
		 */
		public function getTransactionMetadata($txId, $vOut)
		{
			return $this->mcObj->setDebug(true)->getTxOutData($txId, $vOut);
		}


		/**
		 *  Get transaction details for an address
		 */
		public function getAddressTransaction($address, $txId)
		{
			return $this->mcObj->setDebug(true)->getAddressTransaction($address, $txId);
		}


		/**
		 *  Get list of transactions for an address
		 */
		public function listAddressTransactions($address, $count = 100, $skip = 0, $verbose = true)
		{
			return $this->mcObj->setDebug(true)->listAddressTransactions($address, $count, $skip, $verbose);
		}


		/**
		 *  Get list of transactions for an address
		 */
		public function signMessage($signerAddress, $fileHash)
		{
			return $this->mcObj->setDebug(true)->signMessage($signerAddress, $fileHash);
		}


	    /**
	     * Get Blocks count.
	     */
	    public function getBlockCount()
	    {
	        return $this->mcObj->setDebug(true)->getBlockCount();
	    }


	    /**
	     * Get Block details.
	     */
	    public function getBlockDetails($hash, $format = true)
	    {
	        return $this->mcObj->setDebug(true)->getBlock($hash, $format);
	    }


	    /**
	     * Get Addresses count.
	     */
	    public function getAddressesCount()
	    {
	        return count($this->mcObj->setDebug(true)->getAddresses());
	    }


	    /**
	     * Get Assets count.
	     */
	    public function getAssetsCount()
	    {
	        return count($this->mcObj->setDebug(true)->listAssets());
	    }


		/**
		* Gets asset balances for address, by asset reference.
		*
		*/
		public function getAssetBalanceForAddressByAssetName($address, $assetName) {
			$assetsBalances = $this->mcObj->setDebug(true)->getAddressBalances($address);
			foreach($assetsBalances as $assetBalance)
			{
				if($assetBalance["name"] == $assetName)
					return $assetBalance["qty"];
			}

			return 0;
		}


		/**
		 *  Get recent vault items for user
		 */
		public function getRecentUploadsForUser($address, $index=0, $count = 10)
		{
			$start = ($index - $count);
			return $this->mcObj->setDebug(true)->listStreamPublisherItems(MultichainParams::DATA_STREAMS['FILE_DETAILS'], $address, true, $count, $start);
		}


		/**
		 *  Get upload transaction item
		 */
		public function getDocumentUploadTransactionItem($txID)
		{
			return $this->mcObj->setDebug(true)->getStreamItem(MultichainParams::DATA_STREAMS['FILE_DATA'], $txID, true);
		}


		/**
		 *  Get file content from Blockchain
		 */
		public function getFileDataFromBlockchain($hash)
		{
			$streamItems = $this->mcObj->setDebug(true)->listStreamKeyItems(MultichainParams::DATA_STREAMS['FILE_DATA'], $hash, true, 1, -1);
			return hex2bin($this->getDataFromDataItem($streamItems[0]['data']));
		}


		/**
		 *  Check if address is valid
		 */
		public function isAddressValid($address)
		{
			
			$addressInfo = $this->mcObj->setDebug(true)->validateAddress($address);

			return $addressInfo['isvalid'];
		}


		/**
		 *  Check if address has the specified permissions
		 */
		public function hasPermissions($address ,$permissions)
		{
			try
			{
				$permissionsInfo = $this->mcObj->setDebug(true)->listPermissions($permissions, $address);
				
				if (count($permissionsInfo) > 0)
				{
					return true;
				}
				else
				{
					return false;
				}
			}
			catch (Exception $e) {
				throw $e;
			}
				
		}
		

		/**
		 *  Send asset with message to user 
		 */
		public function sendAssetWithMessage($fromAddress, $toAddress, $assetName, $units, $metadata)
		{
			try 
			{
				return $this->mcObj->setDebug(true)->sendWithMetadataFrom($fromAddress, $toAddress, array($assetName => $units), $metadata);
				
			}
			catch (Exception $e)
			{
				return false;
			}
		}
		

		/**
		 *  Create an asset in the blockchain with all its details in stream
		 */
		public function createAsset($asset, $issuer_address)
		{
			try
			{
				$asset_read_only_metadata = array(
					Literals::ASSET_FIELD_NAMES['UNIT']=>$asset->unit
					);

				$asset->issue_txid = $this->mcObj->setDebug(true)->issueFrom($issuer_address, $issuer_address, array('name'=>$asset->name, 'open'=>$asset->isOpen()), $asset->quantity, $asset->minimum_qty, 0, $asset_read_only_metadata);	// Issuing the asset
				$this->mcHelper->waitForAssetAvailability($asset->issue_txid);
				$this->mcObj->setDebug(true)->subscribe($asset->name);	// Subscribing the node to the asset
				$asset->asset_ref = $this->getAssetRef($asset->name);
				$asset->minimum_qty = sprintf("%0.8f",$asset->minimum_qty);
				$this->saveAssetCustomDetails($asset, $issuer_address);		// Storing the asset details in stream

				return $asset->issue_txid;
			}
			catch (Exception $e)
			{
				throw $e;
			}
		}
		

		/**
		 *  Create an asset in the blockchain with all its details in stream
		 */
		public function saveAssetCustomDetails($asset, $issuer_address)
		{
			try
			{
				$assetCustomDetails = array(
					Literals::ASSET_FIELD_NAMES['DESCRIPTION']=>$asset->description
					);

				$this->mcObj->setDebug(true)->publishFrom($issuer_address, MultichainParams::ASSET_STREAMS['ASSET_DETAILS'], $asset->name, bin2hex(json_encode($asset)));		// Storing the asset details in stream

				return true;
			}
			catch (Exception $e)
			{
				error_log($e->getMessage());
				return false;
			}
		}


		/**
		 *  Issue more quantities of an asset in the blockchain
		 */
		public function issueMore($asset, $from_address, $to_address)
		{
			try
			{
				if (is_null($from_address)) {
					$txID = $this->mcObj->setDebug(true)->issueMore($to_address, $asset->name, $asset->quantity);
				}
				else {
					$txID = $this->mcObj->setDebug(true)->issueMoreFrom($from_address, $to_address, $asset->name, $asset->quantity);
				}
				

				return $txID;
			}
			catch (Exception $e)
			{
				throw $e;
			}
		}
		

		/**
		 *  Get Asset Reference code
		 */
		public function getAssetRef($assetName)
		{
			try
			{
				$assets = $this->mcObj->setDebug(true)->listAssets($assetName);
				if(count($assets)>0)
					return $assets[0]['assetref'];
				return false;
			}
			catch (Exception $e)
			{
				throw $e;
			}
		}
		

		/**
		 *  Get Asset issuance details
		 */
		public function getAssetDetails($assetName)
		{
			try
			{
				$assets = $this->mcObj->setDebug(true)->listAssets($assetName);
				if (count($assets)>0)
					return $assets[0];
				return null;
			}
			catch (Exception $e)
			{
				return null;
			}
		}
		

		/**
		 *  Get Asset's custom details
		 */
		public function getAssetCustomDetails($assetName)
		{
			try
			{
				$assetsItems = $this->mcObj->setDebug(true)->listStreamKeyItems(MultichainParams::ASSET_STREAMS['ASSET_DETAILS'], $assetName, true, 1, -1);

				if (count($assetsItems) ==0) {
					return $assetsItems;
				}
				if (is_string($assetsItems[0]['data'])) {
	                $contentHex = $assetsItems[0]['data'];
	            }
	            else{
	                $contentHex = $this->mcObj->setDebug(true)->getTxOutData($assetsItems[0]['data']['txid'], $assetsItems[0]['data']['vout']);
	            }

	            $assetCustomDetailsJson = hex2bin($contentHex);
				$assetCustomDetails = json_decode($assetCustomDetailsJson, true);
				
				return $assetCustomDetails;
			}
			catch (Exception $e)
			{
				throw $e;
			}
		}
		

		/**
		 *  Get Full details of an Asset
		 */
		public function getAssetFullDetails($asset_identifier, $verbose=true)
		{
			try
			{
				$assets_list = $this->mcObj->setDebug(true)->listAssets($asset_identifier, $verbose);
				
				if (empty($assets_list))
					return null;

				$asset_item = $assets_list[0];
				$asset = new Asset();

				$asset->name = $asset_item['name'];
				$asset->issue_txid = $asset_item['issuetxid'];
				$asset->issuer = $asset_item['issues'][0]['issuers'][0];
				$asset->asset_ref = $asset_item['assetref'];
				$asset->quantity = $asset_item['issueqty'];
				$asset->minimum_qty = $asset_item['units'];

				if (isset($asset_item['details']) && !empty($asset_item['details']))
					$asset->unit=$asset_item['details']['unit'];

				$asset->type = $asset_item['open'] ? Literals::ASSET_TYPE_CODES['OPEN'] : Literals::ASSET_TYPE_CODES['CLOSED'];
				$assetCustomDetails = $this->getAssetCustomDetails($asset->name);

				if (!empty($assetCustomDetails)) {
					$asset->description=$assetCustomDetails[Literals::ASSET_FIELD_NAMES['DESCRIPTION']];
				}

				return $asset;
			}
			catch (Exception $e)
			{
				return null;
			}
		}
		

		/**
		 *  Get Full details of multiple Assets
		 */
		public function getAssetsFullDetails($assetNames)
		{
			try
			{
				$assets = array();
				$assets_list = $this->mcObj->setDebug(true)->listAssets($assetNames);
				foreach ($assets_list as $asset_item) {
					$asset = new Asset();
					$asset->name = $asset_item['name'];
					$asset->issue_txid = $asset_item['issuetxid'];
					$asset->asset_ref = $asset_item['assetref'];
					$asset->quantity = $asset_item['issueqty'];
					$asset->minimum_qty = $asset_item['units'];
					$asset->type = $asset_item['open'] ? Literals::ASSET_TYPE_CODES['OPEN'] : Literals::ASSET_TYPE_CODES['CLOSED'];

					if (isset($asset_item['details']) && !empty($asset_item['details']) && isset($asset_item['details']['unit'])){

						$asset->unit=$asset_item['details']['unit'];
					}
					
					$assets[] = $asset;
				}
				return $assets;
			}
			catch (Exception $e)
			{
				return null;
			}
		}
		

		/**
		 *  Get asset names for an address
		 */
		public function getAssetNamesForAddress($address)
		{
			try
			{
				$userAssets = $this->mcObj->setDebug(true)->getAddressBalances($address);
				$assets_info = array();

				if (count($userAssets)>0) {
					foreach ($userAssets as $asset) {
						$assets_info[] = strtolower($asset['name']);
					}
				}
				
				return $assets_info;
			}
			catch (Exception $e)
			{
				error_log('internal error: '.$e->getMessage());
				return array();
			}
		}
		

		/**
		 *  Gets the asset balances for the specified address
		 */
		public function getAssetBalanceForAddress($address, $assetName)
		{
			try
			{
				$userAssets = $this->mcObj->setDebug(true)->getAddressBalances($address);
				
				if (count($userAssets)>0) {
					
					foreach ($userAssets as $asset) {
						if ($asset['name'] == $assetName) {
							return floatval($asset['qty']);
						}
					}
				}
				
				return 0;
			}
			catch (Exception $e)
			{
				throw $e;
			}
		}
		

		/**
		 *  Gets the asset balances for the specified user 
		 */
		public function getAssetBalancesForAddress($address)
		{
			try
			{
				return $this->mcObj->setDebug(true)->getAddressBalances($address);
			}
			catch (Exception $e)
			{
				throw $e;
			}
		}
		

		/**
		 *  Gets the asset balances for the specified user 
		 */
		// public function getAssetNamesForAddress($address)
		// {
		// 	try
		// 	{
		// 		$address_balances = $this->getAssetBalancesForAddress($address);
		// 		$asset_names = array();
		// 		foreach ($address_balances as $index => $asset) {
		// 			$asset_names[] = strtolower($asset['name']);
		// 		}

		// 		return $asset_names;
		// 	}
		// 	catch (Exception $e)
		// 	{
		// 		throw $e;
		// 	}
		// }
		

		/**
		 *  Checks if the specified address owns a specific asset
		 */
		public function addressOwnsAsset($address, $assetName)
		{
			try
			{
				$userAssets = $this->mcObj->setDebug(true)->getAddressBalances($address);
				
				if (count($userAssets)>0) {
					
					foreach ($userAssets as $asset) {
						if ($asset['name'] == $assetName) {
							return true;
						}
					}
				}
				
				return false;
			}
			catch (Exception $e)
			{
				throw $e;
			}
		}
		

		/**
		 *  Send an asset from one address to another
		 */
		public function sendAssetFrom($fromAddress, $toAddress, $assetName, $quantity)
		{
			try
			{
				return $this->mcObj->setDebug(true)->sendFromAddress($fromAddress, $toAddress, array($assetName => floatval($quantity)));
			}
			catch (Exception $e)
			{
				throw $e;
			}
		}
		

		/**
		 *  Validate Exchange
		 */
		public function validateExchangeForCreator($address, $exchangeAssets)
		{
			try
			{
				if (count($exchangeAssets->offerAssets) < 1) {
					throw new Exception("108", 1);
				}

				foreach ($exchangeAssets->offerAssets as $offerAsset) {
					if (!$this->addressOwnsAsset($address, $offerAsset->name)) {
						throw new Exception("109", 1);						
					}
					if ($this->getAssetBalanceForAddress($address, $offerAsset->name) < $offerAsset->qty) {
						throw new Exception("94", 1);
					}
				}

				return true;
			}
			catch (Exception $e)
			{
				throw $e;
			}
		}
		

		/**
		 *  Validate Exchange for recipient
		 */
		public function validateExchangeForRecipient($address, $exchangeAssets)
		{
			try
			{
				if (count($exchangeAssets->receiveAssets) < 1) {
					throw new Exception("107", 1);
				}

				foreach ($exchangeAssets->receiveAssets as $receiveAsset) {
					if (!$this->addressOwnsAsset($address, $receiveAsset->name)) {
						throw new Exception("106", 1);
					}
					if ($this->getAssetBalanceForAddress($address, $receiveAsset->name) < $receiveAsset->qty) {
						throw new Exception("94", 1);
					}
				}

				return true;
			}
			catch (Exception $e)
			{
				throw $e;
			}
		}


		/**
		 *  Create Offer
		 */
		public function createOffer($userAddress, $exchangeAssets, $publisherAddress=null)
		{
			try
			{
				$publisher = is_null($publisherAddress) ? $userAddress : $publisherAddress;

				$offerAssetsArray = array();	// The array to be used for preparelockunspent API call
				$receiveAssetsArray = array();	// The array to be used for createrawexchange API call

				foreach ($exchangeAssets->offerAssets as $offerAsset) {
					$offerAssetsArray[$offerAsset->name] = $offerAsset->qty;
				}

				foreach ($exchangeAssets->receiveAssets as $receiveAsset) {
					$receiveAssetsArray[$receiveAsset->name] = $receiveAsset->qty;
				}

				if ($this->validateExchangeForCreator($userAddress, $exchangeAssets)) {
					$txLockedObj = $this->mcObj->setDebug(true)->prepareLockUnspentFrom($userAddress, $offerAssetsArray);
				}

				$offerHex = $this->mcObj->setDebug(true)->createRawExchange($txLockedObj['txid'], $txLockedObj['vout'], $receiveAssetsArray);
				// error_log("\nTracker: Offer Create Hex - " . $offerHex, 3, "error.txt");

				$txDetails = $this->mcObj->setDebug(true)->getAddressTransaction($userAddress, $txLockedObj['txid']);

				$this->mcObj->setDebug(true)->publishFrom($publisher, MultichainParams::ASSET_STREAMS['OFFER_HEX'], $txLockedObj['txid'] . Literals::STREAM_KEY_DELIMITER . $txLockedObj['vout'], $offerHex);

				$offer = new Offer();
				$offer->creator = $userAddress;
				$offer->exchangeAssets = $exchangeAssets;
				$offer->transactionIDCreation = $txLockedObj['txid'];
				$offer->vOutCreation = $txLockedObj['vout'];
				$offer->timeCreation = $txDetails['time'];

				$this->mcObj->setDebug(true)->publishFrom($publisher, MultichainParams::ASSET_STREAMS['OFFER_DETAILS'], $txLockedObj['txid'] . Literals::STREAM_KEY_DELIMITER . $txLockedObj['vout'], bin2hex(serialize($offer)));

				// $this->insertOfferToDB($offer);		//Writing to external database
				return $offer;
			}
			catch (Exception $e)
			{
				throw $e;
			}
		}


		/**
		 *  Get Active Offers
		 */
		public function getActiveOffers()
		{
			try
			{
				$transactionsLocked = $this->mcObj->setDebug(true)->listLockUnspent();
				$offersKeys = array();
				$offers = array();
				
				foreach ($transactionsLocked as $transactionLockedObj) {
					$offersKeys[] = $transactionLockedObj['txid'] . Literals::STREAM_KEY_DELIMITER . $transactionLockedObj['vout'];
				}

				$offersStreamItems = $this->mcObj->setDebug(true)->listStreamItems(MultichainParams::ASSET_STREAMS['OFFER_DETAILS'], true, 5000);

				foreach ($offersStreamItems as $offersStreamItem) {
						$offer = unserialize(hex2bin($this->getDataFromDataItem($offersStreamItem['data'])));
						$offers[$offersStreamItem['key']] = $offer;	// Array of items of type ExchangeAssets, with "txid~vout" as key
				}

				$activeOffers = array_intersect_key($offers, array_flip($offersKeys));	// Filtering active offers

				return $activeOffers;

			}
			catch (Exception $e)
			{
				throw $e;
			}
		}


		/**
		 *  Get Active Offers
		 */
		public function getActiveOffersForAddress($userAddress)
		{
			try
			{
				$transactionsLocked = $this->mcObj->setDebug(true)->listLockUnspent();
				$offersKeys = array();
				$offers = array();
				
				foreach ($transactionsLocked as $transactionLockedObj) {
					$offersKeys[] = $transactionLockedObj['txid'] . Literals::STREAM_KEY_DELIMITER . $transactionLockedObj['vout'];
				}

				$offersStreamItems = $this->mcObj->setDebug(true)->listStreamPublisherItems(MultichainParams::ASSET_STREAMS['OFFER_DETAILS'], $userAddress, true, 5000);

				foreach ($offersStreamItems as $offersStreamItem) {
						$offer = unserialize(hex2bin($this->getDataFromDataItem($offersStreamItem['data'])));
						$offers[$offersStreamItem['key']] = $offer;	// Array of items of type ExchangeAssets, with "txid~vout" as key
				}

				$activeOffers = array_intersect_key($offers, array_flip($offersKeys));	// Filtering active offers

				return $activeOffers;

			}
			catch (Exception $e)
			{
				throw $e;
			}
		}


		/**
		 *  Accept Offer
		 */
		public function acceptOffer($address, $txVoutKey)
		{
			try
			{
				$offersHexStreamItems = $this->mcObj->setDebug(true)->listStreamKeyItems(MultichainParams::ASSET_STREAMS['OFFER_HEX'], $txVoutKey, true, 1, -1);

				$txHex = $this->getDataFromDataItem($offersHexStreamItems[0]['data']);

				$txDecoded = $this->mcObj->setDebug(true)->decodeRawExchange($txHex);
				
				$exchangeAssets = new ExchangeAssets();

				foreach ($txDecoded['offer']['assets'] as $asset) {
					$exchangeAssets->offerAssets[] = new ExchangeAsset($asset['name'], $asset['qty']);
				}

				foreach ($txDecoded['ask']['assets'] as $asset) {
					$exchangeAssets->receiveAssets[] = new ExchangeAsset($asset['name'], $asset['qty']);
				}

				if ($this->validateExchangeForRecipient($address, $exchangeAssets)) {
					
					$offerAssetsArray = array();	// The array to be used for preparelockunspent API call
					$receiveAssetsArray = array();	// The array to be used for createrawexchange API call

					foreach ($exchangeAssets->offerAssets as $offerAsset) {
						$offerAssetsArray[$offerAsset->name] = $offerAsset->qty;
					}

					foreach ($exchangeAssets->receiveAssets as $receiveAsset) {
						$receiveAssetsArray[$receiveAsset->name] = $receiveAsset->qty;
					}

					$txLockedObj = $this->mcObj->setDebug(true)->prepareLockUnspentFrom($address, $receiveAssetsArray);
					$appendRawExchangeOutput = $this->mcObj->setDebug(true)->appendRawExchange($txHex, $txLockedObj['txid'], $txLockedObj['vout'], $offerAssetsArray);
					$txHexComplete = $appendRawExchangeOutput['hex'];
					$txID = $this->mcObj->setDebug(true)->sendRawTransaction($txHexComplete);

					$tempArr = explode(Literals::STREAM_KEY_DELIMITER, $txVoutKey);
					$txLockedCreatorObj = array("txid" => $tempArr[0], "vout" => intval($tempArr[1]));

					$this->mcObj->setDebug(true)->lockUnspent(true, array($txLockedCreatorObj, $txLockedObj));

					return $txID;
				}

			}
			catch (Exception $e)
			{
				throw $e;
			}
		}


		/**
		 *  Unlock offer assets
		 */
		public function unlockOfferAssets($offer)
		{
			try
			{
				$this->mcObj->setDebug(true)->lockUnspent(true, array(array(
					'txid' => $offer->transactionIDCreation,
					'vout' => intval($offer->vOutCreation)
				)));
			}
			catch (Exception $e)
			{
				throw $e;
			}
		}


/*******************************************************************************************************************
Upload a document
*******************************************************************************************************************/
		/*
		 * @return Transaction ID for the transaction
		 */
		public function uploadDocument($user_address,$file_hash,$file_details, $file_bin_data)
		{
			try
			{

				$fileDetailsArr = array(
					Literals::UPLOAD_FIELD_NAMES['DESCRIPTION'] => $file_details['description']
				);

				$fileDetailsHex = bin2hex(json_encode($fileDetailsArr));

				$rawInput1 = array(
					"for" => MultichainParams::DATA_STREAMS['FILE_DETAILS'],
					"key" => $file_hash,
					"data" => $fileDetailsHex
				);

				$rawInput2 = array(
					"for" => MultichainParams::DATA_STREAMS['FILE_DATA'],
					"key" => $file_hash,
					"data" => bin2hex($file_bin_data)
				);		/// Hex encoding the metadata

				$tid_detail = $this->mcObj->setDebug(true)->createRawSendFrom($user_address, null, array($rawInput1, $rawInput2), 'send');

				return $tid_detail;
			}
			catch (Exception $e)
			{
				throw $e;
			}
		}

	}

?>