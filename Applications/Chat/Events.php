<?php
/**
 * This file is part of workerman.
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the MIT-LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @author walkor<walkor@workerman.net>
 * @copyright walkor<walkor@workerman.net>
 * @link http://www.workerman.net/
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 */

/**
 * 用于检测业务代码死循环或者长时间阻塞等问题
 * 如果发现业务卡死，可以将下面declare打开（去掉//注释），并执行php start.php reload
 * 然后观察一段时间workerman.log看是否有process_timeout异常
 */
//declare(ticks=1);


/**
 * 聊天主逻辑
 * 主要是处理 onMessage onClose 
 */
use \GatewayWorker\Lib\Gateway;

$gl_chatid = "123";

$gl_client_user='{"stat":"OK","type":"UonlineUser","roomListUser":[{"client_id":8888,"client_name":{"roomid":"1","chatid":"x0528EEF1","nick":"\u6e38\u5ba20528EEF1","sex":"0","age":"0","qx":"0","ip":"113.88.73.252","vip":"AA6","color":"0","cam":"0","state":"0","mood":""}}]}';


 if(get_magic_quotes_gpc()){
      function stripslashes_deep($value){
          $value=is_array($value)?array_map('stripslashes_deep',$value):stripslashes($value);
          return $value;
      }
      $_POST=array_map('stripslashes_deep',$_POST);
      $_GET=array_map('stripslashes_deep',$_GET);
      $_COOKIE=array_map('stripslashes_deep',$_COOKIE);
      $_REQUEST=array_map('stripslashes_deep',$_REQUEST);
 }



//下面就来完成上面用到的字符串截取函数getNeedBetween。该函数可实现简单的从字符串($kw)截取两个指定的字符($mark1,$mark2)之间字符串，失败返回0，成功返回截取的字符串。

function getNeedBetween($kw1,$mark1,$mark2)
{
	$kw = $kw1;
	$kw = '123'.$kw.'123';
	$st = stripos($kw, $mark1);
	$ed = stripos($kw, $mark2);
	if(($st == false || $ed == false) || $st >= $ed )
    	return 0;

	$kw=substr($kw,($st+1),($ed-$st-1));
	    return $kw;
}


class Events
{
   /**
    * 有消息时
    * @param int $client_id
    * @param mixed $message
    */

	

   public static function onMessage($client_id, $message)
   {


	   global $gl_chatid;
   	  global $gl_client_user;
	  
$gl_client_user = array (
	"stat"=> "OK",
	"type"=> "UonlineUser",
	"roomListUser"=> array (
	array
	( 
		"client_id"=> 8888,
		"client_name"=> array 
		(
			"roomid"=> "1",
			"chatid"=>"x05287A98",
			"nick"=> "test1",
			"sex"=> "0",
			"age"=> "0",
			"qx"=> "0",
			"ip"=> "113.88.73.252",
			"vip"=> "AA6",
			"color"=> "0",
			"cam"=> "0",
			"state"=> "0",
			"mood"=> ""
		)
	),
	array
	( 
		"client_id"=> 8288,
		"client_name"=> array 
		(
			"roomid"=> "1",
			"chatid"=>"x05287A91",
			"nick"=> "test2",
			"sex"=> "0",
			"age"=> "0",
			"qx"=> "0",
			"ip"=> "113.88.73.252",
			"vip"=> "AA6",
			"color"=> "0",
			"cam"=> "0",
			"state"=> "0",
			"mood"=> ""
		)
	)


  )
);


        // debug
        echo "client:{$_SERVER['REMOTE_ADDR']}:{$_SERVER['REMOTE_PORT']} gateway:{$_SERVER['GATEWAY_ADDR']}:{$_SERVER['GATEWAY_PORT']}  client_id:$client_id session:".json_encode($_SESSION)."\n onMessage:".$message."\n";
        
        // 客户端传递的是json数据
        $message_data = json_encode($message, true);
	    
		/*
        if(!$message_data)
        {
            return ;
        }
        */


		$n = strpos($message,'=M=');//寻找位置
		if ($n) $stroption = substr($message, 0, $n);//删除后面

		echo ($stroption); // 显示发来的请求字符串

		 switch($stroption)
		{

			case 'Login':

/*
				$need=getNeedBetween($message, '=' , '|' );
				$chatid = $need;
				echo "\n";
				echo $chatid;
				echo "\n";
*/

				//处理字符分配到数组
				$res=explode("|",$message);
				//for($i=0;$i<11;$i++)
				 //echo "$res[$i]\n";

				$res[0] = '1';
				$roomid = '"roomid":"'.$res[0].'",';
				$chatid =  '"chatid":"'.$res[1].'",';
				$gl_chatid = $res[1];

				$nick = '"nick":"'.$res[2].'",';

				echo "\n send nick = ".$nick."\n";

				$sex= '"sex":"'.$res[3].'",';
				$age= '"age":"'.$res[4].'",';
				$qx='"qx":"'.$res[5].'",';
				$ip= '"ip":"'.$res[6].'",';
				$vip='"vip":"'.$res[7].'",';
				$color= '"color":"'.$res[8].'",';
				$cam= '"cam":"'.$res[9].'",';
				$state= '"state":"'.$res[10].'",';
				$mood= '"mood":"'.$res[11].'"';


				//取后面四位
				$client_newstr = strval($client_id);
				$client_newid = substr($client_newstr, -4);
				$client_newid=intval($client_newid);


				

				$resultstr = '{"stat":"OK","type":"Ulogin","Ulogin":{'.$roomid.$chatid.$nick.$sex.$age.$qx.$ip.$vip.$color.$cam.$state.$mood.'}}';
				//echo "\n";
				//echo $resultstr;

                
				//一个房间
				$one_romid = '{'.$roomid.$chatid.$nick.$sex.$age.$qx.$ip.$vip.$color.$cam.$state.$mood.'}';


				//数字转字符串
				$client_idstr = '"client_id":'.$client_newid.',';

				$client_name = '"client_name":';

				$online_client_id_one = '{'.$client_idstr.$client_name.$one_romid.'}';

				$resultstr2 = '{"stat":"OK","type":"UonlineUser","roomListUser":['.$online_client_id_one.']}';

				//echo "\n";
				//echo $resultstr2;



				//{"stat":"OK","type":"Ulogin","Ulogin":{"roomid":"1","chatid":"x423A87E","nick":"\u6e38\u5ba20423A87E","sex":"0","age":"0","qx":"0","ip":"116.25.79.1","vip":"AA6","color":"0","cam":"0","state":"0","mood":""})

				//$str='"Ulogin":{"rommid":.$rommid.+My.chatid+'|'+My.nick+'|'+My.sex+'|'+My.age+'|'+My.qx+'|'+My.ip+'|'+My.vip+'|'+My.color+'|'+My.cam+'|'+My.state+'|'+My.mood";


				//var str='Login=M='+My.roomid+'|'+My.chatid+'|'+My.nick+'|'+My.sex+'|'+My.age+'|'+My.qx+'|'+My.ip+'|'+My.vip+'|'+My.color+'|'+My.cam+'|'+My.state+'|'+My.mood;
				//$message = '{"stat":"OK","type":"Ulogin","Ulogin":{"roomid":"1","chatid":"x43363E9","nick":"\u6e38\u5ba2042263E9","sex":"0","age":"0","qx":"0","ip":"116.25.79.1","vip":"AA6","color":"0","cam":"0","state":"0","mood":""}}';
                //Gateway::sendToCurrentClient($message);
				//$sendmsg = '{"stat":"OK","type":"Ulogin","Ulogin":{"roomid":"1","chatid":"x43363E9","nick":"\u6e38\u5ba2042263E9","sex":"0","age":"0","qx":"0","ip":"116.25.79.1","vip":"AA6","color":"0","cam":"0","state":"0","mood":""}}';

				//$str=stripslashes($message);


//----------------------------------------------------------------------------------------------------------------------------------
		//wangsl add

		$clients_roomListUser = array(
		"client_id"=> 1000,
		"client_name"=> array (
			"roomid"=> "1",
			"chatid"=> "1",
			"nick"=> "test",
			"sex"=> "0",
			"age"=> "0",
			"qx"=> "1",
			"ip"=> "113.88.73.252",
			"vip"=> "AA1",
			"color"=> "2",
			"cam"=> "0",
			"state" => "0",
			"mood"=> "bibi"
		)
);

		//获取客户登陆信息
		var_dump("-------------get user info--------------------");
		$message_data = json_decode($online_client_id_one, true);
		var_dump($message_data);

		//取房间用户列表
		var_dump("-------------get room list user--------------------");
		$clients_roomListUser = $message_data;
		var_dump($clients_roomListUser);

		//检查房间信息是否存在全局数组中
		if (in_array($clients_roomListUser, $gl_client_user['roomListUser']))
		{
			echo "in array key exit! \n";
		}
		else
		{
			echo "in array key no exit! \n";
			array_push($gl_client_user['roomListUser'], $clients_roomListUser);
		}
	

		$out_array = json_encode($gl_client_user, true);
		echo "--- json_encode ---\n";
		//var_dump($out_array);

		//var_dump("-------------gl_client_user--------------------");
		//var_dump($gl_client_user);

		//wangsl  end
//-------------------------------------------------------------------------------------------------------------------------------------------------------------






				  $message_data = json_decode($resultstr, true);

				  Gateway::sendToCurrentClient(json_encode($message_data));

				//Gateway::sendToCurrentClient($resultstr);


				//$commandstr = '{"stat":"OK","type":"UonlineUser","roomListUser":[{"client_id": 2620,"client_name":{"roomid": "1","chatid": "0x42263E9","nick": "游客042263E9","sex": "0","age": "0","qx": "0","ip": "116.25.79.1","vip": "AA6","color": "0","cam": "0","state": "0","mood": ""}}]}';

				 //$str=stripslashes($commandstr);
				 //echo $str;
				 //Gateway::sendToCurrentClient($commandstr);
				 
				  //$message_data = json_decode($resultstr2, true);
				  //Gateway::sendToCurrentClient(json_encode($message_data)); 
	
				 // $message_data = json_decode($gl_client_user, true);
				  //Gateway::sendToCurrentClient(json_encode($gl_client_user));
				//$message_data = json_encode($gl_client_user, true);
				var_dump($out_array);
				Gateway::sendToCurrentClient($out_array);

				return;

			case 'SendMsg':


				//处理字符分配到数组
				$res=explode("|",$message);
				//for($i=0;$i<4;$i++)
				 //echo "$res[$i]\n";

				$gl_chatidstr = '"ChatId":"'.$gl_chatid.'",';

				$res[0] = 'ALL';
				$ToChatId = '"ToChatId":"'.$res[0].'",'; 
				$IsPersonal = '"IsPersonal":"'.$res[1].'",';
				$Style =  '"Style":"'.$res[2].'",';
				$Txt = '"Txt":"'.$res[3].'"';



                //echo "\n";
				//$message = '{"stat":"OK","type":"UMsg","UMsg":{"ChatId":"0x423A87E","ToChatId":"ALL","IsPersonal":"false","Style":"font-weight:;font-style:;text-decoration:;color:rgb(0,0,0);font-family:;font-size:12pt","Txt":"fcb960c4_+_ok"}}';
			    //echo $message;

				echo "\n";
				$resultstr3 = '{"stat":"OK","type":"UMsg","UMsg":{'.$gl_chatidstr.$ToChatId.$IsPersonal.$Style.$Txt.'}}';

                echo "\n";
				echo $resultstr3;

			    //Gateway::sendToCurrentClient($message);
					Gateway::sendToCurrentClient($resultstr3);
				return;

			case 'SendMsg':



		}

		return;

		//echo "send msg ".$message_data."\n";


/*
<?php
$str='http://www.com.com/index.php?id=1';
$str=substr($str,7);//去除前面
$n=strpos($str,'?');//寻找位置
if ($n) $str=substr($str,0,$n);//删除后面
echo $str;
?>
*/



                

        // 根据类型执行不同的业务
        switch($message_data['SendMsg'])
        {
            // 客户端回应服务端的心跳
            case 'SPing':
                return;
            // 客户端登录 message格式: {type:login, name:xx, room_id:1} ，添加到客户端，广播给所有客户端xx进入聊天室

			case 'Login':
          
                echo "xianshi Login";
                $message_data = json_decode($message, true);
				$message_data['type'] = 'ok';


                Gateway::sendToCurrentClient(json_encode($message_data));
                return;
                
            // 客户端发言 message: {type:say, to_client_id:xx, content:xx}

            case 'login':
                // 判断是否有房间号
                if(!isset($message_data['room_id']))
                {
                    throw new \Exception("\$message_data['room_id'] not set. client_ip:{$_SERVER['REMOTE_ADDR']} \$message:$message");
                }
                
                // 把房间号昵称放到session中
                $room_id = $message_data['room_id'];
                $client_name = htmlspecialchars($message_data['client_name']);
                $_SESSION['room_id'] = $room_id;
                $_SESSION['client_name'] = $client_name;
              
                // 获取房间内所有用户列表 
                $clients_list = Gateway::getClientInfoByGroup($room_id);
                foreach($clients_list as $tmp_client_id=>$item)
                {
                    $clients_list[$tmp_client_id] = $item['client_name'];
                }
                $clients_list[$client_id] = $client_name;
                
                // 转播给当前房间的所有客户端，xx进入聊天室 message {type:login, client_id:xx, name:xx} 
                $new_message = array('type'=>$message_data['type'], 'client_id'=>$client_id, 'client_name'=>htmlspecialchars($client_name), 'time'=>date('Y-m-d H:i:s'));
                Gateway::sendToGroup($room_id, json_encode($new_message));
                Gateway::joinGroup($client_id, $room_id);
               
                // 给当前用户发送用户列表 
                $new_message['client_list'] = $clients_list;
                Gateway::sendToCurrentClient(json_encode($new_message));
                return;
                
            // 客户端发言 message: {type:say, to_client_id:xx, content:xx}
            case 'say':
                // 非法请求
                if(!isset($_SESSION['room_id']))
                {
                    throw new \Exception("\$_SESSION['room_id'] not set. client_ip:{$_SERVER['REMOTE_ADDR']}");
                }
                $room_id = $_SESSION['room_id'];
                $client_name = $_SESSION['client_name'];
                
                // 私聊
                if($message_data['to_client_id'] != 'all')
                {
                    $new_message = array(
                        'type'=>'say',
                        'from_client_id'=>$client_id, 
                        'from_client_name' =>$client_name,
                        'to_client_id'=>$message_data['to_client_id'],
                        'content'=>"<b>对你说: </b>".nl2br(htmlspecialchars($message_data['content'])),
                        'time'=>date('Y-m-d H:i:s'),
                    );
                    Gateway::sendToClient($message_data['to_client_id'], json_encode($new_message));
                    $new_message['content'] = "<b>你对".htmlspecialchars($message_data['to_client_name'])."说: </b>".nl2br(htmlspecialchars($message_data['content']));
                    return Gateway::sendToCurrentClient(json_encode($new_message));
                }
                
                $new_message = array(
                    'type'=>'say', 
                    'from_client_id'=>$client_id,
                    'from_client_name' =>$client_name,
                    'to_client_id'=>'all',
                    'content'=>nl2br(htmlspecialchars($message_data['content'])),
                    'time'=>date('Y-m-d H:i:s'),
                );
                return Gateway::sendToGroup($room_id ,json_encode($new_message));
        }
   }
   
   /**
    * 当客户端断开连接时
    * @param integer $client_id 客户端id
    */
   public static function onClose($client_id)
   {
       // debug
       echo "client:{$_SERVER['REMOTE_ADDR']}:{$_SERVER['REMOTE_PORT']} gateway:{$_SERVER['GATEWAY_ADDR']}:{$_SERVER['GATEWAY_PORT']}  client_id:$client_id onClose:''\n";
       
       // 从房间的客户端列表中删除
       if(isset($_SESSION['room_id']))
       {
           $room_id = $_SESSION['room_id'];
           $new_message = array('type'=>'logout', 'from_client_id'=>$client_id, 'from_client_name'=>$_SESSION['client_name'], 'time'=>date('Y-m-d H:i:s'));
           Gateway::sendToGroup($room_id, json_encode($new_message));
       }
   }
  
}
