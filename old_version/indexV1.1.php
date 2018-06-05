<?php
/*
Duck Bot
V.1.0 
	1. สามารถคุยตอบโต้เกี่ยวกับ mission และสรุปเป็นข้อความรายงาน
	2. สามารถลดข้อผิดพลาดในการรายงานได้
	3. การรายงานได้แบบฟอร์มเดียวกัน
	4. หากไม่มี trouble สามารถกดข้ามได้ เพื่อประหยัดเวลา
	ข้อจำกัด
	1. ยังไม่เพิ่มเติมในส่วนของหลาย trouble
	2. หากข้อมูลใน excel มีความถูกต้องสมบูรณ์แล้ว จะพัฒนาในส่วนของ time total, UAV hr., Engine hr. ให้สามารถคำนวณโดบอัตโนมัติ
	3. รอพัฒนาในส่วนของ maintenance
V.1.1
	1. แก้ไขภารกิจ
	ข้อจำกัด
	1. ยังไม่เพิ่มเติมในส่วนของหลาย trouble
	2. หากข้อมูลใน excel มีความถูกต้องสมบูรณ์แล้ว จะพัฒนาในส่วนของ time total, UAV hr., Engine hr. ให้สามารถคำนวณโดบอัตโนมัติ
	3. รอพัฒนาในส่วนของ maintenance
	4. รอเพิ่มการค้นหาส่วนที่ให้ใส่เวลา UAV hr., Engine hr. ถ้าไม่่มีเครื่องหมาย : ให้ใส่ค่าใหม่
	
*/
// include composer autoload
require_once './vendor/autoload.php';
 
// กรณีมีการเชื่อมต่อกับฐานข้อมูล
//require_once("dbconnect.php");
 
///////////// ส่วนของการเรียกใช้งาน class ผ่าน namespace
use \LINE\LINEBot;
use \LINE\LINEBot\HTTPClient;
use \LINE\LINEBot\HTTPClient\CurlHTTPClient;
//use \LINE\LINEBot\Event;
//use \LINE\LINEBot\Event\BaseEvent;
//use \LINE\LINEBot\Event\MessageEvent;
use \LINE\LINEBot\MessageBuilder;
use \LINE\LINEBot\MessageBuilder\TextMessageBuilder;
use \LINE\LINEBot\MessageBuilder\StickerMessageBuilder;
use \LINE\LINEBot\MessageBuilder\ImageMessageBuilder;
use \LINE\LINEBot\MessageBuilder\LocationMessageBuilder;
use \LINE\LINEBot\MessageBuilder\AudioMessageBuilder;
use \LINE\LINEBot\MessageBuilder\VideoMessageBuilder;
use \LINE\LINEBot\ImagemapActionBuilder;
use \LINE\LINEBot\ImagemapActionBuilder\AreaBuilder;
use \LINE\LINEBot\ImagemapActionBuilder\ImagemapMessageActionBuilder ;
use \LINE\LINEBot\ImagemapActionBuilder\ImagemapUriActionBuilder;
use \LINE\LINEBot\MessageBuilder\Imagemap\BaseSizeBuilder;
use \LINE\LINEBot\MessageBuilder\ImagemapMessageBuilder;
use \LINE\LINEBot\MessageBuilder\MultiMessageBuilder;
use \LINE\LINEBot\TemplateActionBuilder;
use \LINE\LINEBot\TemplateActionBuilder\DatetimePickerTemplateActionBuilder;
use \LINE\LINEBot\TemplateActionBuilder\MessageTemplateActionBuilder;
use \LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder;
use \LINE\LINEBot\TemplateActionBuilder\UriTemplateActionBuilder;
use \LINE\LINEBot\MessageBuilder\TemplateBuilder;
use \LINE\LINEBot\MessageBuilder\TemplateMessageBuilder;
use \LINE\LINEBot\MessageBuilder\TemplateBuilder\ButtonTemplateBuilder;
use \LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselTemplateBuilder;
use \LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselColumnTemplateBuilder;
use \LINE\LINEBot\MessageBuilder\TemplateBuilder\ConfirmTemplateBuilder;
use \LINE\LINEBot\MessageBuilder\TemplateBuilder\ImageCarouselTemplateBuilder;
use \LINE\LINEBot\MessageBuilder\TemplateBuilder\ImageCarouselColumnTemplateBuilder;
 
$channel_token = 'lZ+WXE4At+V8NlwkInMHC5wJAvpeeKnCt197Y7l1CVfzSG6uhdee6tVMhG/Esk2GEmFAjl7gvElqWawH4o7AUJxGnKbhpogowCJqIA1cQ57oIF/4qF8CrOx7f0K4RCUjqUy3urKNf4xFaPhCl+faGAdB04t89/1O/w1cDnyilFU=';
$channel_secret = '85a055cff00c5ca119e5ded3225bfdf3';
 
 
$content = file_get_contents('php://input');
$events = json_decode($content, true);
// เชื่อมต่อกับ LINE Messaging API
$httpClient = new CurlHTTPClient($channel_token);
$bot = new LINEBot($httpClient, array('channelSecret' => $channel_secret));
if(!is_null($events)){
    // ถ้ามีค่า สร้างตัวแปรเก็บ replyToken ไว้ใช้งาน
    $replyToken = $events['events'][0]['replyToken'];
    $userID = $events['events'][0]['source']['userId'];
    $sourceType = $events['events'][0]['source']['type'];        
    $is_postback = NULL;
    $is_message = NULL;
    if(isset($events['events'][0]) && array_key_exists('message',$events['events'][0])){
        $is_message = true;
        $typeMessage = $events['events'][0]['message']['type'];
        $userMessage = $events['events'][0]['message']['text'];     
        $idMessage = $events['events'][0]['message']['id'];             
    }
    if(isset($events['events'][0]) && array_key_exists('postback',$events['events'][0])){
        $is_postback = true;
        $dataPostback = NULL;
        parse_str($events['events'][0]['postback']['data'],$dataPostback);;
        $paramPostback = NULL;
		//parse_str($events['events'][0]['postback']['data'],$paramPostback);;
		$paramPostback = $events['events'][0]['postback']['data'];
		$paramPostback = substr($paramPostback,2,20);
        if(array_key_exists('params',$events['events'][0]['postback'])){
            if(array_key_exists('date',$events['events'][0]['postback']['params'])){
                $paramPostback = $events['events'][0]['postback']['params']['date'];
            }
            if(array_key_exists('time',$events['events'][0]['postback']['params'])){
                $paramPostback = $events['events'][0]['postback']['params']['time'];
            }
            if(array_key_exists('datetime',$events['events'][0]['postback']['params'])){
                $paramPostback = $events['events'][0]['postback']['params']['datetime'];
            }
        }
    }   
    if(!is_null($is_postback)){
        /*$textReplyMessage = "ข้อความจาก Postback Event Data = ";
        if(is_array($dataPostback)){
            $textReplyMessage.= json_encode($dataPostback);
			$textReplyMessage.=" \r\nParams = ".$dataPostback;
			$textReplyMessage.=" \r\nParams1 = ";
			$textReplyMessage.= json_encode($paramPostback);
			$textReplyMessage.=" \r\nParams2 = ".substr($paramPostback,2,20);

        }*/
        if(!is_null($paramPostback)){
			$get_result = calculate($paramPostback);
			$is_message = $get_result[0];
			$typeMessage = $get_result[1];
			$userMessage = $get_result[2];
		
            //$textReplyMessage.= " \r\nParams = ".$paramPostback;
			//$textReplyMessage.= "\r\nBot ตอบกลับคุณเป็นข้อความ".$is_message;
			//$textReplyMessage.= "\r\nBot ตอบกลับคุณเป็นข้อความ".$typeMessage;
			//$textReplyMessage.= "\r\nขBot ตอบกลับคุณเป็นข้อความ".$userMessage;
        }
        $replyData = new TextMessageBuilder($textReplyMessage); 		
    }
		$myfile = fopen("x.txt", "r+") or die("Unable to open file!");
		$x=(fgets($myfile));
		fclose($myfile);
		if ($userMessage != "รายงานบิน"){
			if ($userMessage != "รายงานซ่อม"){
				if ($userMessage != 'fuel_qty' && $x == '6'){
				$get_result = calculate($userMessage);
				$userMessage = $get_result[2];
				}
				if ($userMessage != 'fuel_remain' && $x == '7'){
				$get_result = calculate($userMessage);
				$userMessage = $get_result[2];
				}
				if ($userMessage != 'time_uav' && $x == '12'){
				$get_result = calculate($userMessage);
				$userMessage = $get_result[2];
				}
				if ($userMessage != 'time_engine' && $x == '13'){
				$get_result = calculate($userMessage);
				$userMessage = $get_result[2];
				}
				if ($userMessage != 'fuel_cart_17' && $x == '14'){
				$get_result = calculate($userMessage);
				$userMessage = $get_result[2];
				}
				if ($userMessage != 'fuel_cart_32' && $x == '15'){
				$get_result = calculate($userMessage);
				$userMessage = $get_result[2];
				}
				if ($userMessage != 'flight_trouble1' && $x == '17' && $userMessage != 'if_trouble' && $userMessage != 'ct'){
				$get_result = calculate($userMessage);
				$userMessage = $get_result[2];
				}
				if ($userMessage != 'flight_repairable1' && $x == '18'){
				$get_result = calculate($userMessage);
				$userMessage = $get_result[2];
				}
				if ($userMessage != 'flight_item1' && $x == '19'){
				$get_result = calculate($userMessage);
				$userMessage = $get_result[2];
				}
				if ($userMessage != 'flight_sn' && $x == '20'){
				$get_result = calculate($userMessage);
				$userMessage = $get_result[2];
				}
			}
		}
    if(!is_null($is_message)){
        switch ($typeMessage){
            case 'text':
                $userMessage = strtolower($userMessage); // แปลงเป็นตัวเล็ก สำหรับทดสอบ
                switch ($userMessage) {
					case "รายงานบิน":
						$myfile = fopen("abc.txt", "w+") or die("Unable to open file!");
						$strText1 = "";
						fwrite($myfile, $strText1);
						fclose($myfile);
						$myfile = fopen("x.txt", "w+") or die("Unable to open file!");
						fwrite($myfile, 0);
						fclose($myfile);
                        // กำหนด action 4 ปุ่ม 4 ประเภท
                        $actionBuilder = array(
                            new DatetimePickerTemplateActionBuilder(
                                'Date', // ข้อความแสดงในปุ่ม
								http_build_query(array(
									'action'=>'reservation',
									'person'=>5
								)), // ข้อมูลที่จะส่งไปใน webhook ผ่าน postback event
								'date', // date | time | datetime รูปแบบข้อมูลที่จะส่ง ในที่นี้ใช้ datatime
								date("Y-m-d"), // วันที่ เวลา ค่าเริ่มต้นที่ถูกเลือก
								date("Y-m-d",strtotime("+1 day")), //วันที่ เวลา มากสุดที่เลือกได้
								date("Y-m-d",strtotime("-30 day")) //วันที่ เวลา น้อยสุดที่เลือกได้
							),
                        );
                        $imageUrl = 'https://raw.githubusercontent.com/Thanadon99/linebot-code-example/master/pic/report.jpg';
                        $replyData = new TemplateMessageBuilder('Button Template',
                            new ButtonTemplateBuilder(
                                    '1) Date', // กำหนดหัวเรื่อง
                                    'Please select', // กำหนดรายละเอียด
                                    $imageUrl, // กำหนด url รุปภาพ
                                    $actionBuilder  // กำหนด action object
                            )
                        );									
                        break;
					case "mission":
                        // กำหนด action 4 ปุ่ม 4 ประเภท
                        $actionBuilder = array(
                            new PostbackTemplateActionBuilder(
                                'CKT', // ข้อความแสดงในปุ่ม
                                http_build_query(array(
                                    //'action'=>'buy',
                                    //'item'=>100
									'CKT'
                                )), // ข้อมูลที่จะส่งไปใน webhook ผ่าน postback event
                                'CKT'  // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                            ), 
                            new PostbackTemplateActionBuilder(
                                'ISR', // ข้อความแสดงในปุ่ม
                                http_build_query(array(
									'ISR'
                                )), // ข้อมูลที่จะส่งไปใน webhook ผ่าน postback event
                                'ISR'  // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                            ), 
                            new PostbackTemplateActionBuilder(
                                'LD', // ข้อความแสดงในปุ่ม
                                http_build_query(array(
									'LD'
                                )), // ข้อมูลที่จะส่งไปใน webhook ผ่าน postback event
                                'LD'  // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                            ), 					
                        );
						$actionBuilder1 = array(
                            new PostbackTemplateActionBuilder(
                                'ISR&LD', // ข้อความแสดงในปุ่ม
                                http_build_query(array(
                                    //'action'=>'buy',
                                    //'item'=>100
									'ISR_LD'
                                )), // ข้อมูลที่จะส่งไปใน webhook ผ่าน postback event
                                'ISR_LD'  // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                            ), 
                            new PostbackTemplateActionBuilder(
                                'ATF', // ข้อความแสดงในปุ่ม
                                http_build_query(array(
									'ATF'
                                )), // ข้อมูลที่จะส่งไปใน webhook ผ่าน postback event
                                'ATF'  // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                            ), 
                            new PostbackTemplateActionBuilder(
                                'SHK', // ข้อความแสดงในปุ่ม
                                http_build_query(array(
									'SHK'
                                )), // ข้อมูลที่จะส่งไปใน webhook ผ่าน postback event
                                'SHK'  // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                            ), 					
                        );
						$actionBuilder2 = array(
                            new PostbackTemplateActionBuilder(
                                'AT', // ข้อความแสดงในปุ่ม
                                http_build_query(array(
                                    //'action'=>'buy',
                                    //'item'=>100
									'AT'
                                )), // ข้อมูลที่จะส่งไปใน webhook ผ่าน postback event
                                'AT'  // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                            ), 
                            new PostbackTemplateActionBuilder(
                                'TST', // ข้อความแสดงในปุ่ม
                                http_build_query(array(
									'TST'
                                )), // ข้อมูลที่จะส่งไปใน webhook ผ่าน postback event
                                'TST'  // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                            ), 
                            new PostbackTemplateActionBuilder(
                                '-', // ข้อความแสดงในปุ่ม
                                http_build_query(array(
									'-'
                                )), // ข้อมูลที่จะส่งไปใน webhook ผ่าน postback event
                                '-'  // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                            ), 					
                        );
						$actionBuilder3 = array(
                            new PostbackTemplateActionBuilder(
                                '-', // ข้อความแสดงในปุ่ม
                                http_build_query(array(
                                    //'action'=>'buy',
                                    //'item'=>100
									'-'
                                )), // ข้อมูลที่จะส่งไปใน webhook ผ่าน postback event
                                '-'  // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                            ), 
                            new PostbackTemplateActionBuilder(
                                '-', // ข้อความแสดงในปุ่ม
                                http_build_query(array(
									'-'
                                )), // ข้อมูลที่จะส่งไปใน webhook ผ่าน postback event
                                '-'  // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                            ), 
                            new PostbackTemplateActionBuilder(
                                '-', // ข้อความแสดงในปุ่ม
                                http_build_query(array(
									'-'
                                )), // ข้อมูลที่จะส่งไปใน webhook ผ่าน postback event
                                '-'  // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                            ), 					
                        );
                        $replyData = new TemplateMessageBuilder('Carousel',
                            new CarouselTemplateBuilder(
                                array(
                                    new CarouselColumnTemplateBuilder(
                                        '2) Mission',
                                        'Please select',
                                        'https://raw.githubusercontent.com/Thanadon99/linebot-code-example/master/pic/Mission1.jpg',
                                        $actionBuilder
                                    ),
                                    new CarouselColumnTemplateBuilder(
                                        'Mission',
                                        'Please select',
                                        'https://raw.githubusercontent.com/Thanadon99/linebot-code-example/master/pic/BG.jpg',
                                        $actionBuilder1
                                    ),
                                    new CarouselColumnTemplateBuilder(
                                        'Mission',
                                        'Please select',
                                        'https://raw.githubusercontent.com/Thanadon99/linebot-code-example/master/pic/BG.jpg',
                                        $actionBuilder2
                                    ),
									new CarouselColumnTemplateBuilder(
                                        'Mission',
                                        'Please select',
                                        'https://raw.githubusercontent.com/Thanadon99/linebot-code-example/master/pic/BG.jpg',
                                        $actionBuilder3
                                    ),       
                                )
                            )
                        );
                        break;
					case "uavno":
                        // กำหนด action 4 ปุ่ม 4 ประเภท
                        $actionBuilder = array(
                            new PostbackTemplateActionBuilder(
                                '678', // ข้อความแสดงในปุ่ม
                                http_build_query(array(
                                    //'action'=>'buy',
                                    //'item'=>100
									'678'
                                )), // ข้อมูลที่จะส่งไปใน webhook ผ่าน postback event
                                '678'  // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                            ), 
                            new PostbackTemplateActionBuilder(
                                '679', // ข้อความแสดงในปุ่ม
                                http_build_query(array(
									'679'
                                )), // ข้อมูลที่จะส่งไปใน webhook ผ่าน postback event
                                '679'  // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                            ), 
                            new PostbackTemplateActionBuilder(
                                '704', // ข้อความแสดงในปุ่ม
                                http_build_query(array(
									'704'
                                )), // ข้อมูลที่จะส่งไปใน webhook ผ่าน postback event
                                '704'  // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                            ), 					
                        );
						$actionBuilder1 = array(
                            new PostbackTemplateActionBuilder(
                                '705', // ข้อความแสดงในปุ่ม
                                http_build_query(array(
                                    //'action'=>'buy',
                                    //'item'=>100
									'705'
                                )), // ข้อมูลที่จะส่งไปใน webhook ผ่าน postback event
                                '705'  // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                            ), 
                            new PostbackTemplateActionBuilder(
                                '706', // ข้อความแสดงในปุ่ม
                                http_build_query(array(
									'706'
                                )), // ข้อมูลที่จะส่งไปใน webhook ผ่าน postback event
                                '706'  // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                            ), 
                            new PostbackTemplateActionBuilder(
                                '707', // ข้อความแสดงในปุ่ม
                                http_build_query(array(
									'707'
                                )), // ข้อมูลที่จะส่งไปใน webhook ผ่าน postback event
                                '707'  // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                            ), 					
                        );
						$actionBuilder2 = array(
                            new PostbackTemplateActionBuilder(
                                '708', // ข้อความแสดงในปุ่ม
                                http_build_query(array(
                                    //'action'=>'buy',
                                    //'item'=>100
									'708'
                                )), // ข้อมูลที่จะส่งไปใน webhook ผ่าน postback event
                                '708'  // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                            ), 
                            new PostbackTemplateActionBuilder(
                                '-', // ข้อความแสดงในปุ่ม
                                http_build_query(array(
									'-'
                                )), // ข้อมูลที่จะส่งไปใน webhook ผ่าน postback event
                                '-'  // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                            ), 
                            new PostbackTemplateActionBuilder(
                                '-', // ข้อความแสดงในปุ่ม
                                http_build_query(array(
									'-'
                                )), // ข้อมูลที่จะส่งไปใน webhook ผ่าน postback event
                                '-'  // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                            ), 					
                        );
                        $replyData = new TemplateMessageBuilder('Carousel',
                            new CarouselTemplateBuilder(
                                array(
                                    new CarouselColumnTemplateBuilder(
                                        '3) UAV No.',
                                        'Please select',
                                        'https://raw.githubusercontent.com/Thanadon99/linebot-code-example/master/pic/Mission1.jpg',
                                        $actionBuilder
                                    ),
                                    new CarouselColumnTemplateBuilder(
                                        'UAV No.',
                                        'Please select',
                                        'https://raw.githubusercontent.com/Thanadon99/linebot-code-example/master/pic/BG.jpg',
                                        $actionBuilder1
                                    ),
                                    new CarouselColumnTemplateBuilder(
                                        'UAV No.',
                                        'Please select',
                                        'https://raw.githubusercontent.com/Thanadon99/linebot-code-example/master/pic/BG.jpg',
                                        $actionBuilder2
                                    ),    
                                )
                            )
                        );
                        break;
					case "engineno":
                        // กำหนด action 4 ปุ่ม 4 ประเภท
                        $actionBuilder = array(
                            new PostbackTemplateActionBuilder(
                                '08-335', // ข้อความแสดงในปุ่ม
                                http_build_query(array(
									'08-335'
                                )), // ข้อมูลที่จะส่งไปใน webhook ผ่าน postback event
                                '08-335'  // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                            ), 
                            new PostbackTemplateActionBuilder(
                                '08-357', // ข้อความแสดงในปุ่ม
                                http_build_query(array(
									'08-357'
                                )), // ข้อมูลที่จะส่งไปใน webhook ผ่าน postback event
                                '08-357'  // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                            ), 
                            new PostbackTemplateActionBuilder(
                                '10-417', // ข้อความแสดงในปุ่ม
                                http_build_query(array(
									'10-417'
                                )), // ข้อมูลที่จะส่งไปใน webhook ผ่าน postback event
                                '10-417'  // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                            ), 					
                        );
						$actionBuilder1 = array(
                            new PostbackTemplateActionBuilder(
                                '12-452', // ข้อความแสดงในปุ่ม
                                http_build_query(array(
									'12-452'
                                )), // ข้อมูลที่จะส่งไปใน webhook ผ่าน postback event
                                '12-452'  // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                            ), 
                            new PostbackTemplateActionBuilder(
                                '12-455', // ข้อความแสดงในปุ่ม
                                http_build_query(array(
									'12-455'
                                )), // ข้อมูลที่จะส่งไปใน webhook ผ่าน postback event
                                '12-455'  // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                            ), 
                            new PostbackTemplateActionBuilder(
                                '12-466', // ข้อความแสดงในปุ่ม
                                http_build_query(array(
									'12-466'
                                )), // ข้อมูลที่จะส่งไปใน webhook ผ่าน postback event
                                '12-466'  // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                            ), 					
                        );
						$actionBuilder2 = array(
                            new PostbackTemplateActionBuilder(
                                '12-467', // ข้อความแสดงในปุ่ม
                                http_build_query(array(
									'12-467'
                                )), // ข้อมูลที่จะส่งไปใน webhook ผ่าน postback event
                                '12-467'  // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                            ), 
                            new PostbackTemplateActionBuilder(
                                '12-469', // ข้อความแสดงในปุ่ม
                                http_build_query(array(
									'12-469'
                                )), // ข้อมูลที่จะส่งไปใน webhook ผ่าน postback event
                                '12-469'  // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                            ), 
                            new PostbackTemplateActionBuilder(
                                '12-473', // ข้อความแสดงในปุ่ม
                                http_build_query(array(
									'12-473'
                                )), // ข้อมูลที่จะส่งไปใน webhook ผ่าน postback event
                                '12-473'  // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                            ), 					
                        );
						$actionBuilder3 = array(
                            new PostbackTemplateActionBuilder(
                                '13-480', // ข้อความแสดงในปุ่ม
                                http_build_query(array(
									'13480'
                                )), // ข้อมูลที่จะส่งไปใน webhook ผ่าน postback event
                                '13-480'  // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                            ), 
                            new PostbackTemplateActionBuilder(
                                '13-481', // ข้อความแสดงในปุ่ม
                                http_build_query(array(
									'13-481'
                                )), // ข้อมูลที่จะส่งไปใน webhook ผ่าน postback event
                                '13-481'  // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                            ), 
                            new PostbackTemplateActionBuilder(
                                '13-482', // ข้อความแสดงในปุ่ม
                                http_build_query(array(
									'13-482'
                                )), // ข้อมูลที่จะส่งไปใน webhook ผ่าน postback event
                                '13-482'  // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                            ), 					
                        );
						$actionBuilder4 = array(
                            new PostbackTemplateActionBuilder(
                                '13-483', // ข้อความแสดงในปุ่ม
                                http_build_query(array(
									'13-483'
                                )), // ข้อมูลที่จะส่งไปใน webhook ผ่าน postback event
                                '13-483'  // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                            ), 
                            new PostbackTemplateActionBuilder(
                                '13-484', // ข้อความแสดงในปุ่ม
                                http_build_query(array(
									'13-484'
                                )), // ข้อมูลที่จะส่งไปใน webhook ผ่าน postback event
                                '13-484'  // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                            ), 
                            new PostbackTemplateActionBuilder(
                                '-', // ข้อความแสดงในปุ่ม
                                http_build_query(array(
									'-'
                                )), // ข้อมูลที่จะส่งไปใน webhook ผ่าน postback event
                                '-'  // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                            ), 					
                        );
                        $replyData = new TemplateMessageBuilder('Carousel',
                            new CarouselTemplateBuilder(
                                array(
                                    new CarouselColumnTemplateBuilder(
                                        '4) Engine No.',
                                        'Please select',
                                        'https://raw.githubusercontent.com/Thanadon99/linebot-code-example/master/pic/Mission1.jpg',
                                        $actionBuilder
                                    ),
                                    new CarouselColumnTemplateBuilder(
                                        'Engine No.',
                                        'Please select',
                                        'https://raw.githubusercontent.com/Thanadon99/linebot-code-example/master/pic/BG.jpg',
                                        $actionBuilder1
                                    ),
                                    new CarouselColumnTemplateBuilder(
                                        'Engine No.',
                                        'Please select',
                                        'https://raw.githubusercontent.com/Thanadon99/linebot-code-example/master/pic/BG.jpg',
                                        $actionBuilder2
                                    ),
									new CarouselColumnTemplateBuilder(
                                        'Engine No.',
                                        'Please select',
                                        'https://raw.githubusercontent.com/Thanadon99/linebot-code-example/master/pic/BG.jpg',
                                        $actionBuilder3
                                    ), 
									new CarouselColumnTemplateBuilder(
                                        'Engine No.',
                                        'Please select',
                                        'https://raw.githubusercontent.com/Thanadon99/linebot-code-example/master/pic/BG.jpg',
                                        $actionBuilder4
                                    ), 
                                )
                            )
                        );
                        break;
					case "gcsno":
                        // กำหนด action 4 ปุ่ม 4 ประเภท
                        $actionBuilder = array(
                            new PostbackTemplateActionBuilder(
                                '26', // ข้อความแสดงในปุ่ม
                                http_build_query(array(
                                    //'action'=>'buy',
                                    //'item'=>100
									'26'
                                )), // ข้อมูลที่จะส่งไปใน webhook ผ่าน postback event
                                '26'  // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                            ), 
                            new PostbackTemplateActionBuilder(
                                '27', // ข้อความแสดงในปุ่ม
                                http_build_query(array(
									'27'
                                )), // ข้อมูลที่จะส่งไปใน webhook ผ่าน postback event
                                '27'  // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                            ), 
                            new PostbackTemplateActionBuilder(
                                '-', // ข้อความแสดงในปุ่ม
                                http_build_query(array(
									'-'
                                )), // ข้อมูลที่จะส่งไปใน webhook ผ่าน postback event
                                '-'  // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                            ), 					
                        );
                        $replyData = new TemplateMessageBuilder('Carousel',
                            new CarouselTemplateBuilder(
                                array(
                                    new CarouselColumnTemplateBuilder(
                                        '5) GCS No.',
                                        'Please select',
                                        'https://raw.githubusercontent.com/Thanadon99/linebot-code-example/master/pic/Mission1.jpg',
                                        $actionBuilder
                                    ),                             
                                )
                            )
                        );
                        break;
					case "payload":
                        // กำหนด action 4 ปุ่ม 4 ประเภท
                        $actionBuilder = array(
                            new PostbackTemplateActionBuilder(
                                'LDH324', // ข้อความแสดงในปุ่ม
                                http_build_query(array(
									'LDH324'
                                )) // ข้อมูลที่จะส่งไปใน webhook ผ่าน postback event
                               // 'LDH324'  // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                            ), 
                            new PostbackTemplateActionBuilder(
                                'LDH326', // ข้อความแสดงในปุ่ม
                                http_build_query(array(
									'LDH326'
                                )) // ข้อมูลที่จะส่งไปใน webhook ผ่าน postback event
                                //'LDH326'  // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                            ), 
                            new PostbackTemplateActionBuilder(
                                'QUAD', // ข้อความแสดงในปุ่ม
                                http_build_query(array(
									'QUAD'
                                )) // ข้อมูลที่จะส่งไปใน webhook ผ่าน postback event
                                //'QUAD'  // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                            ), 					
                        );
						$actionBuilder1 = array(
                            new PostbackTemplateActionBuilder(
                                'SAR', // ข้อความแสดงในปุ่ม
                                http_build_query(array(
									'SAR'
                                )) // ข้อมูลที่จะส่งไปใน webhook ผ่าน postback event
                                //'SAR'  // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                            ), 
                            new PostbackTemplateActionBuilder(
                                'COMMINT', // ข้อความแสดงในปุ่ม
                                http_build_query(array(
									'COMMINT'
                                )) // ข้อมูลที่จะส่งไปใน webhook ผ่าน postback event
                                //'COMMINT'  // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                            ), 
                            new PostbackTemplateActionBuilder(
                                'CCD_PW', // ข้อความแสดงในปุ่ม
                                http_build_query(array(
									'CCD_PW'
                                )) // ข้อมูลที่จะส่งไปใน webhook ผ่าน postback event
                                //'CCD_PW'  // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                            ), 					
                        );
						$actionBuilder2 = array(
                            new PostbackTemplateActionBuilder(
                                'Flir', // ข้อความแสดงในปุ่ม
                                http_build_query(array(
									'Flir'
                                )) // ข้อมูลที่จะส่งไปใน webhook ผ่าน postback event
                                //'Flir'  // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                            ), 
                            new PostbackTemplateActionBuilder(
                                '-', // ข้อความแสดงในปุ่ม
                                http_build_query(array(
									'-'
                                )) // ข้อมูลที่จะส่งไปใน webhook ผ่าน postback event
                                //'-'  // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                            ), 
                            new PostbackTemplateActionBuilder(
                                '-', // ข้อความแสดงในปุ่ม
                                http_build_query(array(
									'-'
                                )) // ข้อมูลที่จะส่งไปใน webhook ผ่าน postback event
                                //'-'  // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                            ), 					
                        );
                        $replyData = new TemplateMessageBuilder('Carousel',
                            new CarouselTemplateBuilder(
                                array(
                                    new CarouselColumnTemplateBuilder(
                                        '6) Payload',
                                        'Please select',
                                        'https://raw.githubusercontent.com/Thanadon99/linebot-code-example/master/pic/Mission1.jpg',
                                        $actionBuilder
                                    ),  
									new CarouselColumnTemplateBuilder(
                                        'Payload',
                                        'Please select',
                                        'https://raw.githubusercontent.com/Thanadon99/linebot-code-example/master/pic/BG.jpg',
                                        $actionBuilder1
                                    ), 
									new CarouselColumnTemplateBuilder(
                                        'Payload',
                                        'Please select',
                                        'https://raw.githubusercontent.com/Thanadon99/linebot-code-example/master/pic/BG.jpg',
                                        $actionBuilder2
                                    ), 
                                )
                            )
                        );
                        break;
					case "fuel_qty":
                        $textReplyMessage = "7) Fuel Qty = ?";
                        $replyData = new TextMessageBuilder($textReplyMessage);
                        break;
					case "fuel_remain":
                        $textReplyMessage = "8) Fuel Remain = ?";
                        $replyData = new TextMessageBuilder($textReplyMessage);
                        break;
					case "time_start":
                        $actionBuilder = array(
                            new DatetimePickerTemplateActionBuilder(
								'Time Picker', // ข้อความแสดงในปุ่ม
								http_build_query(array(
									'action'=>'reservation',
									'person'=>5
								)), // ข้อมูลที่จะส่งไปใน webhook ผ่าน postback event
								'time' // date | time | datetime รูปแบบข้อมูลที่จะส่ง ในที่นี้ใช้ datatime
							),
							new PostbackTemplateActionBuilder(
                                '-', // ข้อความแสดงในปุ่ม
                                http_build_query(array(
									'-'
                                )) // ข้อมูลที่จะส่งไปใน webhook ผ่าน postback event
                               // 'LDH324'  // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                            ),
                        );
                        $imageUrl = 'https://raw.githubusercontent.com/Thanadon99/linebot-code-example/master/pic/time.jpg';
                        $replyData = new TemplateMessageBuilder('Button Template',
                            new ButtonTemplateBuilder(
                                    '9) Start', // กำหนดหัวเรื่อง
                                    'Please select', // กำหนดรายละเอียด
                                    $imageUrl, // กำหนด url รุปภาพ
                                    $actionBuilder  // กำหนด action object
                            )
                        );									
                        break;
					case "time_to":
                        $actionBuilder = array(
                            new DatetimePickerTemplateActionBuilder(
								'Time Picker', // ข้อความแสดงในปุ่ม
								http_build_query(array(
									'action'=>'reservation',
									'person'=>5
								)), // ข้อมูลที่จะส่งไปใน webhook ผ่าน postback event
								'time' // date | time | datetime รูปแบบข้อมูลที่จะส่ง ในที่นี้ใช้ datatime
							),
							new PostbackTemplateActionBuilder(
                                '-', // ข้อความแสดงในปุ่ม
                                http_build_query(array(
									'-'
                                )) // ข้อมูลที่จะส่งไปใน webhook ผ่าน postback event
                               // 'LDH324'  // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                            ),
                        );
                        $imageUrl = 'https://raw.githubusercontent.com/Thanadon99/linebot-code-example/master/pic/time.jpg';
                        $replyData = new TemplateMessageBuilder('Button Template',
                            new ButtonTemplateBuilder(
                                    '10) Take Off', // กำหนดหัวเรื่อง
                                    'Please select', // กำหนดรายละเอียด
                                    $imageUrl, // กำหนด url รุปภาพ
                                    $actionBuilder  // กำหนด action object
                            )
                        );									
                        break;
					case "time_shutdown":
                        $actionBuilder = array(
                            new DatetimePickerTemplateActionBuilder(
								'Time Picker', // ข้อความแสดงในปุ่ม
								http_build_query(array(
									'action'=>'reservation',
									'person'=>5
								)), // ข้อมูลที่จะส่งไปใน webhook ผ่าน postback event
								'time' // date | time | datetime รูปแบบข้อมูลที่จะส่ง ในที่นี้ใช้ datatime
							),
							new PostbackTemplateActionBuilder(
                                '-', // ข้อความแสดงในปุ่ม
                                http_build_query(array(
									'-'
                                )) // ข้อมูลที่จะส่งไปใน webhook ผ่าน postback event
                               // 'LDH324'  // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                            ),
                        );
                        $imageUrl = 'https://raw.githubusercontent.com/Thanadon99/linebot-code-example/master/pic/time.jpg';
                        $replyData = new TemplateMessageBuilder('Button Template',
                            new ButtonTemplateBuilder(
                                    '11) Shutdown', // กำหนดหัวเรื่อง
                                    'Please select', // กำหนดรายละเอียด
                                    $imageUrl, // กำหนด url รุปภาพ
                                    $actionBuilder  // กำหนด action object
                            )
                        );									
                        break;
					case "time_total":
                        $actionBuilder = array(
                            new DatetimePickerTemplateActionBuilder(
								'Time Picker', // ข้อความแสดงในปุ่ม
								http_build_query(array(
									'action'=>'reservation',
									'person'=>5
								)), // ข้อมูลที่จะส่งไปใน webhook ผ่าน postback event
								'time' // date | time | datetime รูปแบบข้อมูลที่จะส่ง ในที่นี้ใช้ datatime
							),
							new PostbackTemplateActionBuilder(
                                '-', // ข้อความแสดงในปุ่ม
                                http_build_query(array(
									'-'
                                )) // ข้อมูลที่จะส่งไปใน webhook ผ่าน postback event
                               // 'LDH324'  // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                            ),
                        );
                        $imageUrl = 'https://raw.githubusercontent.com/Thanadon99/linebot-code-example/master/pic/time.jpg';
                        $replyData = new TemplateMessageBuilder('Button Template',
                            new ButtonTemplateBuilder(
                                    '12) Total', // กำหนดหัวเรื่อง
                                    'Please select', // กำหนดรายละเอียด
                                    $imageUrl, // กำหนด url รุปภาพ
                                    $actionBuilder  // กำหนด action object
                            )
                        );									
                        break;
					case "time_uav":
                        $textReplyMessage = "13) UAV hr. = ?";
                        $replyData = new TextMessageBuilder($textReplyMessage);
                        break;
					case "time_engine":
                        $textReplyMessage = "14) Engine hr. = ?";
                        $replyData = new TextMessageBuilder($textReplyMessage);
                        break;
					case "fuel_cart_17":
                        $textReplyMessage = "15) Fuel Cart 17 remain = ?";
                        $replyData = new TextMessageBuilder($textReplyMessage);
                        break;
					case "fuel_cart_32":
                        $textReplyMessage = "16) Fuel Cart 32 remain = ?";
                        $replyData = new TextMessageBuilder($textReplyMessage);
                        break;
					case "flight_abort":
                        // กำหนด action 4 ปุ่ม 4 ประเภท
                        $actionBuilder = array(
                            new PostbackTemplateActionBuilder(
                                '-', // ข้อความแสดงในปุ่ม
                                http_build_query(array(
									'-'
                                )) // ข้อมูลที่จะส่งไปใน webhook ผ่าน postback event
                                //'26'  // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                            ), 
                            new PostbackTemplateActionBuilder(
                                'Ground Abort', // ข้อความแสดงในปุ่ม
                                http_build_query(array(
									'Ground Abort'
                                )) // ข้อมูลที่จะส่งไปใน webhook ผ่าน postback event
                                //'27'  // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                            ), 
                            new PostbackTemplateActionBuilder(
                                'Air Abort', // ข้อความแสดงในปุ่ม
                                http_build_query(array(
									'Air Abort'
                                )) // ข้อมูลที่จะส่งไปใน webhook ผ่าน postback event
                                //'-'  // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                            ), 					
                        );
                        $replyData = new TemplateMessageBuilder('Carousel',
                            new CarouselTemplateBuilder(
                                array(
                                    new CarouselColumnTemplateBuilder(
                                        '17) Flight Abort',
                                        'Please select',
                                        'https://raw.githubusercontent.com/Thanadon99/linebot-code-example/master/pic/Mission1.jpg',
                                        $actionBuilder
                                    ),                             
                                )
                            )
                        );
                        break;
					case "if_trouble":
                        $replyData = new TemplateMessageBuilder('Confirm Template',
                            new ConfirmTemplateBuilder(
                                    '18) Is there trouble?',
                                    array(
                                        new MessageTemplateActionBuilder(
                                            'Yes',
                                            'flight_trouble1'
                                        ),
                                        new MessageTemplateActionBuilder(
                                            'No',
                                            'ct'
                                        )
                                    )
                            )
                        );
                        break;	
					case "flight_trouble1":
                        $textReplyMessage = "18) Trouble (1) = ?";
                        $replyData = new TextMessageBuilder($textReplyMessage);
                        break;
					case "flight_repairable1":
                        $textReplyMessage = "19) Repairable (1) = ?";
                        $replyData = new TextMessageBuilder($textReplyMessage);
                        break;
					case "flight_item1":
                        $textReplyMessage = "20) Item (1) = ?";
                        $replyData = new TextMessageBuilder($textReplyMessage);
                        break;
					case "flight_sn1":
                        $textReplyMessage = "21) S/N (1) = ?";
                        $replyData = new TextMessageBuilder($textReplyMessage);
                        break;
					case "ct":
                        // กำหนด action 4 ปุ่ม 4 ประเภท
                        $actionBuilder = array(
                            new PostbackTemplateActionBuilder(
                                'Boy', // ข้อความแสดงในปุ่ม
                                http_build_query(array(
									'Boy'
                                )) // ข้อมูลที่จะส่งไปใน webhook ผ่าน postback event
                                //'26'  // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                            ), 
                            new PostbackTemplateActionBuilder(
                                'Bin', // ข้อความแสดงในปุ่ม
                                http_build_query(array(
									'Bin'
                                )) // ข้อมูลที่จะส่งไปใน webhook ผ่าน postback event
                                //'27'  // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                            ), 
                            new PostbackTemplateActionBuilder(
                                'Moo', // ข้อความแสดงในปุ่ม
                                http_build_query(array(
									'Moo'
                                )) // ข้อมูลที่จะส่งไปใน webhook ผ่าน postback event
                                //'-'  // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                            ), 					
                        );
						$actionBuilder1 = array(
                            new PostbackTemplateActionBuilder(
                                'Kan', // ข้อความแสดงในปุ่ม
                                http_build_query(array(
									'Kan'
                                )) // ข้อมูลที่จะส่งไปใน webhook ผ่าน postback event
                                //'26'  // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                            ), 
                            new PostbackTemplateActionBuilder(
                                'Snack', // ข้อความแสดงในปุ่ม
                                http_build_query(array(
									'Snack'
                                )) // ข้อมูลที่จะส่งไปใน webhook ผ่าน postback event
                                //'27'  // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                            ), 
                            new PostbackTemplateActionBuilder(
                                'SandStorm', // ข้อความแสดงในปุ่ม
                                http_build_query(array(
									'SandStorm'
                                )) // ข้อมูลที่จะส่งไปใน webhook ผ่าน postback event
                                //'-'  // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                            ), 					
                        );
                        $replyData = new TemplateMessageBuilder('Carousel',
                            new CarouselTemplateBuilder(
                                array(
                                    new CarouselColumnTemplateBuilder(
                                        '22) Chief Tech',
                                        'Please select',
                                        'https://raw.githubusercontent.com/Thanadon99/linebot-code-example/master/pic/Mission1.jpg',
                                        $actionBuilder
                                    ),
									new CarouselColumnTemplateBuilder(
                                        'Chief Tech',
                                        'Please select',
                                        'https://raw.githubusercontent.com/Thanadon99/linebot-code-example/master/pic/BG.jpg',
                                        $actionBuilder1
                                    ), 
                                )
                            )
                        );
						$myfile = fopen("x.txt", "r+") or die("Unable to open file!");
						$x=(fgets($myfile));
						fclose($myfile);
						
						if ("$x" == "17")
							{
							$myfile = fopen("x.txt", "w") or die("Unable to open file!");
							fwrite($myfile, 21);
							fclose($myfile);
							
							$myfile = fopen("abc.txt", "a+") or die("Unable to open file!");
							$strText1 = "\r\nTrouble (1) =-";
							$strText1.= "\r\nRepairable (1) =-";
							$strText1.= "\r\nItem (1) =-";
							$strText1.= "\r\nS/N (1) =-";
							fwrite($myfile, $strText1);
							fclose($myfile);
							}
                        break;
					// ส่วนการเรียกชื่อบอท	
					case "p":
                        // เรียกดูข้อมูลโพรไฟล์ของ Line user โดยส่งค่า userID ของผู้ใช้ LINE ไปดึงข้อมูล
                        $response = $bot->getProfile($userID);
                        if ($response->isSucceeded()) {
                            // ดึงค่ามาแบบเป็น JSON String โดยใช้คำสั่ง getRawBody() กรณีเป้นข้อความ text
                            $textReplyMessage = $response->getRawBody(); // return string            
                            $replyData = new TextMessageBuilder($textReplyMessage);         
                            break;              
                        }
                        // กรณีไม่สามารถดึงข้อมูลได้ ให้แสดงสถานะ และข้อมูลแจ้ง ถ้าไม่ต้องการแจ้งก็ปิดส่วนนี้ไปก็ได้
                        $failMessage = json_encode($response->getHTTPStatus() . ' ' . $response->getRawBody());
                        $replyData = new TextMessageBuilder($failMessage);
                        break;              
                    case "บอท":
                        // เรียกดูข้อมูลโพรไฟล์ของ Line user โดยส่งค่า userID ของผู้ใช้ LINE ไปดึงข้อมูล
                        $response = $bot->getProfile($userID);
                        if ($response->isSucceeded()) {
                            // ดึงค่าโดยแปลจาก JSON String .ให้อยู่ใรูปแบบโครงสร้าง ตัวแปร array 
                            $userData = $response->getJSONDecodedBody(); // return array     
                            // $userData['userId']
                            // $userData['displayName']
                            // $userData['pictureUrl']
                            // $userData['statusMessage']
                            $textReplyMessage = 'สวัสดีครับ คุณ '.$userData['displayName'];
							$textReplyMessage.= "\r\nสามารถ กด Mission หรือ Maintenace เพื่อพิมพ์รายงานตามลำดับขั้นตอนได้เลยครับ";
   							//$textReplyMessage.= "\r\nรายงานบิน";
							//$textReplyMessage.= "\r\nรายงานซ่อม";
							
                            $replyData = new TextMessageBuilder($textReplyMessage);         
                            break;              
                        }
                        // กรณีไม่สามารถดึงข้อมูลได้ ให้แสดงสถานะ และข้อมูลแจ้ง ถ้าไม่ต้องการแจ้งก็ปิดส่วนนี้ไปก็ได้
                        $failMessage = json_encode($response->getHTTPStatus() . ' ' . $response->getRawBody());
                        $replyData = new TextMessageBuilder($failMessage);
                        break; 
					case "รายงานค่า":
						$data=file('abc.txt');
						for($i=0;$i<count($data);$i++){
							$textReplyMessage = $data[0];
							$textReplyMessage.= $data[1];
							$textReplyMessage.= $data[2];
							$textReplyMessage.= $data[3];
							$textReplyMessage.= $data[4];
							$textReplyMessage.= $data[5];
							$textReplyMessage.= $data[6];
							$textReplyMessage.= $data[7];
							$textReplyMessage.= $data[8];
							$textReplyMessage.= $data[9];
							$textReplyMessage.= $data[10];
							$textReplyMessage.= $data[11];
							$textReplyMessage.= $data[12];
							$textReplyMessage.= $data[13];
							$textReplyMessage.= $data[14];
							$textReplyMessage.= $data[15];
							$textReplyMessage.= $data[16];
							$textReplyMessage.= $data[17];
							$textReplyMessage.= $data[18];
							$textReplyMessage.= $data[19];
							$textReplyMessage.= $data[20];
							$textReplyMessage.= $data[21];
							$textReplyMessage.= $data[22];
						}
						$replyData = new TextMessageBuilder($textReplyMessage);
						break;
					case "x=?":
						$data=file('x.txt');
						for($i=0;$i<count($data);$i++){
							$textReplyMessage = $data[0];
							$textReplyMessage.= $data[1];
							$textReplyMessage.= $data[2];
						}
						$replyData = new TextMessageBuilder($textReplyMessage);
						break;
					
                    default:
                        //$textReplyMessage = " คุณไม่ได้พิมพ์ ค่า ตามที่กำหนด";
                        //$replyData = new TextMessageBuilder($textReplyMessage);   						
                        break;                                      
                }
                break;
            default:
                $textReplyMessage = json_encode($events);
                $replyData = new TextMessageBuilder($textReplyMessage);         
                break;  
        }
    }
}
$response = $bot->replyMessage($replyToken,$replyData);
if ($response->isSucceeded()) {
    echo 'Succeeded!';
    return;
}
 
// Failed
echo $response->getHTTPStatus() . ' ' . $response->getRawBody();
Function calculate($postdata)
{
		$myfile = fopen("x.txt", "r+") or die("Unable to open file!");
		$x=(fgets($myfile));
		fclose($myfile);
			if ($x<"1")
			{
				$is_message = 1;
				$typeMessage = 'text';
				$userMessage = "mission";
				$M_date = substr($postdata,8,2);
				$M_month =substr($postdata,5,2);
				$M_year = substr($postdata,0,4);
				$pushdata = "Date =".$M_date;
				$pushdata.= "/".$M_month;
				$pushdata.= "/".$M_year;
			}
			elseif ($x<"2")
			{
				$is_message = 1;
				$typeMessage = 'text';
				$userMessage = "uavno";
				//$pushdata = "\r\nMission =".substr($postdata,2,20);
				$pushdata = "\r\nMission =".$postdata;
			}
			elseif ($x<"3") 
			{
				$is_message = 1;
				$typeMessage = 'text';
				$userMessage = "engineno";
				//$pushdata = "\r\nUAV No. =".substr($postdata,2,20);
				$pushdata = "\r\nUAV No. =".$postdata;
			}
			elseif ($x<"4") 
			{
				$is_message = 1;
				$typeMessage = 'text';
				$userMessage = "gcsno";
				//$pushdata = "\r\nEngine No. =".substr($postdata,2,20);
				$pushdata = "\r\nEngine No. =".$postdata;
			}
			elseif ($x<"5")
			{
				$is_message = 1;
				$typeMessage = 'text';
				$userMessage = "payload";
				//$pushdata = "\r\nGCS No. =".substr($postdata,2,20);
				$pushdata = "\r\nGCS No. =".$postdata;
			}
			elseif ($x<"6")
			{
				$is_message = 1;
				$typeMessage = 'text';
				$userMessage = "fuel_qty";
				//$pushdata = "\r\nPayload =".substr($postdata,2,20);
				$pushdata = "\r\nPayload =".$postdata;
			}
			elseif ($x<"7")
			{
				$is_message = 1;
				$typeMessage = 'text';
				$userMessage = "fuel_remain";
				$pushdata = "\r\nFuel Qty =".$postdata;
			}
			elseif ($x<"8")
			{
				$is_message = 1;
				$typeMessage = 'text';
				$userMessage = "time_start";
				$pushdata = "\r\nFuel Remain =".$postdata;
			}
			elseif ($x<"9")
			{
				$is_message = 1;
				$typeMessage = 'text';
				$userMessage = "time_to";
				$pushdata = "\r\nStart =".$postdata;
			}
			elseif ($x<"10")
			{
				$is_message = 1;
				$typeMessage = 'text';
				$userMessage = "time_shutdown";
				$pushdata = "\r\nTake Off =".$postdata;
			}
			elseif ($x<"11")
			{
				$is_message = 1;
				$typeMessage = 'text';
				$userMessage = "time_total";
				$pushdata = "\r\nShutdown =".$postdata;
			}
			elseif ($x<"12")
			{
				$is_message = 1;
				$typeMessage = 'text';
				$userMessage = "time_uav";
				$pushdata = "\r\nTotal =".$postdata;
			}
			elseif ($x<"13")
			{
				$is_message = 1;
				$typeMessage = 'text';
				$userMessage = "time_engine";
				$pushdata = "\r\nUAV hr. =".$postdata;
			}
			elseif ($x<"14")
			{
				$is_message = 1;
				$typeMessage = 'text';
				$userMessage = "fuel_cart_17";
				$pushdata = "\r\nEngine hr. =".$postdata;
			}
			elseif ($x<"15")
			{
				$is_message = 1;
				$typeMessage = 'text';
				$userMessage = "fuel_cart_32";
				$pushdata = "\r\nFuel Cart 17 remain =".$postdata;
			}
			elseif ($x<"16")
			{
				$is_message = 1;
				$typeMessage = 'text';
				$userMessage = "flight_abort";
				$pushdata = "\r\nFuel Cart 32 remain =".$postdata;
			}
			elseif ($x<"17")
			{
				$is_message = 1;
				$typeMessage = 'text';
				$userMessage = "if_trouble";
				//$pushdata = "\r\nAbort =".substr($postdata,2,20);
				$pushdata = "\r\nAbort =".$postdata;
			}
			elseif ($x<"18")
			{
				$is_message = 1;
				$typeMessage = 'text';
				$userMessage = "flight_repairable1";
				$pushdata = "\r\nTrouble (1) =".$postdata;
			}
			elseif ($x<"19")
			{
				$is_message = 1;
				$typeMessage = 'text';
				$userMessage = "flight_item1";
				$pushdata = "\r\nRepairable (1) =".$postdata;
			}
			elseif ($x<"20")
			{
				$is_message = 1;
				$typeMessage = 'text';
				$userMessage = "flight_sn1";
				$pushdata = "\r\nItem (1) =".$postdata;
			}
			elseif ($x<"21")
			{
				$is_message = 1;
				$typeMessage = 'text';
				$userMessage = "ct";
				$pushdata = "\r\nS/N (1) =".$postdata;
			}
			elseif ($x<"22")
			{
				$is_message = 1;
				$typeMessage = 'text';
				$userMessage = "รายงานค่า";
				//$pushdata = "\r\nCT =".substr($postdata,2,20);
				$pushdata = "\r\nCT =".$postdata;
			}
			
		$myfile = fopen("x.txt", "w") or die("Unable to open file!");
			if ("$x"<"22")
			{
				fwrite($myfile, $x+1);
			}
			else
			{
				fwrite($myfile, $x-22);
			}
		fclose($myfile);
		$myfile = fopen("abc.txt", "a+") or die("Unable to open file!");
		fwrite($myfile, $pushdata);
		fclose($myfile);
		
		$result = array($is_message,$typeMessage,$userMessage);
		return $result;		
}
?>