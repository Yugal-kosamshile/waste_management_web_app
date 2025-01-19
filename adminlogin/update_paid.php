<?php
include_once('connection.php');
// error_reporting(0);
$id=$_GET['i'];
$n = $_GET['n'];
$mbl = $_GET['mbl'];
$em = $_GET['em'];
$wt = $_GET['wt'];
$lod = $_GET['lod'];
$f = $_GET['f'];
$d = $_GET['d'];

if(isset($_POST['update'])){
  $id= $_POST['id'];
   $name = $_POST['name'];
    $mobile = $_POST['mobile'];
    $email = $_POST['email'];
    $email =$_POST['email'];
    $location = $_POST['location'];  
	$service = mysqli_real_escape_string($db,$_POST['service']);  
    $locationdescription = $_POST['locationdescription'];
    $date =$_POST['date'];
	// @unlink('upload/'.$f[0]['file']) ;

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
    $query = "update garbageinfo_paid set name='$name',mobile='$mobile',email='$email',location='$location',locationdescription='$locationdescription',file= '$file',date='$date' WHERE Id='$id'" ;
   
    $data = mysqli_query($db,$query);
    
    
    if($data) {

        echo " <span style='color:red'>Record Updated!</span>";   
        
       header("Location: http://localhost/wms3/adminlogin/welcome_paid.php", TRUE, 301);
       exit();
  
    }
    else {
        echo "Failed to Update!";
    }



}
?>
<!DOCTYPE html>
<html>
<head>
<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<link rel="stylesheet"type="text/css"href="styleupdate.css">
    <title>Edit || Update</title>
  
</head>
<body>
  
   <?php 
   $error ='';   
   echo $error; 
   ?>
   <form method="post" action="update_paid.php"enctype="multipart/form-data">
   <div class="container contact">
	<div class="row">
		<div class="col-md-3">
			<div class="contact-info">
				<img src="images.jfif" alt="image"/>
				<h2>Edit Complain</h2>
				<h4>Please provide valid Information !</h4>
			</div>
		</div>
		<div class="col-md-9">
			<div class="contact-form">
				<div class="form-group">
				  <label class="control-label col-sm-2" for="fname"> Name:</label>
				  <div class="col-sm-10">          
					<input type="text" class="form-control" id="name" placeholder="Enter Your Name" name="name" required value="<?php echo "$n"?>" required>
				  </div>
				</div>
				<div class="form-group">
				  <label class="control-label col-sm-2" for="lname">Mobile:</label>
				  <div class="col-sm-10">  
                    <input  value="<?php echo $id ?>" name ="id"  style="display:none">        
					<input type="number" class="form-control" id="mobile" placeholder="Enter Your Mobile Number" name="mobile" required value="<?php echo "$mbl"?>">
				  </div>
				</div>
				<div class="form-group">
				  <div class="col-sm-10">
					<input type="hidden" class="form-control" id="email" placeholder="Enter Your email" name="email" value="<?php echo "$em"?>">
				  </div>
				</div>
				  <!--<div class="form-group">
					<label class="control-label col-sm-2" for="lname">Location:</label>
					<div class="col-sm-10">          
					   <select class="form-control" id="location" name="location" value="<?php echo "$lo"?>">
						   <option class="form-control" >Gajanan Township 1</option>
						   <option class="form-control" >Gajanan Township 2</option>
						   <option class="form-control" >Gajanan Township 3</option>
						   <option class="form-control" >Gajanan Township 4</option>
						   <option class="form-control" >Gajanan Township 5</option>
						   <option class="form-control" >Gajanan Township 6</option>
					   </select>
					</div>
				  </div>-->
				  <div class="form-group">
                            <label class="control-label col-sm-2">Map:</label>
                            <div class="col-sm-10" id="map-container">
                                <iframe id="map" src="map.html"></iframe>
                            </div>
                        </div>
				  <div class="form-group">
					<label class="control-label col-sm-2" for="stype">Type of Service:</label>
					<div class="col-sm-10">          
					   <select class="form-control" id="service" name="service"required>
						    <option class="form-control" >Paid</option>
						</select>
					</div>
				<div class="form-group">
				  
				  <div class="col-sm-10">
					<input type="comment" class="form-control" rows="5" id="locationdescription" placeholder="Enter Location details..." name="locationdescription" value="<?php echo "$lod"?>" required>
				  </div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-2" for="lname">Pictures:</label>
					<div class="col-sm-10">          
					  <input type="file" class="form-control" id="file" name="file" value="<?php echo "$f"?>"required accept="image/*" capture="camera">
					</div>
				  </div>
				<div class="form-group">        
				  <div class="col-sm-offset-2 col-sm-10">
				    <input type="hidden" class="form-control" id="date" name="date" value="<?php $timezone = date_default_timezone_set("Asia/Kolkata");
                                                                                             echo  date("g:ia ,\n l jS F Y");?>">
					<button type="submit" class="btn btn-default" name="update" id="update"  onclick ="CheckBoxCheck()">Update</button>
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
</body>
</html>