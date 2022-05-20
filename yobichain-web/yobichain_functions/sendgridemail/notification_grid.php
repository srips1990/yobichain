<?php

	// Sending out emails using Send in blue API
	include_once(__DIR__."/../config.php");
	include_once 'sms_api.php';

	class notificationEngine
	{
		private $base_url;
		private $email_top;
		private $email_bottom;

		public function __construct()
		{
			$this->base_url = "http://".$_SERVER['HTTP_HOST']."/".WebServerParams::YOBICHAIN_ROOT_DIR;
			
			$this->email_top = "<html><body>
						<table style='background-color: #dcecf6; width: 100%; border: 0; padding: 0px 30px 0px 30px;'>
							<tr>
								<td>
									<table style='margin-left: auto; margin-right: auto; width: 600px; border: 0;'>
										<tr>
											<td>
											</td>
										</tr>
										<tr>
										<td style='padding: 30px; background-color: #FFF; text-align: left; border: 0; font-size: 16px; font-family: Georgia; color: #181818;'>";

			$this->email_bottom = "<p>Have an amazing day!<br/></p>
											</td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								<td style='padding-top: 30px; text-align: center; font-family: Helvetica, Arial, sans-serif; font-size: 14px; color: #999;'>This is an automated transactional email sent from Yobichain<br/>&nbsp;
								</td>
							</tr>
						</table></body></html>";
		}

/*******************************************************************************************************************
Email notification of successful login to the user
*******************************************************************************************************************/

		public function sendLoginNotification($ip,$browser,$user_email,$user_name)
		{ 
			require 'vendor/autoload.php';

			$sendgrid_apikey = NotificationParams::EMAIL_PROVIDER_API_KEY;
			$sendgrid = new SendGrid($sendgrid_apikey);

			$url = 'https://api.sendgrid.com/';
			$pass = $sendgrid_apikey;

			$params =  array( 
				"to" => $user_email,
				 "formname" => "Yobichain",
				"from" => "info@sripathi.co.in",
				"subject" => "Login from $ip",
				"html" => "$this->email_top
									<p>Hi $user_name!<br/><br/>There has been a successful login into your Yobichain account. The details are:</p>
									<table rules='all' style='border-color: #dfd9c2;' cellpadding=10>
										<tr><td>Email:</td><td>$user_email</td></tr>
										<tr><td>IP address:</td><td>$ip</td></tr>
										<tr><td>Browser:</td><td>$browser</td></tr>
									</table>
									<p><br/>If you have initiated this login, you can safely ignore this email. <br/><br/><strong>If you have not initiated this login, someone has just accessed your account without authorization. Please take appropriate action immediately.</strong></p>
									$this->email_bottom",
			);

			$request =  $url.'api/mail.send.json';

			//Generate curl request
			$session = curl_init($request);
			//Tell PHP not to use SSLv3 (instead opting for TLS)
			curl_setopt($session, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1_2);
			curl_setopt($session, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $sendgrid_apikey));
			//Tell curl to use HTTP POST
			curl_setopt ($session, CURLOPT_POST, true);
			//Tell curl that this is the body of the POST
			curl_setopt ($session, CURLOPT_POSTFIELDS, $params);
			//Tell curl not to return headers, but do return the response
			curl_setopt($session, CURLOPT_HEADER, false);
			curl_setopt($session, CURLOPT_RETURNTRANSFER, true);

			//obtain response
			$response = curl_exec($session);
			curl_close($session);

			//print everything out
			print_r($response);

		}

/*******************************************************************************************************************
Email password reset link
*******************************************************************************************************************/

		public function sendPasswordResetEmail($user_email,$user_name,$random)
		{
		
	   		require 'vendor/autoload.php';

			$sendgrid_apikey = NotificationParams::EMAIL_PROVIDER_API_KEY;
			$sendgrid = new SendGrid($sendgrid_apikey);

			$url = 'https://api.sendgrid.com/';
			$pass = $sendgrid_apikey;

			$params =  array(
				 "to" => $user_email,
				   "formname" =>  "Yobichain",
			"from" => "info@sripathi.co.in",
			"subject" => "Password reset instructions",
			"html" => "$this->email_top
								<p>Hi $user_name!<br/><br/>A password reset has been initiated for your Yobichain account. To reset your password, <a href='$this->base_url/reset_password_logged_out.php?user_email=$user_email&random=$random'>click here</a>.<br/>
								</p>
								<p><strong>If you have not initiated this request, simply delete this email.</strong></p>
								$this->email_bottom",
			);
			$request =  $url.'api/mail.send.json';

			//Generate curl request
			$session = curl_init($request);
			//Tell PHP not to use SSLv3 (instead opting for TLS)
			curl_setopt($session, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1_2);
			curl_setopt($session, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $sendgrid_apikey));
			//Tell curl to use HTTP POST
			curl_setopt ($session, CURLOPT_POST, true);
			//Tell curl that this is the body of the POST
			curl_setopt ($session, CURLOPT_POSTFIELDS, $params);
			//Tell curl not to return headers, but do return the response
			curl_setopt($session, CURLOPT_HEADER, false);
			curl_setopt($session, CURLOPT_RETURNTRANSFER, true);

			//obtain response
			$response = curl_exec($session);
			curl_close($session);

			//print everything out
			print_r($response);
		}


/***************************************************************************************
SMS notification for user login
***************************************************************************************/

		public function smsUserLogin($user, $ip_address)
		{
			$mailin = new MailinSms(NotificationParams::SMS_PROVIDER_API_KEY);
			$mailin->addTo($user->user_cell) 
			->setFrom('Yobichain')
			->setText('Hello '.$user->user_name.'! Your account was logged in from the IP address '.$ip_address.' at '. date('d-m-Y H:i:s')) // 160 characters per SMS.
			->setTag('Yobichain')
			->setType('transactional') // Two possible values: marketing or transactional.
			->setCallback('http://www.sripathi.co.in');
			$res = $mailin->send();
		}


/*******************************************************************************************************************
Send activation email to newly created user
*******************************************************************************************************************/

		public function sendActivationEmail($user)
		{
			require 'vendor/autoload.php';

			$sendgrid_apikey = NotificationParams::EMAIL_PROVIDER_API_KEY;
			$sendgrid = new SendGrid($sendgrid_apikey);

			$url = 'https://api.sendgrid.com/';
			$pass = $sendgrid_apikey;

			$params =  array(
				 "to" => $user->user_email,
				 "formname" => "Yobichain",
				"from" => "info@sripathi.co.in",
				"subject" => "Account activation <> " . $user->user_name,
				"html" => $this->email_top . 
							"<p>Hi " . $user->user_name."!<br/><br/>Welcome to Yobichain. Your details are:</p>
								<table rules='all' style='border-color: #dfd9c2;' cellpadding=10>
									<tr><td>Name:</td><td>" . $user->user_name."</td></tr>
								</table>
							<p>If the details are correct, click the 'Activate account' button below and your login credentials will be emailed to you.</p>
							<p style='padding:3px;'><br/><a href='" . $this->base_url."/user_activate.php?user_email=" . urlencode($user->user_email)."&random=" . $user->random."' style='font-family: Helvetica, Arial, sans-serif; font-size: 14px; color: #ffffff; font-weight: bold; border-radius: 6px; background: #427db5; border-style:solid; border-color:#417db4; border-width:1px; text-align:center; padding-top:11px; padding-bottom:11px; padding-left:22px; padding-right:22px; text-decoration: none; border-top-color:#417db4; border-bottom-color:#9A6E19; border-left-color:#C59B29; border-right-color:#C59B29;'>Activate account</a><br/>&nbsp;<br/>&nbsp;</p>
							<p>If you are unable to click the button above, copy paste this link in your browser and press Enter: $this->base_url/user_activate.php?user_email=" . urlencode($user->user_email)."&random=" . $user->random."</p><br/>
							" . $this->email_bottom,
			);
				
			$request =  $url.'api/mail.send.json';

			//Generate curl request
			$session = curl_init($request);
			//Tell PHP not to use SSLv3 (instead opting for TLS)
			curl_setopt($session, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1_2);
			curl_setopt($session, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $sendgrid_apikey));
			//Tell curl to use HTTP POST
			curl_setopt ($session, CURLOPT_POST, true);
			//Tell curl that this is the body of the POST
			curl_setopt ($session, CURLOPT_POSTFIELDS, $params);
			//Tell curl not to return headers, but do return the response
			curl_setopt($session, CURLOPT_HEADER, false);
			curl_setopt($session, CURLOPT_RETURNTRANSFER, true);

			//obtain response
			$response = curl_exec($session);
			curl_close($session);

			//print everything out
			print_r($response);
		}

/*******************************************************************************************************************
Send login credentials to newly created user
*******************************************************************************************************************/

		public function sendLoginCredentials($user_email,$user_name,$user_id,$user_password)
		{
			 require 'vendor/autoload.php';

			$sendgrid_apikey = NotificationParams::EMAIL_PROVIDER_API_KEY;
			$sendgrid = new SendGrid($sendgrid_apikey);

			$url = 'https://api.sendgrid.com/';
			$pass = $sendgrid_apikey;

			$params =  array(
				 "to" => $user_email,
				 "fromname" => "Yobichain",
				"from" => "info@sripathi.co.in",
				"subject" => "Login credentials <> $user_name",
				"html" => "$this->email_top
							<p>Hi $user_name!<br/><br/>This is what you need to login to Yobichain:</p>
							<table rules='all' style='border-color: #dfd9c2;' cellpadding=10>
								<tr><td>Email:</td><td>$user_email</td></tr>
								<tr><td>Password:</td><td>$user_password</td></tr>
								<tr>
									<td>Login at:</td><td><a href='$this->base_url/login.php'>$this->base_url/login.php</a>
								</td>
							</tr>
							</table><br/><br/>
							$this->email_bottom",
			);
				
			$request =  $url.'api/mail.send.json';

			//Generate curl request
			$session = curl_init($request);
			//Tell PHP not to use SSLv3 (instead opting for TLS)
			curl_setopt($session, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1_2);
			curl_setopt($session, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $sendgrid_apikey));
			//Tell curl to use HTTP POST
			curl_setopt ($session, CURLOPT_POST, true);
			//Tell curl that this is the body of the POST
			curl_setopt ($session, CURLOPT_POSTFIELDS, $params);
			//Tell curl not to return headers, but do return the response
			curl_setopt($session, CURLOPT_HEADER, false);
			curl_setopt($session, CURLOPT_RETURNTRANSFER, true);

			//obtain response
			$response = curl_exec($session);
			curl_close($session);

			//print everything out
			print_r($response);
		}


	}

?>