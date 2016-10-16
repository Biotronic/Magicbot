<?php
include("settings.php");
include("cards.php");

$command = $_REQUEST['command'];
$text = strtolower($_REQUEST['text']);
$token = $_REQUEST['token'];

if($token != $settings['token']){ #replace this with the token from your slash command configuration page
  $msg = "The token for the slash command doesn't match. Check your script.";
  die($msg);
}

$cards = new Cards('cards.txt');
$found = $cards->find_card($text);


if (count($found) > 0) {
    header('Content-Type: application/json');
    
    $json = array(
        "response_type" => "in_channel", 
        "text" => "",
        "attachments" => array_map(function($card) {
                global $settings;
                return array(
                    "text" => "http://gatherer.wizards.com/Pages/Card/Details.aspx?multiverseid={$card['id']}",
                    "fallback" => "{$settings['baseUrl']}{$card['set']}/{$card['image_url']}{$settings['suffix']}",
                    "image_url" => "{$settings['baseUrl']}{$card['set']}/{$card['image_url']}{$settings['suffix']}");
                    }, $found)
        );
    echo json_encode($json);
}

?>