<?php
/**
 * Use for return easy answer.
 */

require_once('./vendor/autoload.php');

use \LINE\LINEBot\HTTPClient\CurlHTTPClient;
use \LINE\LINEBot;
use \LINE\LINEBot\MessageBuilder\TextMessageBuilder;

// Token
$channel_token = 'lZ+WXE4At+V8NlwkInMHC5wJAvpeeKnCt197Y7l1CVfzSG6uhdee6tVMhG/Esk2GEmFAjl7gvElqWawH4o7AUJxGnKbhpogowCJqIA1cQ57oIF/4qF8CrOx7f0K4RCUjqUy3urKNf4xFaPhCl+faGAdB04t89/1O/w1cDnyilFU=';
$channel_secret = '85a055cff00c5ca119e5ded3225bfdf3';

// Get message from Line API
$content = file_get_contents('php://input');
$events = json_decode($content, true);

if (!is_null($events['events'])) {

	// Loop through each event
	foreach ($events['events'] as $event) {
    
        // Line API send a lot of event type, we interested in message only.
		if ($event['type'] == 'message' && $event['message']['type'] == 'text') {

            // Get replyToken
            $replyToken = $event['replyToken'];

            switch($event['message']['text']) {
                
                case 'tel':
                    $respMessage = '089-5124512';
                    break;
                case 'address':
                    $respMessage = '99/451 Muang Nonthaburi';
                    break;
                case 'boss':
                    $respMessage = '089-2541545';
                    break;
                case 'idcard':
                    $respMessage = '5845122451245';
                    break;
				case "t_b":
					// กำหนด action 4 ปุ่ม 4 ประเภท
					$actionBuilder = array(
						new MessageTemplateActionBuilder(
							'Message Template',// ข้อความแสดงในปุ่ม
							'This is Text' // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
						),
						new UriTemplateActionBuilder(
							'Uri Template', // ข้อความแสดงในปุ่ม
							'https://www.ninenik.com'
						),
						new DatetimePickerTemplateActionBuilder(
							'Datetime Picker', // ข้อความแสดงในปุ่ม
							http_build_query(array(
								'action'=>'reservation',
								'person'=>5
							)), // ข้อมูลที่จะส่งไปใน webhook ผ่าน postback event
							'datetime', // date | time | datetime รูปแบบข้อมูลที่จะส่ง ในที่นี้ใช้ datatime
							substr_replace(date("Y-m-d H:i"),'T',10,1), // วันที่ เวลา ค่าเริ่มต้นที่ถูกเลือก
							substr_replace(date("Y-m-d H:i",strtotime("+5 day")),'T',10,1), //วันที่ เวลา มากสุดที่เลือกได้
							substr_replace(date("Y-m-d H:i"),'T',10,1) //วันที่ เวลา น้อยสุดที่เลือกได้
						),      
						new PostbackTemplateActionBuilder(
							'Postback', // ข้อความแสดงในปุ่ม
							http_build_query(array(
								'action'=>'buy',
								'item'=>100
							)), // ข้อมูลที่จะส่งไปใน webhook ผ่าน postback event
							'Postback Text'  // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
						),      
					);
					$imageUrl = 'https://www.mywebsite.com/imgsrc/photos/w/simpleflower';
					$replyData = new TemplateMessageBuilder('Button Template',
						new ButtonTemplateBuilder(
								'button template builder', // กำหนดหัวเรื่อง
								'Please select', // กำหนดรายละเอียด
								$imageUrl, // กำหนด url รุปภาพ
								$actionBuilder  // กำหนด action object
						)
					);              
					break;  
                default:
                    break;
            }

            $httpClient = new CurlHTTPClient($channel_token);
            $bot = new LINEBot($httpClient, array('channelSecret' => $channel_secret));

            $textMessageBuilder = new TextMessageBuilder($respMessage);
            $response = $bot->replyMessage($replyToken, $textMessageBuilder);

		}
	}
}

echo "OK";

