<?php
include('connection.php');

   $id = $_GET['i'];
    $query = "delete  from garbageinfo_paid WHERE Id = '$id'" ;

    $data = mysqli_query($con,$query);
    
    if($data) {

      header('Location: http://localhost/wms3/adminsignup/index_private.php');
      exit;
    }
    else {
        echo "<font color='red'>Failed to delete!";
    }

?>