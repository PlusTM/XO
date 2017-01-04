<?php

define('BOT_TOKEN', 'TOKEN');
define('API_URL', 'https://api.telegram.org/bot'.BOT_TOKEN.'/');

function apiRequestWebhook($method, $parameters) {
  if (!is_string($method)) {
    error_log("Method name must be a string\n");
    return false;
  }

  if (!$parameters) {
    $parameters = array();
  } else if (!is_array($parameters)) {
    error_log("Parameters must be an array\n");
    return false;
  }

  $parameters["method"] = $method;

  header("Content-Type: application/json");
  echo json_encode($parameters);
  return true;
}

function exec_curl_request($handle) {
  $response = curl_exec($handle);

  if ($response === false) {
    $errno = curl_errno($handle);
    $error = curl_error($handle);
    error_log("Curl returned error $errno: $error\n");
    curl_close($handle);
    return false;
  }

  $http_code = intval(curl_getinfo($handle, CURLINFO_HTTP_CODE));
  curl_close($handle);

  if ($http_code >= 500) {
    // do not wat to DDOS server if something goes wrong
    sleep(10);
    return false;
  } else if ($http_code != 200) {
    $response = json_decode($response, true);
    error_log("Request has failed with error {$response['error_code']}: {$response['description']}\n");
    if ($http_code == 401) {
 throw new Exception('Invalid access token provided');
    }
    return false;
  } else {
    $response = json_decode($response, true);
    if (isset($response['description'])) {
      error_log("Request was successfull: {$response['description']}\n");
    }
    $response = $response['result'];
  }

  return $response;
}

function apiRequest($method, $parameters) {
  if (!is_string($method)) {
    error_log("Method name must be a string\n");
    return false;
  }

  if (!$parameters) {
    $parameters = array();
  } else if (!is_array($parameters)) {
    error_log("Parameters must be an array\n");
    return false;
  }

  foreach ($parameters as $key => &$val) {
    // encoding to JSON array parameters, for example reply_markup
    if (!is_numeric($val) && !is_string($val)) {
      $val = json_encode($val);
    }
  }
  $url = API_URL.$method.'?'.http_build_query($parameters);

  $handle = curl_init($url);
  curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 5);
  curl_setopt($handle, CURLOPT_TIMEOUT, 60);

  return exec_curl_request($handle);
}

function apiRequestJson($method, $parameters) {
  if (!is_string($method)) {
 error_log("Method name must be a string\n");
    return false;
  }

  if (!$parameters) {
    $parameters = array();
  } else if (!is_array($parameters)) {
    error_log("Parameters must be an array\n");
    return false;
  }

  $parameters["method"] = $method;

  $handle = curl_init(API_URL);
  curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 5);
  curl_setopt($handle, CURLOPT_TIMEOUT, 60);
  curl_setopt($handle, CURLOPT_POSTFIELDS, json_encode($parameters));
  curl_setopt($handle, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));

  return exec_curl_request($handle);
}

function processMessage($message) {
  // process incoming message
  $message_id = $message['message_id'];
  $chat_id = $message['chat']['id'];
  if (isset($message['text'])) {
    // incoming text message
    $text = $message['text'];
    $admin = 115567529;
    $matches = explode(' ', $text);
    $substr = substr($text, 0,7 );
    if (strpos($text, "/start") === 0) {
        apiRequest("sendMessage", array('chat_id' => $chat_id, 'text' => "سلام 😇👋\n\n👌ربات خود را از @botfather ساخته و به من بده ❤️\n\nآموزش ساخت ربات 👉 /crtoken\n\n📌 توجه داشته بايد با دستور setinline/ در @BotFather قابليت اينلاين ربات خود را نيز فعال كنيد ...","parse_mode"=>"MARKDOWN",'reply_markup' => array(
        'keyboard' => array(array('🔄 ساخت ربات'),array('☢ حذف ربات')),
        'resize_keyboard' => true)));

if (strpos($users , $chat_id) !== false)
			{ 
			
			}
		else { 
			$myfile2 = fopen("members.txt", "a") or die("Unable to open file!");	
			fwrite($myfile2, $chat_id."\n");
			fclose($myfile2);
		     }
        if($chat_id == $admin)
        {
          if(!file_exists('tokens.txt')){
        file_put_contents('tokens.txt',"");
           }
        $tokens = file_get_contents('tokens.txt');
        $part = explode("\n",$tokens);
       $tcount =  count($part)-1;

      apiRequestWebhook("sendMessage", array('chat_id' => $chat_id,  "text" => "تعداد کل ربات های آنلاین  <code>".$tcount."</code>","parse_mode"=>"HTML"));

        }
    }else if ($text == "Version") {
      apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => "<b>xogame</b>
<b>ver. 3.0</b>
<code>Coded By</code> @YGBlack
Copy Right 2016©","parse_mode"=>"html"));	}else if ($text == "/crtoken") {      
apiRequest("sendMessage", array('chat_id' => $chat_id, 'text' => "[مطالعه](https://telegram.me/FastFlashTM/83)", 'parse_mode' => "Markdown"));

	}else if ($matches[0] == "/setvip") {
		$vipidbot =$matches[1];
		$vipbot =$matches[2];
		file_put_contents($vipidbot.'/vip.txt',$vipbot);
		apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => "<i>ربات مورد نظر شما ارتقا یافت 🇮🇷</i>","parse_mode" =>"HTML"));
	}else if ($matches[0] == "/sendtoall"&&$chat_id == $admin) {
    $texttoall =$matches[1];
	$sendtestall = str_replace("+"," ",$texttoall);
		$ttxtt = file_get_contents('members.txt');
		$membersidd= explode("\n",$ttxtt);
		for($y=0;$y<count($membersidd);$y++){
			apiRequest("sendMessage", array('chat_id' => $membersidd[$y], "text" => $sendtestall,"parse_mode" =>"HTML"));
		}
		$memcout = count($membersidd)-1;
	 	apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => "<b>Message Sent To</b> 
<code>".$memcout."</code>
<b>Members Sir</b>
------------------
<b>پیام به </b> 
<code>".$memcout."</code>
<b>نفر ارسال شد</b>
.","parse_mode" =>"HTML"));
    }else if ($matches[0] == "/update"&& strpos($matches[1], ":")) {				
	apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => "در حال برسی توکن دریافتی ... ♻️"));		
		$id = $message['chat']['id'];
        file_put_contents($id.'/booleans.txt',"false");
        $phptext = file_get_contents('phptext.txt');
        $phptext = str_replace("**TOKEN**",$matches[1],$phptext);
        $phptext = str_replace("**ADMIN**",$chat_id,$phptext);
        file_put_contents($id.'/xogame.php',$phptext);
        file_get_contents('https://api.telegram.org/bot'.$matches[1].'$texttwebhook?url=');
        file_get_contents('https://api.telegram.org/bot'.$matches[1].'/setwebhook?url=https://alphavps.cf/bots/xocreator/'.$chat_id.'/xogame.php');
apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => "🚀 ربات شما با مـوفقیت آپدیت شد ♻️"));


    }else if ($text == "🔙 برگشت") {
      apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => "🔃 به منوی اصلی خوش آمدید","parse_mode"=>"Markdown",'reply_markup' => array(
        'keyboard' => array(array('🔄 ساخت ربات'),array('☢ حذف ربات')),
        'resize_keyboard' => true)));
	}else if ($text == "☢ حذف ربات") {
	if (is_dir($chat_id)) { 
      apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => "_در حال پاک کردن ربات شما 🔄_","parse_mode"=>"Markdown"));
	  $objects = scandir($chat_id);
     foreach ($objects as $object) {
       if ($object != "." && $object != "..") {
         if (filetype($chat_id."/".$object) == "dir") rrmdir($chat_id."/".$object); else unlink($chat_id."/".$object);
       }
     }
     reset($objects);
     rmdir($chat_id); 
	  apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => "🚀 ربات شما با مـوفقیت پاک شد ♻️"));
	}}else if ($text == "🔄 ساخت ربات") {
      apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => "⚙ به بخش ساخت ربات خوش آمدید

جهت ساخت ربات توکن دریافتی از بات فادر را ارسال یا فروارد کنید 🙏.

🤖 @PlusTM ","parse_mode"=>"Markdown",'reply_markup' => array(
        'keyboard' => array(array('🔙 برگشت')),
        'resize_keyboard' => true)));
	}else if ($matches[0] != "/update" && $matches[1] == "") {
      if (strpos($text, ":")) {
apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => "در حال برسی توکن دریافتی ... ♻️"));
    $url = "http://api.telegram.org/bot".$matches[0]."/getme";
    $json = file_get_contents($url);
    $json_data = json_decode($json, true);
    $id = $chat_id;
    
   $txt = file_get_contents('lastmembers.txt');
    $membersid= explode("\n",$txt);
    
    if($json_data["result"]["username"]!=null){
      
      if(file_exists($id)==false && in_array($chat_id,$membersid)==false){
          

        $aaddd = file_get_contents('tokens.txt');
                $aaddd .= $text."
";
        file_put_contents('tokens.txt',$aaddd);

     mkdir($id, 0700);
        file_put_contents($id.'/vip.txt',"free");
		file_put_contents($id.'/ad_vip.txt',"PlusTM");
        file_put_contents($id.'/step.txt',"none");
		file_put_contents($id.'/users.txt',"");
		file_put_contents($id.'/token.txt',"$text");
        file_put_contents($id.'/start.txt',"سلام 😊👋
به ربات دوز خوش اومدي ! ❤️‌ , اگه ميخواي تو تلگرام هم با دوستات بازي كني و لذت ببري بايد از من استفاده كني 😇
براي شروع روي شروع بازي بزن و دوستت رو انتخاب كن در اخر روي پنجره باز شده بزن 🌹");
        $phptext = file_get_contents('phptext.txt');
        $phptext = str_replace("**TOKEN**",$text,$phptext);
        $phptext = str_replace("**ADMIN**",$chat_id,$phptext);
        file_put_contents($token.$id.'/xogame.php',$phptext);
        file_get_contents('https://api.telegram.org/bot'.$text.'/setwebhook?url=');
        file_get_contents('https://api.telegram.org/bot'.$text.'/setwebhook?url=https://alphavps.cf/bots/xocreator/'.$chat_id.'/xogame.php');
    $unstalled = "ربات شما با موفقیت نصب شده است🚀 
برای ورود به ربات خود کلیک کنید 👇😃
.";
    
    $bot_url    = "https://api.telegram.org/bot314995812:AAGAw_EMGs-lMGT7ESGtiOCKed-bOnQSsYA/"; 
    $url        = $bot_url . "sendMessage?chat_id=" . $chat_id ; 

$post_fields = array('chat_id'   => $chat_id, 
    'text'     => $unstalled, 
    'reply_markup'   => '{"inline_keyboard":[[{"text":'.'"@'.$json_data["result"]["username"].'"'.',"url":'.'"'."http://telegram.me/".$json_data["result"]["username"].'"'.'}]]}' ,
    'disable_web_page_preview'=>"true"
); 

$ch = curl_init(); 
curl_setopt($ch, CURLOPT_HTTPHEADER, array( 
    "Content-Type:multipart/form-data" 
)); 
curl_setopt($ch, CURLOPT_URL, $url); 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields); 

$output = curl_exec($ch); 
    
    
    $textinstall = "ربات شما با موفقیت به سرور XO پلاس تیم متصل شد 📨";
  $install = "http://api.telegram.org/bot".$matches[0]."/sendMessage?chat_id=".$chat_id."&text=".$textinstall;
  $json = file_get_contents($install);



      }
      else{
         apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => "👾 شما قبلا یک ربات ثبت کرده اید  و قادر به ثبت ربات دوم نیستید.

🔹هر نفر = یک ربات ✖️
🔸ربات دوم = 2000ت ✔️

🤖 در صورت تمایل به ساخت ربات های بیشتر به ایدی زیر پیام دهید.
🚀 @YGBlackBot"));
      }
    }
      
    else{
          apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => "توکن نا معتبر  ❌\n\nلطفا توکن خود را از @BotFather دریافت کنید ✅"));
    }
}
else{
            apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => "توکن نا معتبر  ❌\n\nلطفا توکن خود را از @BotFather دریافت کنید ✅"));

}

        }else if ($matches[0] != "/update"&&$matches[1] != ""&&$matches[2] != "") {
          
        if (strpos($text, ":")) {
          
          
apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => "در حال برسی توکن دریافتی ... ♻️"));
    $url = "http://api.telegram.org/bot".$matches[0]."/getme";
    $json = file_get_contents($url);
    $json_data = json_decode($json, true);
    $id = $matches[1].$matches[2];
    
    $txt = file_get_contents('lastmembers.txt');
    $membersid= explode("\n",$txt);
    
    if($json_data["result"]["username"]!=null ){
        
      if(file_exists($id)==false && in_array($id,$membersid)==false){

        $aaddd = file_get_contents('tokens.txt');
                $aaddd .= $text."
";
        file_put_contents('tokens.txt',$aaddd);

     mkdir($id, 0700);
        file_put_contents($id.'/users.txt',"");
		file_put_contents($id.'/vip.txt',"free");
		file_put_contents($id.'/ad_vip.txt',"PlusTM");
        file_put_contents($id.'/step.txt',"none");
		file_put_contents($id.'/token.txt',"$text");
        file_put_contents($id.'/start.txt',"سلام 😊👋
به ربات دوز خوش اومدي ! ❤️‌ , اگه ميخواي تو تلگرام هم با دوستات بازي كني و لذت ببري بايد از من استفاده كني 😇
براي شروع روي شروع بازي بزن و دوستت رو انتخاب كن در اخر روي پنجره باز شده بزن 🌹");
        $phptext = file_get_contents('phptext.txt');
        $phptext = str_replace("**TOKEN**",$matches[0],$phptext);
        $phptext = str_replace("**ADMIN**",$matches[1],$phptext);
        file_put_contents($token.$id.'/xogame.php',$phptext);
        file_get_contents('https://api.telegram.org/bot'.$matches[0].'/setwebhook?url=');
        file_get_contents('https://api.telegram.org/bot'.$matches[0].'/setwebhook?url=https://alphavps.cf/bots/xocreator/'.$id.'/xogame.php');
    $unstalled = "ربات شما با موفقیت نصب شده است🚀 
برای ورود به ربات خود کلیک کنید 👇😃
.";
    
    $bot_url    = "https://api.telegram.org/bot314995812:AAGAw_EMGs-lMGT7ESGtiOCKed-bOnQSsYA/"; 
    $url        = $bot_url . "sendMessage?chat_id=" . $chat_id ; 

$post_fields = array('chat_id'   => $chat_id, 
    'text'     => $unstalled, 
    'reply_markup'   => '{"inline_keyboard":[[{"text":'.'"@'.$json_data["result"]["username"].'"'.',"url":'.'"'."http://telegram.me/".$json_data["result"]["username"].'"'.'}]]}' ,
    'disable_web_page_preview'=>"true"
); 

$ch = curl_init(); 
curl_setopt($ch, CURLOPT_HTTPHEADER, array( 
    "Content-Type:multipart/form-data" 
)); 
curl_setopt($ch, CURLOPT_URL, $url); 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields); 

$output = curl_exec($ch); 
	$textinstall = "ربات شما با موفقیت به سرور XO پلاس تیم متصل شد 📨";
  $install = "http://api.telegram.org/bot".$matches[0]."/sendMessage?chat_id=".$chat_id."&text=".$textinstall;
  $json = file_get_contents($install);
      }
      else{
         apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => "👾 شما قبلا یک ربات ثبت کرده اید  و قادر به ثبت ربات دوم نیستید.

🔹هر نفر = یک ربات ✖️
🔸ربات دوم = 2000ت ✔️

🤖 در صورت تمایل به ساخت ربات های بیشتر به ایدی زیر پیام دهید.
🚀 @YGBlackBot"));
      }

    }
    else{
          apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => "توکن نا معتبر  ❌\n\nلطفا توکن خود را از @BotFather دریافت کنید ✅"));

    }
}
else{
            apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => "توکن نا معتبر  ❌\n\nلطفا توکن خود را از @BotFather دریافت کنید ✅"));

}

        } else if (strpos($text, "/stop") === 0) {
      // stop now
    } else {
      apiRequestWebhook("sendMessage", array('chat_id' => $chat_id, "reply_to_message_id" => $message_id, "text" => '❌ دستور نا معتبر 
🌀برای راهنمایی /start را بزنید.
.'));
    }
  } else {
    apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => '❌ دستور نا معتبر 
🌀برای راهنمایی /start را بزنید.
.'));
  }
}


define('WEBHOOK_URL', 'https://www.alphaplus.cf/bots/xocreator/');

if (php_sapi_name() == 'cli') {
  // if run from console, set or delete webhook
  apiRequest('setWebhook', array('url' => isset($argv[1]) && $argv[1] == 'delete' ? '' : WEBHOOK_URL));
  exit;
}


$content = file_get_contents("php://input");
$update = json_decode($content, true);

if (!$update) {
  // receive wrong update, must not happen
  exit;
}

if (isset($update["message"])) {
  processMessage($update["message"]);
}


