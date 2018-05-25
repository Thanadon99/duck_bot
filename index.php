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
		//parse_str($events['events'][0]['postback']['data'],$paramPostback);;
		$paramPostback = $events['events'][0]['postback']['data'];
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
			//$textReplyMessage.= "\r\nข้อความยาวๆๆๆ".$typeMessage;
			//$textReplyMessage.= "\r\nข้อความยาวๆๆๆxตัวบน ".$userMessage;
			
        }

        $replyData = new TextMessageBuilder($textReplyMessage); 		
    }
	
		//$myfile = fopen("abc.txt", "a+") or die("Unable to open file!");
		//fwrite($myfile, $userMessage);
		//fclose($myfile);
	
		$myfile = fopen("x.txt", "r+") or die("Unable to open file!");
		$x=(fgets($myfile));
		fclose($myfile);
		if ($userMessage != "รายงานบิน"){
			if ($userMessage != "รายงานซ่อม"){
				if ($userMessage != 'fuel_qty' && $x == '6'){
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
                                'ISR_Laser', // ข้อความแสดงในปุ่ม
                                http_build_query(array(
									'ISR_Laser'
                                )), // ข้อมูลที่จะส่งไปใน webhook ผ่าน postback event
                                'ISR_Laser'  // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                            ), 					
                        );
						$actionBuilder1 = array(
                            new PostbackTemplateActionBuilder(
                                'ISR_DT_TST', // ข้อความแสดงในปุ่ม
                                http_build_query(array(
                                    //'action'=>'buy',
                                    //'item'=>100
									'ISR_DT_TST'
                                )), // ข้อมูลที่จะส่งไปใน webhook ผ่าน postback event
                                'ISR_DT_TST'  // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                            ), 
                            new PostbackTemplateActionBuilder(
                                'SR', // ข้อความแสดงในปุ่ม
                                http_build_query(array(
									'SR'
                                )), // ข้อมูลที่จะส่งไปใน webhook ผ่าน postback event
                                'SR'  // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                            ), 
                            new PostbackTemplateActionBuilder(
                                'CB', // ข้อความแสดงในปุ่ม
                                http_build_query(array(
									'CB'
                                )), // ข้อมูลที่จะส่งไปใน webhook ผ่าน postback event
                                'CB'  // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                            ), 					
                        );
						$actionBuilder2 = array(
                            new PostbackTemplateActionBuilder(
                                'HOC', // ข้อความแสดงในปุ่ม
                                http_build_query(array(
                                    //'action'=>'buy',
                                    //'item'=>100
									'HOC'
                                )), // ข้อมูลที่จะส่งไปใน webhook ผ่าน postback event
                                'HOC'  // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                            ), 
                            new PostbackTemplateActionBuilder(
                                'CAS', // ข้อความแสดงในปุ่ม
                                http_build_query(array(
									'CAS'
                                )), // ข้อมูลที่จะส่งไปใน webhook ผ่าน postback event
                                'CAS'  // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                            ), 
                            new PostbackTemplateActionBuilder(
                                'ABGD', // ข้อความแสดงในปุ่ม
                                http_build_query(array(
									'ABGD'
                                )), // ข้อมูลที่จะส่งไปใน webhook ผ่าน postback event
                                'ABGD'  // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                            ), 					
                        );
						$actionBuilder3 = array(
                            new PostbackTemplateActionBuilder(
                                'Laser', // ข้อความแสดงในปุ่ม
                                http_build_query(array(
                                    //'action'=>'buy',
                                    //'item'=>100
									'Laser'
                                )), // ข้อมูลที่จะส่งไปใน webhook ผ่าน postback event
                                'Laser'  // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                            ), 
                            new PostbackTemplateActionBuilder(
                                'CDF', // ข้อความแสดงในปุ่ม
                                http_build_query(array(
									'CDF'
                                )), // ข้อมูลที่จะส่งไปใน webhook ผ่าน postback event
                                'CDF'  // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                            ), 
                            new PostbackTemplateActionBuilder(
                                'ATF', // ข้อความแสดงในปุ่ม
                                http_build_query(array(
									'ATF'
                                )), // ข้อมูลที่จะส่งไปใน webhook ผ่าน postback event
                                'ATF'  // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                            ), 					
                        );
						$actionBuilder4 = array(
                            new PostbackTemplateActionBuilder(
                                'CAS_DT_CSAR', // ข้อความแสดงในปุ่ม
                                http_build_query(array(
                                    //'action'=>'buy',
                                    //'item'=>100
									'CAS_DT_CSAR'
                                )), // ข้อมูลที่จะส่งไปใน webhook ผ่าน postback event
                                'CAS_DT_CSAR'  // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                            ), 
                            new PostbackTemplateActionBuilder(
                                'CAS_CSAR', // ข้อความแสดงในปุ่ม
                                http_build_query(array(
									'CAS_CSAR'
                                )), // ข้อมูลที่จะส่งไปใน webhook ผ่าน postback event
                                'CAS_CSAR'  // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
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
									new CarouselColumnTemplateBuilder(
                                        'Mission',
                                        'Please select',
                                        'https://raw.githubusercontent.com/Thanadon99/linebot-code-example/master/pic/BG.jpg',
                                        $actionBuilder4
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
                                )), // ข้อมูลที่จะส่งไปใน webhook ผ่าน postback event
                                'LDH324'  // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                            ), 
                            new PostbackTemplateActionBuilder(
                                'LDH326', // ข้อความแสดงในปุ่ม
                                http_build_query(array(
									'LDH326'
                                )), // ข้อมูลที่จะส่งไปใน webhook ผ่าน postback event
                                'LDH326'  // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                            ), 
                            new PostbackTemplateActionBuilder(
                                'QUAD', // ข้อความแสดงในปุ่ม
                                http_build_query(array(
									'QUAD'
                                )), // ข้อมูลที่จะส่งไปใน webhook ผ่าน postback event
                                'QUAD'  // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                            ), 					
                        );
						$actionBuilder1 = array(
                            new PostbackTemplateActionBuilder(
                                'SAR', // ข้อความแสดงในปุ่ม
                                http_build_query(array(
									'SAR'
                                )), // ข้อมูลที่จะส่งไปใน webhook ผ่าน postback event
                                'SAR'  // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                            ), 
                            new PostbackTemplateActionBuilder(
                                'COMMINT', // ข้อความแสดงในปุ่ม
                                http_build_query(array(
									'COMMINT'
                                )), // ข้อมูลที่จะส่งไปใน webhook ผ่าน postback event
                                'COMMINT'  // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                            ), 
                            new PostbackTemplateActionBuilder(
                                'CCD_PW', // ข้อความแสดงในปุ่ม
                                http_build_query(array(
									'CCD_PW'
                                )), // ข้อมูลที่จะส่งไปใน webhook ผ่าน postback event
                                'CCD_PW'  // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                            ), 					
                        );
						$actionBuilder2 = array(
                            new PostbackTemplateActionBuilder(
                                'Flir', // ข้อความแสดงในปุ่ม
                                http_build_query(array(
									'Flir'
                                )), // ข้อมูลที่จะส่งไปใน webhook ผ่าน postback event
                                'Flir'  // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
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
                        $textReplyMessage = "Fuel Qty = ?";
                        $replyData = new TextMessageBuilder($textReplyMessage);
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
				$pushdata = "Date = ".$postdata;
			}
			elseif ($x<"2")
			{
				$is_message = 1;
				$typeMessage = 'text';
				$userMessage = "uavno";
				$pushdata = "\r\nMission = ".substr($postdata,2,20);
			}
			elseif ($x<"3") 
			{
				$is_message = 1;
				$typeMessage = 'text';
				$userMessage = "engineno";
				$pushdata = "\r\nUAV No. = ".substr($postdata,2,20);
			}
			elseif ($x<"4") 
			{
				$is_message = 1;
				$typeMessage = 'text';
				$userMessage = "gcsno";
				$pushdata = "\r\nEngine No. = ".substr($postdata,2,20);
			}
			elseif ($x<"5")
			{
				$is_message = 1;
				$typeMessage = 'text';
				$userMessage = "payload";
				$pushdata = "\r\nGCS No. = ".substr($postdata,2,20);
			}
			elseif ($x<"6")
			{
				$is_message = 1;
				$typeMessage = 'text';
				$userMessage = "fuel_qty";
				$pushdata = "\r\nPayload = ".substr($postdata,2,20);
			}
			elseif ($x<"7")
			{
				$is_message = 1;
				$typeMessage = 'text';
				$userMessage = "ทดสอบ";
				$pushdata = "\r\nFuel Qty = ".$postdata;
			}
			elseif ($x<"8")
			{
				$is_message = 1;
				$typeMessage = 'text';
				$userMessage = "ทดสอบ";
				$pushdata = "\r\nFuel Remain = ".$postdata;
			}
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
		$myfile = fopen("abc.txt", "a+") or die("Unable to open file!");
		fwrite($myfile, $pushdata);
		fclose($myfile);
		
		$result = array($is_message,$typeMessage,$userMessage);
		return $result;		
}
?>