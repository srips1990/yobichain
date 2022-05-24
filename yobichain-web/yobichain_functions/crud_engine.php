<?php
/*******************************************************************************************************************
Class for database interactions
*******************************************************************************************************************/

include_once(__DIR__."/"."config.php");
include_once(__DIR__."/../"."classes/classes.php");

	class crudEngine
	{
		private $dbo;

		function __construct()
		{
			$this->dbo = new PDO("mysql:host=".DBParams::DB_HOST_NAME.";dbname=".DBParams::DATABASES['YOBICHAIN'], DBParams::DB_USER_NAME, DBParams::DB_PASSWORD);
		}

/*******************************************************************************************************************
Begin transaction
*******************************************************************************************************************/

		public function beginTransaction()
		{
			$this->dbo->beginTransaction();
		}

/*******************************************************************************************************************
Commit transaction
*******************************************************************************************************************/

		public function commit()
		{
			$this->dbo->commit();
		}

/*******************************************************************************************************************
Rollback transaction
*******************************************************************************************************************/

		public function rollBack()
		{
			try {
				$this->dbo->rollBack();
				return true;
			}
			catch (PDOException $ex) {
				return false;
			}
		}


/*******************************************************************************************************************
		 * Gets user's details from Blockchain
*******************************************************************************************************************/

		public function getUserAddress($userID)
		{
			try
			{
				$stmt = $this->dbo->prepare("SELECT user_public_address FROM user_masterlist WHERE user_id=:user_id");
				$stmt->bindParam(':user_id', $userID);
				if (!$stmt->execute())
					throw new Exception("Error Processing Request", 1);
					
				$row  = $stmt->fetch();

				if(count($row) > 0)
		        	return $row[0];
		        else
		            throw new Exception("No address(es) found for this user!", 1);
			}
			catch (Exception $e)
			{
				throw $e;
			}
				
		}


/*******************************************************************************************************************
		 * Gets user's name using Blockchain address
*******************************************************************************************************************/

		public function getUserNameFromPublicAddress($address)
		{
			try
			{
				$stmt = $this->dbo->prepare("SELECT user_name FROM user_masterlist WHERE user_public_address=:user_public_address");
				$stmt->bindParam(':user_public_address', $address);
				if (!$stmt->execute())
					return false;					
				$row  = $stmt->fetch(PDO::FETCH_ASSOC);
				return ((count($row) > 0) ? $row['user_name'] : null);
			}
			catch (Exception $e)
			{
				throw $e;
			}
				
		}


/*******************************************************************************************************************
		 * Update public address and public key for a user
*******************************************************************************************************************/

		public function updateUserPublicAddress($user_id, $user_public_address, $user_public_key)
		{
			try
			{
				$stmt = $this->dbo->prepare("UPDATE user_masterlist SET user_public_address=:user_public_address, user_public_key=:user_public_key WHERE user_id=:user_id");
				$stmt->bindParam(':user_id', $user_id);
				$stmt->bindParam(':user_public_address', $user_public_address);
				$stmt->bindParam(':user_public_key', $user_public_key);
				
				if(!$stmt->execute())
		            throw new Exception("No address(es) found for this user!", 1);
			}
			catch (Exception $e)
			{
				throw $e;
			}
				
		}


/*******************************************************************************************************************
Check if a user exists in the db based on the user_id
*******************************************************************************************************************/

		public function userExists($user_id)
		{
			$STM = $this->dbo->prepare("SELECT id, user_name, user_email, user_password, creator FROM user_masterlist WHERE id=:user_id");
			$STM->bindParam(':user_id', $userID);
			$STM->execute();
			$row  = $STM -> fetch();
			if(count($row)>0)
	        	return true;
	        else
	            return false;
		}


/*******************************************************************************************************************
Get user's name, email and cell number from the database
*******************************************************************************************************************/

		public function getUserDetails($user_id)
		{
			$STM = $this->dbo->prepare("SELECT user_id, user_name, user_cell, user_email, user_public_address FROM user_masterlist WHERE user_id = :user_id AND is_deleted='n'");
			$STM->bindParam(":user_id", $user_id);
			if (!$STM->execute())
				{
					return false;
				}
			return $STM->fetch(PDO::FETCH_ASSOC);
		}


/*******************************************************************************************************************
Get user's name, email and cell number from the database by user's email
*******************************************************************************************************************/

		public function getUserDetailsByEmail($user_email)
		{
			$STM = $this->dbo->prepare("SELECT user_id, user_name, user_cell, user_email, user_public_address FROM user_masterlist WHERE user_email = :user_email AND is_deleted='n'");
			$STM->bindParam(":user_email", $user_email);
			if (!$STM->execute())
				{
					return false;
				}
			return $STM->fetch(PDO::FETCH_ASSOC);
		}


/*******************************************************************************************************************
Get user's full details from the database
*******************************************************************************************************************/

		public function getUserFullDetails($search_param, $returnAsObjects=false)
		{
			$STM = $this->dbo->prepare("SELECT user_id, user_name, user_email, user_cell, checked, user_public_address, user_public_key, user_created_by, timestamp, random, is_deleted FROM user_masterlist WHERE user_id = :search_param OR user_email = :search_param");

			$STM->bindParam(":search_param", $search_param);
			
			if (!$STM->execute())
				{
					return false;
				}
			$record  = $STM -> fetch(PDO::FETCH_ASSOC);

			if ($returnAsObjects === false)
				return $record;
			else {
				$obj = new User();
				foreach ($record as $key => $value) {
					$obj->{$key} = $value;
				}
				return $obj;
			}
		}

/*******************************************************************************************************************
Get the name / title of a role based on role_code
*******************************************************************************************************************/

		public function getRoleTitle($role_code)
		{
			$STM = $this->dbo->prepare("SELECT role_title FROM role_masterlist WHERE role_code=:role_code");
			$STM->bindParam(':role_code', $role_code);
			$STM->execute();
			//$count = $STM->rowCount(); 
			$row = $STM -> fetch(); 
			$role_title=$row[0];
			return $role_title;
		}

/*******************************************************************************************************************
Add new department into the database
*******************************************************************************************************************/

		public function addNewDepartment($dept_name,$user_id)
		{
			$STM = $this->dbo->prepare("INSERT INTO dept_masterlist (dept_name, user_id) VALUES (:dept_name, :user_id)");
			$STM->bindParam(":dept_name", $dept_name);
			$STM->bindParam(":user_id", $user_id);

			if (!$STM->execute())
				{
					return false;
				}
			$row  = $STM -> fetch();
		}

/*******************************************************************************************************************
Count number of users from database
*******************************************************************************************************************/

		public function countUsers()
		{
			$STM = $this->dbo->prepare("SELECT count(*) as count FROM user_masterlist WHERE is_deleted='n'");
			if (!$STM->execute())
				{
					return false;
				}
			$record = $STM->fetch(PDO::FETCH_ASSOC);
			return $record['count'];
		}

/*******************************************************************************************************************
Count number of assets from database
*******************************************************************************************************************/

		public function countAssets()
		{
			$STM = $this->dbo->prepare("SELECT count(*) as count FROM asset_masterlist");
			if (!$STM->execute())
				{
					return false;
				}
			$record = $STM->fetch(PDO::FETCH_ASSOC);
			return $record['count'];
		}


/*******************************************************************************************************************
Get List of roles from database.
*******************************************************************************************************************/

		public function getRolesList()
		{
			$STM = $this->dbo->prepare("SELECT role_code, role_title FROM role_masterlist ORDER BY role_id");
			if (!$STM->execute())
				return false;
			
			return $STM->fetchAll(PDO::FETCH_ASSOC);
		}

/*******************************************************************************************************************
Get all role categories by role category codes
*******************************************************************************************************************/

		public function getRoleCategoriesByRoleCategoryCodes($role_category_codes)
		{
			$role_categories = array();
			$sql = "SELECT role_category_id, role_category_code, role_category_title, role_category_icon FROM role_category WHERE role_category_code in (";

			foreach ($role_category_codes as $index => $role_category_code_item) {
				$sql .= ":role_category_code".$index.",";
			}

			$sql = rtrim($sql,',');

			// $sql .= implode(', :', $role_category_codes);
			$sql .= ")";

			$STM = $this->dbo->prepare($sql);

			foreach ($role_category_codes as $index => $role_category_code_item) {
				$STM->bindValue(":role_category_code".$index, $role_category_code_item);
			}

			if (!$STM->execute())
				{
					return false;
				}
			$records = $STM->fetchAll(PDO::FETCH_ASSOC);

			foreach ($records as $index => $record) {
				$role_categories[$record['role_category_code']] = $record;
			}

			return $role_categories;
		}

/*******************************************************************************************************************
Get Roles information for the given role category
*******************************************************************************************************************/

		public function getActiveRolesForCategory($role_category_code)
		{
			$STM = $this->dbo->prepare("SELECT role_code, role_title FROM role_masterlist WHERE role_masterlist.role_category=:role_category_code AND role_display='y'");
			$STM->bindparam(':role_category_code', $role_category_code);
			if (!$STM->execute())
				return false;
			
			return $STM->fetchAll(PDO::FETCH_ASSOC);
		}

/*******************************************************************************************************************
Does a user with this email address exist in the database?
*******************************************************************************************************************/

		public function doesEmailExist($user_email)
		{
	        $STM = $this->dbo->prepare("SELECT user_id FROM user_masterlist WHERE user_email=:user_email");
	        $STM->bindParam(":user_email", $user_email);
	        if(!$STM->execute())
	        	throw new Exception("Error validating email address!", 1);
	        $count = $STM->rowCount();              
	        if($count==0)   
	            {
					return false;
				}
			else
				{
					return true;
				}
		}


/*******************************************************************************************************************
Insert new user details into database
*******************************************************************************************************************/

		public function createNewUser($user)
		{
			$sql = "INSERT INTO user_masterlist (";
			$params_array = array();
			$values_array = array();
			$columns_array = array();

			foreach ($user as $key => $value) {
				if (!empty($value) && !is_null($value)) {
					$columns_array[] = $key;
					$params_array[] = ':'.$key;
					$values_array[] = $value;
				}
			}
			
			// echo "<br>".implode(',', $columns_array)."<br>"."<br>";
			// echo implode(',', $params_array)."<br>"."<br>";
			// echo implode(',', $values_array)."<br>"."<br>";
			// echo "'".implode("', '", $values_array)."'"."<br>"."<br>";

			$sql .= implode(',', $columns_array) . ") VALUES (" . implode(',', $params_array).")";
			// echo $sql;
			$STM = $this->dbo->prepare($sql);
			echo $sql."<br>";
			echo json_encode($values_array)."<br><br>";
			foreach ($params_array as $index => $param_key) {
				$STM->bindValue($param_key, $values_array[$index]);
			}

			return $STM->execute();
		}

/*******************************************************************************************************************
Update existing user details in database
*******************************************************************************************************************/

		public function updateUser($user)
		{
			$sql = "UPDATE user_masterlist SET ";
			$columns_params_array = array();

			foreach ($user as $key => $value) {
				if (!empty($value) && !is_null($value) && !in_array($key, array('user_email', 'user_password', 'user_id'))) {
					$columns_params_array[] = $key.'=:'.$key;
				}
			}

			$sql .= implode(',', $columns_params_array) . " WHERE user_id=:user_id";
			echo $sql;
			$STM = $this->dbo->prepare($sql);
			$paramsArray = array();		// ONLY FOR DEBUGGING
			foreach ($user as $key => $value) {
				if (!empty($value) && !is_null($value) && !in_array($key, array('user_email', 'user_password'))){
					$STM->bindValue(':'.$key, $value);
					$paramsArray[] = ':'.$key;		// ONLY FOR DEBUGGING
				}
			}
			echo json_encode($paramsArray);			// ONLY FOR DEBUGGING

			return $STM->execute();
		}

/*******************************************************************************************************************
Verify user login
*******************************************************************************************************************/

		public function verifyCredential($user_email, $password)
		{
			$STM = $this->dbo->prepare("SELECT user_password FROM user_masterlist WHERE user_email = :user_email");
			$STM->bindParam(':user_email', $user_email);
			$STM->execute();
			$count = $STM->rowCount();
			$row  = $STM -> fetch();
			$hash=$row[0];

			return (password_verify($password, $hash));
		}

/*******************************************************************************************************************
Update user credential
*******************************************************************************************************************/

		public function updateCredential($user_id, $new_password)
		{
			$STM = $this->dbo->prepare("UPDATE user_masterlist SET user_password=:new_password WHERE user_id = :user_id");
			$STM->bindValue(':new_password', password_hash($new_password, PASSWORD_DEFAULT));
			$STM->bindValue(':user_id', $user_id);
			return $STM->execute();
		}


/*******************************************************************************************************************
Get all details of all users
*******************************************************************************************************************/

		public function listAllUsersAllDetails()
		{

			$STM = $this->dbo->prepare("SELECT 
											user_masterlist.user_id, 
											user_masterlist.user_name,
											user_masterlist.user_email, 
											user_masterlist.user_cell, 
											user_masterlist.orgn_id, 
											user_masterlist.occupation, 
											user_masterlist.addr_line1, 
											user_masterlist.addr_line2, 
											user_masterlist.city, 
											user_masterlist.zip_code, 
											user_masterlist.state, 
											user_masterlist.country, 
											user_masterlist.nationality, 
											user_masterlist.user_phone, 
											user_masterlist.kyc_doc_type_id, 
											user_masterlist.kyc_doc_hash, 
											user_masterlist.checked, 
											user_masterlist.user_public_address, 
											user_masterlist.user_public_key, 
											user_masterlist.user_created_by, 
											user_masterlist.timestamp, 
											user_masterlist.random, 
											user_masterlist.is_deleted, 
											orgn_masterlist.orgn_name

										FROM 
											user_masterlist, 
											orgn_masterlist 

										WHERE 
											orgn_masterlist.orgn_id = user_masterlist.orgn_id
										");

			if (!$STM->execute())
				{
					return false;
				}
			return $STM->fetchAll(PDO::FETCH_ASSOC);				
		}


/*******************************************************************************************************************
Insert user activity into logs
*******************************************************************************************************************/

		public function insertIntoLogs($user_id,$url,$ip,$browser,$ref)
		{
			$STM = $this->dbo->prepare("INSERT into log (user_id, url, ip, browser, ref) VALUES (:user_id, :url, :ip, :browser, :ref)");
			$STM->bindParam(':user_id', $user_id); 
			$STM->bindParam(':url', $url); 
			$STM->bindParam(':ip', $ip); 
			$STM->bindParam(':browser', $browser); 
			$STM->bindParam(':ref', $ref);  
			if (!$STM->execute())
				{
					return false;
				}
			$row  = $STM -> fetch();
		}

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
Generate a random string
*******************************************************************************************************************/

		public function random_num($length, $keyspace = '123456789')
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
Does a user with this email address exist in the database?
*******************************************************************************************************************/

		public function doesPendingUserExist($user_email,$random)
		{
	        $STM = $this->dbo->prepare("SELECT user_id FROM user_masterlist WHERE user_email=:user_email AND random=:random AND checked='n'");
	        $STM->bindParam(":user_email", $user_email);
	        $STM->bindParam(":random", $random);
	        $STM->execute();
	        $count = $STM->rowCount();              
	        if($count==0)   
	            {
					return false;
				}
			else
				{
					return true;
				}
		}

/*******************************************************************************************************************
Update user as verified
*******************************************************************************************************************/

		public function verifyUser($user_email,$user_password_hash,$random)
		{
			$STM = $this->dbo->prepare("UPDATE user_masterlist SET checked = 'y', user_password=:user_password_hash WHERE user_email=:user_email AND checked='n' AND random=:random");
			$STM->bindParam(':user_email', $user_email);
			$STM->bindParam(':random', $random);
			$STM->bindParam(':user_password_hash', $user_password_hash);
			if (!$STM->execute())
				{
					return false;
				}
			return ($STM->rowCount() > 0);
		}

/*******************************************************************************************************************
Retreive user info for sending credential emails
*******************************************************************************************************************/

		public function getUserInfoForWelcomeEmail($user_email, $random, $returnAsObjects=false)
		{
			$STM = $this->dbo->prepare("SELECT user_id, user_name, user_email, user_cell, checked, user_public_address, user_public_key, user_created_by, timestamp, random, is_deleted FROM user_masterlist WHERE user_email=:user_email AND random=:random");
			$STM->bindParam(':user_email', $user_email);
			$STM->bindParam(':random', $random);

			if (!$STM->execute())
				return false;

			$record = $STM->fetch(PDO::FETCH_ASSOC);

			if ($returnAsObjects === false)
				return $record;
			else {
				$obj = new User();
				foreach ($record as $key => $value) {
					$obj->{$key} = $value;
				}
				return $obj;
			}
		}

/*******************************************************************************************************************
Retreive user info for sending credential emails
*******************************************************************************************************************/

		public function getUserIdFromUserEmail($user_email)
		{
			$STM = $this->dbo->prepare("SELECT user_id FROM user_masterlist WHERE user_email=:user_email AND checked='n'");
			$STM->bindParam(':user_email', $user_email);
			if (!$STM->execute())
				{
					return false;
				}
			$row  = $STM -> fetch();
			return $row[0];
		}


/*******************************************************************************************************************
Retreive user info for sending credential emails
*******************************************************************************************************************/

		public function getUserIdsFromUserEmails($emails)
		{
			$sql = "SELECT user_id FROM user_masterlist WHERE user_email IN (";
			$paramsArray = array();
			foreach ($emails as $index => $value) {
				$paramsArray[$index] = ":email".$index;
			}

			$sql .= implode(",", $paramsArray).")";

			$STM = $this->dbo->prepare($sql);

			foreach ($emails as $index => $value) {
				$STM->bindValue($paramsArray[$index], $value);
			}

			if (!$STM->execute())
				{
					return false;
				}
			unset($paramsArray);
			$rows  = $STM -> fetchAll();
			$userIDs = array();

			foreach ($rows as $row) {
				$userIDs[] = $row[0];
			}

			return $userIDs;
		}

/*******************************************************************************************************************
Forgot Password - is email and random correct
*******************************************************************************************************************/

		public function isEmailRandomCorrect($user_email,$random)
		{
	        $STM = $this->dbo->prepare("SELECT user_id FROM user_masterlist WHERE user_email=:user_email AND random = :random");
	        $STM->bindParam(":user_email", $user_email);
	        $STM->bindParam(":random", $random);
	        $STM->execute();
	        $count = $STM->rowCount();              
	        if($count==0)   
	            {
					return false;
				}
			else
				{
					return true;
				}
		}

/*******************************************************************************************************************
Forgot Password - update password
*******************************************************************************************************************/

		public function updateNewPassword($new_password,$random,$user_email)
		{
	        $STM = $this->dbo->prepare("UPDATE user_masterlist SET user_password=:user_password, random=:random WHERE user_email = :user_email AND checked='y'");
			$STM->bindParam(':user_password', $new_password);
			$STM->bindParam(':random', $random);
			$STM->bindParam(':user_email', $user_email);
			if (!$STM->execute())
				{
					return false;
				}
			else
				{
					return true;
				}
		}

/*******************************************************************************************************************
Get user's public key and address from database
*******************************************************************************************************************/

		public function getUserAddressAndPublicKey($user_id)
		{
			$STM = $this->dbo->prepare("SELECT user_public_key, user_public_address FROM user_masterlist WHERE user_id = :user_id");
			$STM->bindParam(":user_id", $user_id);
			if (!$STM->execute())
				{
					return false;
				}
			$row  = $STM -> fetch();
			return array('user_public_key'=>$row[0], 'user_public_address'=>$row[1]);
		}

/*******************************************************************************************************************
Get user's public key and address from database
*******************************************************************************************************************/

		public function recordLoginInDB($user_id,$ip,$browser)
		{
			$STM = $this->dbo->prepare("INSERT into login (user_id, ip, browser) VALUES (:user_id, :ip, :browser)");
			$STM->bindParam(':user_id', $user_id); 
			$STM->bindParam(':ip', $ip); 
			$STM->bindParam(':browser', $browser); 
			if (!$STM->execute())
				{
					return false;
				}
			$row  = $STM -> fetch();
		}

/*******************************************************************************************************************
Get details of user's previous login
*******************************************************************************************************************/

		public function usersPreviousLoginDetails($user_id)
		{
	        $STM = $this->dbo->prepare("SELECT ip, browser, timestamp FROM login WHERE user_id=:user_id ORDER BY timestamp DESC LIMIT 0,1");
	        $STM->bindParam(":user_id", $user_id);

			if (!$STM->execute())
				{
					return false;
				}
			$row  = $STM -> fetch();
			return array('ip'=>$row[0], 'browser'=>$row[1], 'timestamp'=>$row[2]);
		}

/*******************************************************************************************************************
Update newly generated random string in database
*******************************************************************************************************************/

		public function updateRandom($user_email,$random)
		{
	        $STM = $this->dbo->prepare("UPDATE user_masterlist SET random=:random WHERE user_email = :user_email AND checked='y'");
		$STM->bindParam(':random', $random);
		$STM->bindParam(':user_email', $user_email);

			if (!$STM->execute())
				{
					return false;
				}
			else
				{
					return true;
				}
		}

/*******************************************************************************************************************
Get data from db for emailing password reset email
*******************************************************************************************************************/

		public function getDataForPasswordResetEmail($user_email)
		{
	        $STM = $this->dbo->prepare("SELECT user_name, random FROM user_masterlist WHERE user_email = :user_email AND checked='y'");
	        $STM->bindParam(":user_email", $user_email);

			if (!$STM->execute())
				{
					return false;
				}
			$row  = $STM -> fetch();
			return array('user_name'=>$row[0], 'random'=>$row[1]);
		}

/*******************************************************************************************************************
Get message details from db
*******************************************************************************************************************/

		public function getMessage($message_id)
		{
			$STM = $this->dbo->prepare("SELECT message, alert FROM message_masterlist WHERE message_id=:message_id");
			$STM->bindParam(':message_id', $message_id);
			if (!$STM->execute())
				{
					return false;
				}
			$row  = $STM -> fetch();
			return array('message'=>$row[0], 'alert'=>$row[1]);
		}

/*******************************************************************************************************************
Password reset - is old password correct
*******************************************************************************************************************/

		public function isOldPasswordCorrect($user_id,$old_password)
		{
	        $STM = $this->dbo->prepare("SELECT user_password FROM user_masterlist WHERE user_id = :user_id");
	        $STM->bindParam(":user_id", $user_id);
	        //$STM->bindParam(":random", $random);
	        $STM->execute();
	        //$count = $STM->rowCount();  
	        $row  = $STM -> fetch();            
	        	{
					$hash=$row[0];
					if (password_verify($old_password, $hash)) 
						{
							return true;
						}
					else
						{
							return false;
						}
				}
		}

/*******************************************************************************************************************
Password reset - update password
*******************************************************************************************************************/

		public function updatePassword($user_id,$new_password)
		{
	        $STM = $this->dbo->prepare("UPDATE user_masterlist SET user_password=:new_password WHERE user_id=:user_id");
	        $STM->bindParam(":user_id", $user_id);
	        $STM->bindParam(":new_password", $new_password);
			if (!$STM->execute())
				{
					return false;
				}
			else
				{
					return true;
				}
		}


/*******************************************************************************************************************
Get all Assets names as list
*******************************************************************************************************************/

		public function listAllAssetsNames()
		{
			$STM = $this->dbo->prepare("SELECT asset_name FROM asset_masterlist");

			if (!$STM->execute())
				return false;

			$assets_array = $STM -> fetchAll(PDO::FETCH_ASSOC);
			$assets_info = array();

			foreach ($assets_array as $asset_item) {
				$assets_info[] = $asset_item['asset_name'];
			}

			return $assets_info;
		}


/*******************************************************************************************************************
Get Assets names list for an organization
*******************************************************************************************************************/

		public function listAssetsNamesForUser($user_id)
		{
			$STM = $this->dbo->prepare("SELECT asset_name FROM asset_masterlist WHERE created_by=:created_by");
			$STM->bindParam(":created_by", $user_id);

			if (!$STM->execute())
				return false;

			$assets_array = $STM -> fetchAll(PDO::FETCH_ASSOC);
			$assets_info = array();

			foreach ($assets_array as $asset_item) {
				$assets_info[] = $asset_item['asset_name'];
			}

			return $assets_info;
		}


/*******************************************************************************************************************
Check whether an asset belongs to the specified organization
*******************************************************************************************************************/

		public function doesAssetBelongToUser($asset_name, $user_id)
		{
			$STM = $this->dbo->prepare("SELECT asset_name FROM asset_masterlist WHERE lower(asset_name)=:asset_name AND created_by=:created_by");
			$STM->bindValue(":asset_name", strtolower($asset_name));
			$STM->bindValue(":created_by", $user_id);
			if (!$STM->execute())
				throw new Exception("Error verifying validating asset!");

			return ($STM->rowCount() > 0);
		}


/*******************************************************************************************************************
Marks a group as deleted
*******************************************************************************************************************/

		public function deleteGroup($orgn_id)
		{
			$STM = $this->dbo->prepare("UPDATE orgn_masterlist SET is_deleted='y' WHERE orgn_id=:orgn_id");
			$STM->bindParam(":orgn_id", $orgn_id);

			return $STM->execute();
		}


/*******************************************************************************************************************
Marks a KYC document type as deleted
*******************************************************************************************************************/

		public function deleteKycDocumentType($document_id)
		{
			$STM = $this->dbo->prepare("UPDATE kyc_document_masterlist SET is_deleted='y' WHERE document_id=:document_id");
			$STM->bindParam(":document_id", $document_id);

			return $STM->execute();
		}


/*******************************************************************************************************************
Marks an asset as deleted
*******************************************************************************************************************/

		public function deleteUser($user_id)
		{
			$STM = $this->dbo->prepare("UPDATE user_masterlist SET is_deleted='y' WHERE user_id=:user_id");
			$STM->bindParam(":user_id", $user_id);

			return $STM->execute();
		}

/******************************************************************************************************************/


/*******************************************************************************************************************
Insert asset details into DB
*******************************************************************************************************************/

		public function insertAssetDetailsToDB($asset)
		{
			$STM = $this->dbo->prepare("INSERT INTO asset_masterlist (asset_name, created_by) VALUES (:asset_name, :created_by)");
			$STM->bindValue(":asset_name", $asset->name);
			$STM->bindValue(":created_by", $asset->created_by);
			return $STM->execute();
		}


/*******************************************************************************************************************
Get asset details from DB
*******************************************************************************************************************/

		public function getAssetDetailsFromDB($asset)
		{
			$STM = $this->dbo->prepare("SELECT created_by, timestamp FROM asset_masterlist WHERE asset_name=:asset_name");
			$STM->bindValue(":asset_name", $asset->name);
			
			if (!$STM->execute())
				throw new Exception("Unable to fetch asset details!", 1);

			$record = $STM->fetch(PDO::FETCH_ASSOC);

			foreach ($record as $key => $value) {
				$asset->{$key} = $value;
			}
			return $asset;
		}


/*******************************************************************************************************************
Insert upload details to Database
*******************************************************************************************************************/

		public function insertUploadToDB($upload)
		{
			$STM = $this->dbo->prepare("INSERT INTO user_uploads (file_hash, transaction_id, user_id) VALUES (:file_hash, :transaction_id, :user_id)");
			$STM->bindParam(':file_hash', $upload->file_hash); 
			$STM->bindParam(':transaction_id', $upload->transaction_id); 
			$STM->bindParam(':user_id', $upload->user_id); 
			return $STM->execute();
		}

/******************************************************************************************************************/


/*******************************************************************************************************************
Insert asset details into DB
*******************************************************************************************************************/

		public function getUploadsByTransactionIds($transactionIDs)
		{
			$sql = "SELECT upload_id, file_hash, transaction_id, user_id, timestamp FROM user_uploads WHERE transaction_id IN ";

			$params .= "(";
			foreach ($transactionIDs as $index => $transactionID) {
				$params .= "':transaction_id".($index+1)."'";
				if ($index == (count($transactionIDs)-1))
					$params .= ",";
			}
			$params .= ")";

			$STM = $this->dbo->prepare($sql);

			foreach ($transactionIDs as $index => $transactionID) {
				$STM->bindValue(":transaction_id".($index+1), $transactionID);
			}

			if (!$STM->execute())
				return false;

			return $STM->fetchAll(PDO::FETCH_ASSOC);
		}

/******************************************************************************************************************/
	}
?>