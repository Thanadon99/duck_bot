<?php
 
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
//สร้างตัวแปร
$DateUAV = NULL;
$MissionUAV = NULL;
$UAV = NULL;
$Engine = NULL;
$GCS = NULL;
$Payload = NULL;
$Fuel_Qty = NULL;
$Fuel_Remain = NULL;
$Start = NULL;
$Takeoff = NULL;
$Shutdown = NULL;
$Total = NULL;
$UAV_hr = NULL;
$Engine_hr = NULL;
$Fuel_Cart17 = NULL;
$Fuel_Cart32 = NULL;
$Abort = NULL;
$Trouble = NULL;
$Repairable = NULL;
$Item = NULL;
$Serial = NULL;
$CT = NULL;
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
        }*/
        if(!is_null($paramPostback)){
			
		$get_result = calculate($paramPostback);
		$is_message = $get_result[0];
		$typeMessage = $get_result[1];
		$userMessage = $get_result[2];
		
            $textReplyMessage.= " \r\nParams = ".$paramPostback;
			$textReplyMessage.= "\r\nBot ตอบกลับคุณเป็นข้อความ".$is_message;
			$textReplyMessage.= "\r\nข้อความยาวๆๆๆ".$typeMessage;
			$textReplyMessage.= "\r\nข้อความยาวๆๆๆxตัวบน ".$userMessage;
			
        }
        $replyData = new TextMessageBuilder($textReplyMessage); 		
    }
	$myfile = fopen("x.txt", "r+") or die("Unable to open file!");
	$x=(fgets($myfile));
	fclose($myfile);
	if ($userMessage = 'CKT'){
		$get_result = calculate($userMessage);
		//$is_message = $get_result[0];
		//$typeMessage = $get_result[1];
		$userMessage = $get_result[2];
	}
		$myfile = fopen("abc.txt", "a+") or die("Unable to open file!");
		fwrite($myfile, $userMessage);
		fclose($myfile);


    if(!is_null($is_message)){
        switch ($typeMessage){
            case 'text':
                $userMessage = strtolower($userMessage); // แปลงเป็นตัวเล็ก สำหรับทดสอบ
                switch ($userMessage) {
                    case "t":
                        $textReplyMessage = "Bot ตอบกลับคุณเป็นข้อความ".$DateUAV;
						$textReplyMessage.= "\r\nข้อความยาวๆๆๆ".$MissionUAV;
                        $replyData = new TextMessageBuilder($textReplyMessage);
                        break;
                    case "i":
                        $picFullSize = 'https://www.mywebsite.com/imgsrc/photos/f/simpleflower';
                        $picThumbnail = 'https://www.mywebsite.com/imgsrc/photos/f/simpleflower/240';
                        $replyData = new ImageMessageBuilder($picFullSize,$picThumbnail);
                        break;
                    case "v":
                        $picThumbnail = 'https://www.mywebsite.com/imgsrc/photos/f/sampleimage/240';
                        $videoUrl = "https://www.ninenik.com/line/simplevideo.mp4";             
                        $replyData = new VideoMessageBuilder($videoUrl,$picThumbnail);
                        break;
                    case "a":
                        $audioUrl = "https://www.ninenik.com/line/S_6988827932080.wav";
                        $replyData = new AudioMessageBuilder($audioUrl,20000);
                        break;
                    case "l":
                        $placeName = "ที่ตั้งร้าน";
                        $placeAddress = "แขวง พลับพลา เขต วังทองหลาง กรุงเทพมหานคร ประเทศไทย";
                        $latitude = 13.780401863217657;
                        $longitude = 100.61141967773438;
                        $replyData = new LocationMessageBuilder($placeName, $placeAddress, $latitude ,$longitude);              
                        break;
                    case "m":
                        $textReplyMessage = "Bot ตอบกลับคุณเป็นข้อความ";
                        $textMessage = new TextMessageBuilder($textReplyMessage);
                                         
                        $picFullSize = 'https://www.mywebsite.com/imgsrc/photos/f/simpleflower';
                        $picThumbnail = 'https://www.mywebsite.com/imgsrc/photos/f/simpleflower/240';
                        $imageMessage = new ImageMessageBuilder($picFullSize,$picThumbnail);
                                         
                        $placeName = "ที่ตั้งร้าน";
                        $placeAddress = "แขวง พลับพลา เขต วังทองหลาง กรุงเทพมหานคร ประเทศไทย";
                        $latitude = 13.780401863217657;
                        $longitude = 100.61141967773438;
                        $locationMessage = new LocationMessageBuilder($placeName, $placeAddress, $latitude ,$longitude);        
     
                        $multiMessage =     new MultiMessageBuilder;
                        $multiMessage->add($textMessage);
                        $multiMessage->add($imageMessage);
                        $multiMessage->add($locationMessage);
                        $replyData = $multiMessage;                                     
                        break;                  
                    case "s":
                        $stickerID = 22;
                        $packageID = 2;
                        $replyData = new StickerMessageBuilder($packageID,$stickerID);
                        break;      
                    case "im":
                        $imageMapUrl = 'https://www.mywebsite.com/imgsrc/photos/w/sampleimagemap';
                        $replyData = new ImagemapMessageBuilder(
                            $imageMapUrl,
                            'This is Title',
                            new BaseSizeBuilder(699,1040),
                            array(
                                new ImagemapMessageActionBuilder(
                                    'test image map',
                                    new AreaBuilder(0,0,520,699)
                                    ),
                                new ImagemapUriActionBuilder(
                                    'http://www.ninenik.com',
                                    new AreaBuilder(520,0,520,699)
                                    )
                            )); 
                        break;          
                    case "tm":
                        $replyData = new TemplateMessageBuilder('Confirm Template',
                            new ConfirmTemplateBuilder(
                                    'Confirm template builder',
                                    array(
                                        new MessageTemplateActionBuilder(
                                            'Yes',
                                            'Text Yes'
                                        ),
                                        new MessageTemplateActionBuilder(
                                            'No',
                                            'Text NO'
                                        )
                                    )
                            )
                        );
                        break;          
                    case "ทดสอบ":
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
                                )) // ข้อมูลที่จะส่งไปใน webhook ผ่าน postback event
         //                     'Postback Text'  // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
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
                    case "t_f":
                        $replyData = new TemplateMessageBuilder('Confirm Template',
                            new ConfirmTemplateBuilder(
                                    'Confirm template builder', // ข้อความแนะนหรือบอกวิธีการ หรือคำอธิบาย
                                    array(
                                        new MessageTemplateActionBuilder(
                                            'Yes', // ข้อความสำหรับปุ่มแรก
                                            'YES'  // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                                        ),
                                        new MessageTemplateActionBuilder(
                                            'No', // ข้อความสำหรับปุ่มแรก
                                            'NO' // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                                        )
                                    )
                            )
                        );
                        break;      
                    case "t_c":
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
                            new PostbackTemplateActionBuilder(
                                'Postback', // ข้อความแสดงในปุ่ม
                                http_build_query(array(
                                    'action'=>'buy',
                                    'item'=>100
                                )), // ข้อมูลที่จะส่งไปใน webhook ผ่าน postback event
                                'Postback Text'  // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                            ),      
                        );
                        $replyData = new TemplateMessageBuilder('Carousel',
                            new CarouselTemplateBuilder(
                                array(
                                    new CarouselColumnTemplateBuilder(
                                        'Title Carousel',
                                        'Description Carousel',
                                        'https://www.mywebsite.com/imgsrc/photos/f/sampleimage/700',
                                        $actionBuilder
                                    ),
                                    new CarouselColumnTemplateBuilder(
                                        'Title Carousel',
                                        'Description Carousel',
                                        'https://www.mywebsite.com/imgsrc/photos/f/sampleimage/700',
                                        $actionBuilder
                                    ),
                                    new CarouselColumnTemplateBuilder(
                                        'Title Carousel',
                                        'Description Carousel',
                                        'https://www.mywebsite.com/imgsrc/photos/f/sampleimage/700',
                                        $actionBuilder
                                    ),                                          
                                )
                            )
                        );
                        break;      
                    case "t_ic":
                        $replyData = new TemplateMessageBuilder('Image Carousel',
                            new ImageCarouselTemplateBuilder(
                                array(
                                    new ImageCarouselColumnTemplateBuilder(
                                        'https://www.mywebsite.com/imgsrc/photos/f/sampleimage/700',
                                        new UriTemplateActionBuilder(
                                            'Uri Template', // ข้อความแสดงในปุ่ม
                                            'https://www.ninenik.com'
                                        )
                                    ),
                                    new ImageCarouselColumnTemplateBuilder(
                                        'https://www.mywebsite.com/imgsrc/photos/f/sampleimage/700',
                                        new UriTemplateActionBuilder(
                                            'Uri Template', // ข้อความแสดงในปุ่ม
                                            'https://www.ninenik.com'
                                        )
                                    )                                       
                                )
                            )
                        );
                        break;
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
                                    'เลือกวันที่ปฏิบัติภารกิจ', // กำหนดหัวเรื่อง
                                    'Please select23', // กำหนดรายละเอียด
                                    $imageUrl, // กำหนด url รุปภาพ
                                    $actionBuilder  // กำหนด action object
                            )
                        );									
                        break;
					case "mission":
                        // กำหนด action 4 ปุ่ม 4 ประเภท
                        $actionBuilder = array(
                            new MessageTemplateActionBuilder(
                                'CKT',// ข้อความแสดงในปุ่ม
                                'CKT' // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                            ),
                            new MessageTemplateActionBuilder(
                                'EP CKT',// ข้อความแสดงในปุ่ม
                                'EP CKT' // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                            ),
                            new MessageTemplateActionBuilder(
                                'ISR',// ข้อความแสดงในปุ่ม
                                'ISR' // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                            ),						
                        );
						$actionBuilder1 = array(
                            new MessageTemplateActionBuilder(
                                'ISR&DT&TST',// ข้อความแสดงในปุ่ม
                                'ISR&DT&TST' // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                            ),
                            new MessageTemplateActionBuilder(
                                'IL',// ข้อความแสดงในปุ่ม
                                'IL' // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                            ),
                            new MessageTemplateActionBuilder(
                                'CB',// ข้อความแสดงในปุ่ม
                                'CB' // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                            ),							
                        );
						$actionBuilder2 = array(
                            new MessageTemplateActionBuilder(
                                'CG',// ข้อความแสดงในปุ่ม
                                'CG' // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                            ),
                            new MessageTemplateActionBuilder(
                                'CB',// ข้อความแสดงในปุ่ม
                                'CB' // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                            ),
                            new MessageTemplateActionBuilder(
                                'SR',// ข้อความแสดงในปุ่ม
                                'SR' // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                            ),						
                        );
                        $replyData = new TemplateMessageBuilder('Carousel',
                            new CarouselTemplateBuilder(
                                array(
                                    new CarouselColumnTemplateBuilder(
                                        'Mission',
                                        'Please select',
                                        'https://raw.githubusercontent.com/Thanadon99/linebot-code-example/master/pic/mission.jpg',
                                        $actionBuilder
                                    ),
                                    new CarouselColumnTemplateBuilder(
                                        'Mission',
                                        'Please select',
                                        'https://raw.githubusercontent.com/Thanadon99/linebot-code-example/master/pic/mission.jpg',
                                        $actionBuilder1
                                    ),
                                    new CarouselColumnTemplateBuilder(
                                        'Mission',
                                        'Please select',
                                        'https://raw.githubusercontent.com/Thanadon99/linebot-code-example/master/pic/mission.jpg',
                                        $actionBuilder2
                                    ),    
                                )
                            )
                        );
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
							$textReplyMessage.= " พิมพ์คำสั่งบอทได้ตามนี้ครับ";
   							$textReplyMessage.= "\r\nรายงานบิน";
							$textReplyMessage.= "\r\nรายงานซ่อม";
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
						
						
						
						/*$objFopen = fopen('abc.txt', 'a+');
						$strText1 = "\r\nI Love ThaiCreate.Com Line4\r\n";
						fwrite($objFopen, $strText1);
						$strText2 = "I Love ThaiCreate.Com Line5\r\n";
						fwrite($objFopen, $strText2);
						$strText3 = "I Love ThaiCreate.Com Line6\r\n";
						fwrite($objFopen, $strText3);
						fclose($objFopen);
						
						$replyData = new TextMessageBuilder($textReplyMessage);
                        //$replyData = new TextMessageBuilder($textReplyMessage);   
*/						
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
Function calculate($paramPostback)
{
		$myfile = fopen("x.txt", "r+") or die("Unable to open file!");
		$x=(fgets($myfile));
		fclose($myfile);
		$myfile = fopen("x.txt", "w") or die("Unable to open file!");
			if ("$x"<"8")
			{
				fwrite($myfile, $x+1);
			}
			else
			{
				fwrite($myfile, $x-8);
			}
			fclose($myfile);
			if ($x<"1")
			{
				$is_message = 1;
				$typeMessage = 'text';
				$userMessage = "mission";
				$pushdata = "Date = ".$paramPostback;
			}
			elseif ($x<"2")
			{
				$is_message = 1;
				$typeMessage = 'text';
				$pushdata = "\r\nMission = ".$userMessage;
				$userMessage = "ทดสอบ";
			}
			elseif ($x<"3") 
			{
				$is_message = 1;
				$typeMessage = 'text';
				$userMessage = "ทดสอบ";
				$pushdata = "\r\nUAV No. = ".$paramPostback;
			}
			elseif ($x<"4") 
			{
				$is_message = 1;
				$typeMessage = 'text';
				$userMessage = "ทดสอบ";
				$pushdata = "\r\nEngine No. = ".$paramPostback;
			}
			elseif ($x<"5")
			{
				$is_message = 1;
				$typeMessage = 'text';
				$userMessage = "ทดสอบ";
				$pushdata = "\r\nGCS No. = ".$paramPostback;
			}
			elseif ($x<"6")
			{
				$is_message = 1;
				$typeMessage = 'text';
				$userMessage = "ทดสอบ";
				$pushdata = "\r\nPayload = ".$paramPostback;
			}
			elseif ($x<"7")
			{
				$is_message = 1;
				$typeMessage = 'text';
				$userMessage = "ทดสอบ";
				$pushdata = "\r\nFuel Qty = ".$paramPostback;
			}
			elseif ($x<"8")
			{
				$is_message = 1;
				$typeMessage = 'text';
				$userMessage = "ทดสอบ";
				$pushdata = "\r\nFuel Remain = ".$paramPostback;
			}
		$myfile = fopen("abc.txt", "a+") or die("Unable to open file!");
		fwrite($myfile, $pushdata);
		fclose($myfile);
		
		$result = array($is_message,$typeMessage,$userMessage);
		return $result;		
}
?>