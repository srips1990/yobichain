<?php

	/**
	* 
	*/
	class Literals
	{

		const STREAM_KEY_DELIMITER = "~";

		const MINIMUM_DIVISIBLE_UNIT_MIN_VALUE = 0.00000001;

		const MAX_ALLOWED_ASSET_RAW_QUANTITY = 1000000000000;

		const MAX_ALLOWED_FILE_SIZE_IN_BYTES = 1048576;

		const ASSET_TYPE_CODES = array(
				"OPEN" => "open",
				"CLOSED" => "closed"
			);

		const ASSET_TYPE_DESC = array(
				"open" => "Open",
				"closed" => "Closed"
			);

		const ASSET_FIELD_NAMES = array(
				"ID" => "asset_ref",
				"NAME" => "name",
				"QUANTITY" => "quantity",
				"MINIMUM_QTY" => "minimum_qty",
				"THRESHOLD" => "threshold",
				"ISSUER" => "issuer",
				"OWNER" => "owner",
				"GROUP_CODE" => "group_code",
				"TYPE" => "type",
				"UNIT" => "unit",
				"REGION" => "region",
				"DESCRIPTION" => "description",
				"ISSUE_TX_ID" => "issue_txId",
				"IS_OPEN" => "is_open"
			);

		const ASSET_FIELD_DESC = array(
				"asset_ref" => "Asset ID",
				"name" => "Asset Name",
				"quantity" => "Initial Quantity",
				"minimum_qty" => "Minimum quantity",
				"threshold" => "Threshold",
				"issuer" => "Issuer",
				"owner" => "Owner",
				"group_code" => "Group code",
				"type" => "Type",
				"unit" => "Unit",
				"region" => "Region",
				"description" => "Description",
				"issue_txId" => "Issue Transaction ID",
				"is_open" => "Is Reissuable?"
			);

		const UPLOAD_FIELD_NAMES = array(
				"DESCRIPTION" => "description"
			);
	}

?>