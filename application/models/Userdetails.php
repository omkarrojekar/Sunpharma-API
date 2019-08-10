<?php 
	class Userdetails extends CI_Model {
		public function index()
		{
		}

		function check_email_exist($email)
		{
			$sql = "SELECT * FROM `accounts` WHERE email = '$email'";
			$result = $this->db->query($sql);
			$row = $result->row();
			//$count = $result->num_rows();
			if($row)
			{
				return 0;
			}
			else
			{
				return 1;
			}
		}
		function check_password($email,$password)
		{
			$sql = "SELECT * FROM `accounts` WHERE email = '$email' AND password = '$password'";
			$result = $this->db->query($sql);
			$row = $result->row();
			//$count = $result->num_rows();
			if($row)
			{
				return 0;
			}
			else
			{
				return 1;
			}
		}
		function user_loggedIn_Status($email)
		{
			$sql = "SELECT * FROM `accounts` WHERE email = '$email'";
			$result = $this->db->query($sql);
			$row = $result->row();
			//$count = $result->num_rows();
			if($row->loginstatus == 1)
			{
				return 0;
			}
			else
			{
				return 1;
			}	
		}
		function activate_login_Status($email)
		{
				$this->db->set('loginstatus', 1); //value that used to update column  
				$this->db->where('email', $email); //which row want to upgrade  
				$this->db->update('accounts');  //table name
		}
		private $email_code;
		private $forget_email_code;
		// public function add_details($post)
		// {
		// 	//print_r($post['email']); exit();

		// 	unset($post['Submit']);
		// 	$id = rand(111111111,999999999);
		// 	$random = rand(111111111,999999999);
		// 	$fname = $post['firstName'];
		// 	$lname = $post['lastName'];
		// 	$email = $post['email'];
		// 	$usr = $post['username'];
		// 	$pass = $post['password'];
		// 	$contact = $post['phoneNumber'];
		// 	$run = $this->db->insert('users',['user_id'=>$id,'first_name'=>$fname,'last_name'=>$lname,'email'=>$email,'username'=>$usr,'password'=>$pass,'contact'=>$contact,'random'=>$random,'activated'=>0,'status'=>1,'reg_time'=>'CURRENT_DATE']);	
		// 	if($this->db->affected_rows()===1)
		// 	{
		// 		$this->set_session($id,$fname,$lname,$email);
		// 		if($this->send_validation_email())
		// 		{
		// 			return $fname;	
		// 		}
				
		// 	}

		// }


		function _custom_query($mysql_query) {
		    $query = $this->db->query($mysql_query);
		    return $query;
		}


		public function add_details($post)
		{
			//print_r($post['email']); exit();

			unset($post['Submit']);
			$id = rand(111111111,999999999);
			$random = rand(111111111,999999999);
			$fname = $post['firstName'];
			$lname = $post['lastName'];
			$email = $post['email'];
			$usr = $post['username'];
			$pass = $post['password'];
			$contact = $post['phoneNumber'];
			$run = $this->db->insert('users',['user_id'=>$id,'first_name'=>$fname,'last_name'=>$lname,'email'=>$email,'username'=>$usr,'password'=>$pass,'contact'=>$contact,'random'=>$random,'activated'=>0,'status'=>1,'reg_time'=>'CURRENT_DATE']);	
			if($this->db->affected_rows()===1)
			{
				$this->set_session($email);
				$values = $this->send_validation_email();
				return $values;
				
				
			}

		}
		private function set_session($email)
		{
			$sql = "SELECT `user_id`, `reg_time` FROM `users` WHERE email= '" .$email. "'LIMIT 1 ";
			$result = $this->db->query($sql);
			$row = $result->row();
			
			$this->email_code = md5((string)$row->user_id);
			$abc = $this->email_code;
			//print_r($abc); exit();
			$this->session->set_userdata('emailcode',$abc);
			//$this->session->set_userdata('email',$email);
			//$this->session->set_userdata($sess_data);
		}
		
		// private function send_validation_email()
		// {
		// 	$email = $this->session->userdata('email');	
			
		// 	$email_code = $this->email_code;
		// 	//print_r($email_code); exit();
		// 	$this->email->set_mailtype('html');
		// 	$this->email->from('awacs2017@rrcgvir.com', 'Omkar Rojekar');
		// 	$this->email->to($email);
		// 	$this->email->subject('Please Activate your account');
		// 	$message = '<!DOCTYPE html>
		// 	<html>
		// 	<head>
		// 	</head>
		// 	<body>';
		// 	$message .= '<p>Dear' .$this->session->userdata('fname'). ',</p>';
		// 	$message .= '<p>Thank you please <strong><a href ="' .base_url(). '/home/verify_email/' . $email . '/' . $email_code .'">Click Here</a></strong> to activate your account.</p>';
		// 	$message .= '<p>Thank You!</p>';
		// 	$message .= '</body> </html>';
		// 	print_r($message); exit();
			
		// 	$this->email->message($message);
			
		// 	 $this->email->send();
			
		// 	//echo $this->email->print_debugger();
		// }
		private function send_validation_email()
		{
			$email_code = $this->email_code;
			return $email_code;
			//print_r($email_code); exit();

		}
		// public function verify_email($email,$email_code)
		// {
		// 		$chkemail = $email; 
		// 		$sql = "SELECT `first_name`, `email`,`reg_time` FROM `users` WHERE email= '" .$chkemail. "'LIMIT 1 ";
		// 		$result = $this->db->query($sql);
		// 		$row =  $result->row();
		// 		if(($result->num_rows()===1) && ($row->fname))
		// 		{
		// 				if(md5((string)$row->reg_time)===$email_code)
		// 				{
		// 					$result = $this->activate_account($chkemail);
		// 					if($result === true)
		// 					{
		// 						return true;
		// 					}
		// 					else
		// 					{
		// 						echo "Not done ";
		// 					}
		// 				}
		// 				else
		// 				{
		// 					echo "Not Verified value problem";			
		// 				}
		// 		}
		// 		else
		// 		{
		// 			echo "Not Verified";
		// 		}
		// }
		public function verify_email($codevalue, $email)
		{
			$received_code = $codevalue;
			$received_email = $email;
			//$og = $this->email_code;
			if($received_code == $this->session->userdata('emailcode'))
			{
				$result = $this->activate_account($received_email);
				if($result)
				{
					return true;
				}	
				else
				{
					return false;
				}
			}
			else
			{
				echo "Not matching prameters";
			} 	
			
		}
		/*private function activate_account($chkemail)
		{
			$email = $chkemail;
			$sql = "UPDATE `users` SET `activated`=1 WHERE email= '" .$email. "'LIMIT 1 ";
			$result = $this->db->query($sql);
			if($result)
			{
				return true;
			}
			else
			{
				return false;
			}
		}*/
		private function activate_account($received_email)
		{
			
			$email = $received_email;
			//print_r($email); exit();
			$sql = "UPDATE `users` SET `activated`=1 WHERE email = '$email'";
			$result = $this->db->query($sql);
			//$data = array('activated' => 1);
			//$where = "email = $email";
			//$str = $this->db->update_string('table_name', $data, $where);
			if($result)
			{
				return true;
			}
			else
			{
				return false;
			}
		}
		public function forgetpassword($post)
		{
			$email = $post['email'];
			$sql = "SELECT  `user_id`,`email`  FROM `users` WHERE email= '$email' ";
			$result = $this->db->query($sql);
			$row = $result->row();
			$ogemail = $row->email;
			if($email == $ogemail)
			{
				$this->forget_email_code = md5((string)$row->user_id);
				
				return true;

				//print_r(); exit($this->forget_email_code);
				//echo "Right email";
			}
			else
			{
				echo "Wrong email";	
			}

			//echo  $email;
		}
		public function set_new_password($post)
		{
			$password = $post['password'];
			$email = $post['email'];
			$sql = "UPDATE `users` SET `password`= '$password' WHERE email = '$email'";
			$result = $this->db->query($sql);
			{
				if($result)
				{
					
					return true;
				}
				else
				{
					return false;
				}
			}

		}

		public function storeMrDetails($userData)
		{	
			$run = $this->db->insert('accounts',['name'=>$userData['name'],'email'=>$userData['email'],'contact'=>$userData['contact'],'designation'=>$userData['designation'],'location'=>$userData['location'],'username'=>$userData['username'],'password'=>"password"]);
			if($this->db->affected_rows()===1)
			{
				return true;
			}		
			else
			{
				return false;
			}
		}


		public function storeDoctorDetails($userData)
		{
			$run = $this->db->insert('doctors',['name'=>$userData['name'],'email'=>$userData['email'],'contact'=>$userData['contact'],'mr'=>$userData['mrId']]);
			if($this->db->affected_rows()===1)
			{
				return true;
			}		
			else
			{
				return false;
			}	
		}

		public function get_contact_number($obj)
		{
			$doctorId = $obj['getDoctorId'];	
			$sql = "SELECT * FROM `doctors` WHERE id = '$doctorId'";
			$result = $this->db->query($sql);
			$row = $result->row();
			return $row->contact;
		}

		public function is_duplicate_data($obj)
		{
			$doctorId = $obj['getDoctorId'];
			$fileid = $obj['getFileId'];
			$mrId = $obj['mrId'];
			$sql = "SELECT * FROM `sharedfiles` WHERE drid = '$doctorId' AND mrid = '$mrId' AND fileid = '$fileid'";
			$result = $this->db->query($sql);
			$row = $result->row();
			if($row)
			{
				$data['bool'] = 1;
				$data['linkid'] = $row->linkid;
				return $data;
			}
			else
			{
				$data['bool'] = 0;
				$data['linkid'] = "null";
				return $data;
			}

		}

		public function check_file_is_downloaded_or_not($obj)
		{
				$doctorId = $obj['getDoctorId'];
				$fileid = $obj['getFileId'];
				$mrId = $obj['mrId'];
				$sql = "SELECT * FROM `sharedfiles` WHERE drid = '$doctorId' AND mrid = '$mrId' AND fileid = '$fileid' AND downloadstatus = 1";
				$result = $this->db->query($sql);
				$row = $result->row();
				if($row)
				{
					return true;
				}
				else
				{
					return false;
				}
		}

		public function check_file_viewed_or_not($obj)
		{
			$doctorId = $obj['getDoctorId'];
			$fileid = $obj['getFileId'];
			$mrId = $obj['mrId'];
			$sql = "SELECT * FROM `sharedfiles` WHERE drid = '$doctorId' AND mrid = '$mrId' AND fileid = '$fileid' AND viewed = 'not yet";
			$result = $this->db->query($sql);
			$row = $result->row();
			if($row)
			{
				return true;
			}
			else
			{
				return false;
			}


		}


		public function send_file_to_doctor($obj,$otp,$linkId)
		{
			$date = date('Y-m-d H:i:s');
			$run = $this->db->insert('sharedfiles',['linkid'=>$linkId,'fileid'=>$obj['getFileId'],'drid'=>$obj['getDoctorId'],'mrId'=>$obj['mrId'],'send_on'=>$date,'otp'=>$otp]);
			if($run)
			{
				return $linkId;
			}		
			else
			{
				return false;
			}		
		}

		public function getMrName($userData)
		{
			$mrId = $userData['mrid'];
			$sql = "SELECT * FROM `accounts` WHERE id = '$mrId'";
			$result = $this->db->query($sql);
			$row = $result->row();
			return $row->name;
		}

		public function upload_file($userData)
		{
			$date = date('Y-m-d H:i:s');

			$run = $this->db->insert('files',['name'=>$userData['name'],'description'=>$userData['description'],'base64'=>$userData['base64'],'date'=>$date]);
			if($run)
			{
				return true;
			}
			else
			{
				return false;
			}
		}

		public function update_Mr_Details($userData)
		{
			$id = $userData['id'];
			$name = $userData['name'];
			$email = $userData['email'];
			$contact = $userData['contact'];
			$designation = $userData['designation'];
			$location = $userData['location'];
			$update = $this->db->set('name', $name); //value that used to update column  
						$this->db->set('email', $email); //value that used to update column  
						$this->db->set('contact', $contact); //value that used to update column  
						$this->db->set('designation', $designation); //value that used to update column  
						$this->db->set('location', $location); //value that used to update column  
						$this->db->where('id', $id); //which row want to upgrade  zzzz
						$this->db->update('accounts');  //table name
			if($update)
			{
				return true;
			}
			else
			{
				return false;
			}
		}

		public function update_doctor_Details($userData)
		{
			$update = $this->db->set('name', $userData['name']); //value that used to update column  
						$this->db->set('email', $userData['email']); //value that used to update column  
						$this->db->set('contact', $userData['contact']); //value that used to update column  
						$this->db->set('mr', $userData['mr']); //value that used to update column  
						$this->db->where('id', $userData['id']); //which row want to upgrade  
						$this->db->update('doctors');  //table name
			if($update)
			{
				return true;
			}
			else
			{
				return false;
			}	
		}
		public function check_admin_username($userData)
		{
			$username = $userData['username'];			
			$sql = "SELECT * FROM `admin` WHERE username = '$username'";
			$result = $this->db->query($sql);
			$row = $result->row();
			if($row)
			{
				return true;
			}
			else
			{
				return false;
			}
		}
		public function check_admin_password($userData)
		{
			$username = $userData['username'];
			$password = $userData['password'];
			$sql = "SELECT * FROM `admin` WHERE username = '$username' AND password = '$password'";
			$result = $this->db->query($sql);
			$row = $result->row();
			if($row)
			{
				return true;
			}
			else
			{
				return false;
			}
		}

		public function admin_login($userData)
		{

			$username = $userData['username'];
			$password = $userData['password'];
			$sql = "SELECT * FROM `admin` WHERE username = '$username' AND password = '$password'";
			$result = $this->db->query($sql);
			$row = $result->row();
		 	if($row)
		 	{
		 		$data['id'] = $row->id;
		 		$data['role'] = $row->role;
		 		$data['name'] = $row->name;
		 		return $data;
		 	}
		 	else
		 	{
		 		return false;
		 	}	
		}


		public function user_login($userData)
		{
			$username = $userData['username'];
			$password = $userData['password'];
			$sql = "SELECT * FROM `accounts` WHERE username = '$username' AND password = '$password'";
			$result = $this->db->query($sql);
			$row = $result->row();
		 	if($row)
		 	{
		 		$data['id'] = $row->id;
		 		$data['role'] = $row->role;
		 		$data['name'] = $row->name;
		 		return $data;
		 	}
		 	else
		 	{
		 		return false;
		 	}		
		}
		public function check_username($userData)
		{
			$username = $userData['username'];
			$sql = "SELECT * FROM `accounts` WHERE username = '$username'";
			$result = $this->db->query($sql);
			$row = $result->row();
			if($row)
			{
				return true;
			}
			else
			{
				return false;
			}
		}

		public function check_user_password($userData)
		{
			$username = $userData['username'];
			$password = $userData['password'];
			$sql = "SELECT * FROM `accounts` WHERE username = '$username' AND password = '$password'";
			$result = $this->db->query($sql);
			$row = $result->row();
			if($row)
			{
				return true;
			}
			else
			{
				return false;
			}
		}

		public function change_view_status($sharedId)
		{
			$update = $this->db->set('status', 1); //value that used to update column  
			$this->db->where('id', $sharedId); //which row want to upgrade  
			$this->db->update('sharedfiles');  //table name
			if($update)
			{
				return true;
			}
			else
			{
				return false;
			}
		}

		public function get_visited_time($linkid)
		{
			$sql = "SELECT * FROM `sharedfiles` WHERE linkid = '$linkid'";
			$result = $this->db->query($sql);
			$row = $result->row();
			return $row->visited;
		}
		public function set_visited_time($linkid)
		{
			$date = date('Y-m-d H:i:s');
			$this->db->set('visited', $date); //value that used to update column  
			$this->db->where('linkid', $linkid); //which row want to upgrade  
			$this->db->update('sharedfiles');  //table name
		}
		public function check_message_send_or_not($linkid)
		{
			$sql = "SELECT * FROM `sharedfiles` WHERE linkid = '$linkid'";
			$result = $this->db->query($sql);
			$row = $result->row();
			return $row->message;	
		}
		public function check_already_viewed_or_not($linkid)
		{
			$sql = "SELECT * FROM `sharedfiles` WHERE linkid = '$linkid'";
			$result = $this->db->query($sql);
			$row = $result->row();
			return $row->viewed;			
		}
		public function change_message_status($linkid)
		{
			$this->db->set('message', 'sent'); //value that used to update column  
			$this->db->where('linkid', $linkid); //which row want to upgrade  
			$this->db->update('sharedfiles');  //table name	
		}

		public function get_otp($linkid)
		{
			$sql = "SELECT * FROM `sharedfiles` WHERE linkid = '$linkid'";
			$result = $this->db->query($sql);
			$row = $result->row();
			return $row->otp;
		}

		public function check_otp_available_or_not($linkid)
		{
			$sql = "SELECT * FROM `sharedfiles` WHERE linkid = '$linkid'";
			$result = $this->db->query($sql);
			$row = $result->row();
			return $row->otp;
		}
		public function check_otp_valid_or_not($linkid,$otp)
		{
			$sql = "SELECT * FROM `sharedfiles` WHERE linkid = '$linkid' AND otp = '$otp'";
			$result = $this->db->query($sql);
			$row = $result->row();
			if($row)
			{
				return true;
			}
			else
			{
				return false;
			}
		}
		public function check_file_downloaded_or_not($linkId)
		{
			$sql = "SELECT * FROM `sharedfiles` WHERE linkid = '$linkId'";
			$result = $this->db->query($sql);
			$row = $result->row();
			return $row->downloadstatus;
		}
		public function get_file($fileId)
		{
			$sql = "SELECT * FROM `files` WHERE id = '$fileId'";
			$result = $this->db->query($sql);
			$row = $result->row();
			return $row->base64;	
		}
		public function update_view_time_and_otp($linkid)
		{
			$date = date('Y-m-d H:i:s');
			$this->db->set('viewed', $date); //value that used to update column
			$this->db->set('otp', 0); //value that used to update column
			$this->db->where('linkid', $linkid); //which row want to upgrade  
			$this->db->update('sharedfiles');  //table name	
		}

		public function update_download_status($linkId)
		{
			$this->db->set('downloadstatus', 1);
			$this->db->where('linkid', $linkId); //which row want to upgrade  
			$this->db->update('sharedfiles');  //table name	
		}

		public function view_file($linkid)
		{
			$sql = "SELECT * FROM `sharedfiles` WHERE linkid = '$linkid'";
			$result = $this->db->query($sql);
			$row = $result->row();
			return $row->fileid;

		}

		public function get_count_of_files()
		{
			$sql = "SELECT COUNT(*) AS total FROM `files` WHERE status = 1";
			$result = $this->db->query($sql);
			$row = $result->row();
			return $row->total;
		}

		public function get_count_of_doctors()
		{
			$sql = "SELECT COUNT(*) AS total FROM `doctors` WHERE status = 1";
			$result = $this->db->query($sql);
			$row = $result->row();
			return $row->total;
		}

		public function get_count_of_mr()
		{
			$sql = "SELECT COUNT(*) AS total FROM `accounts` WHERE status = 1";
			$result = $this->db->query($sql);
			$row = $result->row();
			return $row->total;	
		}

		public function is_valid_password($userId,$oldPassword)
		{
			$sql = "SELECT * FROM `accounts` WHERE id = '$userId' AND password = '$oldPassword'";
			$result = $this->db->query($sql);
			$row = $result->row();
			if($row)
			{
				return true;
			}
			else
			{
				return false;
			}
		}

		public function change_password($userId,$newPassword)
		{
				$updateMr = $this->db->set('password', $newPassword);
				$this->db->where('id', $userId); //which row want to upgrade  
				$this->db->update('accounts');  //table name	
				if($updateMr)
				{
					return true;
				}
				else
				{
					return false;
				}
		}

		public function get_doctor_name($doctorId)
		{
			$sql = "SELECT * FROM `doctors` WHERE id = '$doctorId'";
			$result = $this->db->query($sql);
			$row = $result->row();
			return $row->name;
		}

		public function delete_mr($mrid)
		{
			$this->db->set('status', 0);
			$this->db->where('id', $mrid); //which row want to upgrade  
			$this->db->update('accounts');  //table name	
		}

		public function delete_doctor($drid)
		{
			$this->db->set('status', 0);	
			$this->db->where('id', $drid); //which row want to upgrade  
			$this->db->update('doctors');  //table name	
		}

		public function get_download_date($linkId)
		{
			$sql = "SELECT * FROM `sharedfiles` WHERE linkid = '$linkId'";
			$result = $this->db->query($sql);
			$row = $result->row();
			return $row->downloaded;
		}
		function update_download_date_of_file($linkId)
		{
			$date = date('Y-m-d H:i:s');
			$this->db->set('downloaded', $date);	
			$this->db->where('linkid', $linkId); //which row want to upgrade  
			$this->db->update('sharedfiles');  //table name	
		}

		public function get_selected_mr_name($mrId)
		{
			$sql = "SELECT * FROM `accounts` WHERE id = '$mrId'";
			$result = $this->db->query($sql);
			$row = $result->row();
			return $row->name;
		}

	}

 ?>



