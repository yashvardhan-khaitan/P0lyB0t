<?php
require_once "vendor/autoload.php";
use Twilio\TwiML\MessagingResponse;
use Twilio\Rest\Client;

$body = $_REQUEST['Body'];
$from = $_REQUEST['From'];

// Set the content-type to XML to send back TwiML from the PHP Helper Library
header("content-type: text/xml");
date_default_timezone_set('America/Los_Angeles');

$response = new MessagingResponse();

$database = mysqli_connect('ijj1btjwrd3b7932.cbetxkdyhwsb.us-east-1.rds.amazonaws.com', 'dpcycf52per13u7c', 'woh5bbwm27z9jses', 'fski9rr2aqedsbig');

// Initiate overall bot

if (($body == 'Mono') or ($body == 'mono') or ($body == 'MONO') or ($body == 'Mono ') or ($body == 'mono ') or ($body == 'MONO ')) {
    $response->message("Hello. I am the Monopoly Banker Bot. Reply COMMANDS to view list of commands. My creator is Yashvardhan Khaitan.");
    print $response;
}

// List all commands that user can use to interact with the bot

if (($body == 'Commands') or ($body == 'commands') or ($body == 'COMMANDS') or ($body == 'Commands ') or ($body == 'commands ') or ($body == 'COMMANDS ')) {
    $response->message("Here are a list of commands:
DICE
1. ROLL - Roll a die
2. ROLL2 - Roll two dice
3. ROLL3 - Roll three dice
BANKER
1. BANKER - Register information
2. START - Start a game
3. ADD - Add an amount to a player
4. DEDUCT - Deduct an amount from player
5. TRANSFER - Transfer an amount from one player to another
6. GO - Add GO amount to a player
7. PLAYERS - View active players and their amount
8. REVERSE - Cancel a transaction while doing it
9. RESET - Reset game
    ");
    print $response;
}

// Command for user to roll only one die

if (($body == 'Roll') or ($body == 'roll') or ($body == 'ROLL') or ($body == 'Roll ') or ($body == 'roll ') or ($body == 'ROLL ')) {
    $roll_1 = rand(1, 6);
    $response->message("${roll_1}");
    print $response;
}

// Command for user to roll two dice

if (($body == 'Roll2') or ($body == 'roll2') or ($body == 'ROLL2') or ($body == 'Roll2 ') or ($body == 'roll2 ') or ($body == 'ROLL2 ')) {
    $roll_2_1 = rand(1, 6);
    $roll_2_2 = rand(1, 6);
    $response->message("${roll_2_1}, ${roll_2_2}");
    print $response;
}

// Command for user to roll three dice

if (($body == 'Roll3') or ($body == 'roll3') or ($body == 'ROLL3') or ($body == 'Roll3 ') or ($body == 'roll3 ') or ($body == 'ROLL3 ')) {
    $roll_3_1 = rand(1, 6);
    $roll_3_2 = rand(1, 6);
    $roll_3_3 = rand(1, 6);
    $response->message("${roll_3_1}, ${roll_3_2}, ${roll_3_3}");
    print $response;
}

// Start of the Monopoly Banker

// Initiate the banker

if (($body == 'Banker') or ($body == 'banker') or ($body == 'BANKER') or ($body == 'Banker ') or ($body == 'banker ') or ($body == 'BANKER ')) {
    $select_phone_number = "SELECT phone_number FROM user WHERE user.phone_number = $from";
    error_log("Executing query: " . $select_phone_number);
    error_log("Mysql error for query: " . mysqli_error($database));
    $select_phone_number_result = mysqli_query($database, $select_phone_number);
    $user_rows = mysqli_num_rows($select_phone_number_result);
    
    if ($user_rows == 0) {
        $response->message("To start using the banker, click this link: https://p0lyb0t.herokuapp.com/register.php?phoneNumber=$from to register. My creator is Yashvardhan Khaitan.");
        print $response;
    } else {
        $response->message("You have already registered before. Reply COMMANDS to view list of commands.");
        print $response;
    }
} else {

    if (($body == 'Start') or ($body == 'start') or ($body == 'START') or ($body == 'Start ') or ($body == 'start ') or ($body == 'START ')) {
        $select_phone_number = "SELECT phone_number FROM user WHERE user.phone_number = $from";
        error_log("Executing query: " . $select_phone_number);
        error_log("Mysql error for query: " . mysqli_error($database));
        $select_phone_number_result = mysqli_query($database, $select_phone_number);
        $user_rows = mysqli_num_rows($select_phone_number_result);

        if ($user_rows == 0) {
            $response->message("This command is currently unavailable because you have not registered. Reply BANKER to register.");
            print $response;
        } else {
            $get_user_id = "SELECT user.user_id FROM user WHERE user.phone_number = $from";
            error_log("Executing query: " . $get_user_id);
            error_log("Mysql error for query: " . mysqli_error($database));
            $get_user_id_result = mysqli_query($database, $get_user_id);
            $get_user_id_row = mysqli_fetch_assoc($get_user_id_result);
            $user_id = $get_user_id_row['user_id'];

            $select_game_state = "SELECT * FROM game_state WHERE game_state.user_id = $user_id AND game_state.start = 'Y'";
            error_log("Executing query: " . $select_game_state);
            error_log("Mysql error for query: " . mysqli_error($database));
            $select_game_state_result = mysqli_query($database, $select_game_state);
            $game_state_num_row = mysqli_num_rows($select_game_state_result);

            if ($game_state_num_row == 0) {
                $response->message("Click this link: https://p0lyb0t.herokuapp.com/player_info.php?phoneNumber=$from to input player information.");
                print $response;
            } else {
                $response->message("A game is already in session. Reply RESET to stop. Reply PLAYERS to view game status.");
                print $response;
            }
        }
    }

    if (($body == 'Reset') or ($body == 'reset') or ($body == 'RESET') or ($body == 'Reset ') or ($body == 'reset ') or ($body == 'RESET ')) {
        $select_phone_number = "SELECT phone_number FROM user WHERE user.phone_number = $from";
        error_log("Executing query: " . $select_phone_number);
        error_log("Mysql error for query: " . mysqli_error($database));
        $select_phone_number_result = mysqli_query($database, $select_phone_number);
        $user_rows = mysqli_num_rows($select_phone_number_result);

        if ($user_rows == 0) {
            $response->message("This command is currently unavailable because you have not registered. Reply BANKER to register.");
            print $response;
        } else {
            $get_user_id = "SELECT user.user_id FROM user WHERE user.phone_number = $from";
            error_log("Executing query: " . $get_user_id);
            error_log("Mysql error for query: " . mysqli_error($database));
            $get_user_id_result = mysqli_query($database, $get_user_id);
            $get_user_id_row = mysqli_fetch_assoc($get_user_id_result);
            $user_id = $get_user_id_row['user_id'];

            $select_game = "SELECT * FROM game_state WHERE game_state.user_id = $user_id AND game_state.start = 'Y'";
            error_log("Executing query: " . $select_game);
            error_log("Mysql error for query: " . mysqli_error($database));
            $select_game_result = mysqli_query($database, $select_game);
            $game_row = mysqli_num_rows($select_game_result);

            if ($game_row == 0) {
                $response->message("You currently don't have a game running. Reply START to start a game");
                print $response;
            } else {
                $stop_game = "UPDATE game_state SET game_state.start = 'N' WHERE game_state.user_id = $user_id";
                error_log("Executing query: " . $stop_game);
                error_log("Mysql error for query: " . mysqli_error($database));
                $stop_game_result = mysqli_query($database, $stop_game);

                $stop_game1 = "UPDATE game_state SET game_state.stop = 'Y' WHERE game_state.user_id = $user_id";
                error_log("Executing query: " . $stop_game1);
                error_log("Mysql error for query: " . mysqli_error($database));
                $stop_game1_result = mysqli_query($database, $stop_game1);

                $delete_data = "DELETE FROM player_info WHERE player_info.user_id = $user_id";
                error_log("Executing query: " . $delete_data);
                error_log("Mysql error for query: " . mysqli_error($database));
                $delete_data_result = mysqli_query($database, $delete_data);

                $delete_data1 = "DELETE FROM add_state WHERE add_state.user_id = $user_id";
                error_log("Executing query: " . $delete_data1);
                error_log("Mysql error for query: " . mysqli_error($database));
                $delete_data1_result = mysqli_query($database, $delete_data1);

                $delete_data2 = "DELETE FROM deduct_state WHERE deduct_state.user_id = $user_id";
                error_log("Executing query: " . $delete_data2);
                error_log("Mysql error for query: " . mysqli_error($database));
                $delete_data2_result = mysqli_query($database, $delete_data2);

                $delete_data3 = "DELETE FROM transfer_state WHERE transfer_state.user_id = $user_id";
                error_log("Executing query: " . $delete_data3);
                error_log("Mysql error for query: " . mysqli_error($database));
                $delete_data3_result = mysqli_query($database, $delete_data3);

                $delete_data4 = "DELETE FROM transactions_add WHERE transactions_add.user_id = $user_id";
                error_log("Executing query: " . $delete_data4);
                error_log("Mysql error for query: " . mysqli_error($database));
                $delete_data4_result = mysqli_query($database, $delete_data4);

                $delete_data5 = "DELETE FROM transactions_deduct WHERE transactions_deduct.user_id = $user_id";
                error_log("Executing query: " . $delete_data5);
                error_log("Mysql error for query: " . mysqli_error($database));
                $delete_data5_result = mysqli_query($database, $delete_data5);

                $delete_data6 = "DELETE FROM transactions_transfer WHERE transactions_transfer.user_id = $user_id";
                error_log("Executing query: " . $delete_data6);
                error_log("Mysql error for query: " . mysqli_error($database));
                $delete_data6_result = mysqli_query($database, $delete_data6);

                $response->message("Game finished. Reply START to start new game.");
                print $response;
            }
        }
    }

    if (($body == 'Add') or ($body == 'add') or ($body == 'ADD') or ($body == 'Add ') or ($body == 'add ') or ($body == 'ADD ')) {
        $select_phone_number = "SELECT phone_number FROM user WHERE user.phone_number = $from";
        error_log("Executing query: " . $select_phone_number);
        error_log("Mysql error for query: " . mysqli_error($database));
        $select_phone_number_result = mysqli_query($database, $select_phone_number);
        $user_rows = mysqli_num_rows($select_phone_number_result);

        if ($user_rows == 0) {
            $response->message("This command is currently unavailable because you have not registered. Reply BANKER to register.");
            print $response;
        } else {

            $get_user_id = "SELECT user.user_id FROM user WHERE user.phone_number = $from";
            error_log("Executing query: " . $get_user_id);
            error_log("Mysql error for query: " . mysqli_error($database));
            $get_user_id_result = mysqli_query($database, $get_user_id);
            $get_user_id_row = mysqli_fetch_assoc($get_user_id_result);
            $user_id = $get_user_id_row['user_id'];

            $select_add_state = "SELECT * FROM add_state WHERE add_state.user_id = $user_id";
            error_log("Executing query: " . $select_add_state);
            error_log("Mysql error for query: " . mysqli_error($database));
            $select_add_state_result = mysqli_query($database, $select_add_state);
            $select_add_state_row = mysqli_num_rows($select_add_state_result);

            if ($select_add_state_row == 0) {
                $insert_add_state = "INSERT INTO add_state (user_id,start_add,how_much_add,which_user_add,stop_add) VALUES ('$user_id', 'Y', 'Y', 'N', 'N')";
                error_log("Executing query: " . $insert_add_state);
                error_log("Mysql error for query: " . mysqli_error($database));
                $insert_add_state_result = mysqli_query($database, $insert_add_state);

                $response->message("How much?");
                print $response;                

            } else {
                $update_add_state_start = "UPDATE add_state SET add_state.start_add = 'Y' WHERE add_state.user_id = $user_id";
                error_log("Executing query: " . $update_add_state_start);
                error_log("Mysql error for query: " . mysqli_error($database));
                $update_add_state_result = mysqli_query($database, $update_add_state_start);

                $update_add_state_how_much = "UPDATE add_state SET add_state.how_much_add = 'Y' WHERE add_state.user_id = $user_id";
                error_log("Executing query: " . $update_add_state_how_much);
                error_log("Mysql error for query: " . mysqli_error($database));
                $update_add_state_how_much_result = mysqli_query($database, $update_add_state_how_much);

                $update_add_state_which_user = "UPDATE add_state SET add_state.which_user_add = 'N' WHERE add_state.user_id = $user_id";
                error_log("Executing query: " . $update_add_state_which_user);
                error_log("Mysql error for query: " . mysqli_error($database));
                $update_add_state_which_user_result = mysqli_query($database, $update_add_state_which_user);

                $update_add_state_stop = "UPDATE add_state SET add_state.stop_add = 'N' WHERE add_state.user_id = $user_id";
                error_log("Executing query: " . $update_add_state_stop);
                error_log("Mysql error for query: " . mysqli_error($database));
                $update_add_state_stop_result = mysqli_query($database, $update_add_state_stop);

                $response->message("How much?");
                print $response;
            }
        }

    } else {

        $get_user_id = "SELECT user.user_id FROM user WHERE user.phone_number = $from";
        error_log("Executing query: " . $get_user_id);
        error_log("Mysql error for query: " . mysqli_error($database));
        $get_user_id_result = mysqli_query($database, $get_user_id);
        $get_user_id_row = mysqli_fetch_assoc($get_user_id_result);
        $user_id = $get_user_id_row['user_id'];

        $get_add_state = "SELECT * FROM add_state WHERE add_state.user_id = $user_id";
        error_log("Executing query: " . $get_add_state);
        error_log("Mysql error for query: " . mysqli_error($database));
        $get_add_state_result = mysqli_query($database, $get_add_state);
        $get_add_state_row = mysqli_fetch_assoc($get_add_state_result);

        $add_start_state = $get_add_state_row['start_add'];
        $add_how_much_state = $get_add_state_row['how_much_add'];
        $add_which_user_state = $get_add_state_row['which_user_add'];
        $add_stop_state = $get_add_state_row['stop_add'];

        if (($add_start_state == 'Y') && ($add_how_much_state == 'Y') && ($add_which_user_state == 'N') && ($add_stop_state == 'N')) {

            if (($body == 'Reverse') or ($body == 'reverse') or ($body == 'REVERSE') or ($body == 'Reverse ') or ($body == 'reverse ') or ($body == 'REVERSE ')) {
                if (($add_start_state == 'Y') or ($add_how_much_state == 'Y') or ($add_which_user_state == 'Y') or ($add_stop_state == 'Y')) {
                    $update_state_status_start = "UPDATE add_state SET add_state.start_add = 'N' WHERE add_state.user_id = $user_id";
                    $update_state_status_start_result = mysqli_query($database, $update_state_status_start);
    
                    $update_state_how_much_status = "UPDATE add_state SET add_state.how_much_add = 'N' WHERE add_state.user_id = $user_id";
                    $update_state_how_much_status_result = mysqli_query($database, $update_state_how_much_status);
    
                    $update_state_which_user_status = "UPDATE add_state SET add_state.which_user_add = 'N' WHERE add_state.user_id = $user_id";
                    $update_state_which_user_status_result = mysqli_query($database, $update_state_which_user_status);
    
                    $update_state_stop_status = "UPDATE add_state SET add_state.stop_add = 'N' WHERE add_state.user_id = $user_id";
                    $update_state_stop_status_result = mysqli_query($database, $update_state_stop_status);
                    
                    $response->message("Transaction cancelled.");
                    print $response;
                    
                }
            } else {
                $wanted_amount = str_replace(',', '', $body);
                $wanted_amount = preg_replace('/[\$,]/', '', $wanted_amount);
                $wanted_amount = str_replace(' ', '', $wanted_amount);

                if ($wanted_amount != is_numeric($wanted_amount)) {
                    error_log("wanted_amount is not numeric " . is_numeric($wanted_amount));

                    if ((stripos($wanted_amount, 'k') == TRUE)) {
                        error_log("K is found in the wanted amount");

                        if (ctype_alpha($wanted_amount) == TRUE) {
                            $response->message("Please type a valid number. You can abbreviate numbers like $4,000 to $4K.");
                            print $response;
                        } else {

                            $wanted_amount = str_ireplace('k', '', $wanted_amount);
                            $wanted_amount = str_replace(' ', '', $wanted_amount);
                            $wanted_amount = $wanted_amount * 1000;

                            $delete_previous_amount = "DELETE FROM transactions_add WHERE transactions_add.user_id = $user_id ORDER BY transac_id DESC LIMIT 1";
                            error_log("Executing query: " . $delete_previous_amount);
                            error_log("Mysql error for query: " . mysqli_error($database));
                            $delete_previous_amount_result = mysqli_query($database, $delete_previous_amount);

                            $insert_amount = "INSERT INTO transactions_add (user_id,wanted_amount) VALUES ('$user_id', '$wanted_amount')";
                            error_log("Executing query: " . $insert_amount);
                            error_log("Mysql error for query: " . mysqli_error($database));
                            $insert_amount_result = mysqli_query($database, $insert_amount);

                            $update_which_user_add = "UPDATE add_state SET add_state.which_user_add = 'Y' WHERE add_state.user_id = $user_id";
                            error_log("Executing query: " . $update_which_user_add);
                            error_log("Mysql error for query: " . mysqli_error($database));
                            $update_which_user_add_result = mysqli_query($database, $update_which_user_add);

                            $select_players = "SELECT player_name, amount FROM player_info WHERE player_info.user_id = $user_id";
                            error_log("Executing query: " . $select_players);
                            error_log("Mysql error for query: " . mysqli_error($database));
                            $select_players_result = mysqli_query($database, $select_players);
                                
                            $player_name = array();
                            $player_amount = array();

                            while ($player_data = mysqli_fetch_array($select_players_result)) {
                                error_log(print_r('row: ' . $player_data['player_name'],true));
                                $player_name[] = $player_data['player_name'];
                                $player_amount[] = $player_data['amount'];
                            }

                            error_log(print_r($player_name,true));
                            error_log(print_r($player_amount,true));

                            $player_info = '';
                            for ($x = 0; $x < count($player_name); $x++) {
                                if (end($player_name) == $player_name[$x]) {
                                    $player_info .= $player_name[$x] . ' => ' . '$' . number_format($player_amount[$x]);
                                } else {
                                    $player_info .= $player_name[$x] . ' => ' . '$' . number_format($player_amount[$x]) . '; ';
                                }
                            }

                            error_log(print_r($player_info,true));

                            $response->message("Which player? Current players: 

${player_info}");
                            print $response;
                        }

                    } elseif ((stripos($wanted_amount, 'm') == TRUE)) {

                        if (ctype_alpha($wanted_amount) == TRUE) {
                            $response->message("Please type a valid number. You can abbreviate numbers like $1,400,000 to $1.4M.");
                            print $response;
                        } else {

                            $wanted_amount = str_ireplace('m', '', $wanted_amount);
                            $wanted_amount = str_replace(' ', '', $wanted_amount);
                            $wanted_amount = $wanted_amount * 1000000;

                            $delete_previous_amount = "DELETE FROM transactions_add WHERE transactions_add.user_id = $user_id ORDER BY transac_id DESC LIMIT 1";
                            error_log("Executing query: " . $delete_previous_amount);
                            error_log("Mysql error for query: " . mysqli_error($database));
                            $delete_previous_amount_result = mysqli_query($database, $delete_previous_amount);

                            $insert_amount = "INSERT INTO transactions_add (user_id,wanted_amount) VALUES ('$user_id', '$wanted_amount')";
                            error_log("Executing query: " . $insert_amount);
                            error_log("Mysql error for query: " . mysqli_error($database));
                            $insert_amount_result = mysqli_query($database, $insert_amount);

                            $update_which_user_add = "UPDATE add_state SET add_state.which_user_add = 'Y' WHERE add_state.user_id = $user_id";
                            error_log("Executing query: " . $update_which_user_add);
                            error_log("Mysql error for query: " . mysqli_error($database));
                            $update_which_user_add_result = mysqli_query($database, $update_which_user_add);

                            $select_players = "SELECT player_name, amount FROM player_info WHERE player_info.user_id = $user_id";
                            error_log("Executing query: " . $select_players);
                            error_log("Mysql error for query: " . mysqli_error($database));
                            $select_players_result = mysqli_query($database, $select_players);
                            
                            $player_name = array();
                            $player_amount = array();

                            while ($player_data = mysqli_fetch_array($select_players_result)) {
                                error_log(print_r('row: ' . $player_data['player_name'],true));
                                $player_name[] = $player_data['player_name'];
                                $player_amount[] = $player_data['amount'];
                            }

                            error_log(print_r($player_name,true));
                            error_log(print_r($player_amount,true));

                            $player_info = '';
                            for ($x = 0; $x < count($player_name); $x++) {
                                if (end($player_name) == $player_name[$x]) {
                                    $player_info .= $player_name[$x] . ' => ' . '$' . number_format($player_amount[$x]);
                                } else {
                                    $player_info .= $player_name[$x] . ' => ' . '$' . number_format($player_amount[$x]) . '; ';
                                }
                            }

                            error_log(print_r($player_info,true));

                            $response->message("Which player? Current players: 

${player_info}");
                            print $response;
                        }

                    } else {
                        $response->message("Please type a valid number. You can abbreviate numbers like $4,000,000 to $4M.");
                        print $response;
                    }

                } else {

                    $insert_amount = "INSERT INTO transactions_add (user_id,wanted_amount) VALUES ('$user_id', '$wanted_amount')";
                    error_log("Executing query: " . $insert_amount);
                    error_log("Mysql error for query: " . mysqli_error($database));
                    $insert_amount_result = mysqli_query($database, $insert_amount);

                    $update_which_user_add = "UPDATE add_state SET add_state.which_user_add = 'Y' WHERE add_state.user_id = $user_id";
                    error_log("Executing query: " . $update_which_user_add);
                    error_log("Mysql error for query: " . mysqli_error($database));
                    $update_which_user_add_result = mysqli_query($database, $update_which_user_add);

                    $select_players = "SELECT player_name, amount FROM player_info WHERE player_info.user_id = $user_id";
                    error_log("Executing query: " . $select_players);
                    error_log("Mysql error for query: " . mysqli_error($database));
                    $select_players_result = mysqli_query($database, $select_players);
                    
                    $player_name = array();
                    $player_amount = array();

                    while ($player_data = mysqli_fetch_array($select_players_result)) {
                        error_log(print_r('row: ' . $player_data['player_name'],true));
                        $player_name[] = $player_data['player_name'];
                        $player_amount[] = $player_data['amount'];
                    }

                    error_log(print_r($player_name,true));
                    error_log(print_r($player_amount,true));

                    $player_info = '';
                    for ($x = 0; $x < count($player_name); $x++) {
                        if (end($player_name) == $player_name[$x]) {
                            $player_info .= $player_name[$x] . ' => ' . '$' . number_format($player_amount[$x]);
                        } else {
                            $player_info .= $player_name[$x] . ' => ' . '$' . number_format($player_amount[$x]) . '; ';
                        }
                    }

                    error_log(print_r($player_info,true));

                    $response->message("Which player? Current players: 

${player_info}");
                    print $response;
                }
            }
        }

        if (($add_start_state == 'Y') && ($add_how_much_state == 'Y') && ($add_which_user_state == 'Y') && ($add_stop_state == 'N')) {

            if (($body == 'Reverse') or ($body == 'reverse') or ($body == 'REVERSE') or ($body == 'Reverse ') or ($body == 'reverse ') or ($body == 'REVERSE ')) {
                if (($add_start_state == 'Y') or ($add_how_much_state == 'Y') or ($add_which_user_state == 'Y') or ($add_stop_state == 'Y')) {
                    $update_state_status_start = "UPDATE add_state SET add_state.start_add = 'N' WHERE add_state.user_id = $user_id";
                    $update_state_status_start_result = mysqli_query($database, $update_state_status_start);
    
                    $update_state_how_much_status = "UPDATE add_state SET add_state.how_much_add = 'N' WHERE add_state.user_id = $user_id";
                    $update_state_how_much_status_result = mysqli_query($database, $update_state_how_much_status);
    
                    $update_state_which_user_status = "UPDATE add_state SET add_state.which_user_add = 'N' WHERE add_state.user_id = $user_id";
                    $update_state_which_user_status_result = mysqli_query($database, $update_state_which_user_status);
    
                    $update_state_stop_status = "UPDATE add_state SET add_state.stop_add = 'N' WHERE add_state.user_id = $user_id";
                    $update_state_stop_status_result = mysqli_query($database, $update_state_stop_status);
                    
                    $response->message("Transaction cancelled.");
                    print $response;
                    
                }
            } else {

                $wanted_user = str_replace(' ', '', $body);

                $select_all_players = "SELECT player_info.player_name FROM player_info WHERE player_info.user_id = $user_id AND player_info.player_name = '$wanted_user'";
                error_log("Executing query: " . $select_all_players);
                error_log("Mysql error for query: " . mysqli_error($database));
                $select_all_players_result = mysqli_query($database, $select_all_players);
                $select_all_players_row = mysqli_num_rows($select_all_players_result);

                if ($select_all_players_row != 0) {
                    $update_user_name = "UPDATE transactions_add SET transactions_add.user = '$wanted_user' WHERE transactions_add.user_id = $user_id";
                    error_log("Executing query: " . $update_user_name);
                    error_log("Mysql error for query: " . mysqli_error($database));
                    $update_user_name_result = mysqli_query($database, $update_user_name);

                    $update_stop_state = "UPDATE add_state SET add_state.stop_add = 'Y' WHERE add_state.user_id = $user_id";
                    error_log("Executing query: " . $update_stop_state);
                    error_log("Mysql error for query: " . mysqli_error($database));
                    $update_stop_state_result = mysqli_query($database, $update_stop_state);

                    $select_transaction = "SELECT player_info.player_name, player_info.amount, transactions_add.wanted_amount FROM player_info, transactions_add WHERE player_info.user_id = transactions_add.user_id AND player_info.player_name = transactions_add.user AND transactions_add.paid = 'N'";
                    error_log("Executing query: " . $select_transaction);
                    error_log("Mysql error for query: " . mysqli_error($database));
                    $select_transaction_result = mysqli_query($database, $select_transaction);
                    $select_transaction_row = mysqli_fetch_assoc($select_transaction_result);
                        
                    $user_amount = $select_transaction_row['amount'];
                    $wanted_amount = $select_transaction_row['wanted_amount'];
                    $user = $select_transaction_row['player_name'];
                    $new_amount = $user_amount + $wanted_amount;

                    $update_new_amount = "UPDATE player_info SET player_info.amount = $new_amount WHERE player_info.user_id = $user_id AND player_info.player_name = '$user'";
                    error_log("Executing query: " . $update_new_amount);
                    error_log("Mysql error for query: " . mysqli_error($database));
                    $update_new_amount_result = mysqli_query($database, $update_new_amount);

                    $update_paid = "UPDATE transactions_add SET transactions_add.paid = 'Y' WHERE transactions_add.user = '$user' AND transactions_add.user_id = $user_id";
                    error_log("Executing query: " . $update_paid);
                    error_log("Mysql error for query: " . mysqli_error($database));
                    $update_paid_result = mysqli_query($database, $update_paid);

                    $select_players = "SELECT player_name, amount FROM player_info WHERE player_info.user_id = $user_id";
                    error_log("Executing query: " . $select_players);
                    error_log("Mysql error for query: " . mysqli_error($database));
                    $select_players_result = mysqli_query($database, $select_players);
                    
                    $player_name = array();
                    $player_amount = array();

                    while ($player_data = mysqli_fetch_array($select_players_result)) {
                        error_log(print_r('row: ' . $player_data['player_name'],true));
                        $player_name[] = $player_data['player_name'];
                        $player_amount[] = $player_data['amount'];
                    }

                    error_log(print_r($player_name,true));
                    error_log(print_r($player_amount,true));

                    $player_info = '';
                    for ($x = 0; $x < count($player_name); $x++) {
                        if (end($player_name) == $player_name[$x]) {
                            $player_info .= $player_name[$x] . ' => ' . '$' . number_format($player_amount[$x]);
                        } else {
                            $player_info .= $player_name[$x] . ' => ' . '$' . number_format($player_amount[$x]) . '; ';
                        }
                    }

                    error_log(print_r($player_info,true));

                    $response->message("Transaction complete. New Amounts: 

${player_info}");
                    print $response;
                } else {
                    $response->message("Invalid player.");
                    print $response;
                }
            }
        }
    }

    if (($body == 'Deduct') or ($body == 'deduct') or ($body == 'DEDUCT') or ($body == 'Deduct ') or ($body == 'deduct ') or ($body == 'DEDUCT ')) {
        $select_phone_number = "SELECT phone_number FROM user WHERE user.phone_number = $from";
        error_log("Executing query: " . $select_phone_number);
        error_log("Mysql error for query: " . mysqli_error($database));
        $select_phone_number_result = mysqli_query($database, $select_phone_number);
        $user_rows = mysqli_num_rows($select_phone_number_result);

        if ($user_rows == 0) {
            $response->message("This command is currently unavailable because you have not registered. Reply BANKER to register.");
            print $response;
        } else {

            $get_user_id = "SELECT user.user_id FROM user WHERE user.phone_number = $from";
            error_log("Executing query: " . $get_user_id);
            error_log("Mysql error for query: " . mysqli_error($database));
            $get_user_id_result = mysqli_query($database, $get_user_id);
            $get_user_id_row = mysqli_fetch_assoc($get_user_id_result);
            $user_id = $get_user_id_row['user_id'];

            $select_deduct_state = "SELECT * FROM deduct_state WHERE deduct_state.user_id = $user_id";
            error_log("Executing query: " . $select_deduct_state);
            error_log("Mysql error for query: " . mysqli_error($database));
            $select_deduct_state_result = mysqli_query($database, $select_deduct_state);
            $select_deduct_state_row = mysqli_num_rows($select_deduct_state_result);

            if ($select_deduct_state_row == 0) {
                $insert_deduct_state = "INSERT INTO deduct_state (user_id,start_deduct,how_much_deduct,which_user_deduct,stop_deduct) VALUES ('$user_id', 'Y', 'Y', 'N', 'N')";
                error_log("Executing query: " . $insert_deduct_state);
                error_log("Mysql error for query: " . mysqli_error($database));
                $insert_deduct_state_result = mysqli_query($database, $insert_deduct_state);
                $response->message("How much?");
                print $response;                

            } else {
                $update_deduct_state_start = "UPDATE deduct_state SET deduct_state.start_deduct = 'Y' WHERE deduct_state.user_id = $user_id";
                error_log("Executing query: " . $update_deduct_state_start);
                error_log("Mysql error for query: " . mysqli_error($database));
                $update_deduct_state_result = mysqli_query($database, $update_deduct_state_start);

                $update_deduct_state_how_much = "UPDATE deduct_state SET deduct_state.how_much_deduct = 'Y' WHERE deduct_state.user_id = $user_id";
                error_log("Executing query: " . $update_deduct_state_how_much);
                error_log("Mysql error for query: " . mysqli_error($database));
                $update_deduct_state_how_much_result = mysqli_query($database, $update_deduct_state_how_much);

                $update_deduct_state_which_user = "UPDATE deduct_state SET deduct_state.which_user_deduct = 'N' WHERE deduct_state.user_id = $user_id";
                error_log("Executing query: " . $update_deduct_state_which_user);
                error_log("Mysql error for query: " . mysqli_error($database));
                $update_deduct_state_which_user_result = mysqli_query($database, $update_deduct_state_which_user);

                $update_deduct_state_stop = "UPDATE deduct_state SET deduct_state.stop_deduct = 'N' WHERE deduct_state.user_id = $user_id";
                error_log("Executing query: " . $update_deduct_state_stop);
                error_log("Mysql error for query: " . mysqli_error($database));
                $update_deduct_state_stop_result = mysqli_query($database, $update_deduct_state_stop);

                $response->message("How much?");
                print $response;
            }
        }

    } else {

        $get_user_id = "SELECT user.user_id FROM user WHERE user.phone_number = $from";
        error_log("Executing query: " . $get_user_id);
        error_log("Mysql error for query: " . mysqli_error($database));
        $get_user_id_result = mysqli_query($database, $get_user_id);
        $get_user_id_row = mysqli_fetch_assoc($get_user_id_result);
        $user_id = $get_user_id_row['user_id'];

        $get_deduct_state = "SELECT * FROM deduct_state WHERE deduct_state.user_id = $user_id";
        error_log("Executing query: " . $get_deduct_state);
        error_log("Mysql error for query: " . mysqli_error($database));
        $get_deduct_state_result = mysqli_query($database, $get_deduct_state);
        $get_deduct_state_row = mysqli_fetch_assoc($get_deduct_state_result);

        $deduct_start_state = $get_deduct_state_row['start_deduct'];
        $deduct_how_much_state = $get_deduct_state_row['how_much_deduct'];
        $deduct_which_user_state = $get_deduct_state_row['which_user_deduct'];
        $deduct_stop_state = $get_deduct_state_row['stop_deduct'];

        if (($deduct_start_state == 'Y') && ($deduct_how_much_state == 'Y') && ($deduct_which_user_state == 'N') && ($deduct_stop_state == 'N')) {

            if (($body == 'Reverse') or ($body == 'reverse') or ($body == 'REVERSE') or ($body == 'Reverse ') or ($body == 'reverse ') or ($body == 'REVERSE ')) {
                if (($deduct_start_state == 'Y') or ($deduct_how_much_state == 'Y') or ($deduct_which_user_state == 'Y') or ($deduct_stop_state == 'Y')) {
                    $update_state_status_start = "UPDATE deduct_state SET deduct_state.start_deduct = 'N' WHERE deduct_state.user_id = $user_id";
                    $update_state_status_start_result = mysqli_query($database, $update_state_status_start);
    
                    $update_state_how_much_status = "UPDATE deduct_state SET deduct_state.how_much_deduct = 'N' WHERE deduct_state.user_id = $user_id";
                    $update_state_how_much_status_result = mysqli_query($database, $update_state_how_much_status);
    
                    $update_state_which_user_status = "UPDATE deduct_state SET deduct_state.which_user_deduct = 'N' WHERE deduct_state.user_id = $user_id";
                    $update_state_which_user_status_result = mysqli_query($database, $update_state_which_user_status);
    
                    $update_state_stop_status = "UPDATE deduct_state SET deduct_state.stop_deduct = 'N' WHERE deduct_state.user_id = $user_id";
                    $update_state_stop_status_result = mysqli_query($database, $update_state_stop_status);
                    
                    $response->message("Transaction cancelled.");
                    print $response;
                    
                }
            } else {

                $wanted_amount = str_replace(',', '', $body);
                $wanted_amount = preg_replace('/[\$,]/', '', $wanted_amount);
                $wanted_amount = str_replace(' ', '', $wanted_amount);

                if ($wanted_amount != is_numeric($wanted_amount)) {
                    error_log("wanted_amount is not numeric " . is_numeric($wanted_amount));

                    if ((stripos($wanted_amount, 'k') == TRUE)) {
                        error_log("K is found in the wanted amount");

                        if (ctype_alpha($wanted_amount) == TRUE) {
                            $response->message("Please type a valid number. You can abbreviate numbers like $4,000 to $4K.");
                            print $response;
                        } else {

                            $wanted_amount = str_ireplace('k', '', $wanted_amount);
                            $wanted_amount = str_replace(' ', '', $wanted_amount);
                            $wanted_amount = $wanted_amount * 1000;

                            $delete_previous_amount = "DELETE FROM transactions_deduct WHERE transactions_deduct.user_id = $user_id ORDER BY transacd_id DESC LIMIT 1";
                            error_log("Executing query: " . $delete_previous_amount);
                            error_log("Mysql error for query: " . mysqli_error($database));
                            $delete_previous_amount_result = mysqli_query($database, $delete_previous_amount);

                            $insert_amount = "INSERT INTO transactions_deduct (user_id,wanted_amount_deduct) VALUES ('$user_id', '$wanted_amount')";
                            error_log("Executing query: " . $insert_amount);
                            error_log("Mysql error for query: " . mysqli_error($database));
                            $insert_amount_result = mysqli_query($database, $insert_amount);

                            $update_which_user_deduct = "UPDATE deduct_state SET deduct_state.which_user_deduct = 'Y' WHERE deduct_state.user_id = $user_id";
                            error_log("Executing query: " . $update_which_user_deduct);
                            error_log("Mysql error for query: " . mysqli_error($database));
                            $update_which_user_deduct_result = mysqli_query($database, $update_which_user_deduct);

                            $select_players = "SELECT player_name, amount FROM player_info WHERE player_info.user_id = $user_id";
                            error_log("Executing query: " . $select_players);
                            error_log("Mysql error for query: " . mysqli_error($database));
                            $select_players_result = mysqli_query($database, $select_players);
                                
                            $player_name = array();
                            $player_amount = array();

                            while ($player_data = mysqli_fetch_array($select_players_result)) {
                                error_log(print_r('row: ' . $player_data['player_name'],true));
                                $player_name[] = $player_data['player_name'];
                                $player_amount[] = $player_data['amount'];
                            }

                            error_log(print_r($player_name,true));
                            error_log(print_r($player_amount,true));

                            $player_info = '';
                            for ($x = 0; $x < count($player_name); $x++) {
                                if (end($player_name) == $player_name[$x]) {
                                    $player_info .= $player_name[$x] . ' => ' . '$' . number_format($player_amount[$x]);
                                } else {
                                    $player_info .= $player_name[$x] . ' => ' . '$' . number_format($player_amount[$x]) . '; ';
                                }
                            }

                            error_log(print_r($player_info,true));

                            $response->message("Which player? Current players: 

${player_info}");
                            print $response;
                        }

                    } elseif ((stripos($wanted_amount, 'm') == TRUE)) {

                        if (ctype_alpha($wanted_amount) == TRUE) {
                            $response->message("Please type a valid number. You can abbreviate numbers like $1,400,000 to $1.4M.");
                            print $response;
                        } else { 

                            $wanted_amount = str_ireplace('m', '', $wanted_amount);
                            $wanted_amount = str_replace(' ', '', $wanted_amount);
                            $wanted_amount = $wanted_amount * 1000000;

                            $delete_previous_amount = "DELETE FROM transactions_deduct WHERE transactions_deduct.user_id = $user_id ORDER BY transacd_id DESC LIMIT 1";
                            error_log("Executing query: " . $delete_previous_amount);
                            error_log("Mysql error for query: " . mysqli_error($database));
                            $delete_previous_amount_result = mysqli_query($database, $delete_previous_amount);

                            $insert_amount = "INSERT INTO transactions_deduct (user_id,wanted_amount_deduct) VALUES ('$user_id', '$wanted_amount')";
                            error_log("Executing query: " . $insert_amount);
                            error_log("Mysql error for query: " . mysqli_error($database));
                            $insert_amount_result = mysqli_query($database, $insert_amount);

                            $update_which_user_deduct = "UPDATE deduct_state SET deduct_state.which_user_deduct = 'Y' WHERE deduct_state.user_id = $user_id";
                            error_log("Executing query: " . $update_which_user_deduct);
                            error_log("Mysql error for query: " . mysqli_error($database));
                            $update_which_user_deduct_result = mysqli_query($database, $update_which_user_deduct);

                            $select_players = "SELECT player_name, amount FROM player_info WHERE player_info.user_id = $user_id";
                            error_log("Executing query: " . $select_players);
                            error_log("Mysql error for query: " . mysqli_error($database));
                            $select_players_result = mysqli_query($database, $select_players);
                            
                            $player_name = array();
                            $player_amount = array();

                            while ($player_data = mysqli_fetch_array($select_players_result)) {
                                error_log(print_r('row: ' . $player_data['player_name'],true));
                                $player_name[] = $player_data['player_name'];
                                $player_amount[] = $player_data['amount'];
                            }

                            error_log(print_r($player_name,true));
                            error_log(print_r($player_amount,true));

                            $player_info = '';
                            for ($x = 0; $x < count($player_name); $x++) {
                                if (end($player_name) == $player_name[$x]) {
                                    $player_info .= $player_name[$x] . ' => ' . '$' . number_format($player_amount[$x]);
                                } else {
                                    $player_info .= $player_name[$x] . ' => ' . '$' . number_format($player_amount[$x]) . '; ';
                                }
                            }

                            error_log(print_r($player_info,true));

                            $response->message("Which player? Current players: 

${player_info}");
                            print $response;
                        }
                    } else {
                        $response->message("Please type a valid number. You can abbreviate numbers like $4,000,000 to $4M.");
                        print $response;
                    }

                } else {

                    $insert_amount = "INSERT INTO transactions_deduct (user_id,wanted_amount_deduct) VALUES ('$user_id', '$wanted_amount')";
                    error_log("Executing query: " . $insert_amount);
                    error_log("Mysql error for query: " . mysqli_error($database));
                    $insert_amount_result = mysqli_query($database, $insert_amount);

                    $update_which_user_deduct = "UPDATE deduct_state SET deduct_state.which_user_deduct = 'Y' WHERE deduct_state.user_id = $user_id";
                    error_log("Executing query: " . $update_which_user_deduct);
                    error_log("Mysql error for query: " . mysqli_error($database));
                    $update_which_user_deduct_result = mysqli_query($database, $update_which_user_deduct);

                    $select_players = "SELECT player_name, amount FROM player_info WHERE player_info.user_id = $user_id";
                    error_log("Executing query: " . $select_players);
                    error_log("Mysql error for query: " . mysqli_error($database));
                    $select_players_result = mysqli_query($database, $select_players);
                    
                    $player_name = array();
                    $player_amount = array();

                    while ($player_data = mysqli_fetch_array($select_players_result)) {
                        error_log(print_r('row: ' . $player_data['player_name'],true));
                        $player_name[] = $player_data['player_name'];
                        $player_amount[] = $player_data['amount'];
                    }

                    error_log(print_r($player_name,true));
                    error_log(print_r($player_amount,true));

                    $player_info = '';
                    for ($x = 0; $x < count($player_name); $x++) {
                        if (end($player_name) == $player_name[$x]) {
                            $player_info .= $player_name[$x] . ' => ' . '$' . number_format($player_amount[$x]);
                        } else {
                            $player_info .= $player_name[$x] . ' => ' . '$' . number_format($player_amount[$x]) . '; ';
                        }
                    }

                    error_log(print_r($player_info,true));

                    $response->message("Which player? Current players: 

${player_info}");
                    print $response;
                }
            }
        }

        if (($deduct_start_state == 'Y') && ($deduct_how_much_state == 'Y') && ($deduct_which_user_state == 'Y') && ($deduct_stop_state == 'N')) {

            if (($body == 'Reverse') or ($body == 'reverse') or ($body == 'REVERSE') or ($body == 'Reverse ') or ($body == 'reverse ') or ($body == 'REVERSE ')) {
                if (($deduct_start_state == 'Y') or ($deduct_how_much_state == 'Y') or ($deduct_which_user_state == 'Y') or ($deduct_stop_state == 'Y')) {
                    $update_state_status_start = "UPDATE deduct_state SET deduct_state.start_deduct = 'N' WHERE deduct_state.user_id = $user_id";
                    $update_state_status_start_result = mysqli_query($database, $update_state_status_start);
    
                    $update_state_how_much_status = "UPDATE deduct_state SET deduct_state.how_much_deduct = 'N' WHERE deduct_state.user_id = $user_id";
                    $update_state_how_much_status_result = mysqli_query($database, $update_state_how_much_status);
    
                    $update_state_which_user_status = "UPDATE deduct_state SET deduct_state.which_user_deduct = 'N' WHERE deduct_state.user_id = $user_id";
                    $update_state_which_user_status_result = mysqli_query($database, $update_state_which_user_status);
    
                    $update_state_stop_status = "UPDATE deduct_state SET deduct_state.stop_deduct = 'N' WHERE deduct_state.user_id = $user_id";
                    $update_state_stop_status_result = mysqli_query($database, $update_state_stop_status);
                    
                    $response->message("Transaction cancelled.");
                    print $response;
                    
                }
            } else {

                $wanted_user = str_replace(' ', '', $body);

                $select_all_players = "SELECT player_info.player_name FROM player_info WHERE player_info.user_id = $user_id AND player_info.player_name = '$wanted_user'";
                error_log("Executing query: " . $select_all_players);
                error_log("Mysql error for query: " . mysqli_error($database));
                $select_all_players_result = mysqli_query($database, $select_all_players);
                $select_all_players_row = mysqli_num_rows($select_all_players_result);

                if ($select_all_players_row != 0) {
                    $update_user_name = "UPDATE transactions_deduct SET transactions_deduct.user_ded = '$wanted_user' WHERE transactions_deduct.user_id = $user_id";
                    error_log("Executing query: " . $update_user_name);
                    error_log("Mysql error for query: " . mysqli_error($database));
                    $update_user_name_result = mysqli_query($database, $update_user_name);

                    $update_stop_state = "UPDATE deduct_state SET deduct_state.stop_deduct = 'Y' WHERE deduct_state.user_id = $user_id";
                    error_log("Executing query: " . $update_stop_state);
                    error_log("Mysql error for query: " . mysqli_error($database));
                    $update_stop_state_result = mysqli_query($database, $update_stop_state);

                    $select_transaction = "SELECT player_info.player_name, player_info.amount, transactions_deduct.wanted_amount_deduct FROM player_info, transactions_deduct WHERE player_info.user_id = transactions_deduct.user_id AND player_info.player_name = transactions_deduct.user_ded AND transactions_deduct.paid_d = 'N'";
                    error_log("Executing query: " . $select_transaction);
                    error_log("Mysql error for query: " . mysqli_error($database));
                    $select_transaction_result = mysqli_query($database, $select_transaction);
                    $select_transaction_row = mysqli_fetch_assoc($select_transaction_result);
                        
                    $user_amount = $select_transaction_row['amount'];
                    $wanted_amount = $select_transaction_row['wanted_amount_deduct'];
                    $user = $select_transaction_row['player_name'];
                    $new_amount = $user_amount - $wanted_amount;

                    $update_new_amount = "UPDATE player_info SET player_info.amount = $new_amount WHERE player_info.user_id = $user_id AND player_info.player_name = '$user'";
                    error_log("Executing query: " . $update_new_amount);
                    error_log("Mysql error for query: " . mysqli_error($database));
                    $update_new_amount_result = mysqli_query($database, $update_new_amount);

                    $update_paid = "UPDATE transactions_deduct SET transactions_deduct.paid_d = 'Y' WHERE transactions_deduct.user_ded = '$user' AND transactions_deduct.user_id = $user_id";
                    error_log("Executing query: " . $update_paid);
                    error_log("Mysql error for query: " . mysqli_error($database));
                    $update_paid_result = mysqli_query($database, $update_paid);

                    $select_players = "SELECT player_name, amount FROM player_info WHERE player_info.user_id = $user_id";
                    error_log("Executing query: " . $select_players);
                    error_log("Mysql error for query: " . mysqli_error($database));
                    $select_players_result = mysqli_query($database, $select_players);
                    
                    $player_name = array();
                    $player_amount = array();

                    while ($player_data = mysqli_fetch_array($select_players_result)) {
                        error_log(print_r('row: ' . $player_data['player_name'],true));
                        $player_name[] = $player_data['player_name'];
                        $player_amount[] = $player_data['amount'];
                    }

                    error_log(print_r($player_name,true));
                    error_log(print_r($player_amount,true));

                    $player_info = '';
                    for ($x = 0; $x < count($player_name); $x++) {
                        if (end($player_name) == $player_name[$x]) {
                            $player_info .= $player_name[$x] . ' => ' . '$' . number_format($player_amount[$x]);
                        } else {
                            $player_info .= $player_name[$x] . ' => ' . '$' . number_format($player_amount[$x]) . '; ';
                        }
                    }

                    error_log(print_r($player_info,true));

                    $response->message("Transaction complete. New Amounts: 

${player_info}");
                    print $response;
                } else {
                    $response->message("Invalid player.");
                    print $response;
                }
            }
        }
    }

    if (($body == 'Transfer') or ($body == 'transfer') or ($body == 'TRANSFER') or ($body == 'Transfer ') or ($body == 'transfer ') or ($body == 'TRANSFER ')) {
        $select_phone_number = "SELECT phone_number FROM user WHERE user.phone_number = $from";
        error_log("Executing query: " . $select_phone_number);
        error_log("Mysql error for query: " . mysqli_error($database));
        $select_phone_number_result = mysqli_query($database, $select_phone_number);
        $user_rows = mysqli_num_rows($select_phone_number_result);

        if ($user_rows == 0) {
            $response->message("This command is currently unavailable because you have not registered. Reply BANKER to register.");
            print $response;
        } else {

            $get_user_id = "SELECT user.user_id FROM user WHERE user.phone_number = $from";
            error_log("Executing query: " . $get_user_id);
            error_log("Mysql error for query: " . mysqli_error($database));
            $get_user_id_result = mysqli_query($database, $get_user_id);
            $get_user_id_row = mysqli_fetch_assoc($get_user_id_result);
            $user_id = $get_user_id_row['user_id'];

            $select_transfer_state = "SELECT * FROM transfer_state WHERE transfer_state.user_id = $user_id";
            error_log("Executing query: " . $select_transfer_state);
            error_log("Mysql error for query: " . mysqli_error($database));
            $select_transfer_state_result = mysqli_query($database, $select_transfer_state);
            $select_transfer_state_row = mysqli_num_rows($select_transfer_state_result);

            if ($select_transfer_state_row == 0) {
                $insert_transfer_state = "INSERT INTO transfer_state (user_id,start_transfer,how_much_transfer,which_user_transfer,from_user_transfer,stop_transfer) VALUES ('$user_id', 'Y', 'Y', 'N', 'N', 'N')";
                error_log("Executing query: " . $insert_transfer_state);
                error_log("Mysql error for query: " . mysqli_error($database));
                $insert_transfer_state_result = mysqli_query($database, $insert_transfer_state);
                $response->message("How much?");
                print $response;                

            } else {
                $update_transfer_state_start = "UPDATE transfer_state SET transfer_state.start_transfer = 'Y' WHERE transfer_state.user_id = $user_id";
                error_log("Executing query: " . $update_transfer_state_start);
                error_log("Mysql error for query: " . mysqli_error($database));
                $update_transfer_state_result = mysqli_query($database, $update_transfer_state_start);

                $update_transfer_state_how_much = "UPDATE transfer_state SET transfer_state.how_much_transfer = 'Y' WHERE transfer_state.user_id = $user_id";
                error_log("Executing query: " . $update_transfer_state_how_much);
                error_log("Mysql error for query: " . mysqli_error($database));
                $update_transfer_state_how_much_result = mysqli_query($database, $update_transfer_state_how_much);

                $update_transfer_state_which_user = "UPDATE transfer_state SET transfer_state.which_user_transfer = 'N' WHERE transfer_state.user_id = $user_id";
                error_log("Executing query: " . $update_transfer_state_which_user);
                error_log("Mysql error for query: " . mysqli_error($database));
                $update_transfer_state_which_user_result = mysqli_query($database, $update_transfer_state_which_user);

                $update_transfer_state_from_user = "UPDATE transfer_state SET transfer_state.from_user_transfer = 'N' WHERE transfer_state.user_id = $user_id";
                error_log("Executing query: " . $update_transfer_state_from_user);
                error_log("Mysql error for query: " . mysqli_error($database));
                $update_transfer_state_from_user_result = mysqli_query($database, $update_transfer_state_from_user);

                $update_transfer_state_stop = "UPDATE transfer_state SET transfer_state.stop_transfer = 'N' WHERE transfer_state.user_id = $user_id";
                error_log("Executing query: " . $update_transfer_state_stop);
                error_log("Mysql error for query: " . mysqli_error($database));
                $update_transfer_state_stop_result = mysqli_query($database, $update_transfer_state_stop);

                $response->message("How much?");
                print $response;
            }
        }

    } else {

        $get_user_id = "SELECT user.user_id FROM user WHERE user.phone_number = $from";
        error_log("Executing query: " . $get_user_id);
        error_log("Mysql error for query: " . mysqli_error($database));
        $get_user_id_result = mysqli_query($database, $get_user_id);
        $get_user_id_row = mysqli_fetch_assoc($get_user_id_result);
        $user_id = $get_user_id_row['user_id'];

        $get_transfer_state = "SELECT * FROM transfer_state WHERE transfer_state.user_id = $user_id";
        error_log("Executing query: " . $get_transfer_state);
        error_log("Mysql error for query: " . mysqli_error($database));
        $get_transfer_state_result = mysqli_query($database, $get_transfer_state);
        $get_transfer_state_row = mysqli_fetch_assoc($get_transfer_state_result);

        $transfer_start_state = $get_transfer_state_row['start_transfer'];
        $transfer_how_much_state = $get_transfer_state_row['how_much_transfer'];
        $transfer_which_user_state = $get_transfer_state_row['which_user_transfer'];
        $transfer_from_user_state = $get_transfer_state_row['from_user_transfer'];
        $transfer_stop_state = $get_transfer_state_row['stop_transfer'];

        if (($transfer_start_state == 'Y') && ($transfer_how_much_state == 'Y') && ($transfer_which_user_state == 'N') && ($transfer_from_user_state == 'N') && ($transfer_stop_state == 'N')) {

            if (($body == 'Reverse') or ($body == 'reverse') or ($body == 'REVERSE') or ($body == 'Reverse ') or ($body == 'reverse ') or ($body == 'REVERSE ')) {
                if (($transfer_start_state == 'Y') or ($transfer_how_much_state == 'Y') or ($transfer_which_user_state == 'Y') or ($transfer_from_user_state == 'Y') or ($transfer_stop_state == 'Y')) {
                    $update_state_status_start = "UPDATE transfer_state SET transfer_state.start_transfer = 'N' WHERE transfer_state.user_id = $user_id";
                    $update_state_status_start_result = mysqli_query($database, $update_state_status_start);
    
                    $update_state_how_much_status = "UPDATE transfer_state SET transfer_state.how_much_transfer = 'N' WHERE transfer_state.user_id = $user_id";
                    $update_state_how_much_status_result = mysqli_query($database, $update_state_how_much_status);
    
                    $update_state_which_user_status = "UPDATE transfer_state SET transfer_state.which_user_transfer = 'N' WHERE transfer_state.user_id = $user_id";
                    $update_state_which_user_status_result = mysqli_query($database, $update_state_which_user_status);
    
                    $update_state_from_user_status = "UPDATE transfer_state SET transfer_state.from_user_transfer = 'N' WHERE transfer_state.user_id = $user_id";
                    $update_state_from_user_status_result = mysqli_query($database, $update_state_from_user_status);
    
                    $update_state_stop_status = "UPDATE transfer_state SET transfer_state.stop_transfer = 'N' WHERE transfer_state.user_id = $user_id";
                    $update_state_stop_status_result = mysqli_query($database, $update_state_stop_status);
                    
                    $response->message("Transaction cancelled.");
                    print $response;
                    
                }
            } else {

                $wanted_amount = str_replace(',', '', $body);
                $wanted_amount = preg_replace('/[\$,]/', '', $wanted_amount);
                $wanted_amount = str_replace(' ', '', $wanted_amount);

                if ($wanted_amount != is_numeric($wanted_amount)) {
                    error_log("wanted_amount is not numeric " . is_numeric($wanted_amount));

                    if ((stripos($wanted_amount, 'k') == TRUE)) {
                        error_log("K is found in the wanted amount");

                        if (ctype_alpha($wanted_amount) == TRUE) {
                            $response->message("Please type a valid number. You can abbreviate numbers like $4,000 to $4K.");
                            print $response;
                        } else { 

                            $wanted_amount = str_ireplace('k', '', $wanted_amount);
                            $wanted_amount = str_replace(' ', '', $wanted_amount);
                            $wanted_amount = $wanted_amount * 1000;

                            $delete_previous_amount = "DELETE FROM transactions_transfer WHERE transactions_transfer.user_id = $user_id ORDER BY transac_id DESC LIMIT 1";
                            error_log("Executing query: " . $delete_previous_amount);
                            error_log("Mysql error for query: " . mysqli_error($database));
                            $delete_previous_amount_result = mysqli_query($database, $delete_previous_amount);

                            $insert_amount = "INSERT INTO transactions_transfer (user_id,wanted_amount_transfer) VALUES ('$user_id', '$wanted_amount')";
                            error_log("Executing query: " . $insert_amount);
                            error_log("Mysql error for query: " . mysqli_error($database));
                            $insert_amount_result = mysqli_query($database, $insert_amount);

                            $update_which_user_transfer = "UPDATE transfer_state SET transfer_state.which_user_transfer = 'Y' WHERE transfer_state.user_id = $user_id";
                            error_log("Executing query: " . $update_which_user_transfer);
                            error_log("Mysql error for query: " . mysqli_error($database));
                            $update_which_user_transfer_result = mysqli_query($database, $update_which_user_transfer);

                            $select_players = "SELECT player_name, amount FROM player_info WHERE player_info.user_id = $user_id";
                            error_log("Executing query: " . $select_players);
                            error_log("Mysql error for query: " . mysqli_error($database));
                            $select_players_result = mysqli_query($database, $select_players);
                                
                            $player_name = array();
                            $player_amount = array();

                            while ($player_data = mysqli_fetch_array($select_players_result)) {
                                error_log(print_r('row: ' . $player_data['player_name'],true));
                                $player_name[] = $player_data['player_name'];
                                $player_amount[] = $player_data['amount'];
                            }

                            error_log(print_r($player_name,true));
                            error_log(print_r($player_amount,true));

                            $player_info = '';
                            for ($x = 0; $x < count($player_name); $x++) {
                                if (end($player_name) == $player_name[$x]) {
                                    $player_info .= $player_name[$x] . ' => ' . '$' . number_format($player_amount[$x]);
                                } else {
                                    $player_info .= $player_name[$x] . ' => ' . '$' . number_format($player_amount[$x]) . '; ';
                                }
                            }

                            error_log(print_r($player_info,true));

                            $response->message("Adding to which player? Current players: 

${player_info}");
                            print $response;
                        }
                    } elseif ((stripos($wanted_amount, 'm') == TRUE)) {

                        if (ctype_alpha($wanted_amount) == TRUE) {
                            $response->message("Please type a valid number. You can abbreviate numbers like $1,400,000 to $1.4M.");
                            print $response;
                        } else { 

                            $wanted_amount = str_ireplace('m', '', $wanted_amount);
                            $wanted_amount = str_replace(' ', '', $wanted_amount);
                            $wanted_amount = $wanted_amount * 1000000;

                            $delete_previous_amount = "DELETE FROM transactions_transfer WHERE transactions_transfer.user_id = $user_id ORDER BY transac_id DESC LIMIT 1";
                            error_log("Executing query: " . $delete_previous_amount);
                            error_log("Mysql error for query: " . mysqli_error($database));
                            $delete_previous_amount_result = mysqli_query($database, $delete_previous_amount);

                            $insert_amount = "INSERT INTO transactions_transfer (user_id,wanted_amount_transfer) VALUES ('$user_id', '$wanted_amount')";
                            error_log("Executing query: " . $insert_amount);
                            error_log("Mysql error for query: " . mysqli_error($database));
                            $insert_amount_result = mysqli_query($database, $insert_amount);

                            $update_which_user_transfer = "UPDATE transfer_state SET transfer_state.which_user_transfer = 'Y' WHERE transfer_state.user_id = $user_id";
                            error_log("Executing query: " . $update_which_user_transfer);
                            error_log("Mysql error for query: " . mysqli_error($database));
                            $update_which_user_transfer_result = mysqli_query($database, $update_which_user_transfer);

                            $select_players = "SELECT player_name, amount FROM player_info WHERE player_info.user_id = $user_id";
                            error_log("Executing query: " . $select_players);
                            error_log("Mysql error for query: " . mysqli_error($database));
                            $select_players_result = mysqli_query($database, $select_players);
                            
                            $player_name = array();
                            $player_amount = array();

                            while ($player_data = mysqli_fetch_array($select_players_result)) {
                                error_log(print_r('row: ' . $player_data['player_name'],true));
                                $player_name[] = $player_data['player_name'];
                                $player_amount[] = $player_data['amount'];
                            }

                            error_log(print_r($player_name,true));
                            error_log(print_r($player_amount,true));

                            $player_info = '';
                            for ($x = 0; $x < count($player_name); $x++) {
                                if (end($player_name) == $player_name[$x]) {
                                    $player_info .= $player_name[$x] . ' => ' . '$' . number_format($player_amount[$x]);
                                } else {
                                    $player_info .= $player_name[$x] . ' => ' . '$' . number_format($player_amount[$x]) . '; ';
                                }
                            }

                            error_log(print_r($player_info,true));

                            $response->message("Adding to which player? Current players: 

${player_info}");
                            print $response;
                        }
                    } else {
                        $response->message("Please type a valid number. You can abbreviate numbers like $1,400,000 to $1.4M.");
                        print $response;
                    }

                } else {

                    $insert_amount = "INSERT INTO transactions_transfer (user_id,wanted_amount_transfer) VALUES ('$user_id', '$wanted_amount')";
                    error_log("Executing query: " . $insert_amount);
                    error_log("Mysql error for query: " . mysqli_error($database));
                    $insert_amount_result = mysqli_query($database, $insert_amount);

                    $update_which_user_deduct = "UPDATE transfer_state SET transfer_state.which_user_transfer = 'Y' WHERE transfer_state.user_id = $user_id";
                    error_log("Executing query: " . $update_which_user_transfer);
                    error_log("Mysql error for query: " . mysqli_error($database));
                    $update_which_user_transfer_result = mysqli_query($database, $update_which_user_transfer);

                    $select_players = "SELECT player_name, amount FROM player_info WHERE player_info.user_id = $user_id";
                    error_log("Executing query: " . $select_players);
                    error_log("Mysql error for query: " . mysqli_error($database));
                    $select_players_result = mysqli_query($database, $select_players);
                    
                    $player_name = array();
                    $player_amount = array();

                    while ($player_data = mysqli_fetch_array($select_players_result)) {
                        error_log(print_r('row: ' . $player_data['player_name'],true));
                        $player_name[] = $player_data['player_name'];
                        $player_amount[] = $player_data['amount'];
                    }

                    error_log(print_r($player_name,true));
                    error_log(print_r($player_amount,true));

                    $player_info = '';
                    for ($x = 0; $x < count($player_name); $x++) {
                        if (end($player_name) == $player_name[$x]) {
                            $player_info .= $player_name[$x] . ' => ' . '$' . number_format($player_amount[$x]);
                        } else {
                            $player_info .= $player_name[$x] . ' => ' . '$' . number_format($player_amount[$x]) . '; ';
                        }
                    }

                    error_log(print_r($player_info,true));

                    $response->message("Adding to which player? Current players: 

${player_info}");
                    print $response;
                }
            }
        }

        if (($transfer_start_state == 'Y') && ($transfer_how_much_state == 'Y') && ($transfer_which_user_state == 'Y') && ($transfer_from_user_state == 'N') && ($transfer_stop_state == 'N')) {

            if (($body == 'Reverse') or ($body == 'reverse') or ($body == 'REVERSE') or ($body == 'Reverse ') or ($body == 'reverse ') or ($body == 'REVERSE ')) {
                if (($transfer_start_state == 'Y') or ($transfer_how_much_state == 'Y') or ($transfer_which_user_state == 'Y') or ($transfer_from_user_state == 'Y') or ($transfer_stop_state == 'Y')) {
                    $update_state_status_start = "UPDATE transfer_state SET transfer_state.start_transfer = 'N' WHERE transfer_state.user_id = $user_id";
                    $update_state_status_start_result = mysqli_query($database, $update_state_status_start);
    
                    $update_state_how_much_status = "UPDATE transfer_state SET transfer_state.how_much_transfer = 'N' WHERE transfer_state.user_id = $user_id";
                    $update_state_how_much_status_result = mysqli_query($database, $update_state_how_much_status);
    
                    $update_state_which_user_status = "UPDATE transfer_state SET transfer_state.which_user_transfer = 'N' WHERE transfer_state.user_id = $user_id";
                    $update_state_which_user_status_result = mysqli_query($database, $update_state_which_user_status);
    
                    $update_state_from_user_status = "UPDATE transfer_state SET transfer_state.from_user_transfer = 'N' WHERE transfer_state.user_id = $user_id";
                    $update_state_from_user_status_result = mysqli_query($database, $update_state_from_user_status);
    
                    $update_state_stop_status = "UPDATE transfer_state SET transfer_state.stop_transfer = 'N' WHERE transfer_state.user_id = $user_id";
                    $update_state_stop_status_result = mysqli_query($database, $update_state_stop_status);
                    
                    $response->message("Transaction cancelled.");
                    print $response;
                    
                }
            } else {

                $wanted_user = str_replace(' ', '', $body);

                $select_all_players = "SELECT player_info.player_name FROM player_info WHERE player_info.user_id = $user_id AND player_info.player_name = '$wanted_user'";
                error_log("Executing query: " . $select_all_players);
                error_log("Mysql error for query: " . mysqli_error($database));
                $select_all_players_result = mysqli_query($database, $select_all_players);
                $select_all_players_row = mysqli_num_rows($select_all_players_result);

                if ($select_all_players_row != 0) {
                    $update_user_name = "UPDATE transactions_transfer SET transactions_transfer.user_which = '$wanted_user' WHERE transactions_transfer.user_id = $user_id";
                    error_log("Executing query: " . $update_user_name);
                    error_log("Mysql error for query: " . mysqli_error($database));
                    $update_user_name_result = mysqli_query($database, $update_user_name);

                    $update_transfer_from_state = "UPDATE transfer_state SET transfer_state.from_user_transfer = 'Y' WHERE transfer_state.user_id = $user_id";
                    error_log("Executing query: " . $update_transfer_from_state);
                    error_log("Mysql error for query: " . mysqli_error($database));
                    $update_transfer_from_state_result = mysqli_query($database, $update_transfer_from_state);

                    $select_players = "SELECT player_name, amount FROM player_info WHERE player_info.user_id = $user_id";
                    error_log("Executing query: " . $select_players);
                    error_log("Mysql error for query: " . mysqli_error($database));
                    $select_players_result = mysqli_query($database, $select_players);
                    
                    $player_name = array();
                    $player_amount = array();

                    while ($player_data = mysqli_fetch_array($select_players_result)) {
                        error_log(print_r('row: ' . $player_data['player_name'],true));
                        $player_name[] = $player_data['player_name'];
                        $player_amount[] = $player_data['amount'];
                    }

                    error_log(print_r($player_name,true));
                    error_log(print_r($player_amount,true));

                    $player_info = '';
                    for ($x = 0; $x < count($player_name); $x++) {
                        if (end($player_name) == $player_name[$x]) {
                            $player_info .= $player_name[$x] . ' => ' . '$' . number_format($player_amount[$x]);
                        } else {
                            $player_info .= $player_name[$x] . ' => ' . '$' . number_format($player_amount[$x]) . '; ';
                        }
                    }

                    error_log(print_r($player_info,true));

                    $response->message("Deducting which player? Current players: 

${player_info}");
                    print $response;
                } else {
                    $response->message("Invalid player.");
                    print $response;
                }
            }
        }
        
        if (($transfer_start_state == 'Y') && ($transfer_how_much_state == 'Y') && ($transfer_which_user_state == 'Y') && ($transfer_from_user_state == 'Y') && ($transfer_stop_state == 'N')) {

            if (($body == 'Reverse') or ($body == 'reverse') or ($body == 'REVERSE') or ($body == 'Reverse ') or ($body == 'reverse ') or ($body == 'REVERSE ')) {
                if (($transfer_start_state == 'Y') or ($transfer_how_much_state == 'Y') or ($transfer_which_user_state == 'Y') or ($transfer_from_user_state == 'Y') or ($transfer_stop_state == 'Y')) {
                    $update_state_status_start = "UPDATE transfer_state SET transfer_state.start_transfer = 'N' WHERE transfer_state.user_id = $user_id";
                    $update_state_status_start_result = mysqli_query($database, $update_state_status_start);
    
                    $update_state_how_much_status = "UPDATE transfer_state SET transfer_state.how_much_transfer = 'N' WHERE transfer_state.user_id = $user_id";
                    $update_state_how_much_status_result = mysqli_query($database, $update_state_how_much_status);
    
                    $update_state_which_user_status = "UPDATE transfer_state SET transfer_state.which_user_transfer = 'N' WHERE transfer_state.user_id = $user_id";
                    $update_state_which_user_status_result = mysqli_query($database, $update_state_which_user_status);
    
                    $update_state_from_user_status = "UPDATE transfer_state SET transfer_state.from_user_transfer = 'N' WHERE transfer_state.user_id = $user_id";
                    $update_state_from_user_status_result = mysqli_query($database, $update_state_from_user_status);
    
                    $update_state_stop_status = "UPDATE transfer_state SET transfer_state.stop_transfer = 'N' WHERE transfer_state.user_id = $user_id";
                    $update_state_stop_status_result = mysqli_query($database, $update_state_stop_status);
                    
                    $response->message("Transaction cancelled.");
                    print $response;
                    
                }
            } else {

                $wanted_user = str_replace(' ', '', $body);

                $select_all_players = "SELECT player_info.player_name FROM player_info WHERE player_info.user_id = $user_id AND player_info.player_name = '$wanted_user'";
                error_log("Executing query: " . $select_all_players);
                error_log("Mysql error for query: " . mysqli_error($database));
                $select_all_players_result = mysqli_query($database, $select_all_players);
                $select_all_players_row = mysqli_num_rows($select_all_players_result);

                if ($select_all_players_row != 0) {
                    $update_user_name = "UPDATE transactions_transfer SET transactions_transfer.user_from = '$wanted_user' WHERE transactions_transfer.user_id = $user_id";
                    error_log("Executing query: " . $update_user_name);
                    error_log("Mysql error for query: " . mysqli_error($database));
                    $update_user_name_result = mysqli_query($database, $update_user_name);

                    $update_stop_state = "UPDATE transfer_state SET transfer_state.stop_transfer = 'Y' WHERE transfer_state.user_id = $user_id";
                    error_log("Executing query: " . $update_stop_state);
                    error_log("Mysql error for query: " . mysqli_error($database));
                    $update_stop_state_result = mysqli_query($database, $update_stop_state);

                    $select_transaction = "SELECT transactions_transfer.user_which, player_info.amount, transactions_transfer.wanted_amount_transfer FROM player_info, transactions_transfer WHERE player_info.user_id = transactions_transfer.user_id AND player_info.player_name = transactions_transfer.user_which AND transactions_transfer.paid_t = 'N'";
                    error_log("Executing query: " . $select_transaction);
                    error_log("Mysql error for query: " . mysqli_error($database));
                    $select_transaction_result = mysqli_query($database, $select_transaction);
                    $select_transaction_row = mysqli_fetch_assoc($select_transaction_result);

                    $user = $select_transaction_row['user_which'];
                    error_log("users name " . $user);
                    $user_amount = $select_transaction_row['amount'];
                    error_log("User amount: " . $user_amount);
                    
                    $wanted_amount = $select_transaction_row['wanted_amount_transfer'];
                    
                    $new_amount = $user_amount + $wanted_amount;
                    error_log("New amount: " . $new_amount);

                    $update_new_amount = "UPDATE player_info SET player_info.amount = $new_amount WHERE player_info.user_id = $user_id AND player_info.player_name = '$user'";
                    error_log("Executing query: " . $update_new_amount);
                    error_log("Mysql error for query: " . mysqli_error($database));
                    $update_new_amount_result = mysqli_query($database, $update_new_amount);

                    $select_transaction1 = "SELECT transactions_transfer.user_from, player_info.amount FROM player_info, transactions_transfer WHERE player_info.user_id = transactions_transfer.user_id AND player_info.player_name = transactions_transfer.user_from AND transactions_transfer.paid_t = 'N'";
                    error_log("Executing query: " . $select_transaction1);
                    error_log("Mysql error for query: " . mysqli_error($database));
                    $select_transaction1_result = mysqli_query($database, $select_transaction1);
                    $select_transaction1_row = mysqli_fetch_assoc($select_transaction1_result);
                        
                    $user1 = $select_transaction1_row['user_from'];
                    error_log("users name " . $user1);
                    $user_amount1 = $select_transaction1_row['amount'];
                    error_log("User amount: " . $user_amount1);
                    $new_amount1 = $user_amount1 - $wanted_amount;
                    error_log("New amount: " . $new_amount1);

                    $update_new_amount1 = "UPDATE player_info SET player_info.amount = $new_amount1 WHERE player_info.user_id = $user_id AND player_info.player_name = '$user1'";
                    error_log("Executing query: " . $update_new_amount1);
                    error_log("Mysql error for query: " . mysqli_error($database));
                    $update_new_amount_result1 = mysqli_query($database, $update_new_amount1);

                    $update_paid = "UPDATE transactions_transfer SET transactions_transfer.paid_t = 'Y' WHERE transactions_transfer.user_which = '$user' AND transactions_transfer.user_id = $user_id";
                    error_log("Executing query: " . $update_paid);
                    error_log("Mysql error for query: " . mysqli_error($database));
                    $update_paid_result = mysqli_query($database, $update_paid);

                    $select_players = "SELECT player_name, amount FROM player_info WHERE player_info.user_id = $user_id";
                    error_log("Executing query: " . $select_players);
                    error_log("Mysql error for query: " . mysqli_error($database));
                    $select_players_result = mysqli_query($database, $select_players);
                    
                    $player_name = array();
                    $player_amount = array();

                    while ($player_data = mysqli_fetch_array($select_players_result)) {
                        error_log(print_r('row: ' . $player_data['player_name'],true));
                        $player_name[] = $player_data['player_name'];
                        $player_amount[] = $player_data['amount'];
                    }

                    error_log(print_r($player_name,true));
                    error_log(print_r($player_amount,true));

                    $player_info = '';
                    for ($x = 0; $x < count($player_name); $x++) {
                        if (end($player_name) == $player_name[$x]) {
                            $player_info .= $player_name[$x] . ' => ' . '$' . number_format($player_amount[$x]);
                        } else {
                            $player_info .= $player_name[$x] . ' => ' . '$' . number_format($player_amount[$x]) . '; ';
                        }
                    }

                    error_log(print_r($player_info,true));

                    $response->message("Transaction complete. New Amounts: 

${player_info}");
                    print $response;
                } else {
                    $response->message("Invalid player.");
                    print $response;
                }
            }
        }
    }

    if (($body == 'Go') or ($body == 'go') or ($body == 'GO') or ($body == 'Go ') or ($body == 'go ') or ($body == 'GO ')) {
        $select_phone_number = "SELECT phone_number FROM user WHERE user.phone_number = $from";
        error_log("Executing query: " . $select_phone_number);
        error_log("Mysql error for query: " . mysqli_error($database));
        $select_phone_number_result = mysqli_query($database, $select_phone_number);
        $user_rows = mysqli_num_rows($select_phone_number_result);

        if ($user_rows == 0) {
            $response->message("This command is currently unavailable because you have not registered. Reply BANKER to register.");
            print $response;
        } else {

            $get_user_id = "SELECT user.user_id FROM user WHERE user.phone_number = $from";
            error_log("Executing query: " . $get_user_id);
            error_log("Mysql error for query: " . mysqli_error($database));
            $get_user_id_result = mysqli_query($database, $get_user_id);
            $get_user_id_row = mysqli_fetch_assoc($get_user_id_result);
            $user_id = $get_user_id_row['user_id'];

            $select_go_state = "SELECT * FROM go_state WHERE go_state.user_id = $user_id";
            error_log("Executing query: " . $select_go_state);
            error_log("Mysql error for query: " . mysqli_error($database));
            $select_go_state_result = mysqli_query($database, $select_go_state);
            $select_go_state_row = mysqli_num_rows($select_go_state_result);

            if ($select_go_state_row == 0) {
                $insert_go_state = "INSERT INTO go_state (user_id,start_go,which_user_go,stop_go) VALUES ('$user_id', 'Y', 'Y', 'N')";
                error_log("Executing query: " . $insert_go_state);
                error_log("Mysql error for query: " . mysqli_error($database));
                $insert_go_state_result = mysqli_query($database, $insert_go_state);

                $select_players = "SELECT player_name, amount FROM player_info WHERE player_info.user_id = $user_id";
                error_log("Executing query: " . $select_players);
                error_log("Mysql error for query: " . mysqli_error($database));
                $select_players_result = mysqli_query($database, $select_players);
                                
                $player_name = array();
                $player_amount = array();

                while ($player_data = mysqli_fetch_array($select_players_result)) {
                    error_log(print_r('row: ' . $player_data['player_name'],true));
                    $player_name[] = $player_data['player_name'];
                    $player_amount[] = $player_data['amount'];
                }

                error_log(print_r($player_name,true));
                error_log(print_r($player_amount,true));

                $player_info = '';
                for ($x = 0; $x < count($player_name); $x++) {
                    if (end($player_name) == $player_name[$x]) {
                        $player_info .= $player_name[$x] . ' => ' . '$' . number_format($player_amount[$x]);
                    } else {
                        $player_info .= $player_name[$x] . ' => ' . '$' . number_format($player_amount[$x]) . '; ';
                    }
                }

                error_log(print_r($player_info,true));

                $response->message("Which player? Current players: 

${player_info}");
                print $response;                

            } else {
                $update_go_state_start = "UPDATE go_state SET go_state.start_go = 'Y' WHERE go_state.user_id = $user_id";
                error_log("Executing query: " . $update_go_state_start);
                error_log("Mysql error for query: " . mysqli_error($database));
                $update_go_state_result = mysqli_query($database, $update_go_state_start);

                $update_go_state_which_user = "UPDATE go_state SET go_state.which_user_go = 'Y' WHERE go_state.user_id = $user_id";
                error_log("Executing query: " . $update_go_state_which_user);
                error_log("Mysql error for query: " . mysqli_error($database));
                $update_go_state_which_user_result = mysqli_query($database, $update_go_state_which_user);

                $update_go_state_stop = "UPDATE go_state SET go_state.stop_go = 'N' WHERE go_state.user_id = $user_id";
                error_log("Executing query: " . $update_go_state_stop);
                error_log("Mysql error for query: " . mysqli_error($database));
                $update_go_state_stop_result = mysqli_query($database, $update_go_state_stop);

                $select_players = "SELECT player_name, amount FROM player_info WHERE player_info.user_id = $user_id";
                error_log("Executing query: " . $select_players);
                error_log("Mysql error for query: " . mysqli_error($database));
                $select_players_result = mysqli_query($database, $select_players);
                                
                $player_name = array();
                $player_amount = array();

                while ($player_data = mysqli_fetch_array($select_players_result)) {
                    error_log(print_r('row: ' . $player_data['player_name'],true));
                    $player_name[] = $player_data['player_name'];
                    $player_amount[] = $player_data['amount'];
                }

                error_log(print_r($player_name,true));
                error_log(print_r($player_amount,true));

                $player_info = '';
                for ($x = 0; $x < count($player_name); $x++) {
                    if (end($player_name) == $player_name[$x]) {
                        $player_info .= $player_name[$x] . ' => ' . '$' . number_format($player_amount[$x]);
                    } else {
                        $player_info .= $player_name[$x] . ' => ' . '$' . number_format($player_amount[$x]) . '; ';
                    }
                }

                error_log(print_r($player_info,true));

                $response->message("Which player? Current players: 

${player_info}");
                print $response;
            }
        }

    } else {

        $get_user_id = "SELECT user.user_id FROM user WHERE user.phone_number = $from";
        error_log("Executing query: " . $get_user_id);
        error_log("Mysql error for query: " . mysqli_error($database));
        $get_user_id_result = mysqli_query($database, $get_user_id);
        $get_user_id_row = mysqli_fetch_assoc($get_user_id_result);
        $user_id = $get_user_id_row['user_id'];

        $get_go_state = "SELECT * FROM go_state WHERE go_state.user_id = $user_id";
        error_log("Executing query: " . $get_go_state);
        error_log("Mysql error for query: " . mysqli_error($database));
        $get_go_state_result = mysqli_query($database, $get_go_state);
        $get_go_state_row = mysqli_fetch_assoc($get_go_state_result);

        $go_start_state = $get_go_state_row['start_go'];
        $go_which_user_state = $get_go_state_row['which_user_go'];
        $go_stop_state = $get_go_state_row['stop_go'];

        if (($go_start_state == 'Y') && ($go_which_user_state == 'Y') && ($go_stop_state == 'N')) {

            if (($body == 'Reverse') or ($body == 'reverse') or ($body == 'REVERSE') or ($body == 'Reverse ') or ($body == 'reverse ') or ($body == 'REVERSE ')) {
                if (($go_start_state == 'Y') or ($go_which_user_state == 'Y') or ($go_stop_state == 'Y')) {
                    $update_state_status_start = "UPDATE go_state SET go_state.start_go = 'N' WHERE go_state.user_id = $user_id";
                    $update_state_status_start_result = mysqli_query($database, $update_state_status_start);

                    $update_state_which_user_status = "UPDATE go_state SET go_state.which_user_go = 'N' WHERE go_state.user_id = $user_id";
                    $update_state_which_user_status_result = mysqli_query($database, $update_state_which_user_status);
    
                    $update_state_stop_status = "UPDATE go_state SET go_state.stop_go = 'N' WHERE go_state.user_id = $user_id";
                    $update_state_stop_status_result = mysqli_query($database, $update_state_stop_status);
                    
                    $response->message("Transaction cancelled.");
                    print $response;
                    
                }
            } else {
                $wanted_user = str_replace(' ', '', $body);

                $select_all_players = "SELECT player_info.player_name FROM player_info WHERE player_info.user_id = $user_id AND player_info.player_name = '$wanted_user'";
                error_log("Executing query: " . $select_all_players);
                error_log("Mysql error for query: " . mysqli_error($database));
                $select_all_players_result = mysqli_query($database, $select_all_players);
                $select_all_players_row = mysqli_num_rows($select_all_players_result);

                if ($select_all_players_row != 0) {

                    $update_stop_state = "UPDATE go_state SET go_state.stop_go = 'Y' WHERE go_state.user_id = $user_id";
                    error_log("Executing query: " . $update_stop_state);
                    error_log("Mysql error for query: " . mysqli_error($database));
                    $update_stop_state_result = mysqli_query($database, $update_stop_state);

                    $select_transaction = "SELECT player_info.player_name, player_info.amount, game_state.gamount FROM player_info, game_state WHERE player_info.user_id = game_state.user_id AND player_info.player_name = '$wanted_user'";
                    error_log("Executing query: " . $select_transaction);
                    error_log("Mysql error for query: " . mysqli_error($database));
                    $select_transaction_result = mysqli_query($database, $select_transaction);
                    $select_transaction_row = mysqli_fetch_assoc($select_transaction_result);
                        
                    $user_amount = $select_transaction_row['amount'];
                    $wanted_amount = $select_transaction_row['gamount'];
                    $user = $select_transaction_row['player_name'];
                    $new_amount = $user_amount + $wanted_amount;

                    $update_new_amount = "UPDATE player_info SET player_info.amount = $new_amount WHERE player_info.user_id = $user_id AND player_info.player_name = '$user'";
                    error_log("Executing query: " . $update_new_amount);
                    error_log("Mysql error for query: " . mysqli_error($database));
                    $update_new_amount_result = mysqli_query($database, $update_new_amount);

                    $select_players = "SELECT player_name, amount FROM player_info WHERE player_info.user_id = $user_id";
                    error_log("Executing query: " . $select_players);
                    error_log("Mysql error for query: " . mysqli_error($database));
                    $select_players_result = mysqli_query($database, $select_players);
                    
                    $player_name = array();
                    $player_amount = array();

                    while ($player_data = mysqli_fetch_array($select_players_result)) {
                        error_log(print_r('row: ' . $player_data['player_name'],true));
                        $player_name[] = $player_data['player_name'];
                        $player_amount[] = $player_data['amount'];
                    }

                    error_log(print_r($player_name,true));
                    error_log(print_r($player_amount,true));

                    $player_info = '';
                    for ($x = 0; $x < count($player_name); $x++) {
                        if (end($player_name) == $player_name[$x]) {
                            $player_info .= $player_name[$x] . ' => ' . '$' . number_format($player_amount[$x]);
                        } else {
                            $player_info .= $player_name[$x] . ' => ' . '$' . number_format($player_amount[$x]) . '; ';
                        }
                    }

                    error_log(print_r($player_info,true));

                    $response->message("Transaction complete. New Amounts: 

${player_info}");
                    print $response;
                } else {
                    $response->message("Invalid player.");
                    print $response;
                }
            }
        }
    }

    if (($body == 'Players') or ($body == 'players') or ($body == 'PLAYERS') or ($body == 'Players ') or ($body == 'players ') or ($body == 'PLAYERS ')) {
        $select_phone_number = "SELECT phone_number FROM user WHERE user.phone_number = $from";
        error_log("Executing query: " . $select_phone_number);
        error_log("Mysql error for query: " . mysqli_error($database));
        $select_phone_number_result = mysqli_query($database, $select_phone_number);
        $user_rows = mysqli_num_rows($select_phone_number_result);

        if ($user_rows == 0) {
            $response->message("This command is currently unavailable because you have not registered. Reply BANKER to register.");
            print $response;
        } else {
            $get_user_id = "SELECT user.user_id FROM user WHERE user.phone_number = $from";
            error_log("Executing query: " . $get_user_id);
            error_log("Mysql error for query: " . mysqli_error($database));
            $get_user_id_result = mysqli_query($database, $get_user_id);
            $get_user_id_row = mysqli_fetch_assoc($get_user_id_result);
            $user_id = $get_user_id_row['user_id'];

            $select_game = "SELECT * FROM game_state WHERE game_state.user_id = $user_id AND game_state.start = 'Y'";
            error_log("Executing query: " . $select_game);
            error_log("Mysql error for query: " . mysqli_error($database));
            $select_game_result = mysqli_query($database, $select_game);
            $game_row = mysqli_num_rows($select_game_result);

            if ($game_row == 0) {
                $response->message("You currently don't have a game running. Reply START to start a game");
                print $response;
            } else {
                $select_players = "SELECT player_name, amount FROM player_info WHERE player_info.user_id = $user_id";
                error_log("Executing query: " . $select_players);
                error_log("Mysql error for query: " . mysqli_error($database));
                $select_players_result = mysqli_query($database, $select_players);
                
                $player_name = array();
                $player_amount = array();

                while ($player_data = mysqli_fetch_array($select_players_result)) {
                    error_log(print_r('row: ' . $player_data['player_name'],true));
                    $player_name[] = $player_data['player_name'];
                    $player_amount[] = $player_data['amount'];
                }

                error_log(print_r($player_name,true));
                error_log(print_r($player_amount,true));

                $player_info = '';
                for ($x = 0; $x < count($player_name); $x++) {
                    if (end($player_name) == $player_name[$x]) {
                        $player_info .= $player_name[$x] . ' => ' . '$' . number_format($player_amount[$x]);
                    } else {
                        $player_info .= $player_name[$x] . ' => ' . '$' . number_format($player_amount[$x]) . '; ';
                    }
                }

                error_log(print_r($player_info,true));

                $response->message("Current players: 

${player_info}");
                print $response;
            }
        }
    }
}
?>