<?php 
require_once '../controllerUserData.php';
include('database.inc');
$registration_success = false;
$msg ="";
$current_date = date('dmy');
$email = $_SESSION['email'];
$password = $_SESSION['password'];

if($email != false && $password != false){
    $sql = "SELECT * FROM usertable WHERE email = '$email'";
    $run_Sql = mysqli_query($con, $sql);
    if($run_Sql){
        $fetch_info = mysqli_fetch_assoc($run_Sql);
        $status = $fetch_info['status'];
        $code = $fetch_info['code'];
        if($status == "verified"){
            if($code != 0){
                header('Location: ../reset-code.php');
            }
        }else{
            header('Location: ../user-otp.php');
        }
    }
}else{
    header('Location: ../login-user.php');
}?>

<?php

 require_once "../controllerUserData.php";
 
error_reporting(0);
include('database.inc');
$msg ="";


if(isset($_POST['submit'])){
	//header("Location: http://localhost/wms3/phpGmailSMTP/complaint_success.php");

    $name = mysqli_real_escape_string($con,$_POST['name']);
    $mobile = mysqli_real_escape_string($con,$_POST['mobile']);
    $checkbox1=$_POST['wastetype'];  
    $chk="";  
      foreach($checkbox1 as $chk1)  
             {  
                 $chk .= $chk1.",";  
             } 

    $email = mysqli_real_escape_string($con,$_POST['email']);
	$status = isset($_POST['status']) && !empty($_POST['status']) ? mysqli_real_escape_string($con, $_POST['status']) : "Registered";
	$statusdesc = mysqli_real_escape_string($con,$_POST['statusdesc']);
    $location = mysqli_real_escape_string($con,$_POST['location']); 
	$service = mysqli_real_escape_string($con,$_POST['service']);    
    $locationdescription = mysqli_real_escape_string($con,$_POST['locationdescription']);
	$date = $_POST['date'];
	
	$file = $_FILES['file']['name'];
	$target_dir = "upload/";
	$target_file = $target_dir . basename($_FILES["file"]["name"]);
  
	// Select file type
	$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
  
	// Valid file extensions
	$extensions_arr = array("jpg","jpeg","png","gif","tif", "tiff");
	
  
	//validate file size 
  //   $filesize = $_FILES["file"]["size"] < 5 * 1024 ;
  
	// Check extension
	if( in_array($imageFileType,$extensions_arr) ){
   
	
	   // Upload file
	   move_uploaded_file($image = $_FILES['file']['tmp_name'],$target_dir.$file);
  
	}
	$sql = "insert into garbageinfo(name,mobile,email,wastetype,location,service,locationdescription,file,date,status,statusdesc)values('$name','$mobile','$email','$chk','$location','$service','$locationdescription','$file','$date','$status','$statusdesc')";
	if(mysqli_query($con, $sql)) {
		// Get the last inserted ID (assuming your primary key is auto-incremented)
		$complaint_id = mysqli_insert_id($con);
		// Format the ID to F-0000+id
		$formatted_complaint_id = 'F-' . $current_date . str_pad($complaint_id, 6 - strlen($current_date), '0', STR_PAD_LEFT);
		
		// Update the inserted record with the formatted complaint ID
		$update_sql = "UPDATE garbageinfo SET complaint_id = '$formatted_complaint_id' WHERE id = $complaint_id";
		if(mysqli_query($con, $update_sql)){
		$html = "<table><tr><td>Complaint ID: $formatted_complaint_id</td></tr><tr><td>FirstName: $name</td></tr><tr><td>Mobile: $mobile</td></tr><tr><td>Email: $email</td></tr><tr><td>Type Of Waste: $chk</td></tr><tr><td>Area: $location</td></tr><tr><td>Type of service: Free</td></tr><tr><td>Area description: $locationdescription</td></tr><tr><td>Images: $file  </td></tr><tr><td>Date: $date</td></tr></table>";
		 if(smtp_mailer($email,'Confirmation mail',$html)){
		header("Location: http://localhost/wms3/phpGmailSMTP/complaint_success.php");
		$mail->send();
		exit();
	
	}else {
		$msg= '<div class = "alert alert-warning"><span class="fa fa-times"> Failed to Registered !"</span></div>';
	}
}
}
			

		/*$html = "<table><tr><td>FirstName: $name</td></tr><tr><td>Mobile: $mobile</td></tr><tr><td>Email: $email</td></tr><tr><td>Type Of Waste: $chk</td></tr><tr><td>Area: $location</td></tr><tr><td>Type of service: $service</td></tr><tr><td>Area description: $locationdescription</td></tr><tr><td>Images: $file  </td></tr><tr><td>Date: $date</td></tr></table>";
     	if(smtp_mailer($email,'Confirmation mail',$html)){
			header("Location: http://localhost/wms3/phpGmailSMTP/complaint_success.php");
     	$mail->send();
			header("Location: http://localhost/wms3/phpGmailSMTP/complaint_success.php");
			exit();
			}
			else {
			$msg= '<div class = "alert alert-warning"><span class="fa fa-times"> Failed to Registered !"</span></div>';
		}*/
	


     $html = "<table><tr><td>FirstName: $name</td></tr><tr><td>Mobile: $mobile</td></tr><tr><td>Email: $email</td></tr><tr><td>Type Of Waste: $chk</td></tr><tr><td>Type of service: $service</td></tr><tr><td>Area description: $locationdescription</td></tr><tr><td>Images: $file  </td></tr><tr><td>Date: $date</td></tr></table>";
     if(smtp_mailer($email,'Confirmation mail',$html)){
     $mail->send();
	 echo "Hello";
	 function function_alert($message) {   
		// Display the alert box    
		   echo "<script type ='text/JavaScript'>";  
		   echo "alert('$message')";  
		   echo "</script>";   
	   }   
	   // Function call   
	   function_alert(" Complaint Registered Successfully  ");  

	 $link="http://localhost/wms3/adminlogin/welcome.php";
	 $link_text="Complaint Status";
	 $message = "Email sent successfully! Click <a href='$link'>$link_text</a> to check your complain status.";
	} else {
		// Email sending failed
		$message = "Email sending failed. Please try again later.";
	}
	
	// Display the message on the screen
	echo $message;
	
	
 }

?>
<?php if ($registration_success): ?>
<script>
    var confirmationMessage = "Complaint Registered Successfully!";

    alert(confirmationMessage);

    // Redirect to the desired page
    window.location.href = "http://localhost/EmailVerification/adminlogin/welcome.php";
</script>
<?php endif; 
?>
<!DOCTYPE html>
<html>
<head>
	<title>Complain</title>
	<link href="Capture.PNG" rel="icon">
	<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
	<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
	<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<link rel="stylesheet"type="text/css"href="style.css">

  
</head>
<body>
	<div>
        <a href="http://localhost/wms3/index1.html"  class="fa fa-home">	Home </a>
    </div>
   <?php 
   $error ='';   
   ?>
   <form method="post" action="trash_free.php" enctype="multipart/form-data">
   <div class="container">
	<div class="row">
		<div class="col-md-3">
			<div class="contact-info">
				<img src="images.jfif" alt="image"/>
				<h2>Register Your Complain</h2>
				<h4>We would love to hear from you !</h4>
			</div>
		</div>
		<div class="col-md-9">
			<div class="contact-form">
				<div class="form-group">
				<div id="error"></div>
              <span style="color:red"><?php echo "<b>$msg</b>"?></span>
				  <label class="control-label col-sm-2" for="fname"> Name:</label>
				  <div class="col-sm-10">          
					<input type="text" class="form-control" id="name" placeholder="Enter Your Name" name="name" required>
				  </div>
				</div>
				<div class="form-group">
				  <label class="control-label col-sm-2" for="lname">Mobile:</label>
				  <div class="col-sm-10">          
					<input type="number" class="form-control" id="mobile" placeholder="Enter Your Mobile Number" name="mobile"required min ="80000000" max="100000000000">
				  </div>
				</div>
				<div class="form-group">
				  <!-- <label class="control-label col-sm-2" for="email">Email:</label>
				  <div class="col-sm-10"> -->
					<input type="hidden" class="form-control" id="email" placeholder="Enter Your email" name="email" value="<?php echo   $_SESSION['email'];?>"> 
				  <!-- </div> -->
				</div>
				<div class="form-group">
					<label class="control-label col-sm-2" for="option">Category:</label>
					<div class="col-sm-10">          
					    <input type="checkbox" name="wastetype[]" value="organic"> Organic
                        <input type="checkbox" name="wastetype[]" value="inorganic"> Inorganic
                        <input type="checkbox" name="wastetype[]" value="Household"> Household
                        <input type="checkbox" name="wastetype[]" value="mixed"id="mycheck"> All
					</div>
				  </div>
				 <!-- <div class="form-group">
					<label class="control-label col-sm-2" for="lname">Location:</label>
					<div class="col-sm-10">          
					   <select class="form-control" id="location" name="location"required>
						   <option class="form-control" >Gajanan Township 1</option>
						   <option class="form-control" >Gajanan Township 2</option>
						   <option class="form-control" >Gajanan Township 3</option>
						   <option class="form-control" >Gajanan Township 4</option>
						   <option class="form-control" >Gajanan Township 5</option>
						   <option class="form-control" >Gajanan Township 6</option>
					   </select>
					</div>
				  </div>
				  <div class="form-group">
					<label class="control-label col-sm-2" for="stype">Type of Service:</label>
					<div class="col-sm-10">          
					   <select class="form-control" id="service" name="service"required>
					   		<option class="form-control" >Free</option>
						</select>
					</div>-->
					<!-- Map -->
					<div class="form-group">
                            <label class="control-label col-sm-2">Map:</label>
                            <div class="col-sm-10" id="map-container">
                                <iframe id="map" src="map.html"></iframe>
                            </div>
                        </div>
				<div class="form-group">
				  
				  <div class="col-sm-10">
					<textarea class="form-control" rows="5" id="locationdescription" placeholder="Enter Location details..." name="locationdescription" required></textarea>
				  </div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-2" for="lname">Pictures:</label>
					<div class="col-sm-10">          
					  <input type="file" class="form-control" id="file" name="file"required accept="image/*" capture="camera">
					</div>
				  </div>
				<div class="form-group">        
				  <div class="col-sm-offset-2 col-sm-10">
				   <input type='hidden' class="form-control" id="date" name="status" value="Registered">
				    <input type="hidden" class="form-control" id="date" name="date" value="<?php $timezone = date_default_timezone_set("Asia/Kolkata");
                                                                                             echo  date("g:ia ,\n l jS F Y");?>">
					<button type="submit" class="btn btn-default" name="submit" >Register</button>
				  </div>
				</div>
			</div>
		</div>
	</div>
</div>
   </form>
</div>
<script>
        // Receive message from map iframe
        window.addEventListener('message', function(event) {
            if (event.origin === window.location.origin) {
                var data = event.data;
                if (data.latlng) {
                    // Reverse geocode the coordinates to get the address
                    var latlng = data.latlng;
                    var url = 'https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=' + latlng.lat + '&lon=' + latlng.lng;
                    $.getJSON(url, function(response) {
                        if (response.display_name) {
                            $('#locationdescription').val(response.display_name);
                        } else {
                            console.log('Location not found');
                        }
                    });
                }
            }
        });
    </script>
<script type="text/javascript"  src="formValidation.js"></script>
</body>

</html>



