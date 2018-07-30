<?php
	include_once("httpful.phar");

	/**
	* 
	*/
	class XmlHelper
	{

		public function httpGet($uri)
		{
			$response = \Httpful\Request::get($uri)
				->expectsXml()
				->send();

			return $response;
		}
	}

?>