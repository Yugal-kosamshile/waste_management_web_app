<?php 
$db = new mysqli('localhost','root','','waste_3');
if(!$db) {
    die('Please check Your database connection'.mysqli_error($db));
}

?>