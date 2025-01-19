<?php
require_once '../controllerUserData.php';
include('database.inc');

$msg = "";
$email = $_SESSION['email'] ?? '';
$current_date = date('dmy');
$password = $_SESSION['password'] ?? '';

if ($email != false && $password != false) {
    $sql = "SELECT * FROM usertable WHERE email = '$email'";
    $run_Sql = mysqli_query($con, $sql);
    if ($run_Sql) {
        $fetch_info = mysqli_fetch_assoc($run_Sql);
        $status = $fetch_info['status'];
        $code = $fetch_info['code'];
        if ($status == "verified") {
            if ($code != 0) {
                header('Location: ../reset-code.php');
                exit;
            }
        } else {
            header('Location: ../user-otp.php');
            exit;
        }
    }
} else {
    header('Location: ../login-user.php');
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the data from the form
    $name = mysqli_real_escape_string($con, $_POST['name'] ?? '');
    $mobile = mysqli_real_escape_string($con, $_POST['mobile'] ?? '');
    $location = mysqli_real_escape_string($con, $_POST['location'] ?? '');
    $service = mysqli_real_escape_string($con, $_POST['service'] ?? '');
    $wasteType = mysqli_real_escape_string($con, $_POST['wastetype'] ?? '');
    $locationDescription = mysqli_real_escape_string($con, $_POST['locationdescription'] ?? '');
    $date = mysqli_real_escape_string($con, $_POST['date'] ?? '');
    $status = 'Registered'; // Assuming this is the default status
    $file = $_FILES['file']['name'];

    // Assuming $payment_successful is set based on your payment processing logic
    $payment_successful = true;

    if ($payment_successful) {
        // Insert data into garbageinfo_premium table
        $sql = "INSERT INTO garbageinfo_premium (name, mobile, email, location, service, wastetype, locationdescription, file, date, status) 
                VALUES ('$name', '$mobile', '$email', '$location', '$service', '$wasteType', '$locationDescription', '$file', '$date', '$status')";
        $result = mysqli_query($con, $sql);

        if ($result) {
            // Data inserted successfully
            $_SESSION['paymentid'] = $_POST['payment_id'];
            $complaint_id = mysqli_insert_id($con);
			// Format the ID to F-0000+id
			$formatted_complaint_id = 'G-' . $current_date . str_pad($complaint_id, 6 - strlen($current_date), '0', STR_PAD_LEFT);
			
			// Update the inserted record with the formatted complaint ID
			$update_sql = "UPDATE garbageinfo_premium SET complaint_id = '$formatted_complaint_id' WHERE id = $complaint_id";
			if(mysqli_query($con, $update_sql)){
			$html = "<table><tr><td>Complaint ID: $formatted_complaint_id</td></tr><tr><td>FirstName: $name</td></tr><tr><td>Mobile: $mobile</td></tr><tr><td>Email: $email</td></tr><tr><td>Type Of Waste: $wasteType</td></tr><tr><td>Type of service: Paid</td></tr><tr><td>Area description: $locationDescription</td></tr><tr><td>Images: $file  </td></tr><tr><td>Date: $date</td></tr></table>";
     		if(smtp_mailer($email,'Confirmation mail',$html)){
                echo "<script>window.location.href = 'http://localhost/wms3/phpGmailSMTP/complaint_success_premium.php';</script>";
                    exit();
            //echo 'done';
        } else {
            // Error in insertion
            echo "Error: " . mysqli_error($con);
        }
    } else {
        echo "Payment unsuccessful";
    }
}
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Complain</title>
    <link href="Capture.PNG" rel="icon">
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <link rel="stylesheet" type="text/css" href="style.css">
    <style>
    /* Custom styles for a classy and vibrant look */
    body {
        font-family: 'Arial', sans-serif;
        background-color: #f8f9fa;
        color: #444;
    }

    .container {
        margin-top: 50px;
        border-radius: 10px;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        background: linear-gradient(to right, #fafafa, #f0f0f0);
        padding: 30px;
    }

    h2 {
        font-weight: 700;
        color: #333;
        margin-bottom: 30px;
    }

    label {
        font-weight: 600;
    }

    #map-container {
        height: 300px;
        width: 100%;
        border-radius: 8px;
        overflow: hidden;
        margin-bottom: 20px;
        background: #f7f7f7; /* Light gray background */
    }

    #map {
        height: 100%;
        width: 100%;
    }

    button[type="submit"] {
        background: #3399cc; /* Blue button background */
        color: #fff;
        border: none;
        padding: 10px 20px;
        border-radius: 5px;
        font-weight: 600;
        transition: background-color 0.3s;
    }

    button[type="submit"]:hover {
        background: #267aad; /* Darker blue on hover */
    }

    input[type="text"],
    input[type="number"],
    textarea,
    select {
        border: none;
        border-bottom: 2px solid #3399cc; /* Blue border below textbox areas */
        padding: 8px;
        width: 100%;
        background: #f7f7f7; /* Light gray background for form fields */
    }

    input[type="file"] {
        border: 1px solid #ccc;
        border-radius: 5px;
        padding: 8px;
        width: 100%;
        background: #f7f7f7; /* Light gray background */
    }

    /* Classy color accents */
    a {
        color: #3399cc; /* Blue link color */
    }

    a:hover {
        color: #267aad; /* Darker blue on hover */
    }

    .fa-home {
        color: #3399cc; /* Blue home icon */
        text-decoration: none;
        font-size: 24px;
        margin-right: 10px;
    }
</style>
</head>

<body>
    <div>
        <a href="http://localhost/wms3/index1.html" class="fa fa-home"> Home </a>
    </div>
    <?php
    $error = '';
    ?>
    <form id="payment-form" enctype="multipart/form-data" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    <div class="contact-info">
                        <img src="images.jfif" alt="image" />
                        <h2>Register Your Complain</h2>
                        <h4>We would love to hear from you !</h4>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="contact-form">
                        <!-- Example: Name -->
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="fname"> Name:</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="name" placeholder="Enter Your Name" name="name" required>
                            </div>
                        </div>

                        <!-- Example: Mobile -->
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="lname">Mobile:</label>
                            <div class="col-sm-10">
                                <input type="number" class="form-control" id="mobile" placeholder="Enter Your Mobile Number" name="mobile" required min="80000000" max="100000000000">
                            </div>
                        </div>

                        <!-- Type of Waste -->
                        <div class="form-group">
                            <label class="control-label col-sm-2">Type of Waste:</label>
                            <div class="col-sm-10">
                                <label><input type="radio" id="organic" name="wastetype" value="organic"> Organic</label><br>
                                <label><input type="radio" id="inorganic" name="wastetype" value="inorganic"> Inorganic</label><br>
                                <label><input type="radio" id="household" name="wastetype" value="household"> Household</label>
                            </div>
                        </div>

                        <!-- Map -->
                        <div class="form-group">
                            <label class="control-label col-sm-2">Map:</label>
                            <div class="col-sm-10" id="map-container">
                                <iframe id="map" src="map.html"></iframe>
                            </div>
                        </div>

                        <!-- Location Description -->
                        <div class="form-group">
                            <label class="control-label col-sm-2">Location Description:</label>
                            <div class="col-sm-10">
                                <textarea class="form-control" rows="5" id="locationdescription" placeholder="Selected Location will appear here" name="locationdescription" required readonly></textarea>
                            </div>
                        </div>

                        <!-- Example: Pictures -->
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="lname">Pictures:</label>
                            <div class="col-sm-10">
                                <input type="file" class="form-control" id="file" name="file" required accept="image/*" capture="camera">
                            </div>
                        </div>

                        <!-- Example: Hidden Fields -->
                        <input type='hidden' class="form-control" id="date" name="date" value="<?php $timezone = date_default_timezone_set("Asia/Kolkata");
                        echo  date("g:ia ,\n l jS F Y"); ?>">
                        <input type="hidden" class="form-control" id="status" name="status" value="Registered">

                        <!-- Submit button -->
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <button type="submit" id="rzp-button" class="btn btn-default">Pay</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <!-- Your Razorpay script -->
    <!-- ... -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
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
    <script>
        $("#rzp-button").click(function(e) {
            e.preventDefault();

            var amount = 0;
            var wasteType = $("input[name='wastetype']:checked").val();
            switch (wasteType) {
                case 'organic':
                    amount = 2000;
                    break;
                case 'inorganic':
                    amount = 3500;
                    break;
                case 'household':
                    amount = 800;
                    break;
                default:
                    amount = 0;
                    break;
            }

            var options = {
                "key": "rzp_test_WK8LGpySSWrb9E",
                "amount": amount * 100,
                "name": "WMS",
                "description": "Complaint Payment",
                "image": "http://localhost/wms3/phpGmailSMTP/Capture.PNG",
                "handler": function(response) {
                    var paymentid = response.razorpay_payment_id;
                    $("form").append('<input type="hidden" name="payment_id" value="' + paymentid + '">');
                    $("form").submit(); // Submit the form after appending the payment ID
                },
                "theme": {
                    "color": "#3399cc"
                }
            };
            var rzp1 = new Razorpay(options);
            rzp1.open();
        });
    </script>
</body>

</html>
