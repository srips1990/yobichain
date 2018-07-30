<?php
	include_once('config.php');
	include_once('resources.php');

	function randomNDigitNumber($digits)
	{
		$number = rand(pow(10, $digits-1), pow(10, $digits)-1);
		return $number;
	}

	function generatePassword()
	{
	    $range = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ01234567129!@#$%^&*()_+-=?";
	    $pwd = array(); 
	    $rangelength = strlen($range) - 1; 
	    for ($i = 0; $i < 12; $i++) {
	        $n = rand(0, $rangelength);
	        $pwd[] = $range[$n];
	    }
	    return implode($pwd); 
	}

	function generateGUID()
	{
		if (function_exists('com_create_guid'))
	    {
	        return com_create_guid();
	    }
	    else
	    {
	        mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
	        $charid = strtoupper(md5(uniqid(rand(), true)));
	        $hyphen = chr(45);// "-"
	        $uuid = chr(123)// "{"
	            .substr($charid, 0, 8).$hyphen
	            .substr($charid, 8, 4).$hyphen
	            .substr($charid,12, 4).$hyphen
	            .substr($charid,16, 4).$hyphen
	            .substr($charid,20,12)
	            .chr(125);// "}"
	        return trim($uuid,'{}');
	    }
	}

	function printSuccessMessage($msg)
	{
		echo "<p style='color:green'><b>".$msg."</b></p>";
	}

	function printErrorMessage($msg)
	{
		echo "<p style='color:red'><b>".$msg."</b></p>";
	}

	function validateName($name)
	{
		return preg_match('/^[a-zA-Z ]*$/', $name);
	}
	
	function validateEmail($email)
	{
		return filter_var($email, FILTER_VALIDATE_EMAIL);
	}

	function validateUsername($username)
	{
		return preg_match('/^[a-zA-Z0-9]{5,50}$/', $username);
	}

	function validatePassword($password)
	{
		return preg_match('/^(?=.*\d)(?=.*[A-Za-z])[0-9A-Za-z!@#$%]{8,16}$/', $password);
	}

	function bin_data_to_file($data)
	{
		$parts=explode("\x00", $data, 4);
		
		if ( (count($parts)!=4) || ($parts[0]!='') )
			return null;
		
		return array(
			'filename' => $parts[1],
			'mimetype' => $parts[2],
			'content' => $parts[3],
		);
	}


	function file_to_txout_bin($filename, $mimetype, $content)
	{
		return "\x00".$filename."\x00".$mimetype."\x00".$content;
	}


	function prepareGetRequest($uri, $params)
	{
		$getParamValue = array();
		foreach ($params as $key => $value) {
			$get_param_value[] = $key . '=' . urlencode($value);
		}

		return $uri. '?' . implode('&', $get_param_value);
	}


	function getFileDataType($fileSignature)
	{
		if(strpos($fileSignature, "FFD8FF") === 0)
		{
			$fileExtension = "jpg";
			$fileDataType = "image/jpeg";
		}
		elseif(strpos($fileSignature, "474946") === 0)
		{
			$fileExtension = "gif";
			$fileDataType = "image/".$fileExtension;
		}
		elseif(strpos($fileSignature, "89504E") === 0)
		{
			$fileExtension = "png";
			$fileDataType = "image/".$fileExtension;
		}
		elseif(strpos($fileSignature, "424D") === 0)
		{
			$fileExtension = "bmp";
			$fileDataType = "image/".$fileExtension;
		}
		elseif(strpos($fileSignature, "492049") === 0)
		{
			$fileExtension = "tif";
			$fileDataType = "image/".$fileExtension;
		}
		elseif(strpos($fileSignature, "25504446") === 0)
		{
			$fileExtension = "pdf";
			$fileDataType = "application/".$fileExtension;
		}
		else
		{
			throw new Exception("File type not supported.");
		}

		return $fileDataType;
	}

	/**
	*** Get the recipient address
	*/
	function getRecipientsFromTransaction($transaction)
	{
		$transferred = 2;

		foreach($transaction as $param_name=>$param_value)
		{
			if($param_name=="balance")
			{
				$amount = $param_value["amount"];
				$assets = $param_value["assets"];

				if($amount<0)
					$transferred = 1;
				else if($amount>0)
					$transferred = 2;
				else
					$transferred = 1;			//change: Shd be modified to zero later.

				foreach($param_value["assets"] as $index=>$value)
				{
					$asset_name = $value["name"];
					$asset_qty = $value["qty"];

					if($value["qty"]<0)
					{
						$transferred = 1;
						array_push($assets, $value);
					}
					else if($value["qty"]>0)
					{
						$transferred = 2;
						array_push($assets, $value);
					}
					else
					{
						//$transferred = 0;
					}

				}
			}
			else if($param_name=="myaddresses")
			{
				if($transferred==1)
					$sender = $param_value;
				else if($transferred==2)
					$recipient = $param_value;
			}
			else if($param_name=="addresses")
			{
				if($transferred==1)
					$recipient = $param_value;
				else
					$sender = $param_value;
			}
			
		}

		return $recipient;
	}



	/**
	*** Get the recipient address
	*/
	function getAssestsAmountFromTransaction($transaction)
	{
		$transferred = 2;

		foreach($transaction as $param_name=>$param_value)
		{
			if($param_name=="balance")
			{
				$amount = $param_value["amount"];
				$assets = $param_value["assets"];
				$assetsAmount = array();

				if($amount<0)
					$transferred = 1;
				else if($amount>0)
					$transferred = 2;
				else
					$transferred = 1;			//change: Shd be modified to zero later.

				foreach($param_value["assets"] as $index=>$value)
				{
					$asset_name = $value["name"];
					$asset_qty = $value["qty"];

					$assetsAmount[$asset_name] = abs($asset_qty);

				}
			}
			
		}

		return $assetsAmount;
	}


	/**
	*** Prints the basic details of a transaction.
	*/
	function printStreamTransactionBasicDetailsVertically($transaction)
	{
		//global $explorer_tx_url,$explorer_address_url,$explorer_block_url;
	
		$printDetails = "<div class='table-responsive scrollable has-scrollbar scrollable-content ' data-plugin-scrollable><table class='table table-bordered table-hover table-condensed mb-none'>";

		foreach($transaction as $param_name=>$param_value)
		{		
			if($param_name=="txid")
			{
				$txId = $param_value;
			}
			else if($param_name=="myaddresses" || $param_name=="publishers")
			{
				$publisherAddress = $param_value;
			}			
			else if($param_name=="blockhash")
			{
				$blockHash = $param_value;
			}
			else if($param_name=="blockindex")
			{
				$blockIndex = $param_value;
			}
			else if($param_name=="time")
			{
				$time = $param_value;
			}
			else if($param_name=="comment")
			{
				$comment = $param_value;
			}
			else if($param_name=="data")
			{
				$data = $param_value;
			}
			else if($param_name=="confirmations")
			{
				$confirmations = $param_value;
			}

		}

		$printDetails .= "<tr height=25><th width=150 style='border-style: ridge;'>Transaction Id</th><td align='left' style='border-style: ridge;'>"."<a href='".ExplorerParams::$TX_URL_PREFIX."$txId' target='_new'>".$txId."</a>"."</td></tr>";
		
		$printDetails .= "<tr height=25><th style='border-style: ridge;'>Uploader</th><td align='left' style='border-style: ridge;'>"."<a href='".ExplorerParams::$ADDRESS_URL_PREFIX.$publisherAddress[0]."' target='_new'>".$publisherAddress[0]."</a>"."</td></tr>";
		
		$printDetails .= "<tr height=25><th width=150 style='border-style: ridge;'>Block Hash</th><td align='left' style='border-style: ridge;'>".(isset($blockHash) ? "<a href='".ExplorerParams::$BLOCK_URL_PREFIX."$blockHash' target='_new'>".$blockHash."</a>" : "")."</td></tr>";
		
		$printDetails .= "<tr height=25><th width=150 style='border-style: ridge;'>Confirmations</th><td align='left' style='border-style: ridge;'>".$confirmations."</td></tr>";

		// $printDetails .= (is_string($data[0])) ? "<tr height=25><th width=150 style='border-style: ridge;'>Data</th><td align='left' style='border-style: ridge;'>".json_encode(json_decode(hex2bin($data[0])), JSON_PRETTY_PRINT)."</td></tr>" : "";
		$printDetails .= "</table></div>";
		return $printDetails;
	}


	/**
	*** Prints the basic details of a transaction.
	*/
	function printStreamItemDetailsVertically($transaction)
	{
		date_default_timezone_set('UTC');
	
		$printDetails = "<div><table class='table table-bordered' style='color:#24877d'>";

		foreach($transaction as $param_name=>$param_value)
		{		
			if($param_name=="txid")
			{
				$txId = $param_value;
			}
			else if($param_name=="myaddresses" || $param_name=="publishers")
			{
				$publisherAddress = $param_value;
			}			
			else if($param_name=="blockhash")
			{
				$blockHash = $param_value;
			}
			else if($param_name=="blockindex")
			{
				$blockIndex = $param_value;
			}
			else if($param_name=="time")
			{
				$time = $param_value;
			}
			else if($param_name=="comment")
			{
				$comment = $param_value;
			}
			else if($param_name=="data")
			{
				$data = $param_value;
			}
			else if($param_name=="confirmations")
			{
				$confirmations = $param_value;
			}

		}

		$printDetails .= "<tr height=25><th width=150 style='border-style: ridge;'>Transaction Id</th><td align='left' style='border-style: ridge;'>".$txId."</td></tr>";
		
		//$printDetails .= "<tr height=25><th style='border-style: ridge;'>Uploader</th><td align='left' style='border-style: ridge;'>"."<a href='".ExplorerParams::ADDRESS_URL_PREFIX.$publisherAddress[0]."' target='_new'>".$publisherAddress[0]."</a>"."</td></tr>";
		
		$printDetails .= "<tr height=25><th width=150 style='border-style: ridge;'>Block Hash</th><td align='left' style='border-style: ridge;'>".(isset($blockHash) ? $blockHash : "")."</td></tr>";
		
		$printDetails .= "<tr height=25><th width=150 style='border-style: ridge;'>Confirmations</th><td align='left' style='border-style: ridge;'>".$confirmations."</td></tr>";

		$printDetails .= "<tr height=25><th width=150 style='border-style: ridge;'>Time</th><td align='left' style='border-style: ridge;'>".date('m-d-Y'.',  '.'h:i:s a'.',  T', $time)."</td></tr>";

		// $printDetails .= (is_string($data[0])) ? "<tr height=25><th width=150 style='border-style: ridge;'>Data</th><td align='left' style='border-style: ridge;'>".json_encode(json_decode(hex2bin($data[0])), JSON_PRETTY_PRINT)."</td></tr>" : "";
		$printDetails .= "</table></div>";
		return $printDetails;
	}


	/**
	*** Prints the basic details of a transaction.
	*/
	function printBlockDetailsVertically($block)
	{
		date_default_timezone_set('UTC');
	
		$printDetails = "<div><table class='table table-bordered' style='color:#24877d'>";

		foreach($block as $param_name=>$param_value)
		{		
			if($param_name=="hash")
			{
				$blockHash = $param_value;
			}
			else if($param_name=="height")
			{
				$blockHeight = $param_value;
			}
			else if($param_name=="size")
			{
				$size = $param_value;
			}
			else if($param_name=="merkleroot")
			{
				$merkleRoot = $param_value;
			}
			else if($param_name=="tx")
			{
				$transactions = "<ul><li>".implode("</li><li>", $param_value)."</li></ul>";
			}
			else if($param_name=="confirmations")
			{
				$confirmations = $param_value;
			}
			else if($param_name=="nonce")
			{
				$nonce = $param_value;
			}
			else if($param_name=="chainwork")
			{
				$chainWork = $param_value;
			}
			else if($param_name=="previousblockhash")
			{
				$previousBlockHash = $param_value;
			}
			else if($param_name=="nextblockhash")
			{
				$nextBlockHash = $param_value;
			}
			else if($param_name=="time")
			{
				$time = $param_value;
			}

		}
		
		$printDetails .= "<tr height=25><th width=150 style='border-style: ridge;'>Block Hash</th><td align='left' style='border-style: ridge;'>". $blockHash."</td></tr>";

		$printDetails .= "<tr height=25><th width=150 style='border-style: ridge;'>Block Height</th><td align='left' style='border-style: ridge;'>".$blockHeight."</td></tr>";

		$printDetails .= "<tr height=25><th width=150 style='border-style: ridge;'>Size</th><td align='left' style='border-style: ridge;'>".$size." <i>Bytes</i>"."</td></tr>";

		$printDetails .= "<tr height=25><th width=150 style='border-style: ridge;'>Merkle root</th><td align='left' style='border-style: ridge;'>".$merkleRoot."</td></tr>";

		$printDetails .= "<tr height=25><th width=150 style='border-style: ridge;'>Transactions</th><td align='left' style='border-style: ridge;'>".$transactions."</td></tr>";
		
		$printDetails .= "<tr height=25><th width=150 style='border-style: ridge;'>Confirmations</th><td align='left' style='border-style: ridge;'>".$confirmations."</td></tr>";

		$printDetails .= "<tr height=25><th width=150 style='border-style: ridge;'>Mined at</th><td align='left' style='border-style: ridge;'>".date('m-d-Y'.',  '.'h:i:s a'.',  T', $time)."</td></tr>";

		$printDetails .= "<tr height=25><th width=150 style='border-style: ridge;'>Nonce</th><td align='left' style='border-style: ridge;'>".$nonce."</td></tr>";

		$printDetails .= "<tr height=25><th width=150 style='border-style: ridge;'>Chainwork</th><td align='left' style='border-style: ridge;'>".$chainWork."</td></tr>";
		
		$printDetails .= "<tr height=25><th width=150 style='border-style: ridge;'>Previous Block Hash</th><td align='left' style='border-style: ridge;'>".$previousBlockHash."</td></tr>";
		
		$printDetails .= "<tr height=25><th width=150 style='border-style: ridge;'>Next Block Hash</th><td align='left' style='border-style: ridge;'>".(isset($nextBlockHash) ? $nextBlockHash : "<i>On the way...</i>")."</td></tr>";

		$printDetails .= "</table></div>";
		return $printDetails;
	}



	/**
	*** Print uploads
	*/
	function printUploads($uploads)
	{
		$printDetails = "<div><table class='table table-bordered' style='color:#24877d'>";

		$printDetails .= "<tr><th>File details</th><th></th></tr>";

		foreach ($uploads as $upload) {
			$txID = $upload['txid'];
			$file_hash = $upload['key'];
			$time = $upload['time'];
			$description = json_decode(hex2bin($upload['data']), true)[Literals::UPLOAD_FIELD_NAMES['DESCRIPTION']];
			$printDetails .= "<tr><td>Transaction ID<br><a href='transaction_details.php?msg_data=".$txID."' target='_new'>".$txID."</a><br><br>Description<br>".$description."<br><br>Upload Time<br>".date('m-d-Y'.',  '.'h:i:s a'.',  T', $time)."</td><td><a href='file_download.php?hash=".$file_hash."' target='_new'><button class='btn btn-primary'>Download file</button></a></td></tr>";
			date('m-d-Y'.',  '.'h:i:s a'.',  T', $time);
		}

		$printDetails .= "</table></div>";

		return $printDetails;
	}


	/**
	*** Prints elements of an array in vertical format
	*/
	function printArray($arr, $lvl=0)
	{
		$ret_str = "";

		foreach($arr as $item=>$value)
		{
			$str = "";		

			for ($i = 0; $i <= $lvl; $i++)
			{
					$str= $str."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
			}

			$item_name = (is_numeric($item) || ($item==""))?"":$item.":";

			//echo gettype($value);
			if(gettype($value)=="array")
			{
					$ret_str .= "<br/>".$str.$item_name."<br/>";
					$ret_str .= printArray($value, $lvl+1);
					$ret_str .= "<br/>";
			}
			else
			{
					$ret_str .= $str.$item_name."&nbsp;&nbsp;".$value."<br/>";
			}

		}

		return $ret_str;

	}
?>