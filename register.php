<?php 
require_once "vendor/autoload.php";
use Twilio\Rest\Client;

$database = mysqli_connect('ijj1btjwrd3b7932.cbetxkdyhwsb.us-east-1.rds.amazonaws.com', 'dpcycf52per13u7c', 'woh5bbwm27z9jses', 'fski9rr2aqedsbig');
$phoneNumber = $_GET['phoneNumber'];

$info = "SELECT user.activated FROM user WHERE user.phone_number = $phoneNumber";
$info_result = mysqli_query($database, $info);
$info_row = mysqli_fetch_assoc($info_result);
$activated = $info_row['activated'];

if ($activated == 'Y') {
    header('location: registered_already.html');
} else {

    if (isset($_POST['submit'])) {

        if ($_POST['password'] != $_POST['confirm_password']) {

            echo "<center><p style='color: white; font-family: 'Montserrat', sans-serif;'>Passwords do not match. Try again.</p></center>";

        } else {

            $username = $_POST['username'];
            $email = $_POST['email'];
            $phoneNumber = $_GET['phoneNumber'];
            $password = $_POST['password'];
            $confirm_password = $_POST['confirm_password'];

            $username = mysqli_real_escape_string($database, $username);
            $email = mysqli_real_escape_string($database, $email);
            $phoneNumber = mysqli_real_escape_string($database, $phoneNumber);
            $password = mysqli_real_escape_string($database, $password);
            $confirm_password = mysqli_real_escape_string($database, $confirm_password);

            $hashFormat = "$2y$10$";
            $salt = "7557ubIMMG08Hj16z9WVus";
            $hashF_and_salt = $hashFormat . $salt;
            $password = crypt($password,$hashF_and_salt);

            $insert_info = "INSERT INTO user (name,email,phone_number,password) VALUES ('$username', '$email', '$phoneNumber', '$password')";
            error_log("Executing query: " . $insert_info);
            error_log("Mysql error for query: " . mysqli_error($database));
            $insert_info_result = mysqli_query($database, $insert_info);

            $get_user_id = "SELECT user.user_id FROM user WHERE user.phone_number = '$phoneNumber'";
            error_log("Executing query: " . $get_user_id);
            error_log("Mysql error for query: " . mysqli_error($database));
            $get_user_id_result = mysqli_query($database, $get_user_id);
            $row = mysqli_fetch_assoc($get_user_id_result);
            $user_id = $row['user_id'];

            $update_activation = "UPDATE user SET user.activated = 'Y' WHERE user.user_id = $user_id";
            error_log("Executing query: " . $update_activation);
            error_log("Mysql error for query: " . mysqli_error($database));
            $update_activation_result = mysqli_query($database, $update_activation);

            header('location: thankyou.html');
            
            // Your Account SID and Auth Token from twilio.com/console
            $account_sid = 'AC42801de7116b9b3b56a485a4ab05c158';
            $auth_token = '344466661e5227f7d2590b961b829c49';

            // A Twilio number you own with SMS capabilities
            $twilio_number = "+12057496213";

            $client = new Client($account_sid, $auth_token);
            $client->messages->create(
                // Where to send a text message (your cell phone?)
                $phoneNumber,
                array(
                    'from' => $twilio_number,
                    'body' => "Ah! Your username is ${username}. I love it. You may now start using the banker. Reply START to start a game. Reply COMMANDS for a list of commands."
                )
            );
        }
    }
}

?>
<!DOCTYPE html>
    <title>P0lyB0t - My Information</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <head>
        <link rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="style.css">
        <meta charset="UTF-8">
    </head>

    <body style="background-color:#CEE6D1;">
        <div class="boxed">
            <div class="col-xs-6">
                <form method="post">
                    <h1 align="center" class="personal_label"><b>My Information</b></h1>
                    <br>
                    <div class="form-group" style="text-align:center">
                        <h4 class="personal_label">Username</h4>
                        <input type="text" class="form-control" placeholder="Username" name="username" required>
                        <small id="passwordHelp" class="form-text text-muted">Username cannot be changed later</small>
                    </div>
                    <br>
                    <div class="form-group" style="text-align:center">
                        <h4 class="personal_label">Email</h4>
                        <input type="email" class="form-control" placeholder="Email" name="email" required>
                    </div>
                    <br>
                    <div class="form-group" style="text-align:center">
                        <h4 class="personal_label">Password</hr>
                        <input type="password" class="form-control" placeholder="Password" name="password" required>
                    </div>
                    <br>
                    <div class="form-group" style="text-align:center">
                        <h4 class="personal_label">Confirm Password</hr>
                        <input type="password" class="form-control" placeholder="Confirm Password" name="confirm_password" required>
                    </div>
                    <br>
                    <center><small id="passwordHelp" class="form-text text-muted">Passwords are Encrypted and Stored</small></center>
                    <br>
                    <center><input class="btn btn-primary" type="submit" name="submit" value="Submit" style="width: auto; height: auto; font-size: 25px; text-align: center;" required></center>
                </form>
            </div>
        </div>
    </body>
