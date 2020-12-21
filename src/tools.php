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
	function track($action=''){
		global $db;
		$ip = get_ip();
		if($ip!='192.168.1.1' || !empty($action)){
			$user_agent = isset($_SERVER['HTTP_USER_AGENT'])?$_SERVER['HTTP_USER_AGENT']:'';
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
	function redirect($delay=0,$url=false){
		if(!$url){
			if(isset($_GET['redirect_to'])){
				$url = $_GET['redirect_to'];
			} else {
				$url = get_last_page();
			}
		}
		header("refresh:$delay;url=$url");
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
	function token_login_check(){
		if(isset($_GET['uid']) && isset($_GET['token'])){
			global $db;
			$res = $db->user_token_login($_GET['uid'], $_GET['token']);
			if($res){
				session_destroy();
				session_start();
				$_SESSION['user_id'] = $res['id'];
				$_SESSION['username'] = $res['username'];
				$_SESSION['access'] = $res['access'];
				$_SESSION['loggedin'] = true;
			}
			track("Token Login Attempt - uid:$_GET[uid]; result:".($res!=false?'1':'0'));
			if($res!=false){
				redirect(0);
				exit();
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
	function set_auth_values(){
		global $db;

		$auth = $db->auth_by_user_id($_SESSION['user_id']);
		if(!$auth){
			$auth = $db->add_auth_token($_SESSION['user_id']);
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
	function email($to, $subject, $message){
		$from = 'no-reply@idleuser.com';
		$header = "From: $from";
		return mail($to, $subject, $message, $header);
	}
	function api_call($url, $payload){

	}
?>
