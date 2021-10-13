<?php
require_once "vendor/autoload.php";
use Twilio\Rest\Client;
$database = mysqli_connect('ijj1btjwrd3b7932.cbetxkdyhwsb.us-east-1.rds.amazonaws.com', 'dpcycf52per13u7c', 'woh5bbwm27z9jses', 'fski9rr2aqedsbig');
$phoneNumber = $_GET['phoneNumber'];
date_default_timezone_set('America/Los_Angeles');

$select_user_id = "SELECT user_id FROM user WHERE phone_number = $phoneNumber";
error_log("Executing query: " . $select_user_id);
error_log("Mysql error for query: " . mysqli_error($database));
$select_result = mysqli_query($database, $select_user_id);
$row = mysqli_fetch_assoc($select_result);
$user_id = $row['user_id'];

$info = "SELECT user.activated FROM user WHERE user.phone_number = $phoneNumber";
$info_result = mysqli_query($database, $info);
$info_row = mysqli_fetch_assoc($info_result);
$activated = $info_row['activated'];

if ($activated == 'N') {
    header('location: not_registered.html');
}
    if(isset($_POST['submit'])) {
        
        $player = $_POST['player'];
        $amount = $_POST['amount'];
        $g_amount = $_POST['gamount'];
        $g_amount = str_replace( ',', '', $g_amount);
        $g_amount = preg_replace('/[^a-z0-9 -]+/', '', $g_amount);
        
        foreach($player AS $key => $value) {
            
            $parse_amount = str_replace( ',', '', $amount[$key]);
            $parse_amount = preg_replace('/[^a-z0-9 -]+/', '', $parse_amount);

            $insert_player_info_query = "INSERT INTO player_info(user_id,player_name,amount) VALUES ('$user_id', '$value', '$parse_amount')";
            error_log("Executing query: " . $insert_player_info_query);
            error_log("Mysql error for query: " . mysqli_error($database));
            $insert_player_info_query_result = mysqli_query($database, $insert_player_info_query);

        }

        $select_game = "SELECT * FROM game_state WHERE game_state.user_id = $user_id";
        error_log("Executing query: " . $select_game);
        error_log("Mysql error for query: " . mysqli_error($database));
        $select_game_result = mysqli_query($database, $select_game);
        $game_row = mysqli_num_rows($select_game_result);

        if ($game_row == 0) {
            $insert_game = "INSERT INTO game_state(user_id,gamount) VALUES ('$user_id', '$g_amount')";
            error_log("Executing query: " . $insert_game);
            error_log("Mysql error for query: " . mysqli_error($database));
            $insert_game_result = mysqli_query($database, $insert_game);
            
            $update_game_start = "UPDATE game_state SET game_state.start = 'Y' WHERE game_state.user_id = $user_id";
            error_log("Executing query: " . $update_game_start);
            error_log("Mysql error for query: " . mysqli_error($database));
            $update_game_start_result = mysqli_query($database, $update_game_start);

            $update_game_stop = "UPDATE game_state SET game_state.stop = 'N' WHERE game_state.user_id = $user_id";
            error_log("Executing query: " . $update_game_stop);
            error_log("Mysql error for query: " . mysqli_error($database));
            $update_game_stop_result = mysqli_query($database, $update_game_stop);
        } else {
            $update_game1 = "UPDATE game_state SET game_state.start = 'Y' WHERE game_state.user_id = $user_id";
            error_log("Executing query: " . $update_game1);
            error_log("Mysql error for query: " . mysqli_error($database));
            $update_game1_result = mysqli_query($database, $update_game1);

            $update_game2 = "UPDATE game_state SET game_state.stop = 'N' WHERE game_state.user_id = $user_id";
            error_log("Executing query: " . $update_game2);
            error_log("Mysql error for query: " . mysqli_error($database));
            $update_game2_result = mysqli_query($database, $update_game2);

            $update_game3 = "UPDATE game_state SET game_state.gamount = $g_amount WHERE game_state.user_id = $user_id";
            error_log("Executing query: " . $update_game3);
            error_log("Mysql error for query: " . mysqli_error($database));
            $update_game3_result = mysqli_query($database, $update_game3);
        }

        header('location: thankyou_player.html');

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
                'body' => "I have gotten all your players information. You can now start playing. Reply COMMANDS for a list of commands to use to play."
            )
        );
    }
?>

<!DOCTYPE html>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <title>P0lyB0t - Input Players</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

    <head>
    <link rel="stylesheet" type="text/css" href="style.css">
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    </head>

    <body style="background-color:#CEE6D1;">
        <h1 class="player_label">Player Information</h1>
        <hr>
        <div>
            <form method="post" id="container">
                <div id="contained">
                    <h3 align="center" class="personal_label">Player</h3>
                    <center><input type="text" class="form-control" placeholder="Player" name="player[]"></center>
                    <br><br>
                    <center>
                        <h4 align="center" class="personal_label">Starting Amount</h4>
                        <input type="text" class="form-control" placeholder="Amount" name="amount[]">
                    </center>
                    <br><br>
                </div>
                <h4 align="center" class="personal_label">PASS GO Amount</h4>
                <input type="text" class="form-control" placeholder="Amount" name="gamount">
                <br><br>
            </form>
        </div>
        <center><button class ="btn btn-success"id="addMore">+ Another Player</button></center>
        <br>
        <center><input class="btn btn-primary" type="submit" form="container" name="submit" value="Start" style="width: auto; height: auto; font-size: 25px; text-align: center;" required></center>
        <br>
    </body>

    <script>
        var contents = `<center><div><button class="remove" type="button">- Player</button>
                        ${jQuery("#contained").html()}
                        </div></center>`;

        jQuery("#addMore").click(function(){
            jQuery('#contained').append(contents);
        });

        jQuery('#contained').on('click', '.remove',function(e){
            e.preventDefault();
            $(this).parent().remove();
        });
    </script>
