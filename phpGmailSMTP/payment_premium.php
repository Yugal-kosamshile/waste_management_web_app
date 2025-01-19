<?php
include('conection.php');
session_start();
date_default_timezone_set("Asia/Calcutta");

$paymentid = $_POST['payment_id'];
$productid = $_POST['product_id'];
$dt = date('Y-m-d h:i:s');

// Assuming $payment_successful is set based on your payment processing logic
$payment_successful = true;

if ($payment_successful) {
    // Get the data from the session variables
    $name = mysqli_real_escape_string($con, $_SESSION['name'] ?? '');
    $mobile = mysqli_real_escape_string($con, $_SESSION['mobile'] ?? '');
    $email = mysqli_real_escape_string($con, $_SESSION['email'] ?? '');
    $location = mysqli_real_escape_string($con, $_SESSION['location'] ?? '');
    /*$wasteType = mysqli_real_escape_string($con, $_SESSION['wastetype'] ?? '');
    $service = 'Premium'; // Assuming this is a fixed value
    $locationDescription = mysqli_real_escape_string($con, $_SESSION['locationdescription'] ?? '');*/
    $file = $_FILES['file']['name'];
    /*$date = date('Y-m-d h:i:s'); // Get current date and time
    $status = 'Registered'; // Assuming this is the default status*/

    // Insert data into garbageinfo_premium table
    $sql = "INSERT INTO garbageinfo_premium (name, mobile, email, location, locationdescription) 
            VALUES ('$name', '$mobile', '$email', '$location', '$locationDescription')";
    $result = mysqli_query($con, $sql);

    if ($result) {
        // Data inserted successfully
        $_SESSION['paymentid'] = $paymentid;
        echo 'done';
    } else {
        // Error in insertion
        echo "Error: " . mysqli_error($con);
    }
} else {
    echo "Payment unsuccessful";
}
?>
