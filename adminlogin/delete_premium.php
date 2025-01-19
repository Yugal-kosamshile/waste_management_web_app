<?php
include('connection.php');

   $id = $_GET['i'];
    $query = "delete  from garbageinfo_premium WHERE Id = '$id'" ;

    $data = mysqli_query($db,$query);
    
    if($data) {

        echo "<span></span>";
        ?>
        
        <META HTTP-EQUIV="Refresh" CONTENT="0; URL=http://localhost/wms3/adminlogin/welcome_premium.php">
        <?php
    }
    else {
        echo "<font color='red'>Failed to delete!";
    }

?>