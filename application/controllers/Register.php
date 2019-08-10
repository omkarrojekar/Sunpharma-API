	<?php
defined('BASEPATH') OR exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');

class Register extends CI_Controller {


	public function index()
	{
		  header("Content-Type: application/json");
        //print_r($query->result());
      $query = $this->db->query("SELECT  * FROM accounts WHERE status = 1;");
    	echo json_encode($query->result());
	}

  public function alldoctors()
  {
      header("Content-Type: application/json");
      $query = $this->db->query("SELECT doctors.`id`,doctors.`name` , doctors.`email`, doctors.`contact`, accounts.`name` as mrname FROM doctors ,accounts WHERE doctors.`status` = 1 AND doctors.`mr` = accounts.`id`;");
      echo json_encode($query->result());
  }

  public function allfiles()
  {
       header("Content-Type: application/json");
       $query = $this->db->query("SELECT * from files WHERE status = 1;");
      echo json_encode($query->result());
      //$query = $this->db->get('files');
      //echo json_encode($query->result());
  }
  public function changepassword()
  {
      $message = "";
      $json = file_get_contents('php://input');
      header("Content-Type: application/json");
      $obj = json_decode($json,true);
      $userId = $obj['userId'];
      $oldPassword = $obj['oldPassword'];
      $newPassword = $obj['newPassword'];
      $confirmPassword = $obj['confirmPassword'];
      $checkValidPassword = $this->Userdetails->is_valid_password($userId,$oldPassword);
      if($checkValidPassword)
      {
        //$CheckPasswordAndConfirmPassword = $this->Userdetails->check_password_and_confirmpassword($newPassword,$confirmpassword);
        if($newPassword == $confirmPassword)
        {
            $changepassword = $this->Userdetails->change_password($userId,$newPassword);
            if($changepassword)
            {
              $message = "Password Changed Successfully !!!";
            }
            else
            {
              $message = "There Is Some Problem  Please Try Again later !!!"; 
            }
        } 
        else
        {
          $message = "Password & Confirm Password Does Not Match !!!";
        }
      }
      else
      {
        $message = "You have Entered Wrong Password Please Enter Correct Password !!!";
      }
      //$CheckPasswordAndConfirmPassword = $this->Userdetails->check_password_and_confirmpassword($newPassword,$confirmpassword);
      //$message = $userId;
      echo json_encode($message);
  }

  public function getmr()
  {
      $json = file_get_contents('php://input');
      $obj = json_decode($json,true);
      //$userData['mrid'] = $obj['getMrId'];
      $userData = $obj['getMrId'];
      $query = $this->db->query("SELECT * from accounts WHERE id = '$userData';");
      echo json_encode($query->result());
  }


    public function getfile()
    {
      $json = file_get_contents('php://input');
      $obj = json_decode($json,true);
      //$userData['mrid'] = $obj['getMrId'];
      $userData = $obj['getFileId'];
      $query = $this->db->query("SELECT * from files WHERE id = '$userData' AND status = 1;");
      echo json_encode($query->result());
    }

    public function getdoctorfile()
    {
      $message = '';
      header("Content-Type: application/json");
      $json = file_get_contents('php://input');
      $obj = json_decode($json,true);
      
      $fileId = $obj['getFileId'];
      $linkId = $obj['getLinkId'];
      $checkDownLoadStatus = $this->Userdetails->check_file_downloaded_or_not($linkId);
      if($checkDownLoadStatus == 1)
        {
          $message['file'] = $this->Userdetails->get_file($fileId);  //null;//"File Is UnAvailable After Refresh Page";
          $message['show_status'] = 0;
          //$message = "No file ";
        }
        else
        {
          $message['file'] = $this->Userdetails->get_file($fileId);
          $message['show_status'] = 1;
          //$message="yes file";
          $this->Userdetails->update_download_status($linkId);
        }
        $message['isDownload'] = $this->Userdetails->get_download_date($linkId);

      $json = json_encode($message);
      echo $json;    

    }

    public function changedownloadstatus()
    {
        header("Content-Type: application/json");
        $json = file_get_contents('php://input');
        $obj = json_decode($json,true);
        $linkId = $obj['getLinkId'];
        
        $this->Userdetails->update_download_status($linkId);

    }



  public function publish()
  {
    $message = '';
    header("Content-Type: application/json");
    $json = file_get_contents('php://input');
    $obj = json_decode($json,true);
    $userData['base64'] = $obj['base64'];
    $userData['name'] = $obj['fileName'];
    $userData['description'] = $obj['fileDescription'];
    
    if($userData['name'] == "")
    {
        $message = 'Please Select a File  !!!';
    }
    else
    {
      $uploadFile = $this->Userdetails->upload_file($userData);
      if($uploadFile) 
      {
         $message = 'File Successfully Uploaded !!!';
      }
      else 
      {
        $message = 'File Not Uploaded please try again later !!!';
      }
    }
    $json = json_encode($message);
    echo $json; 
  }

  public function updatemr()
  {
      $message =  '';
      $json = file_get_contents('php://input');
      $obj = json_decode($json,true);
      $userData['id'] = $obj['id'];
      $userData['name'] = $obj['name'];
      $userData['email'] = $obj['email'];
      $userData['contact'] = $obj['contact'];
      $userData['designation'] = $obj['designation'];
      $userData['location'] = $obj['location'];
      
      $updateDetails = $this->Userdetails->update_Mr_Details($userData);
      if($updateDetails)
      {
        $message = "MR Details Updated SuccessFully !!!";
      }
      else
      {
        $message = "MR Details Not Updated please try again later!!!"; 
      }
      $json = json_encode($message);
      echo $json; 
  }


  public function updatedoctor()
  {
      $message =  '';
      $json = file_get_contents('php://input');
      $obj = json_decode($json,true);
      
      $userData['id'] = $obj['doctorId'];
      $userData['name'] = $obj['name'];
      $userData['email'] = $obj['email'];
      $userData['contact'] = $obj['contact'];
      $userData['mr'] = $obj['mr'];
      $updateDetails = $this->Userdetails->update_doctor_Details($userData);
      if($updateDetails)
      {
        $message = "Doctor Details Updated SuccessFully !!!";
      }
      else
      {
        $message = "Doctor Details Not Updated !!!"; 
      }
      $json = json_encode($message);
      echo $json; 
  }

  public function getdoctor()
  {
      $json = file_get_contents('php://input');
      $obj = json_decode($json,true);
      //$userData['mrid'] = $obj['getMrId'];
      $userData = $obj['getDoctorId'];
      $query = $this->db->query("SELECT * from doctors WHERE id = '$userData';");
      echo json_encode($query->result()); 
      //echo json_encode($userData); 
  }




	public  function deleteUser()
	{
			 $json = file_get_contents('php://input');
  			$obj = json_decode($json,true);			
  			$email = $obj['email'];
  			$mysql_query = "DELETE  FROM accounts WHERE email = '$email'";
  			$query = $this->_custom_query($mysql_query);
  			if($query)
      	{
      		$message = 'User Deleted Succesfully!';
      		$json = json_encode($message);
      		echo $json;
      	}
      	else
      	{
      		$json = json_encode('Not Done ...');
      		echo $json;
      	}
	}




  //Register User With Details
	public function registerUser()
	{

		$json = file_get_contents('php://input');
  		$obj = json_decode($json,true);	

      $name = $obj['name'];
  		$email = $obj['email'];
  		$contact = $obj['contact'];
      $username = $obj['username'];
      $password = $obj['password'];
      $confirmpassword = $obj['confirmpassword'];

      //If functions returns value 1 then condition is true else false
      $isValidEmail = $this->_is_Valid_Email($email);
       if($isValidEmail == 1)
       {
            $passwordValidation = $this->_password_validation($password,$confirmpassword); // Line 93
            if($passwordValidation == 1)
            {
              $isNotDuplicateEmail = $this->_check_email_already_exist($email);  // line 99
              if($isNotDuplicateEmail == 1)
              {
                $mysql_query = "INSERT INTO `accounts`(`name`, `email`, `username`, `password`, `contact`, `loginstatus`) VALUES ('$name','$email','$contact','$username','$password',0)";
                  $query = $this->_custom_query($mysql_query);
                  if($query == true)
                  {
                    $message = 'User Registered Succesfully!';
                    $json = json_encode($message);
                    echo $json;
                  }
                else
                  {
                    $json = json_encode('Not Done ...');
                    echo $json;
                  }
              }
            else
              {
                $message = 'The Email Is already exist Please Enter Unique Email!!';
                $json = json_encode($message);
                echo $json;    
              }
            }
            else
            {
              $message = 'Password & Confirm Password Is Not matching';
              $json = json_encode($message);
              echo $json; 
            }
       }
       else
       {
          $json = json_encode('Please Enter Valid Email...!!');
          echo $json;
       } 
	}
  //Login With Email And Password
    function loginUser()
    {
      $json = file_get_contents('php://input');
      $obj = json_decode($json,true); 
      $email = $obj['email'];
      $password = $obj['password'];
      $isEmailExist = $this->_check_email_already_exist($email);
      if($isEmailExist == 0)
      {
        $isPasswordCorrect = $this->_check_password($email,$password);
        if($isPasswordCorrect == 0)
        {
            $isUserNotLoggedIn = $this->_check_user_already_loggedIn($email);
            if($isUserNotLoggedIn == 1)
            {
                $this->_activate_loggedIn_Status($email);
               $message = 'Welcome You SuccessFully Logged In !!!';
                $json = json_encode($message);
                echo $json;         
            }
            else
            {
              $message = 'You Are Logged In Other Device If you want to logout click on ok!!';
                $json = json_encode($message);
                echo $json;          
            }
        }
        else
        {
                $message = 'Password you Entered Is Not Correct!!';
                $json = json_encode($message);
                echo $json;          
        }
      }
      else
      {
         $message = 'The Email You Entered Is Not Valid !!';
        $json = json_encode($message);
        echo $json;  
      }
      //echo "string";
    } 
	function _custom_query($mysql_query)
	{
	    
	    $mysql_query = $this->Userdetails->_custom_query($mysql_query);
	    return $mysql_query;
	}

  function _is_Valid_Email($email)
  {
    //$pattern = "^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$"; 
    if (filter_var($email, FILTER_VALIDATE_EMAIL))
    {
      return 1;
    }
    else
    {
      return 0; 
    } 
    //if (eregi($pattern, $email))
    
  }
  function _password_validation($password,$confirmpassword)
  {
    if($password == $confirmpassword){ return 1;}
    else{ return 0;}
  }

	function _check_email_already_exist($email)
  {
   
   $check  = $this->Userdetails->check_email_exist($email);
   return $check;
  }
  function _check_password($email,$password)
  {
    
    $check  = $this->Userdetails->check_password($email,$password);
    return $check;
  }
  function _check_user_already_loggedIn($email)
  {
    
    $check  = $this->Userdetails->user_loggedIn_Status($email);
    return $check;  
  }
  function _activate_loggedIn_Status($email)
  {
    
    $this->Userdetails->activate_login_Status($email);
   // return $check;   
  }


  public function addmr()
  {
      $message = "";
      $json = file_get_contents('php://input');
      $obj = json_decode($json,true);
      $userData['name'] = $obj['name'];
      $userData['email'] = $obj['email'];
      $userData['contact'] = $obj['contact'];
      $userData['designation'] = $obj['designation'];
      $userData['location'] = $obj['location'];
      $userData['username'] = $obj['username'];
      
      $save = $this->Userdetails->storeMrDetails($userData);
      if($save)
      {
        $message = "MR Added Successfully !!!";
      }
      else
      {
        $message = "MR Not Added please try again !!!";
      }
      $json = json_encode($message);
      echo $json;
  }


  public function adddoctor()
  {
      $message = "";
      $json = file_get_contents('php://input');
      $obj = json_decode($json,true);
      $userData['name'] = $obj['name'];
      $userData['email'] = $obj['email'];
      $userData['contact'] = $obj['contact'];
      $userData['mrId'] = $obj['mrId'];
      
      $save = $this->Userdetails->storeDoctorDetails($userData);
      if($save)
      {
        $message = "Doctor Added Successfully !!!";
      }
      else
      {
        $message = "Doctor Not Added Please Check Interner Connection";
      }
      $json = json_encode($message);
      echo $json;
  }

  public function getmrname()
  {
      //$message = "";
      $json = file_get_contents('php://input');
      $obj = json_decode($json,true);
      $userData['mrid'] = $obj['id'];
      //$userData = $obj['id'];
      
      $message = $this->Userdetails->getMrName($userData);
      //$message = $userData;
      $json = json_encode($message);
      echo $json; 
  }

  public function adminlogin()
  {
      $message = "";
      $json = file_get_contents('php://input');
      $obj = json_decode($json,true);
      $userData['username'] = $obj['username'];
      $userData['password'] = $obj['password'];
      
      $checkAdminUserName = $this->Userdetails->check_admin_username($userData);
      if($checkAdminUserName)
      {
        $checkAdminPassword = $this->Userdetails->check_admin_password($userData);
        if($checkAdminPassword)
        {
            $login = $this->Userdetails->admin_login($userData);
            if($login)
            {
              $message['text'] = ""; 
              $message = $login;
              $message['redirect'] = 1;
            }
            else
            {
              //$message = "Not Login Invalid ";
              $message['text'] = "Not Login Invalid Credentials";
              $message['redirect'] = 0;
            }
        }
        else
        {
                $message['text'] = "Password is not valid !!";
                $message['redirect'] = 0;   
        }
      }
      else
      {
          $message['text'] = "Username is not valid !!";
          $message['redirect'] = 0;
      }
      $json = json_encode($message);
      echo $json;

  }

  public function userlogin()
  {
      $message = "";
      $json = file_get_contents('php://input');
      $obj = json_decode($json,true);
      $userData['username'] = $obj['username'];
      $userData['password'] = $obj['password'];
      
      $checkUserName = $this->Userdetails->check_username($userData);
        if($checkUserName)
        {
            $checkPassword = $this->Userdetails->check_user_password($userData);
            if($checkPassword)
            {
                $login = $this->Userdetails->user_login($userData);
                if($login)
                {
                  $message['text'] = ""; 
                  $message = $login;
                  $message['redirect'] = 1;
                }
                else
                {
                  $message['text'] = "Not Login Invalid Credentials";
                  $message['redirect'] = 0;
                }
            }
            else
            {
                $message['text'] = "Password is not valid !!!";
                $message['redirect'] = 0;
            }
        }
        else
        {
          $message['text'] = "Username is not valid !!!";
          $message['redirect'] = 0;
        }
    $json = json_encode($message);
    echo $json;    
  }

  public function alldoctorsofmr()
  {
      //$message = '';
      $json = file_get_contents('php://input');
      $obj = json_decode($json,true);
      $mrId = $obj['getMrId'];
      $query = $this->db->query("SELECT * from doctors WHERE mr = '$mrId'  AND status = 1;");
      //echo json_encode($query->result());
      $message['data'] = $query->result();
      $message['MrName'] = $this->Userdetails->get_selected_mr_name($mrId);
      //$message = "Giii";
      $json = json_encode($message);
      echo $json;    
  }
  public function set()
  {
     $message['text'] = 'File is Already Shared with the Doctor';
     $message['linkid']= 'NA';
     // /print_r($message);
     echo json_encode($message);
  }
  public function getdashboarddetails()
  {
      $message = "";
      $json = file_get_contents('php://input');
      


      $totalFiles = $this->Userdetails->get_count_of_files();
     $totalDoctors = $this->Userdetails->get_count_of_doctors();
      $totalMrs = $this->Userdetails->get_count_of_mr();

      $message['totalFiles'] = $totalFiles;
      $message['totalDoctors'] = $totalDoctors;
       $message['totalMrs'] = $totalMrs;
      echo json_encode($message);
  }

  public function sendfiletodoctor()
  {
      $message = "";
      $json = file_get_contents('php://input');
      $obj = json_decode($json,true);
      $data = $obj['mrId'];
      
      $checkDownloadedornot = $this->Userdetails->check_file_is_downloaded_or_not($obj);
      if($checkDownloadedornot)
      {
          $message['text'] = 'File is Already Downloaded By doctor so cant create QR code';
          $message['linkid'] = null;
          $message['downloadstatus'] = 1;
      }
      else
      {

            $checkDuplicatedata = $this->Userdetails->is_duplicate_data($obj);
            if($checkDuplicatedata['bool'] == 1)
            {
              $getContactNumber = $this->Userdetails->get_contact_number($obj);
              $message['text'] = 'File is Already Shared with the Doctor';
              $message['linkid'] = $checkDuplicatedata['linkid'];
              $message['downloadstatus'] = 0;
              $message['contact'] = $getContactNumber;
            }
            else
            {
              $otp = rand(1111,9999);
              $send = $linkId = rand(1111111,99999999);
              $getContactNumber = $this->Userdetails->get_contact_number($obj);
              $this->Userdetails->send_file_to_doctor($obj,$otp,$linkId); 
              $message['text'] = 'File is Successfully Shared with the Doctor';
              $message['linkid'] = $send;
              $message['downloadstatus'] = 0;
              $message['contact'] = $getContactNumber;

            }
          
      }
      $json = json_encode($message);
      echo $json;    
  }

  public function getallsharedfileswithdoctor()
  {
      $json = file_get_contents('php://input');
      $obj = json_decode($json,true);
      $drId = $obj['doctorId'];
      header("Content-Type: application/json");
      $query = $this->db->query("SELECT files.`name` AS filename, accounts.`name` as mrname, sharedfiles.`send_on`,sharedfiles.`id`,sharedfiles.`fileid`,sharedfiles.`id`  FROM accounts ,files, sharedfiles WHERE accounts.`id` = sharedfiles.`mrid` AND files.`id` = sharedfiles.`fileid` AND sharedfiles.`drid` = '$drId' AND sharedfiles.`downloadstatus`= 0");
      echo json_encode($query->result());
  }

  public function filesharing()
  {
      header("Content-Type: application/json");
      $query = $this->db->query("SELECT doctors.`id` AS drid ,doctors.`name` AS drname, accounts.`name` AS mrname,accounts.`id` as mrid, files.`name` AS filename, sharedfiles.`fileid`AS fileid, sharedfiles.`send_on`, sharedfiles.`visited`, sharedfiles.`viewed` FROM doctors ,accounts, sharedfiles, files WHERE sharedfiles.`drid` = doctors.`id` AND sharedfiles.`mrid` = accounts.`id` AND sharedfiles.`fileid` = files.`id`;");
      echo json_encode($query->result());
  }

  public function filesharingofdoctor()
  {
      header("Content-Type: application/json");
      $json = file_get_contents('php://input');
      $obj = json_decode($json,true);
      $doctorId = $obj['getDoctorId'];
      $query = $this->db->query("SELECT doctors.`id` AS drid ,doctors.`name` AS drname, accounts.`name` AS mrname,accounts.`id` as mrid, files.`name` AS filename, sharedfiles.`fileid`AS fileid, sharedfiles.`send_on`, sharedfiles.`visited`, sharedfiles.`viewed` FROM doctors ,accounts, sharedfiles, files WHERE sharedfiles.`drid` = doctors.`id` AND sharedfiles.`mrid` = accounts.`id` AND sharedfiles.`fileid` = files.`id` AND sharedfiles.`drid` = '$doctorId'; ");
      //echo json_encode($query->result());
      $message['data']  = $query->result();
      
      $message['drname'] = $this->Userdetails->get_doctor_name($doctorId);
      echo json_encode($message);
  }
  public function changeviewstatus()
  {
      $message = "";
      $json = file_get_contents('php://input');
      $obj = json_decode($json,true);
      $sharedId = $obj['getSharedId'];
      
      $changeStatus = $this->Userdetails->change_view_status($sharedId);
      if($changeviewstatus)
      {
        $message['text'] = "Changed View Status";
      }
      else
      {
        $message['text'] = "Not Changed View Status";
      }
      $json = json_encode($message);
      echo $json;    
  }

  public function getdoctorcredentials()
  {
      $message = "";
      $json = file_get_contents('php://input');
      $obj = json_decode($json,true);
      $linkid = $obj['getLinkId'];
      $contact = $obj['getContactNumber'];
      $getVisitedTime = $this->Userdetails->get_visited_time($linkid);
      if($getVisitedTime == 'not yet')
      {
        $this->Userdetails->set_visited_time($linkid);
      }
      $otp = $this->Userdetails->get_otp($linkid);
      if($otp == 0)
      {
        //$this->send_otp_is_used_message_to_doctor($otp,$contact); 
        $message['text'] = "THE OTP CAN NOT BE SEND BECAUSE FILE IS VIEWED BY DOCTOR";
      }
      else
      {
        $message_status = $this->Userdetails->check_message_send_or_not($linkid);
        if($message_status == "not yet")
        {
          //$this->send_otp_to_doctor($otp,$contact); 
          $message['text'] = "THE ONE TIME  OTP IS: ".$otp;
          $this->Userdetails->change_message_status($linkid); 
        }
        else
        {
          $message['text'] = "Message Is already Sent: ";
        }
      }
      //$this->send_otp_to_doctor($otp,$contact);
      //$message = $linkid;
      $json = json_encode($message);
      echo $json;    
  }

  public function checkcredentials()
  {
    $message = "";
    $json = file_get_contents('php://input');
    $obj = json_decode($json,true);
    $linkid =   $obj['getLinkId'];
    $contact =  $obj['getContactNumber'];
    $otp =      $obj['otp'];
    
    $checkOTP = $this->Userdetails->check_otp_available_or_not($linkid);
    if($checkOTP !=0)
    {
        $isValidOTP = $this->Userdetails->check_otp_valid_or_not($linkid,$otp);
        if($isValidOTP)
        {
            $check_already_visited_or_not = $this->Userdetails->check_already_viewed_or_not($linkid);
            if($check_already_visited_or_not == "not yet")
            {
              $fileid = $this->Userdetails->view_file($linkid);
              $message['fileid'] = $fileid;
              $message['text'] = 'Valid';
              $message['redirect'] = 'true';
              $this->Userdetails->update_view_time_and_otp($linkid);
            }
            else
            {
              $message['text'] = "Doctor Already Viewed The File"; 
            }
            //$message = "OTP Is Valid file is: ".$fileid;
        } 
        else
        {
            $message['text'] = "OTP Is Not Valid";
        }
    }
    else
    {
      $message['text'] = "The File Is Viiwed Already By the doctor on ";
    }
    //$message = "con: ".$contact." link: ".$linkid." otp: ".$otp; 
    $json = json_encode($message);
    echo $json;    
  }

  public function deletemr()
  {
    $json = file_get_contents('php://input');
    $obj = json_decode($json,true);
    $mrid =   $obj['getMrId'];
    
    $this->Userdetails->delete_mr($mrid);
  }
  public function deletedoctor()
  {
   $json = file_get_contents('php://input');
    $obj = json_decode($json,true);
    $drid =   $obj['getDrId'];
    
    $this->Userdetails->delete_doctor($drid); 
  }

  public function adddownloaddate()
  {
      $message = '';
      header("Content-Type: application/json");
      $json = file_get_contents('php://input');
      $obj = json_decode($json,true);
      $linkId = $obj['getLinkId'];
      $this->Userdetails->update_download_date_of_file($linkId);
  }

}

/* End of file Home.php */
/* Location: ./application/controllers/Home.php */
