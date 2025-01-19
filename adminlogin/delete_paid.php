<?php
include('connection.php');

   $id = $_GET['i'];
    $query = "delete  from garbageinfo_paid WHERE Id = '$id'" ;

    $data = mysqli_query($db,$query);
    
    if($data) {

        echo "<span></span>";
        ?>
        
        <META HTTP-EQUIV="Refresh" CONTENT="0; URL=http://localhost/wms3/adminlogin/welcome_paid.php">
        <?php
    }
    else {
        echo "<font color='red'>Failed to delete!";
    }

?>