<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Type of Service</title>
    <link href="Capture.PNG" rel="icon">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        .container {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            text-align: center;
        }

        h2 {
            color: #333;
        }

        label {
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
        }

        input[type="radio"] {
            margin-right: 5px;
        }

        button {
            padding: 10px 20px;
            font-size: 16px;
            background-color: #4caf50;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }

        table {
            margin: 20px auto;
            border-collapse: collapse;
            width: 80%;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Select Type of service you want</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <table>
                <tr>
                    <th>Subscription Type</th>
                    <th>Duration</th>
                </tr>
                <tr>
                    <td><label><input type="radio" name="subscription" value="free" checked> Free</label></td>
                    <td>10-15 Days</td>
                </tr>
                <tr>
                    <td><label><input type="radio" name="subscription" value="paid"> Paid</label></td>
                    <td>05-07 Days</td>
                </tr>
                <tr>
                    <td><label><input type="radio" name="subscription" value="premium"> Premium</label></td>
                    <td>01-02 Days</td>
                </tr>
            </table>
            <button type="submit">Next</button>
        </form>
    </div>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Handle the selected subscription option (you can customize this part)

        // Redirect to another location
        $redirectUrl = ($_POST['subscription'] == 'free') ? 'https://crm.amravaticorporation.in/' : ($_POST['subscription'] == 'paid' ? 'http://localhost/wms3/phpGmailSMTP/trash_paid.php' : 'http://localhost/wms3/phpGmailSMTP/trash_premium.php');
        header("Location: $redirectUrl");
        exit();
    }
    ?>
</body>
</html>
