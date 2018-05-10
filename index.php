<?php 
require_once('./vendor/autoload.php'); 

// Namespace 
use \LINE\LINEBot\HTTPClient\CurlHTTPClient; 
use \LINE\LINEBot; 
use \LINE\LINEBot\MessageBuilder\TextMessageBuilder; 

$channel_token = 'lZ+WXE4At+V8NlwkInMHC5wJAvpeeKnCt197Y7l1CVfzSG6uhdee6tVMhG/Esk2GEmFAjl7gvElqWawH4o7AUJxGnKbhpogowCJqIA1cQ57oIF/4qF8CrOx7f0K4RCUjqUy3urKNf4xFaPhCl+faGAdB04t89/1O/w1cDnyilFU='; 
$channel_secret = '85a055cff00c5ca119e5ded3225bfdf3'; 

// Get message from Line API 
$content = file_get_contents('php://input'); 
$events = json_decode($content, true); 


if (!is_null($events['events'])) { 
	// Loop through each event 
	foreach ($events['events'] as $event) { 
		// Line API send a lot of event type, we interested in message only. 
		if ($event['type'] == 'message') { 
			switch($event['message']['type']) { 
				case 'text': 
					// Get replyToken 
					$replyToken = $event['replyToken']; 
					
					// Reply message 
					$respMessage = 'Hello, your message is '. $event['message']['text']; 
					
					$httpClient = new CurlHTTPClient($channel_token); 
					$bot = new LINEBot($httpClient, array('channelSecret' => $channel_secret)); 
					$textMessageBuilder = new TextMessageBuilder($respMessage); 
					$response = $bot->replyMessage($replyToken, $textMessageBuilder); 
				break; 
			} 
		} 
	} 
} 
echo "OK";