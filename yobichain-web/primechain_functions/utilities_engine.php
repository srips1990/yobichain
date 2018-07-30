<?php

class utilitiesEngine
	{


/*******************************************************************************************************************
Generate a random string
*******************************************************************************************************************/

		public function random_str($length, $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ')
				{
				    $str = '';
				    $max = mb_strlen($keyspace, '8bit') - 1;
				    for ($i = 0; $i < $length; ++$i) 
				    {
				        $str .= $keyspace[random_int(0, $max)];
				    }
				    return $str;
				}


/*******************************************************************************************************************
Generate GUID
*******************************************************************************************************************/

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

/*******************************************************************************************************************/

}	
?>
