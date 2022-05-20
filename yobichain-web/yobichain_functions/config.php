<?php

	/**
	 *  Explorer Parameters
	 */
	class ExplorerParams
	{

		public static $hostName;
		public static $PORT;
		public static $CHAIN_NAME;
		public static $TX_URL_PREFIX;
		public static $BLOCK_URL_PREFIX;
		public static $ADDRESS_URL_PREFIX;
		public static $ASSETS_URL_PREFIX;

		public static function init(){
	        self::$hostName = $_SERVER['HTTP_HOST'];
			self::$PORT = "2750";
			self::$CHAIN_NAME = "yobichain";
			self::$TX_URL_PREFIX = "http://".self::$hostName.":".self::$PORT."/".self::$CHAIN_NAME."/tx/";
			self::$BLOCK_URL_PREFIX = "http://".self::$hostName.":".self::$PORT."/".self::$CHAIN_NAME."/block/";
			self::$ADDRESS_URL_PREFIX = "http://".self::$hostName.":".self::$PORT."/".self::$CHAIN_NAME."/address/";
			self::$ASSETS_URL_PREFIX = "http://".self::$hostName.":".self::$PORT."/".self::$CHAIN_NAME."/assets/";
	    }
	}

	ExplorerParams::init();


	/**
	* Web server parameters
	*/
	class WebServerParams
	{		
		public static $hostName;				// IP address or Host Name of the Web Server 
		const YOBICHAIN_ROOT_DIR = "yobichain-web";	// Root directory of Yobichain

		public static function init(){
	        self::$hostName = $_SERVER['HTTP_HOST'];
	    }
	}

	WebServerParams::init();


	/**
	* MULTICHAIN PARAMETERS
	*/
	class MultichainParams
	{
		const HOST_NAME = "localhost";
        const RPC_PORT = "15590";
        const RPC_USER = "iessinsife";
        const RPC_PASSWORD = "XupuoweeR7yehahpah1x";
		
		const ASSET_STREAMS = array(
				"ASSET_DETAILS" => "asset_details",
				"OFFER_HEX" => "offers_hex",
				"OFFER_DETAILS" => "offers_details"
			);
		
		const DATA_STREAMS = array(
				"FILE_DETAILS" => "file_details",
				"FILE_DATA" => "file_data"
			);
	}

	/**
	* aaaa
	*/
	class DBParams
	{

		const DB_HOST_NAME = "localhost";
        const DB_USER_NAME = "swuser";
		const DB_PASSWORD = "geOZYQ2C8tXZBb7CCjOtEoOBDr4lwfMDkrs88lB7";
		
		const DATABASES = array(
				"YOBICHAIN" => "yobichain-db"
			);
	}

	/**
	* aaaa
	*/
	class NotificationParams
	{
		const USE_MAILER = true;
		const SMS_PROVIDER_API_KEY = "A3N21No197";
        const EMAIL_PROVIDER_API_KEY = "";
	}
?>