<?php

use ReCaptcha\ReCaptcha;
use ReCaptcha\RequestMethod\CurlPost;

function validate_recaptchaV2()
{
    require_once getenv('APP_PATH') . '/vendor/google/recaptcha/src/autoload.php';
    if (!isset($_POST['g-recaptcha-response'])) {
        throw new Exception('ReCaptcha is not set.');
    }
    $recaptcha = new ReCaptcha(getenv('RECAPTCHA_V2_SECRET'), new CurlPost());
    $response = $recaptcha->verify($_POST['g-recaptcha-response'], $_SERVER['REMOTE_ADDR']);
    return $response->isSuccess();
}

function get_ip()
{
    $ipVars = array(
        'HTTP_CF_CONNECTING_IP',
        'HTTP_CLIENT_IP',
        'HTTP_X_FORWARDED_FOR',
        'HTTP_X_FORWARDED',
        'HTTP_X_CLUSTER_CLIENT_IP',
        'HTTP_FORWARDED_FOR',
        'HTTP_FORWARDED',
        'REMOTE_ADDR'
    );

    $privateIP = '';

    foreach ($ipVars as $ipVar) {
        if (array_key_exists($ipVar, $_SERVER) === true) {
            foreach (explode(',', $_SERVER[$ipVar]) as $ip) {
                $ip = trim($ip);
                if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 | FILTER_FLAG_IPV6) !== false) {
                    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false) {
                        return $ip;
                    } elseif ($privateIP === '') {
                        $privateIP = $ip;
                    }
                }
            }
        }
    }

    if ($privateIP === '') {
        $privateIP = '0.0.0.0';
    }

    return $privateIP;
}

function track($note = null)
{
    global $db;
    $domain = $_SERVER['HTTP_HOST'];
    $request = "$_SERVER[REQUEST_METHOD] $_SERVER[REQUEST_URI]";
    $user_id = $_SESSION['profile']['id'] ?? 0;
    $ip = get_ip();
    $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? '';
    $db->add_web_traffic($domain, $user_id, $ip, $request, $user_agent, $note);
}

function get_direct_to()
{
    return 'redirect_to=' . urlencode($_SERVER['REQUEST_URI']);
}

function redirect_back()
{
    header("Location: ..");
    exit();
}

function redirect($delay = 0, $url = false)
{
    if (!$url) {
        maybe_redirect_to();
        $url = get_last_page();
    }
    header("refresh:$delay;url=$url");
}

function maybe_redirect_to($delay = 0)
{
    if (isset($_GET['redirect_to'])) {
        $url = $_GET['redirect_to'];
        redirect($delay, $url);
        exit();
    }
}

function set_last_page()
{
    $_SESSION['last_page'] = $_SERVER['REQUEST_URI'];
}

function get_last_page()
{
    if (!isset($_SESSION['last_page'])) {
        $_SESSION['last_page'] = $_SESSION['loggedin'] ? '/account' : '/';
    }
    return $_SESSION['last_page'];
}

function random_hex_color($offset = false, $min = 0x000000, $max = 0xFFFFFF)
{
    $color_code = rand($min, $max);
    $color_code = $offset ? dechex($color_code & $offset) : dechex($color_code);
    return '#' . str_pad($color_code, 6, 0, STR_PAD_LEFT);
}

function requires_login()
{
    if (!$_SESSION['loggedin']) {
        redirect(0, '/login');
        exit();
    }
}

function requires_mod()
{
    requires_login();

    if (!is_mod()) {
        redirect(0, '/403.php');
        exit();
    }
}

function requires_admin()
{
    requires_login();

    if (!is_admin()) {
        redirect(0, '/403.php');
        exit();
    }
}

function is_mod()
{
    return $_SESSION['loggedin'] && $_SESSION['profile']['access'] > 1;
}

function is_admin()
{
    return $_SESSION['loggedin'] && $_SESSION['profile']['access'] === 3;
}

function is_banned()
{
    return $_SESSION['loggedin'] && $_SESSION['profile']['access'] < 1;
}

function logout()
{
    $uid = $_SESSION['profile']['id'];
    session_destroy();
    track("Logout - uid:$uid");
}

function login_token_check()
{
    if (isset($_GET['login_token'])) {
        $res = api_call('GET', 'users/login/token?login_token=' . $_GET['login_token']);
        if ($res['statusCode'] === 200) {
            $_SESSION['profile'] = array_replace($_SESSION['profile'] ?? array(), $res['data']);
            $_SESSION['loggedin'] = true;
        }
        track("Login Token Attempt - result:" . $_SESSION['loggedin'] ?: '0');
        if ($_SESSION['loggedin']) {
            maybe_redirect_to();
        }
    }
}

function check_auth()
{
    if (!$_SESSION['loggedin']) {
        return;
    }
    if (is_banned()) {
        logout();
        redirect(0, '/login.php');
        exit();
    }
    $res = api_call('GET', 'auth');
    if ($res['statusCode'] === 200) {
        $res = api_call('GET', 'users/' . $_SESSION['profile']['id']);
        $_SESSION['profile'] = array_replace($_SESSION['profile'] ?? array(), $res['data']);
        $_SESSION['loggedin'] = true;
    } else {
        track("Check Auth Failed Attempt - user_id:" . $_SESSION['profile']['id']);
        logout();
        redirect(0, '/login.php');
        exit();
    }
}

function page_meta($meta)
{
    $DEFAULT_META = [
        "charset" => "utf-8",
        "viewport" => "width=device-width, initial-scale=1, shrink-to-fit=no",
        "author" => "Jesus Andrade",
        "description" => "",
        "keywords" => "idleuser, Jesus Andrade, website, developer, services, programmer, wrestling, poll, database, analyst, discord, projects, watchwrestling, work, background, profile, web developer",
        "og:title" => "",
        "og:description" => "",
        "og:url" => "https://$_SERVER[SERVER_NAME]",
        "og:image" => "https://$_SERVER[SERVER_NAME]/assets/images/favicon-512x512.png",
        "og:site_name" => "idleuser",
        "og:type" => "website",
        "twitter:title" => "",
        "twitter:description" => "",
        "twitter:url" => "",
        "twitter:image" => "",
        "twitter:card" => "summary",
        "twitter:site" => "",
        "twitter:creator" => ""
    ];
    if ($meta) {
        $keywords = '';
        if (isset($meta['keywords'])) {
            $keywords = $meta['keywords'] . ', ' . $DEFAULT_META['keywords'];
        }
        $meta = array_replace($DEFAULT_META, $meta);
        $meta['keywords'] = empty($keywords) ? $DEFAULT_META['keywords'] : $keywords;
    } else {
        $meta = $DEFAULT_META;
    }
    if (empty($meta['description']) && !empty($meta['og:description'])) {
        $meta['description'] = $meta['og:description'];
    }
    if (empty($meta['twitter:title']) && !empty($meta['og:title'])) {
        $meta['twitter:title'] = $meta['og:title'];
    }
    if (empty($meta['twitter:description']) && !empty($meta['og:description'])) {
        $meta['twitter:description'] = $meta['og:description'];
    }
    if (empty($meta['twitter:url']) && !empty($meta['og:url'])) {
        $meta['twitter:url'] = $meta['og:url'];
    }
    if (empty($meta['twitter:image']) && !empty($meta['og:image'])) {
        $meta['twitter:image'] = $meta['og:image'];
    }
    $output = <<<EOD
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
    $rybbit_website = getenv('RYBBIT_WEBSITE');
    $rybbit_site_id = getenv('RYBBIT_SITE_ID');
    if($rybbit_website && $rybbit_site_id) {
        $output .= <<<EOD
        <script src="{$rybbit_website}/api/script.js" data-site-id="{$rybbit_site_id}" defer></script>
        EOD;
    }
    return $output;
}

function is_email_ignore($email)
{
    global $db;
    $email_ignores = $db->all_email_ignore();
    foreach ($email_ignores as $email_ignore) {
        $pattern = str_replace(['*', '%'], '.*', $email_ignore['contains']);
        if (preg_match('/' . $pattern . '$/i', $email)) {
            $db->email_ignore_hit($email_ignore['id']);
            return true;
        }
    }
    return false;
}

function email($to, $subject, $content)
{
    $noreply_email = getenv('NOREPLY_EMAIL');
    $headers = array(
        'From: ' . $noreply_email,
        'Reply-To: ' . $noreply_email,
        'MIME-Version: 1.0',
        'Content-type:text/html;charset=UTF-8',
        'X-Mailer: PHP/' . phpversion(),
    );
    $headers = implode("\r\n", $headers);
    $message = '<html lang="en"><body style="font-family:Helvetica,sans-serif; font-size:13px;">';
    $message .= $content;
    $message .= '<div style="padding-top:20px;">';
    $message .= '<p style="font-size:10px;">';
    $message .= "This email message was delivered from a send-only address. Please do not reply to this automated message.";
    $message .= '</p>';
    $message .= '</div>';
    $message .= '</body></html>';
//    return mail($to, $subject, $message, $headers); // SMTP
    return mailgun_api($to, $subject, $message, $headers); //  mailgun API
}

function mailgun_api($to, $subject, $message, $headers)
{
    $url = 'https://api.mailgun.net/v3/' . getenv('MAILGUN_DOMAIN') . '/messages';
    $payload = [
        'to' => $to,
        'from' => getenv('NOREPLY_EMAIL'),
        'subject' => $subject,
        'text' => strip_tags($message),
        'html' => $message,
        'h' => $headers,
    ];
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $payload);
    curl_setopt($curl, CURLOPT_USERPWD, "api:" . getenv('MAILGUN_DOMAIN_KEY'));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($curl);
    curl_close($curl);
    if (!$response) {
        die("API Connection Failure");
    }
    $response = json_decode($response, true);
    return isset($response['id']);
}

function email_admin_contact_alert($fname, $lname, $email, $user_subject, $body, $user_ip, $user_id)
{
    if (is_email_ignore($email)) return false;

    $domain = getenv('DOMAIN');
    $subject = "Contact Request Received - $domain - $user_subject";
    $message = "<h2>A contact request was received on $domain.</h2>";
    $message .= "<div>Name:<pre>$fname $lname</pre><div>";
    $message .= "<div>Email:<pre>$email</pre><div>";
    $message .= "<div>IP:<pre>$user_ip</pre><div>";
    $message .= "<div>Is Registered:<pre>" . ($user_id ?: '0') . "</pre><div>";
    $message .= "<div>Subject:<pre>$user_subject</pre><div>";
    $message .= "<div>Body:<pre>$body</pre><div>";
    return email(getenv('ADMIN_EMAIL'), $subject, $message);
}

function email_reset_password_token($to, $username, $token)
{
    $website = getenv('WEBSITE');
    $domain = getenv('DOMAIN');
    $subject = 'Password Reset Request';
    $reset_url = "{$website}reset-password?reset_token=$token";
    $message = "<h2>Hello, $username!</h2>";
    $message .= '<div><p>';
    $message .= "Someone requested to reset your $domain account password. If it wasn't you, please ignore this email and no changes will be made to your account. However, if you have requested to reset your password, please click the link below. You will be redirected to the $domain password reset form.";
    $message .= '</p></div>';
    $message .= "<a href='$reset_url'>Click here to reset your password</a>";
    return email($to, $subject, $message);
}

function email_username($to, $username)
{
    $website = getenv('WEBSITE');
    $subject = 'Username Recovery Request';
    $message = '<h2>Hello there!</h2>';
    $message .= '<div>';
    $message .= '<p>Forgot your username? No worries, it happens.</p>';
    $message .= '<p>Here is your username:</p>';
    $message .= "<a href='$website/login'><strong>$username</strong></a>";
    $message .= "<p style='padding-top:15px;'>If you didn't request to recover your username, you can safely ignore this email.</p>";
    $message .= '</div>';
    return email($to, $subject, $message);
}

function api_call($method, $route, $payload = null)
{
    $url = getenv('API_URL') . $route;
    $curl = curl_init();
    switch ($method) {
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
    curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT'] ?? '');
    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
        $_SESSION['loggedin'] ? ('Authorization: Bearer ' . $_SESSION['profile']['auth_token']) : '',
        'Content-Type: application/json',
        'Client-IP: ' . get_ip(),
    ));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BEARER);
    // EXECUTE:
    $response = curl_exec($curl);
    if (!$response) {
        die("API Connection Failure");
    }
    curl_close($curl);
    return json_decode($response, true);
}

function maybe_process_form()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $method = false;
        $route = false;
        $userUpdate = false;

        if (isset($_POST['login'])) {
            $method = 'POST';
            $route = "users/login";
            $userUpdate = true;
        } elseif (isset($_POST['register'])) {
            $method = 'POST';
            $route = "users/register";
            $userUpdate = true;
        } elseif (isset($_POST['auth-token-update'])) {
            $method = 'POST';
            $route = "auth";
            $userUpdate = true;
        } elseif (isset($_POST['secret-reset'])) {
            $method = 'POST';
            $route = "users/secret/reset";
        } elseif (isset($_POST['secret-update'])) {
            $method = 'PATCH';
            $route = "users/{$_SESSION['profile']['id']}/secret";
            $userUpdate = true;
        } elseif (isset($_POST['username-update'])) {
            $method = 'PATCH';
            $route = "users/{$_SESSION['profile']['id']}/username";
            $userUpdate = true;
        } elseif (isset($_POST['email-update'])) {
            $method = 'PATCH';
            $route = "users/{$_SESSION['profile']['id']}/email";
            $userUpdate = true;
        } elseif (isset($_POST['discord-update'])) {
            $method = 'PATCH';
            $route = "users/{$_SESSION['profile']['id']}/discord";
            $userUpdate = true;
        } elseif (isset($_POST['chatango-update'])) {
            $method = 'PATCH';
            $route = "users/{$_SESSION['profile']['id']}/chatango";
            $userUpdate = true;
        } elseif (isset($_POST['twitter-update'])) {
            $method = 'PATCH';
            $route = "users/{$_SESSION['profile']['id']}/twitter";
            $userUpdate = true;
        } elseif (isset($_POST['royalrumble-entry-add'])) {
            $method = 'POST';
            $route = "watchwrestling/royalrumbles/{$_POST['royalrumble_id']}";
            $_POST['user_id'] = $_SESSION['profile']['id'];
        } elseif (isset($_POST['matches-bet'])) {
            $method = 'POST';
            $route = "watchwrestling/bet";
            $_POST['user_id'] = $_SESSION['profile']['id'];
        } elseif (isset($_POST['matches-rate'])) {
            $method = 'POST';
            $route = "watchwrestling/rate";
            $_POST['user_id'] = $_SESSION['profile']['id'];
        } elseif (isset($_POST['matches-favorite'])) {
            $method = 'POST';
            $route = "watchwrestling/favorite";
            $_POST['user_id'] = $_SESSION['profile']['id'];
        }

        if ($method && $route) {
            $response = api_call($method, $route, json_encode($_POST));
            if ($userUpdate && $response['statusCode'] === 200) {
                $_SESSION['profile'] = array_replace($_SESSION['profile'] ?? array(), $response['data']);
            }
            return $response;
        }
    }
    return false;
}

