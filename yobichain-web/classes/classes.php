<?php
	
	include_once __DIR__.'/../'.'primechain_functions/resources.php';

	/**
	* Asset Details
	*/
	class Asset
	{
		public $asset_ref;
		public $name;
		public $quantity;
		public $minimum_qty;
		public $issuer;
		public $group_code;
		public $type;
		public $unit;
		public $description;
		public $issue_txid;
		public $created_by;
		public $timestamp;
		
		public function __construct()
		{
			$this->isOpen = true;
		}

		public function isOpen()
		{
			if ($this->type == Literals::ASSET_TYPE_CODES['OPEN']) {
				return true;
			}
			else if ($this->type == Literals::ASSET_TYPE_CODES['CLOSED']) {
				return false;
			}
			else {
				throw new Exception("Invalid Asset Type!");
			}
		}
	}
	

	/**
	* Currency Details
	*/
	class Currency
	{
		public $name;
		public $symbol;
		public $code;
	}
	

	/**
	* Asset Custom Details
	*/
	class AssetCustomDetails
	{
		public $issuer;
		public $groupCode;
		public $unit;
		public $region;
		public $description;
	}


	/**
	* Object model for Address
	*/
	class Address
	{
		public $addressLine1;
		public $addressLine2;		
		public $city;
		public $pin;
		public $state;
		public $country;
	}


	/**
	* Object model for User
	*/
	class User
	{
		public $user_id;
		public $user_name;
		public $user_email;
		public $user_password;
		public $user_public_address;
		public $user_public_key;
		public $user_cell;
		public $checked;
		public $user_created_by;
		public $timestamp;
		public $random;
		public $is_deleted;

		public function __construct($userID=null)
		{
			if (!is_null($userID))
				$this->user_id = $userID;
		}
	}


	/**
	* Object model for Role
	*/
	class Role
	{
		public $role_id;
		public $role_category;
		public $role_code;
		public $role_display;
		public $role_title;
		public $role_icon;
		public $role_detail;
		
		public function __construct($role_id=null)
		{
			if (!is_null($role_id))
				$this->role_id = $role_id;
		}
	}


	/**
	* Object model for Role category
	*/
	class RoleCategory
	{
		public $role_category_id;
		public $role_category_code;
		public $role_category_title;
		public $role_category_icon;
		
		public function __construct($role_category_id=null)
		{
			if (!is_null($role_category_id))
				$this->role_category_id = $role_category_id;
		}
	}


	/**
	* Object model for Notification channel
	*/
	class NotificationChannel
	{
		public $id;
		public $channel_code;
		public $channel_desc;
		
		public function __construct($id=null)
		{
			if (!is_null($id))
				$this->id = $id;
		}
	}


	/**
	 * Object model for each asset involved in exchange
	 */
	class ExchangeAsset 
	{
		public $name;
		public $qty;
		
		public function __construct($name="", $qty=0)
		{
			$this->name = $name;
			$this->qty = $qty;
		}
	}


	/**
	* Object model for list of assets involved in exchange
	*/
	class ExchangeAssets
	{
		public $offerAssets = array();
		public $receiveAssets = array();

	}


	/**
	* Custom Exception
	*/
	class CustomException extends Exception
	{
	}


	/**
	* Object model for offer
	*/
	class Offer
	{
		public $transactionIDCreation;
		public $vOutCreation;
		public $timeCreation;
		public $creator;		
		public $transactionIDCompletion;
		public $acceptor;
		public $exchangeAssets;
		
		
		public function __construct()
		{
			$this->creator = "";
			$this->acceptor = "";
			$this->transactionIDCreation = "";
			$this->vOutCreation = 0;
			$this->transactionIDCompletion = "";
			$this->exchangeAssets = new ExchangeAssets();
		}

	}


	/**
	* Object model for Upload
	*/
	class Upload
	{
		public $upload_id;
		public $file_hash;
		public $transaction_id;
		public $user_id;
		public $timestamp;
		
		public function __construct($upload_id=null)
		{
			if (!is_null($upload_id))
				$this->upload_id = $upload_id;
		}
	}
?>