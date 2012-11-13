<?php  

	$debug = true;
	if(!$debug) error_reporting(0);
	
	getData();
	
	function getData(){
	
	global $debug;
	date_default_timezone_set('Asia/Calcutta');
	$time = date("Y-m-d h:i:s");	
	$phonevol = preg_replace('/^91/', '', $_REQUEST['msisdn']); // Gupshup uses a 91 at the start. Remove that.
	$keyword = strtolower($_REQUEST['keyword']);
	$content = $_REQUEST['content'];
	$location = $_REQUEST['location'];
	if($debug) print "From $phonevol at $time:\n";
	
	list($full_name, $phonedonor, $amount) = explode(",", str_replace('TMAD ','', $content));
	$amount = strtolower(trim($amount));
	$full_name = trim($full_name);
	$phonedonor = trim($phonedonor);
	list($short_name) = explode(" ",$full_name);
		
	//googleSpreadsheetInsert($full_name,$phonedonor,$amount,$phonevol,$location,$time);
	databaseInsert($full_name,$phonedonor,$amount,$phonevol,$location,$time);
	send($phonedonor, "Dear $short_name, thank you for registering with Make A Difference. Check your email for more details.");
	
	}
	
	
	function googleSpreadsheetInsert($full_name,$phonedonor,$amount,$phonevol,$location,$time){
	
		global $debug;
		
		define('GDATA_USER','operations.cochin1@makeadiff.in');
		define('GDATA_PASSWORD','madforever');
		
		require_once '/Zend/Loader.php';
		Zend_Loader::loadClass('Zend_Gdata_Spreadsheets');
		Zend_Loader::loadClass('Zend_Gdata_ClientLogin');
		
		
		try{
			$service = Zend_Gdata_Spreadsheets::AUTH_SERVICE_NAME;
			$client = Zend_Gdata_ClientLogin::getHttpClient(GDATA_USER, GDATA_PASSWORD, $service);
			$service = new Zend_Gdata_Spreadsheets($client);
	 
			
			$row = array(
				'name' => $full_name,
				'phonedonor' => $phonedonor,
				'amount' => $amount,
				'phonevol' => $phonevol,
				'city' => $location,
				'timestamp' =>  $time
			);
			
			$insertRowEntry = $service->insertRow($row, '0AvlHVzJovtANdE9ob0lHSVJqUzhTWkpBQjNZcUhaQmc', 'od6');
			if($debug) print "New row entered. Its ID is " . $insertRowEntry->id . "<br/>";
			
		}catch(Exception $e){
			die( 'An ERROR Occurred: ' . $e->getMessage() . ' or perhaps you just need more coffee.' );
		}
	
	}
	
	
	function databaseInsert($full_name,$phonedonor,$amount,$phonevol,$location,$time){
		
		global $debug;
		$con = mysql_connect("localhost","Owner","");
		if(!$con)
			die("Could not connect:" . mysql_error());
		
		mysql_select_db("project_cf",$con);
		
		$sql = "SELECT id,city_id FROM project_cf.volunteer WHERE phone = '$phonevol'";
		if(!($result = mysql_query($sql,$con)))
				die("Error1:" . mysql_error());
		$row = mysql_fetch_array($result);
		
		/*if($debug) {
			print "<br/>Result: $result<br/>";
			$row = mysql_fetch_array($result);
			$vol_id = $row['id'];
			$city_id = $row['city_id'];
			print "<br/>Vol: $vol_id City: $city_id<br/>";
		}*/
		
		if($row['id'] != NULL){
			
			$vol_id = $row['id'];
			$city_id = $row['city_id'];
			$sql1 = "INSERT INTO project_cf.donation(name,phone,amount,volunteer_id,donation_on,city_id) VALUES('$full_name','$phonedonor','$amount','$vol_id','$time','$city_id')";
			if(!mysql_query($sql1,$con))
				die("Error2:" . mysql_error());
		}
		else{
			$sql1 = "INSERT INTO project_cf.volunteer(name,email,phone,status,parent_id,city_id) VALUES('Unknown','Unknown','$phonevol','unknown',0,0)";
			if(!mysql_query($sql1,$con))
				die("Error3:" . mysql_error());
			$result1 = mysql_query("SELECT id FROM project_cf.volunteer WHERE phone = '$phonevol'");
			$row = mysql_fetch_array($result1);
			$vol_id = $row['id'];
			$sql2 = "INSERT INTO project_cf.donation(name,phone,amount,volunteer_id,donation_on,city_id) VALUES('$full_name','$phonedonor','$amount','$vol_id','$time',0)";
			if(!mysql_query($sql2,$con))
				die("Error4:" . mysql_error());
		}
		
		mysql_close($con);		
	}
	
	
		
		
	function send($number, $message) {
		global $debug;
		if(!$number) return;
		$number_array = array();
		if(is_array($number)) {
			$number_array = $number;
			$number = implode('|', $number);
		}
		$data = array();
		
		// If there are more than 20 people, send it in slices of 20 - thats the SMS Gupshup limit.
		if(count($number_array) > 20) {
			$numbers = $number_array;
			while($numbers) {
				$first_twenty = array_slice($numbers, 0, 20);
				$number = implode('|', $first_twenty);
				$data[] = $_sendCall($number, $message);
				$numbers = array_slice($numbers, 20);
			}
		} else {
			$data = _sendCall($number, $message);
		}
		
		return $data;
	}

	function _sendCall($number, $message) {
		
		global $debug;
		$gupshup_account = array('username'=>'2000030788','password'=>'6BeNqpFy6');
		$gupshup_param = array(
			'method'	=>	'sendMessage',
			'v'			=>	'1.1',
			'msg_type'	=>	'TEXT',
			'auth_scheme'=>	'PLAIN',
			'mask'		=>	'MAD',
			'userid'	=>	$gupshup_account['username'],
			'password'	=>	$gupshup_account['password']
		);
				
		$url = str_replace('&amp;', '&', getLink('http://enterprise.smsgupshup.com/GatewayAPI/rest?',$gupshup_param + array('msg'=>$message, 'send_to'=>$number)));
		
		if($debug) print "Sending Text to $number: $message\n";
		
		// Comment the line below to disable Messageing
		//$data =load($url);
				
		return $data;
	}
		
	function getLink($url,$params=array(),$use_existing_arguments=false) {
		if(!$params and !$use_existing_arguments) return $url;
		if($use_existing_arguments) $params = $params + $_GET;
		global $debug;
		$link = $url;
		
		if(strpos($link,'?') === false) {
			$existing_parameters = array();
		} else { // This will make sure that even if the specified param exists in the given url, it will be over written.
			$url_parts = explode('?', $url);
			$link = $url_parts[0];
			$existing_parameters = array();
			
			if($url_parts[1]) {
				$all_url_parameters = split("\&(amp\;)?", $url_parts[1]);
				foreach($all_url_parameters as $part) {
					list($name, $value) = explode("=", $part);
					$existing_parameters[$name] = $value;
				}
			}
		}
		if($existing_parameters) $params = $params + $existing_parameters;
		
		$params_arr = array();
		foreach($params as $key=>$value) {
			if($value === null) continue; // If the value is given as null, don't show it in the query at all. Use arg=>"null" if you want a string null in the query.
			if($use_existing_arguments) {// Success or Error message don't have to be shown.
				if(($key == 'success' and isset($_GET['success']) and $_GET['success'] == $value)
					or ($key == 'error' and isset($_GET['error']) and $_GET['error'] == $value)) continue;
			}
			
			if(gettype($value) == 'array') { //Handle array data properly
				foreach($value as $val) {
					$params_arr[] = $key . '[]=' . urlencode($val);
				}
			} else {
				$params_arr[] = $key . '=' . urlencode($value);
			}
		}
		if($params_arr) $link = $link . '?' . implode('&amp;',$params_arr);
		
		return $link;
	}	
		
			
	function load($url,$options=array()) {
		global $debug;
		$default_options = array(
			'method'		=> 'get',
			'post_data'		=> array(),		// The data that must be send to the URL as post data.
			'return_info'	=> false,		// If true, returns the headers, body and some more info about the fetch.
			'return_body'	=> true,		// If false the function don't download the body - useful if you just need the header or last modified instance.
			'cache'			=> false,		// If true, saves a copy to a local file - so that the file don't have multiple times.
			'cache_folder'	=> '/tmp/php-load-function/', // The folder to where the cache copy of the file should be saved to.
			'cache_timeout'	=> 0,			// If the cached file is older that given time in minutes, it will download the file again and cache it.
			'referer'		=> '',			// The referer of the url.
			'headers'		=> array(),		// Custom headers
			'session'		=> false,		// If this is true, the following load() calls will use the same session - until load() is called with session_close=true.
			'session_close'	=> false,
		);
		// Sets the default options.
		foreach($default_options as $opt=>$value) {
			if(!isset($options[$opt])) $options[$opt] = $value;
		}
	
		$url_parts = parse_url($url);
		$ch = false;
		$info = array(//Currently only supported by curl.
			'http_code'	=> 200
		);
		$response = '';
		
		
		$send_header = array(
			'User-Agent' => 'BinGet/1.00.A (http://www.bin-co.com/php/scripts/load/)'
		) + $options['headers']; // Add custom headers provided by the user.
		
		if($options['cache']) {
			$cache_folder = $options['cache_folder'];
			if(!file_exists($cache_folder)) {
				$old_umask = umask(0); // Or the folder will not get write permission for everybody.
				mkdir($cache_folder, 0777);
				umask($old_umask);
			}
			
			$cache_file_name = md5($url) . '.cache';
			$cache_file = joinPath($cache_folder, $cache_file_name); //Don't change the variable name - used at the end of the function.
			
			if(file_exists($cache_file) and filesize($cache_file) != 0) { // Cached file exists - return that.
				$timedout = false;
				if($options['cache_timeout']) {
					if(((time() - filemtime($cache_file)) / 60) > $options['cache_timeout']) $timedout = true;  // If the cached file is older than the timeout value, download the URL once again.
				}
				
				if(!$timedout) {
					$response = file_get_contents($cache_file);
					
					//Seperate header and content
					$seperator_charector_count = 4;
					$separator_position = strpos($response,"\r\n\r\n");
					if(!$separator_position) {
						$separator_position = strpos($response,"\n\n");
						$seperator_charector_count = 2;
					}
					// If the real seperator(\r\n\r\n) is NOT found, search for the first < char.
					if(!$separator_position) {
						$separator_position = strpos($response,"<"); //:HACK:
						$seperator_charector_count = 0;
					}
					
					$body = '';
					$header_text = '';
					if($separator_position) {
						$header_text = substr($response,0,$separator_position);
						$body = substr($response,$separator_position+$seperator_charector_count);
					}
					
					foreach(explode("\n",$header_text) as $line) {
						$parts = explode(": ",$line);
						if(count($parts) == 2) $headers[$parts[0]] = chop($parts[1]);
					}
					$headers['cached'] = true;
					
					if(!$options['return_info']) return $body;
					else return array('headers' => $headers, 'body' => $body, 'info' => array('cached'=>true));
				}
			}
		}
	
		///////////////////////////// Curl /////////////////////////////////////
		//If curl is available, use curl to get the data.
		if(function_exists("curl_init") 
					and (!(isset($options['use']) and $options['use'] == 'fsocketopen'))) { //Don't use curl if it is specifically stated to use fsocketopen in the options
			
			if(isset($options['post_data']) and $options['post_data']) { //There is an option to specify some data to be posted.
				$page = $url;
				$options['method'] = 'post';
				
				if(is_array($options['post_data'])) { //The data is in array format.
					$post_data = array();
					foreach($options['post_data'] as $key=>$value) {
						if($value)  $post_data[] = "$key=" . urlencode($value);
						else $post_data[] = $key;
					}
					$url_parts['query'] = implode('&', $post_data);
				
				} else { //Its a string
					$url_parts['query'] = $options['post_data'];
				}
			} else {
				if(isset($options['method']) and $options['method'] == 'post') {
					$page = $url_parts['scheme'] . '://' . $url_parts['host'] . $url_parts['path'];
				} else {
					$page = $url;
				}
			}
	
			if($options['session'] and isset($GLOBALS['_binget_curl_session'])) $ch = $GLOBALS['_binget_curl_session']; //Session is stored in a global variable
			else $ch = curl_init($url_parts['host']);
			
			curl_setopt($ch, CURLOPT_URL, $page) or die("Invalid cURL Handle Resouce");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); //Just return the data - not print the whole thing.
			curl_setopt($ch, CURLOPT_HEADER, true); //We need the headers
			curl_setopt($ch, CURLOPT_NOBODY, !($options['return_body'])); //The content - if true, will not download the contents. There is a ! operation - don't remove it.
			if(isset($options['encoding'])) curl_setopt($ch, CURLOPT_ENCODING, $options['encoding']); // Used if the encoding is gzip.
			if(isset($options['method']) and $options['method'] == 'post' and isset($url_parts['query'])) {
				curl_setopt($ch, CURLOPT_POST, true);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $url_parts['query']);
			}
			//Set the headers our spiders sends
			curl_setopt($ch, CURLOPT_USERAGENT, $send_header['User-Agent']); //The Name of the UserAgent we will be using ;)
			unset($send_header['User-Agent']);
			
			$custom_headers = array();
			foreach($send_header as $key => $value) $custom_headers[] = "$key: $value";
			if(isset($options['modified_since']))
				$custom_headers[] = "If-Modified-Since: ".gmdate('D, d M Y H:i:s \G\M\T',strtotime($options['modified_since']));
			curl_setopt($ch, CURLOPT_HTTPHEADER, $custom_headers);
			if($options['referer']) curl_setopt($ch, CURLOPT_REFERER, $options['referer']);
	
			curl_setopt($ch, CURLOPT_COOKIEJAR, "/tmp/binget-cookie.txt"); //If ever needed...
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
			curl_setopt($ch, CURLOPT_MAXREDIRS, 5);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	
			if(isset($url_parts['user']) and isset($url_parts['pass']))
				$custom_headers[] = "Authorization: Basic ".base64_encode($url_parts['user'].':'.$url_parts['pass']);
		
			if($custom_headers) curl_setopt($ch, CURLOPT_HTTPHEADER, $custom_headers);
			$response = curl_exec($ch);
			$info = curl_getinfo($ch); //Some information on the fetch
			
			if($options['session'] and !$options['session_close']) $GLOBALS['_binget_curl_session'] = $ch; //Dont close the curl session. We may need it later - save it to a global variable
			else curl_close($ch);  //If the session option is not set, close the session.
	
		//////////////////////////////////////////// FSockOpen //////////////////////////////
		} else { //If there is no curl, use fsocketopen - but keep in mind that most advanced features will be lost with this approch.
			if(isset($url_parts['query'])) {
				if(isset($options['method']) and $options['method'] == 'post')
					$page = $url_parts['path'];
				else
					$page = $url_parts['path'] . '?' . $url_parts['query'];
			} else {
				$page = $url_parts['path'];
			}
			
			if(!isset($url_parts['port'])) $url_parts['port'] = 80;
			$fp = fsockopen($url_parts['host'], $url_parts['port'], $errno, $errstr, 30);
			if ($fp) {
				$out = '';
				if(isset($options['method']) and $options['method'] == 'post' and isset($url_parts['query'])) {
					$out .= "POST $page HTTP/1.1\r\n";
				} else {
					$out .= "GET $page HTTP/1.0\r\n"; //HTTP/1.0 is much easier to handle than HTTP/1.1
				}
				$out .= "Host: $url_parts[host]\r\n";
				$out .= "Accept: $send_header[Accept]\r\n";
				$out .= "User-Agent: {$send_header['User-Agent']}\r\n";
				if(isset($options['modified_since']))
					$out .= "If-Modified-Since: ".gmdate('D, d M Y H:i:s \G\M\T',strtotime($options['modified_since'])) ."\r\n";
	
				$out .= "Connection: Close\r\n";
				
				//HTTP Basic Authorization support
				if(isset($url_parts['user']) and isset($url_parts['pass'])) {
					$out .= "Authorization: Basic ".base64_encode($url_parts['user'].':'.$url_parts['pass']) . "\r\n";
				}
	
				//If the request is post - pass the data in a special way.
				if(isset($options['method']) and $options['method'] == 'post' and $url_parts['query']) {
					$out .= "Content-Type: application/x-www-form-urlencoded\r\n";
					$out .= 'Content-Length: ' . strlen($url_parts['query']) . "\r\n";
					$out .= "\r\n" . $url_parts['query'];
				}
				$out .= "\r\n";
	
				fwrite($fp, $out);
				while (!feof($fp)) {
					$response .= fgets($fp, 128);
				}
				fclose($fp);
			}
		}
	
		//Get the headers in an associative array
		$headers = array();
	
		if($info['http_code'] == 404) {
			$body = "";
			$headers['Status'] = 404;
		} else {
			//Seperate header and content
			$header_text = substr($response, 0, $info['header_size']);
			$body = substr($response, $info['header_size']);
			
			// If there is a redirect, there will be multiple headers in the response. We need just the last one.
			$tmp=explode("\r\n\r\n", trim($header_text));
			$header_text = end($tmp);
			
			foreach(explode("\n",$header_text) as $line) {
				$parts = explode(": ",$line);
				if(count($parts) == 2) $headers[$parts[0]] = chop($parts[1]);
			}
			
			// :BUGFIX: :UGLY: Some URLs(IMDB has this issue) will do a redirect without the new Location in the header. It will be in the url part of info. If we get such a case, set the header['Location'] as info['url']
			if(!isset($header['Location']) and isset($info['url'])) {
				$header['Location'] = $info['url'];
				$header_text .= "\r\nLocation: $header[Location]";
			}
			
			$response = $header_text . "\r\n\r\n" . $body;
		}
		
		if(isset($cache_file)) { //Should we cache the URL?
			file_put_contents($cache_file, $response);
		}
	
		if($options['return_info']) return array('headers' => $headers, 'body' => $body, 'info' => $info, 'curl_handle'=>$ch);
		return $body;
	} 	
	
	
	//http://localhost/sms_test.php?msisdn=919999999999&location=Cochin&timestamp=1339356546&keyword=TMAD&content=Sanjay+Thomas%2c9633977657%2c1000
?>