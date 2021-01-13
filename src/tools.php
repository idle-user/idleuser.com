<?php
	function get_recaptchaV2_sitekey(){
		global $configs;
		return $configs['RECAPTCHA_V2_SITEKEY'];
	}
	function get_recaptchaV3_sitekey(){
		global $configs;
		return $configs['RECAPTCHA_V3_SITEKEY'];
	}
	function validate_recaptchaV2(){
		global $configs;
		require_once $_SERVER['DOCUMENT_ROOT'] . '/../vendor/google/recaptcha/src/autoload.php';
		if (!isset($_POST['g-recaptcha-response'])) {
			throw new \Exception('ReCaptcha is not set.');
		}
		$recaptcha = new \ReCaptcha\ReCaptcha($configs['RECAPTCHA_V2_SECRET'], new \ReCaptcha\RequestMethod\CurlPost());
		$response = $recaptcha->verify($_POST['g-recaptcha-response'], $_SERVER['REMOTE_ADDR']);
		return $response->isSuccess();
	}
	function validate_recaptchaV3(){
		global $configs;
		require_once $_SERVER['DOCUMENT_ROOT'] . '/../vendor/google/recaptcha/src/autoload.php';
		if (!isset($_POST['g-recaptcha-response'])) {
			throw new \Exception('ReCaptcha is not set.');
		}
		$recaptcha = new \ReCaptcha\ReCaptcha($configs['RECAPTCHA_V3_SECRET'], new \ReCaptcha\RequestMethod\CurlPost());
		$response = $recaptcha->verify($_POST['g-recaptcha-response'], $_SERVER['REMOTE_ADDR']);
		return $response->isSuccess();
	}
	function get_ip(){
		if(!empty($_SERVER['HTTP_CLIENT_IP'])){
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		} else if(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else {
			$ip = $_SERVER['REMOTE_ADDR'];
		}
		return $ip;
	}
	function get_user_agent(){
		return isset($_SERVER['HTTP_USER_AGENT'])?$_SERVER['HTTP_USER_AGENT']:'';
	}
	function track($action=''){
		global $db;
		$ip = get_ip();
		$user_agent = get_user_agent();
		if($ip!='192.168.1.1' || !empty($action)){
			$db->add_web_traffic($_SESSION['user_id'], $ip, $_SERVER['REQUEST_URI'], $user_agent, $action);
		}
	}
	function get_direct_to(){
		return 'redirect_to='.urlencode($_SERVER['REQUEST_URI']);
	}
    function redirect_back(){
        header("Location: ..");
        exit();
	}
	function redirect($delay=0, $url=false){
		if(!$url){
			maybe_redirect_to();
			$url = get_last_page();
		}
		header("refresh:$delay;url=$url");
	}
	function maybe_redirect_to($delay=0){
		if(isset($_GET['redirect_to'])){
			$url = $_GET['redirect_to'];
			redirect($delay, $url);
			exit();
		}
	}
	function set_last_page(){
		$_SESSION['last_page'] = $_SERVER['REQUEST_URI'];
	}
	function get_last_page(){
		if(!isset($_SESSION['last_page'])){
			$_SESSION['last_page'] = '/';
		}
        return $_SESSION['last_page'];
	}
	function random_hex_color($offset=false, $min=0x000000, $max=0xFFFFFF){
		$color_code =  rand($min, $max);
		$color_code = $offset?dechex($color_code & $offset):dechex($color_code);
		return '#'.str_pad($color_code, 6, 0, STR_PAD_LEFT);
	}
	function requires_admin(){
		if(!$_SESSION['loggedin']){
			redirect(0, '/login.php');
			exit();
		}
		if($_SESSION['access'] < 2){
			redirect(0, '/403.php');
			exit();
		}
	}
	function login_token_check(){
		if(isset($_GET['uid']) && isset($_GET['login_token'])){
			global $db;
			$res = $db->user_token_login($_GET['uid'], $_GET['login_token']);
			if($res){
				$_SESSION['user_id'] = $res['id'];
				$_SESSION['username'] = $res['username'];
				$_SESSION['access'] = $res['access'];
				$_SESSION['loggedin'] = true;
				set_auth_values();
			}
			track("Login Token Attempt - uid:{$_GET['uid']}; result:".($res!=false?'1':'0'));
			if($res!=false){
				maybe_redirect_to();
			}
		}
	}
	function defaults_check(){
		if(!isset($_SESSION['user_id'])){
			$_SESSION['user_id'] = 0;
			$_SESSION['username'] = '';
			$_SESSION['access'] = 1;
			$_SESSION['loggedin'] = false;
			$_SESSION['auth_token'] = false;
			$_SESSION['auth_token_exp'] = false;
		}
		$_SESSION['loggedin'] = $_SESSION['user_id'] && !empty($_SESSION['username']);
		if($_SESSION['loggedin'] && !$_SESSION['auth_token']){
			set_auth_values();
		}
		if(!$_SESSION['loggedin']){
			auth_login();
		} else {
			auth_validate();
		}
	}
	function update_user_values(){
		global $db;

		$user = $db->user_info($_SESSION['user_id']);
		$_SESSION['username'] = $user['username'];
		$_SESSION['access'] = $user['access'];
		set_auth_values();
	}
	function set_auth_values(){
		global $db;

		$auth = $db->auth_by_user_id($_SESSION['user_id']);
		if(!$auth){
			$auth_token = $db->add_auth_token($_SESSION['user_id']);
			$auth = $db->auth_by_token(bin2hex($auth_token));
		}
		$_SESSION['auth_token'] = bin2hex($auth['auth_token']);
		$_SESSION['auth_token_exp'] = $auth['auth_token_exp'];
	}
	function auth_login(){
		global $db;

		$token = $_SESSION['auth_token'];
		if($token){
			$auth = $db->auth_by_token(hex2bin($token));
			if($auth){
				$user = $db->user_info($auth['user_id']);
				$_SESSION['user_id'] = $user['user_id'];
				$_SESSION['username'] = $user['username'];
				$_SESSION['access'] = $user['access'];
				$_SESSION['loggedin'] = true;
				$_SESSION['auth_token'] = bin2hex($auth['auth_token']);
				$_SESSION['auth_token_exp'] = $auth['auth_token_exp'];
				redirect(0);
			}
		}
	}
	function auth_validate(){
		global $db;

		$token = $_SESSION['auth_token'];
		if($token){
			$auth = $db->auth_by_token(hex2bin($token));
			if(!$auth){
				logout();
			}
		}
	}
	function logout(){
		$uid = $_SESSION['user_id'];
		session_destroy();
		track("Logout - uid:$uid");
	}
	function page_meta($meta){
		$DEFAULT_META = [
			"charset"				=> "utf-8",
			"viewport"				=> "width=device-width, initial-scale=1, shrink-to-fit=no",
			"author"				=> "Jesus Andrade",
			"description"			=> "",
			"keywords"				=> "idleuser, fancyjesse, Jesus Andrade, Jesus, Andrade, website, developer, services, programmer, wrestling, poll, database, analyst, discord, projects, watchwrestling, work, background, profile",
			"og:title"				=> "",
			"og:description"		=> "",
			"og:url"				=> "https://$_SERVER[SERVER_NAME]",
			"og:image"				=> "https://$_SERVER[SERVER_NAME]/assets/images/favicon-512x512.png",
			"og:site_name"			=> "idleuser",
			"og:type"				=> "website",
			"twitter:title"			=> "",
			"twitter:description"	=> "",
			"twitter:url"			=> "",
			"twitter:image"			=> "",
			"twitter:card"			=> "summary",
			"twitter:site"			=> "",
			"twitter:creator"		=> ""
		];
		if($meta){
			$keywords = '';
			if(isset($meta['keywords'])){
				$keywords = $meta['keywords'].', '.$DEFAULT_META['keywords'];
			}
			$meta = array_replace($DEFAULT_META, $meta);
			$meta['keywords'] = empty($keywords)?$DEFAULT_META['keywords']:$keywords;
		} else {
			$meta = $DEFAULT_META;
		}
		if(empty($meta['description']) && !empty($meta['og:description'])){
			$meta['description'] = $meta['og:description'];
		}
		if(empty($meta['twitter:title']) && !empty($meta['og:title'])){
			$meta['twitter:title'] = $meta['og:title'];
		}
		if(empty($meta['twitter:description']) && !empty($meta['og:description'])){
			$meta['twitter:description'] = $meta['og:description'];
		}
		if(empty($meta['twitter:url']) && !empty($meta['og:url'])){
			$meta['twitter:url'] = $meta['og:url'];
		}
		if(empty($meta['twitter:image']) && !empty($meta['og:image'])){
			$meta['twitter:image'] = $meta['og:image'];
		}
		return <<<EOD
			<meta charset="{$meta['charset']}">
			<meta name="viewport" content="{$meta['viewport']}">
			<meta name="description" content="{$meta['description']}">
			<meta name="keywords" content="{$meta['keywords']}">
			<meta name="author" content="{$meta['author']}">
			<meta property="og:title" content="{$meta['og:title']}">
			<meta property="og:image" content="{$meta['og:image']}">
			<meta property="og:description" content="{$meta['og:description']}">
			<meta property="og:url" content="{$meta['og:url']}">
			<meta property="og:site_name" content="{$meta['og:site_name']}">
			<meta property="og:type" content="{$meta['og:type']}">
			<meta name="twitter:card" content="{$meta['twitter:card']}">
			<meta name="twitter:url" content="{$meta['twitter:url']}">
			<meta name="twitter:title" content="{$meta['twitter:title']}">
			<meta name="twitter:description" content="{$meta['twitter:description']}">
			<meta name="twitter:image" content="{$meta['twitter:image']}">
			<meta name="twitter:site" content="{$meta['twitter:site']}">
		EOD;
	}
	function api_call($method, $route, $payload){
		global $configs;

		$url = $configs['API_URL'] . $route;
		$curl = curl_init();
		switch ($method){
			case "POST":
			   curl_setopt($curl, CURLOPT_POST, 1);
			   if ($payload)
				  curl_setopt($curl, CURLOPT_POSTFIELDS, $payload);
			   break;
			case "PUT":
			   curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
			   if ($payload)
				  curl_setopt($curl, CURLOPT_POSTFIELDS, $payload);
			   break;
			case "PATCH":
				curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PATCH");
				if ($payload)
				   curl_setopt($curl, CURLOPT_POSTFIELDS, $payload);
				break;
			default:
			   if ($payload)
				  $url = sprintf("%s?%s", $url, http_build_query($payload));
		 }
		 // OPTIONS:
		 curl_setopt($curl, CURLOPT_URL, $url);
		 curl_setopt($curl, CURLOPT_USERAGENT, get_user_agent());
		 curl_setopt($curl, CURLOPT_HTTPHEADER, array(
			'Authorization: Bearer '.$_SESSION['auth_token'],
			'Content-Type: application/json',
			'X-Forwarded-For: '.get_ip(),
		 ));
		 curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		 curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BEARER);
		 // EXECUTE:
		 $result = curl_exec($curl);
		 if(!$result){die("API Connection Failure");}
		 curl_close($curl);
		 return $result;
	}
	function maybe_process_form(){
		if($_SERVER['REQUEST_METHOD'] === 'POST'){
			$method = false;
			$route = false;
			$sessionUpdate = false;

			if(isset($_POST['api-update'])){
				$method = 'POST';
				$route = "auth";
				$sessionUpdate = true;
			}

			elseif(isset($_POST['username-update'])){
				$method = 'PATCH';
				$route = "users/{$_SESSION['user_id']}/username";
				$sessionUpdate = true;
			}

			elseif(isset($_POST['email-update'])){
				$method = 'PATCH';
				$route = "users/{$_SESSION['user_id']}/email";
			}

			elseif(isset($_POST['discord-update'])){
				$method = 'PATCH';
				$route = "users/{$_SESSION['user_id']}/discord";
			}

			elseif(isset($_POST['chatango-update'])){
				$method = 'PATCH';
				$route = "users/{$_SESSION['user_id']}/chatango";
			}

			elseif(isset($_POST['twitter-update'])){
				$method = 'PATCH';
				$route = "users/{$_SESSION['user_id']}/twitter";
			}

			elseif(isset($_POST['royalrumble-entry-add'])){
				$method = 'POST';
				$route = "watchwrestling/royalrumbles/{$_POST['royalrumble_id']}";
				$_POST['user_id'] = $_SESSION['user_id'];
			}

			if($method && $route){
				$response = api_call($method, $route, json_encode($_POST));
				if($sessionUpdate){
					update_user_values();
				}
				return json_decode($response, true);
			}
		}
		return false;
	}
?>
