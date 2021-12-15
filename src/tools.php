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

		# check cloudflare
		if (isset($_SERVER['HTTP_CF_CONNECTING_IP'])){
			return $_SERVER['HTTP_CF_CONNECTING_IP'];
		}

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
		$db->add_web_traffic($_SESSION['user_id'], $ip, $_SERVER['REQUEST_URI'], $user_agent, $action);
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
			redirect(0, '/login');
			exit();
		}
		if($_SESSION['access'] < 2){
			redirect(0, '/403.php');
			exit();
		}
	}
	function set_session_values($user){
		global $db;
		if($user){
			$_SESSION['loggedin'] = true;
			$_SESSION['user_id'] = $user['id'];
			$_SESSION['username'] = $user['username'];
			$_SESSION['access'] = $user['access'];
			$auth = $db->auth_by_user_id($user['id']);
			if(!$auth){
				$token = $db->add_auth_token($user['id']);
				$auth = $db->auth_by_token($token);
			}
			$_SESSION['auth_token'] = bin2hex($auth['auth_token']);
			$_SESSION['auth_token_exp'] = $auth['auth_token_exp'];
		}
	}
	function refresh_session_values(){
		global $db;
		$user = $db->user_info($_SESSION['user_id']);
		set_session_values($user);
	}
	function register($username, $secret){
		global $db;
		$user = $db->user_register($username, $secret);
		set_session_values($user);
		track("Register Attempt - username:{$username}; result:{$_SESSION['loggedin']}");
	}
	function username_login($username, $secret){
		global $db;
		$user = $db->username_login($username, $secret);
		set_session_values($user);
		track("Login Attempt - username:{$username}; result:{$_SESSION['loggedin']}");
	}
	function email_login($email, $secret){
		global $db;
		$user = $db->email_login($email, $secret);
		set_session_values($user);
		track("Login Attempt - email:{$email}; result:{$_SESSION['loggedin']}");
	}
	function logout(){
		$uid = $_SESSION['user_id'];
		session_destroy();
		track("Logout - uid:$uid");
	}
	function login_token_check(){
		if(isset($_GET['login_token'])){
			global $db;
			$user = $db->user_token_login($_GET['login_token']);
			set_session_values($user);
			track("Login Token Attempt - result:{$_SESSION['loggedin']}");
			if($_SESSION['loggedin']){
				maybe_redirect_to();
			}
		}
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
	function email($to, $subject, $content){
		global $configs;

		$headers = array(
			'From: ' . $configs['NO-REPLY_EMAIL'],
			'Reply-To: ' . $configs['NO-REPLY_EMAIL'],
			'MIME-Version: 1.0',
			'Content-type:text/html;charset=UTF-8',
			'X-Mailer: PHP/' . phpversion(),
		);
		$headers = implode("\r\n", $headers);
		$message = '<html><body style="font-family:Helvetica,sans-serif; font-size:13px;">';
		$message .= $content;
		$message .= '<div style="padding-top:20px;">';
		$message .= '<p style="font-size:10px;">';
		$message .= "This email message was delivered from a send-only address. Please do not reply to this automated message.";
		$message .= '</p>';
		$message .= '</div>';
		$message .= '</body></html>';
		return mail($to, $subject, $message, $headers);
	}
	function email_admin_contact_alert($fname, $lname, $email, $subject, $body, $user_ip, $user_id){
		global $configs;

		$subject = "Contact Request Received - {$configs['DOMAIN']} - $subject";
		$message = "<h2>A contact request was received on {$configs['DOMAIN']}.</h2>";
		$message .= "<div>Name:<pre>$fname $lname</pre><div>";
		$message .= "<div>Email:<pre>$email</pre><div>";
		$message .= "<div>IP:<pre>$user_ip</pre><div>";
		$message .= "<div>Is Registered:<pre>$user_id</pre><div>";
		$message .= "<div>Subject:<pre>$subject</pre><div>";
		$message .= "<div>Body:<pre>$body</pre><div>";
		return email($configs['ADMIN_EMAIL'], $subject, $message);
	}
	function email_reset_password_token($to, $username, $token){
		global $configs;

		$subject = 'Password Reset Request';
		$reset_url = "{$configs['WEBSITE']}reset-password?reset_token={$token}";
		$message = "<h2>Hello, {$username}!</h2>";
		$message .= '<div><p>';
		$message .= "Someone requested to reset your {$configs['DOMAIN']} account password. If it wasn't you, please ignore this email and no changes will be made to your account. However, if you have requested to reset your password, please click the link below. You will be redirected to the {$configs['DOMAIN']} password reset form.";
		$message .= '</p></div>';
		$message .= "<a href='{$reset_url}'>Click here to reset your password</a>";
		return email($to, $subject, $message);
	}
	function email_username($to, $username){
		global $configs;

		$subject = 'Username Recovery Request';
		$message = '<h2>Hello there!</h2>';
		$message .= '<div>';
		$message .= '<p>Forgot your username? No worries, it happens.</p>';
		$message .= '<p>Here is your username:</p>';
		$message .= "<a href='{$configs['WEBSITE']}/login'><strong>{$username}</strong></a>";
		$message .= "<p style='padding-top:15px;'>If you didn't request to recover your username, you can safely ignore this email.</p>";
		$message .= '</div>';
		return email($to, $subject, $message);
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
			$userUpdate = false;

			if(isset($_POST['api-update'])){
				$method = 'POST';
				$route = "auth";
				$userUpdate = true;
			}

			elseif(isset($_POST['username-update'])){
				$method = 'PATCH';
				$route = "users/{$_SESSION['user_id']}/username";
				$userUpdate = true;
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
				if($userUpdate){
					refresh_session_values();
				}
				return json_decode($response, true);
			}
		}
		return false;
	}
?>
