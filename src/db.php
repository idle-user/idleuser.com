<?php
class MYSQLHandler{

	protected $DB_CONN;
	private $host;
	private $user;
	private $secret;
	private $schema;

	public function __construct($configs){
		$this->host = $configs['DB_HOST'];
		$this->user = $configs['DB_USER'];
		$this->secret = $configs['DB_SECRET'];
		$this->schema = $configs['DB_NAME'];
	}

	public function connect(){
		$this->DB_CONN = new mysqli($this->host, $this->user, $this->secret, $this->schema);
		mysqli_set_charset($this->DB_CONN, 'utf8');
		return !$this->DB_CONN->connect_errno;
	}

	public function close(){
		return $this->DB_CONN->close();
	}

	public function charset(){
		return mysqli_character_set_name($this->DB_CONN);
	}

	public function get_err(){
		return '['.$this->DB_CONN->sqlstate.'] '.$this->DB_CONN->error;
	}

	// UTILS

	public function get_uuid(){
		return $this->DB_CONN->query('SELECT UUID()')->fetch_array()[0];
	}

	// GENERAL

	public function add_web_traffic($user_id, $ip, $uri, $user_agent, $user_action){
		$query = 'CALL usp_web_ins_traffic(?, INET_ATON(?), ?, ?, ?)';
		$stmt = $this->DB_CONN->prepare($query);
		$stmt->bind_param('issss', $user_id, $ip, $uri, $user_agent, $user_action);
		return $stmt->execute();
	}

	public function add_web_contact($fname, $lname, $email, $subject, $body, $ip, $user_id=0){
		$id = $this->get_uuid();
		$query = 'INSERT INTO web_contact (id, fname, lname, email, subject, body, ip, user_id, received_dt) VALUES (UUID_TO_BIN(?), ?, ?, ?, ?, ?, INET_ATON(?), ?, NOW())';
		$stmt = $this->DB_CONN->prepare($query);
		$stmt->bind_param('sssssssi', $id, $fname, $lname, $email, $subject, $body, $ip, $user_id);
		return $stmt->execute();
	}

	// AUTH

	public function auth_token($user_id){
		$query = 'SELECT auth_token, auth_token_exp FROM api_auth WHERE user_id=? AND auth_token_exp>NOW()';
		$stmt = $this->DB_CONN->prepare($query);
		$stmt->bind_param('i', $user_id);
		$stmt->execute();
		return $stmt->get_result()->fetch_array(MYSQLI_ASSOC);
	}

	public function add_auth_token($user_id){
		$token = random_bytes(32);
		$query = 'CALL usp_api_ins_auth(?, ?)';
		$stmt = $this->DB_CONN->prepare($query);
		$stmt->bind_param('is', $user_id, $token);
		$stmt->execute();
		return $token;
	}

	// USER

	public function all_recent_users(){
		$query = 'SELECT id, username, discord_id, chatango_id, date_created FROM user ORDER BY date_created DESC';
		$data = $this->DB_CONN->query($query);
		$result = [];
		while($r = $data->fetch_array(MYSQLI_ASSOC)){
			$result[$r['id']] = $r;
		}
		return $result;
	}

	public function user_info($id){
		$query = 'SELECT * FROM user WHERE id=?';
		$stmt = $this->DB_CONN->prepare($query);
		$stmt->bind_param('i', $id);
		$stmt->execute();
		$user = $stmt->get_result()->fetch_array(MYSQLI_ASSOC);
		if($user){
			unset($user['secret']);
			unset($user['secret_last_updated']);
			unset($user['login_token']);
			unset($user['login_token_exp']);
			unset($user['temp_secret']);
			unset($user['temp_secret_last_updated']);
		}
		return $user;
	}

	public function username_info($username){
		$query = 'SELECT * FROM user WHERE username=?';
		$stmt = $this->DB_CONN->prepare($query);
		$stmt->bind_param('s', $username);
		$stmt->execute();
		$user = $stmt->get_result()->fetch_array(MYSQLI_ASSOC);
		if($user){
			unset($user['secret']);
			unset($user['secret_last_updated']);
			unset($user['login_token']);
			unset($user['login_token_exp']);
			unset($user['temp_secret']);
			unset($user['temp_secret_last_updated']);
		}
		return $user;
	}

	public function user_secret($username){
		$query = 'SELECT secret FROM user WHERE username=?';
		$stmt = $this->DB_CONN->prepare($query);
		$stmt->bind_param('s', $username);
		$stmt->execute();
		return $stmt->get_result()->fetch_array(MYSQLI_ASSOC)['secret'];
	}

	public function user_register($username, $secret){
		$user_info = $this->user_info($username);
		if(!$user_info){
			$query = '
				INSERT INTO user (username, secret, date_created, last_login)
				VALUES (?, ?, NOW(), NOW())';
			$stmt = $this->DB_CONN->prepare($query);
			$hash = password_hash($secret, PASSWORD_BCRYPT);
			$stmt->bind_param('ss', $username, $hash);
			$stmt->execute();
			$user_info = $this->user_info(mysqli_insert_id($this->DB_CONN));
			return $user_info;
		}
		return false;
	}

	public function user_login($username, $secret){
		if(password_verify($secret, $this->user_secret($username))){
			$query = 'UPDATE user SET last_login=NOW() WHERE username=?';
			$stmt = $this->DB_CONN->prepare($query);
			$stmt->bind_param('s', $username);
			$stmt->execute();
			$user_info = $this->username_info($username);
			return $user_info;
		}
		return false;
	}

	public function user_token_login($user_id, $token){
		$query = 'CALL usp_user_upd_login_with_token(?, ?)';
		$stmt = $this->DB_CONN->prepare($query);
		$stmt->bind_param('is', $user_id, $token);
		$stmt->execute();
		if($stmt->affected_rows == 1){
			$user_info = $this->user_info($user_id);
			return $user_info;
		}
		return false;
	}

	public function user_update_login_token($user_id, $username){
		$token = substr(md5(microtime()), 0, 15);
		$query = 'CALL usp_user_upd_login_token(?, ?, ?)';
		$stmt = $this->DB_CONN->prepare($query);
		$stmt->bind_param('iss', $user_id, $username, $token);
		if($stmt->execute()){
			return $token;
		}
		return false;
	}

	public function user_update_temp_secret($user_id, $username){
		$temp = substr(md5(microtime()), 0, 15);
		$query = 'CALL usp_user_upd_temp_secret(?, ?, ?)';
		$stmt = $this->DB_CONN->prepare($query);
		$stmt->bind_param('iss', $user_id, $username, $temp);
		if($stmt->execute()){
			return $temp;
		}
		return false;
	}

	public function user_reset_password($user_id, $username, $temp, $secret){
		$query = 'CALL usp_user_upd_secret_with_temp(?, ?, ?, ?)';
		$stmt = $this->DB_CONN->prepare($query);
		$hash = password_hash($secret, PASSWORD_BCRYPT);
		$stmt->bind_param('isss', $user_id, $username, $temp, $hash);
		$stmt->execute();
		if($stmt->affected_rows == 1){
			$user_info = $this->user_info($user_id);
			return $user_info;
		}
		return false;
	}

	public function user_change_password($user_id, $username, $old_secret, $new_secret){
		if(password_verify($old_secret, $this->user_secret($username))){
			$query = 'CALL usp_user_upd_secret(?, ?, ?)';
			$stmt = $this->DB_CONN->prepare($query);
			$hash = password_hash($new_secret, PASSWORD_BCRYPT);
			$stmt->bind_param('iss', $user_id, $username, $hash);
			$stmt->execute();
			if($stmt->affected_rows == 1){
				$user_info = $this->user_info($user_id);
				return $user_info;
			}
		}
		return false;
	}

	public function user_email($user_id){
		$query = 'SELECT email FROM user WHERE id=?';
		$stmt = $this->DB_CONN->prepare($query);
		$stmt->bind_param('i', $user_id);
		$stmt->execute();
		return $stmt->get_result()->fetch_array(MYSQLI_ASSOC);
	}

	public function user_email_link($user_id, $email){
		$query = '
			UPDATE user
			SET email=?, email_last_updated=NOW()
			WHERE id=?';
		$stmt = $this->DB_CONN->prepare($query);
		$stmt->bind_param('si', $email, $user_id);
		return $stmt->execute();
	}

	// TWITTER

	public function user_twitter($user_id){
		$query = 'SELECT twitter_id FROM user WHERE id=?';
		$stmt = $this->DB_CONN->prepare($query);
		$stmt->bind_param('i', $user_id);
		$stmt->execute();
		return $stmt->get_result()->fetch_array(MYSQLI_ASSOC);
	}

	// DISCORD

	public function user_discord($user_id){
		$query = 'SELECT discord_id FROM user WHERE id=?';
		$stmt = $this->DB_CONN->prepare($query);
		$stmt->bind_param('i', $user_id);
		$stmt->execute();
		return $stmt->get_result()->fetch_array(MYSQLI_ASSOC);
	}

	public function user_discord_link($user_id, $discord_id){
		$query = '
			UPDATE user
			SET discord_id=?, discord_last_updated=NOW()
			WHERE id=?';
		$stmt = $this->DB_CONN->prepare($query);
		$stmt->bind_param('si', $discord_id, $user_id);
		return $stmt->execute();
	}

	public function all_discord_commands(){
		$query = 'SELECT * FROM chatroom_command order by command';
		$data = $this->DB_CONN->query($query);
		$result = [];
		while($r = $data->fetch_array(MYSQLI_ASSOC)){
			$result[$r['id']] = $r;
		}
		return $result;
	}

	public function discord_command($id){
		$query = 'SELECT * FROM chatroom_command WHERE id=?';
		$stmt = $this->DB_CONN->prepare($query);
		$stmt->bind_param('i', $id);
		$stmt->execute();
		return $stmt->get_result()->fetch_array(MYSQLI_ASSOC);
	}

	public function add_discord_command($command, $response, $description=''){
		$query = 'INSERT INTO chatroom_command (command, response, description, last_updated) VALUES (?, ?, ?, NOW())';
		$stmt = $this->DB_CONN->prepare($query);
		$success = $stmt->bind_param('sss', $command, $response, $description);
		if($success){
			$success = $stmt->execute();
		}
		return $success?mysqli_insert_id($this->DB_CONN):false;
	}

	public function update_discord_command($id, $command, $response, $description=''){
		$query = 'UPDATE chatroom_command SET command=?, response=?, description=?, last_updated=NOW() WHERE id=?';
		$stmt = $this->DB_CONN->prepare($query);
		$stmt->bind_param('sssi', $command, $response, $description, $id);
		return $stmt->execute();
	}

	public function all_discord_schedules(){
		$query = 'SELECT * FROM chatroom_scheduler';
		$data = $this->DB_CONN->query($query);
		$result = [];
		while($r = $data->fetch_array(MYSQLI_ASSOC)){
			$result[$r['id']] = $r;
		}
		return $result;
	}

	public function add_discord_schedule($name, $description, $message, $tweet, $start_time, $sunday_flag, $monday_flag, $tuesday_flag, $wednesday_flag, $thursday_flag, $friday_flag, $saturday_flag, $active){
		$query = '
			INSERT INTO chatroom_scheduler (
				name, description, message, tweet,
				start_time,
				sunday_flag, monday_flag, tuesday_flag, wednesday_flag,thursday_flag, friday_flag, saturday_flag,
				active
			) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
		$stmt = $this->DB_CONN->prepare($query);
		$stmt->bind_param('sssssiiiiiiii', $name, $description, $message, $tweet, $start_time, $sunday_flag, $monday_flag, $tuesday_flag, $wednesday_flag, $thursday_flag, $friday_flag, $saturday_flag, $active);
		return $stmt->execute();
	}

	public function updated_discord_schedule($id, $name, $description, $message, $tweet, $start_time, $sunday_flag, $monday_flag, $tuesday_flag, $wednesday_flag, $thursday_flag, $friday_flag, $saturday_flag, $active){
		$query = '
			UPDATE chatroom_scheduler SET
				name=?, description=?, message=?, tweet=?,
				start_time=?,
				sunday_flag=?, monday_flag=?, tuesday_flag=?, wednesday_flag=?, thursday_flag=?, friday_flag=?, saturday_flag=?,
				active=?
			WHERE id=?';
		$stmt = $this->DB_CONN->prepare($query);
		$stmt->bind_param('sssssiiiiiiiii', $name, $description, $message, $tweet, $start_time, $sunday_flag, $monday_flag, $tuesday_flag, $wednesday_flag, $thursday_flag, $friday_flag, $saturday_flag, $active, $id);
		return $stmt->execute();
	}

	// CHATANGO

	public function user_chatango($user_id){
		$query = 'SELECT chatango_id FROM user WHERE id=?';
		$stmt = $this->DB_CONN->prepare($query);
		$stmt->bind_param('i', $user_id);
		$stmt->execute();
		return $stmt->get_result()->fetch_array(MYSQLI_ASSOC);
	}

	public function user_chatango_link($user_id, $chatango_id){
		$query = '
			UPDATE user
			SET chatango_id=?, chatango_last_updated=NOW()
			WHERE id=?';
		$stmt = $this->DB_CONN->prepare($query);
		$stmt->bind_param('si', $chatango_id, $user_id);
		return $stmt->execute();
	}

	// QUOTE

	public function get_quote(){
		$query = 'SELECT * FROM quote ORDER BY RAND() LIMIT 1';
		return $this->DB_CONN->query($query)->fetch_array(MYSQLI_ASSOC);
	}

	// CHATROOM

	public function chatroom_history(){
		$query = '
			SELECT message_id, chatroom.user_id, username, message, time
			FROM chatroom
			LEFT JOIN user ON user.id=chatroom.user_id AND user.access>0
			ORDER BY time DESC LIMIT 50';
		$data = $this->DB_CONN->query($query);
		$result = [];
		while($r = $data->fetch_array(MYSQLI_ASSOC)){
			$result[] = $r;
		}
		$result = array_reverse($result);
		return $result;
	}

	public function chatroom_update($last_message_time){
		$query = '
			SELECT message_id, chatroom.user_id, username, message, time
			FROM chatroom
			LEFT JOIN user ON user.id=chatroom.user_id AND user.access>0
			WHERE time > ?
			ORDER BY time';
		$stmt = $this->DB_CONN->prepare($query);
		$stmt->bind_param('s', $last_message_time);
		$stmt->execute();
		$data = $stmt->get_result();
		$result = [];
		while($r = $data->fetch_array(MYSQLI_ASSOC)){
			$result[] = $r;
		}
		return $result;
	}

	public function chatroom_send_message($user_id, $message){
		$message = trim($message);
		if($message == ''){
			return false;
		}
		$tokens = explode(' ',$message);
		foreach($tokens as $token){
			if(strlen($token)>50){
				return false;
			}
		}
		$query = 'INSERT INTO chatroom (user_id, message, time) VALUES (?, ?, NOW())';
		$stmt = $this->DB_CONN->prepare($query);
		$success = $stmt->bind_param('is', $user_id, $message);
		if($success){
			$success = $stmt->execute();
		}
		return $success;
	}

	// LED-VOTE

	public function led_total_votes(){
		$query = 'SELECT user.username, led.* FROM led JOIN user ON user.id=led.user_id';
		return $this->DB_CONN->query($query);
	}

	public function led_user_votes($user_id){
		$query = 'SELECT * FROM led WHERE user_id=?';
		$stmt = $this->DB_CONN->prepare($query);
		$stmt->bind_param('i', $user_id);
		$stmt->execute();
		return $stmt->get_result()->fetch_array(MYSQLI_ASSOC);
	}

	public function led_vote($user_id, $color_id){
		if(!$this->led_user_votes($user_id)){
			$query = 'INSERT INTO led (user_id) VALUES (?)';
			$stmt = $this->DB_CONN->prepare($query);
			$stmt->bind_param('i', $user_id);
			$stmt->execute();
		}
		$query = 'UPDATE led SET ' . $color_id . '='. $color_id . '+1 WHERE user_id=?';
		$stmt = $this->DB_CONN->prepare($query);
		if($stmt && $stmt->bind_param('i', $user_id)){
			return $stmt->execute();
		}
		return false;
	}

	// MATCHES

	public function matches_base_data(){
		$tables = ['matches_title', 'matches_brand', 'matches_match_type'];
		$result = [];
		foreach($tables as $table){
			$data = $this->DB_CONN->query("SELECT * FROM $table");
			$rows = [];
			while($r = $data->fetch_array(MYSQLI_ASSOC)){
				$rows[$r['id']] = $r;
			}
			$result[$table] = $rows;
		}
		return $result;
	}

	public function all_superstars(){
		$data = $this->DB_CONN->query('SELECT * FROM matches_superstar ORDER BY name');
		$result = [];
		while($r = $data->fetch_array(MYSQLI_ASSOC)){
			$result[$r['id']] = $r;
		}
		return $result;
	}

	public function superstar($superstar_id){
		$query = 'SELECT * FROM matches_superstar WHERE id=?';
		$stmt = $this->DB_CONN->prepare($query);
		$stmt->bind_param('i', $superstar_id);
		$stmt->execute();
		return $stmt->get_result()->fetch_array(MYSQLI_ASSOC);
	}

	public function superstar_info($name){
		$query = 'SELECT * FROM matches_superstar WHERE name=?';
		$stmt = $this->DB_CONN->prepare($query);
		$stmt->bind_param('s', $name);
		$stmt->execute();
		return $stmt->get_result()->fetch_array(MYSQLI_ASSOC);
	}

	public function brand_superstars($brand_id){
		$query = 'SELECT * FROM matches_superstar WHERE brand_id=? ORDER BY name';
		$stmt = $this->DB_CONN->prepare($query);
		$stmt->bind_param('i', $brand_id);
		$stmt->execute();
		$data = $stmt->get_result();
		$result = [];
		while($r = $data->fetch_array(MYSQLI_ASSOC)){
			$result[$r['id']] = $r;
		}
		return $result;
	}

	public function all_matches(){
		$data = $this->DB_CONN->query('SELECT * FROM uv_matches ORDER BY date DESC, bet_open DESC, base_pot DESC, id DESC');
		$result = [];
		while($r = $data->fetch_array(MYSQLI_ASSOC)){
			$result[$r['id']] = $r;
		}
		return $result;
	}

	public function all_matches_recently_updated(){
		$data = $this->DB_CONN->query('SELECT * FROM uv_matches ORDER BY info_last_updated DESC');
		$result = [];
		while($r = $data->fetch_array(MYSQLI_ASSOC)){
			$result[$r['id']] = $r;
		}
		return $result;
	}

	public function s1_matches(){
		$data = $this->DB_CONN->query('
			SELECT * FROM uv_matches
			WHERE id < 369
			ORDER BY date DESC, bet_open DESC, base_pot DESC, id DESC');
		$result = [];
		while($r = $data->fetch_array(MYSQLI_ASSOC)){
			$result[$r['id']] = $r;
		}
		return $result;
	}

	public function s2_matches(){
		$data = $this->DB_CONN->query('
			SELECT * FROM uv_matches
			WHERE id BETWEEN 369 AND 712
			ORDER BY date DESC, bet_open DESC, base_pot DESC, id DESC');
		$result = [];
		while($r = $data->fetch_array(MYSQLI_ASSOC)){
			$result[$r['id']] = $r;
		}
		return $result;
	}

	public function s3_matches(){
		$data = $this->DB_CONN->query('
			SELECT * FROM uv_matches
			WHERE id BETWEEN 712 AND 919
			ORDER BY date DESC, bet_open DESC, base_pot DESC, id DESC');
		$result = [];
		while($r = $data->fetch_array(MYSQLI_ASSOC)){
			$result[$r['id']] = $r;
		}
		return $result;
	}

	public function s4_matches(){
		$data = $this->DB_CONN->query('
			SELECT * FROM uv_matches
			WHERE id > 919
			ORDER BY date DESC, bet_open DESC, base_pot DESC, id DESC');
		$result = [];
		while($r = $data->fetch_array(MYSQLI_ASSOC)){
			$result[$r['id']] = $r;
		}
		return $result;
	}

	public function match($match_id){
		$query = '
			SELECT *
			FROM uv_matches m
			WHERE m.id=?';
		$stmt = $this->DB_CONN->prepare($query);
		$stmt->bind_param('i', $match_id);
		$stmt->execute();
		return $stmt->get_result()->fetch_array(MYSQLI_ASSOC);
	}

	public function match2($match_id){
		$query = '
			SELECT *
			FROM uv_matches_all m
			WHERE m.id=?';
		$stmt = $this->DB_CONN->prepare($query);
		$stmt->bind_param('i', $match_id);
		$stmt->execute();
		return $stmt->get_result()->fetch_array(MYSQLI_ASSOC);
	}

	public function open_matches(){
		$data = $this->DB_CONN->query('
			SELECT * FROM uv_matches
			WHERE bet_open=1
			ORDER BY info_last_updated DESC');
		$result = [];
		while($r = $data->fetch_array(MYSQLI_ASSOC)){
			$result[$r['id']] = $r;
		}
		return $result;
	}

	public function todays_matches(){
		$data = $this->DB_CONN->query('
			SELECT * FROM uv_matches
			WHERE date=CURDATE()
			ORDER BY info_last_updated DESC');
		$result = [];
		while($r = $data->fetch_array(MYSQLI_ASSOC)){
			$result[$r['id']] = $r;
		}
		return $result;
	}

	public function title_matches($title_id){
		$query = '
			SELECT * FROM uv_matches
			WHERE title_id=?';
		$stmt = $this->DB_CONN->prepare($query);
		$stmt->bind_param('i', $title_id);
		$stmt->execute();
		$data = $stmt->get_result();
		$result = [];
		while($r = $data->fetch_array(MYSQLI_ASSOC)){
			$result[$r['id']] = $r;
		}
		return $result;
	}

	public function superstar_matches($superstar_id){
		$query = '
			SELECT
				vm.*
				,mc.*
			FROM uv_matches vm
			JOIN
				matches_contestant mc ON mc.superstar_id=?
				AND mc.match_id=vm.id';
		$stmt = $this->DB_CONN->prepare($query);
		$stmt->bind_param('i', $superstar_id);
		$stmt->execute();
		$data = $stmt->get_result();
		$result = [];
		while($r = $data->fetch_array(MYSQLI_ASSOC)){
			$result[$r['id']] = $r;
		}
		return $result;
	}

	public function user_matches($user_id){
		$query = '
			SELECT
				vm.*
				,IFNULL(umr.rating,0) AS matches_rating
				,IF(ubc.bet_won=1,ubc.potential_cut_points,0) AS points_won
			FROM uv_matches vm
			JOIN matches_bet_calculation ubc ON ubc.user_id=? AND ubc.match_id=vm.id
			LEFT JOIN matches_match_rating umr ON umr.user_id=? AND umr.match_id=vm.id
			ORDER BY vm.date DESC, vm.id DESC';
		$stmt = $this->DB_CONN->prepare($query);
		$stmt->bind_param('ii', $user_id, $user_id);
		$stmt->execute();
		$data = $stmt->get_result();
		$result = [];
		while($r = $data->fetch_array(MYSQLI_ASSOC)){
			$result[$r['id']] = $r;
		}
		return $result;
	}

	public function all_match_contestants(){
		$data = $this->DB_CONN->query('SELECT * FROM matches_contestant');
		$result = [];
		while($r = $data->fetch_array(MYSQLI_ASSOC)){
			$result[] = $r;
		}
		return $result;
	}

	public function match_contestants($match_id){
		$query = 'SELECT * FROM matches_contestant WHERE match_id=?';
		$stmt = $this->DB_CONN->prepare($query);
		$stmt->bind_param('i', $match_id);
		$stmt->execute();
		$data = $stmt->get_result();
		$result = [];
		while($r = $data->fetch_array(MYSQLI_ASSOC)){
			$result[] = $r;
		}
		return $result;
	}

	public function s1_leaderboard(){
		$data = $this->DB_CONN->query('
			SELECT
				vus.user_id
				,vus.username
				,vus.favorite_superstar_id
				,vus.s1_wins as wins
				,vus.s1_losses as losses
				,vus.s1_total_points as points
			FROM uv_matches_stats vus
			WHERE s1_wins+s1_losses > 0
			ORDER BY points DESC');
		$result = [];
		while($r = $data->fetch_array(MYSQLI_ASSOC)){
			$result[] = $r;
		}
		return $result;
	}

	public function s2_leaderboard(){
		$data = $this->DB_CONN->query('
			SELECT
				vus.user_id
				,vus.username
				,vus.favorite_superstar_id
				,vus.s2_wins as wins
				,vus.s2_losses as losses
				,vus.s2_total_points as points
			FROM uv_matches_stats vus
			WHERE s2_wins+s2_losses > 0
			ORDER BY points DESC');
		$result = [];
		while($r = $data->fetch_array(MYSQLI_ASSOC)){
			$result[] = $r;
		}
		return $result;
	}

	public function s3_leaderboard(){
		$data = $this->DB_CONN->query('
			SELECT
				vus.user_id
				,vus.username
				,vus.favorite_superstar_id
				,vus.s3_wins as wins
				,vus.s3_losses as losses
				,vus.s3_total_points as points
			FROM uv_matches_stats vus
			WHERE s3_wins+s3_losses > 0
			ORDER BY points DESC');
		$result = [];
		while($r = $data->fetch_array(MYSQLI_ASSOC)){
			$result[] = $r;
		}
		return $result;
	}

	public function s4_leaderboard(){
		$data = $this->DB_CONN->query('
			SELECT
				vus.user_id
				,vus.username
				,vus.favorite_superstar_id
				,vus.s4_wins as wins
				,vus.s4_losses as losses
				,vus.s4_total_points as points
			FROM uv_matches_stats vus
			WHERE s4_wins+s4_losses > 0
			ORDER BY points DESC');
		$result = [];
		while($r = $data->fetch_array(MYSQLI_ASSOC)){
			$result[] = $r;
		}
		return $result;
	}

	public function user_stats($user_id){
		$query='
			SELECT vus.*
			FROM uv_matches_stats vus
			WHERE vus.user_id=?';
		$stmt = $this->DB_CONN->prepare($query);
		$stmt->bind_param('i', $user_id);
		$stmt->execute();
		return $stmt->get_result()->fetch_array(MYSQLI_ASSOC);
	}

	public function all_user_bets(){
		$query = '
			SELECT username, match_id, team, points, dt_placed FROM matches_bet
			JOIN user on user.id=matches_bet.user_id
			ORDER BY match_id, dt_placed DESC';
		$stmt = $this->DB_CONN->prepare($query);
		$data = $this->DB_CONN->query($query);
		$result = [];
		while($r = $data->fetch_array(MYSQLI_ASSOC)){
			$result[$r['match_id']][] = $r;
		}
		return $result;
	}

	public function match_bets($match_id){
		$query = '
			SELECT username, team, points FROM matches_bet WHERE match_id=?
			JOIN user on user.id=matches_bet.user_id';
		$stmt = $this->DB_CONN->prepare($query);
		$stmt->bind_param('i', $match_id);
		$stmt->execute();
		$data = $stmt->get_result();
		$result = [];
		while($r = $data->fetch_array(MYSQLI_ASSOC)){
			$result[] = $r;
		}
		return $result;
	}

	public function user_bets($user_id){
		$query = '
			SELECT mbc.*, mb.team
			FROM matches_bet_calculation mbc
			JOIN matches_bet mb ON mb.match_id=mbc.match_id AND mb.user_id=mbc.user_id
			WHERE mbc.user_id=?';
		$stmt = $this->DB_CONN->prepare($query);
		$stmt->bind_param('i', $user_id);
		$stmt->execute();
		$data = $stmt->get_result();
		$result = [];
		while($r = $data->fetch_array(MYSQLI_ASSOC)){
			$result[$r['match_id']] = $r;
		}
		return $result;
	}

	public function user_match_bet($user_id, $match_id){
		$query = '
			SELECT mbc.*, mb.team
			FROM matches_bet_calculation mbc
			JOIN matches_bet mb ON mb.match_id=mbc.match_id AND mb.user_id=mbc.user_id
			WHERE mbc.user_id=? AND mbc.match_id=?';
		$stmt = $this->DB_CONN->prepare($query);
		$stmt->bind_param('ii', $user_id, $match_id);
		$stmt->execute();
		return $stmt->get_result()->fetch_array(MYSQLI_ASSOC);
	}

	public function user_rate_match($user_id, $match_id, $rating){
		$query = 'CALL usp_matches_ins_rating(?, ?, ?)';
		$stmt = $this->DB_CONN->prepare($query);
		$stmt->bind_param('iii', $user_id, $match_id, $rating);
		return $stmt->execute();
	}

	public function user_match_ratings($user_id){
		$query = 'SELECT * FROM matches_match_rating WHERE user_id=?';
		$stmt = $this->DB_CONN->prepare($query);
		$stmt->bind_param('i', $user_id);
		$stmt->execute();
		$data = $stmt->get_result();
		$result = [];
		while($r = $data->fetch_array(MYSQLI_ASSOC)){
			$result[$r['match_id']] = $r;
		}
		return $result;
	}

	public function user_match_rating($user_id, $match_id){
		$query = 'SELECT * FROM matches_match_rating WHERE user_id=? AND match_id=?';
		$stmt = $this->DB_CONN->prepare($query);
		$stmt->bind_param('ii', $user_id, $match_id);
		$stmt->execute();
		return $stmt->get_result()->fetch_array(MYSQLI_ASSOC);
	}

	public function all_stable_members(){
		$data = $this->DB_CONN->query('SELECT * FROM matches_stable_member');
		$result = [];
		while($r = $data->fetch_array(MYSQLI_ASSOC)){
			$result[] = $r;
		}
		return $result;
	}

	public function stable_members($stable_id){
		$query = 'SELECT * FROM matches_stable_member WHERE stable_id=?';
		$stmt = $this->DB_CONN->prepare($query);
		$stmt->bind_param('i', $stable_id);
		$stmt->execute();
		$data = $stmt->get_result();
		$result = [];
		while($r = $data->fetch_array(MYSQLI_ASSOC)){
			$result[] = $r['superstar_id'];
		}
		return $result;
	}

	public function add_stable($name){
		$query = 'INSERT INTO matches_stable (name) VALUES (?)';
		$stmt = $this->DB_CONN->prepare($query);
		$success = $stmt->bind_param('s', $name);
		if($success){
			$success = $stmt->execute();
		}
		return $success?mysqli_insert_id($this->DB_CONN):false;
	}

	public function update_stable($id, $name){
		$query = 'UPDATE matches_stable SET name=?,last_updated=NOW() WHERE id=?';
		$stmt = $this->DB_CONN->prepare($query);
		$success = $stmt->bind_param('si', $name, $id);
		if($success){
			$success = $stmt->execute();
		}
		return $success;
	}

	public function add_stable_member($stable_id, $superstar_id){
		$query = 'INSERT INTO matches_stable_member (stable_id, superstar_id) VALUES (?, ?)';
		$stmt = $this->DB_CONN->prepare($query);
		$success = $stmt->bind_param('ii', $stable_id, $superstar_id);
		if($success){
			$success = $stmt->execute();
		}
		return $success;
	}

	public function remove_all_stable_members($stable_id){
		$query = 'DELETE FROM matches_stable_member WHERE stable_id=?';
		$stmt = $this->DB_CONN->prepare($query);
		$success = $stmt->bind_param('i', $stable_id);
		if($success){
			$success = $stmt->execute();
		}
		return $success;
	}

	public function add_superstar($name, $brand_id, $height, $weight, $hometown, $dob, $signature_move, $page_url, $image_url, $bio){
		$superstar_info = $this->superstar_info($name);
		if(!$superstar_info){
			$query = '
				INSERT INTO matches_superstar (name, brand_id, height, weight, hometown, dob, signature_move, page_url, image_url, bio, last_updated)
				VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())';
			$stmt = $this->DB_CONN->prepare($query);
			$success = $stmt->bind_param('sissssssss', $name, $brand_id, $height, $weight, $hometown, $dob, $signature_move, $page_url, $image_url, $bio);
			if($success){
				$success = $stmt->execute();
			}
			return $success?mysqli_insert_id($this->DB_CONN):false;
		}
		return false;
	}

	public function update_superstar($id, $name, $brand_id, $height, $weight, $hometown, $dob, $signature_move, $page_url, $image_url, $bio){
		$query = '
			UPDATE matches_superstar
			SET
				name=?, brand_id=?, height=?, weight=?, hometown=?, dob=?, signature_move=?,
				page_url=?, image_url=?, bio=?, last_updated=NOW()
			WHERE id=?';
		$stmt = $this->DB_CONN->prepare($query);
		$success = $stmt->bind_param('sissssssssi', $name, $brand_id, $height, $weight, $hometown, $dob, $signature_move, $page_url, $image_url, $bio, $id);
		if($success){
			$success = $stmt->execute();
		}
		return $success;
	}

	public function update_superstar_twitter($id, $twitter_name){
		$query = '
			UPDATE matches_superstar_social
			SET twitter_name=?
			WHERE superstar_id=?';
		$stmt = $this->DB_CONN->prepare($query);
		$success = $stmt->bind_param('si', $twitter_name, $id);
		if($success){
			$success = $stmt->execute();
		}
		return $success;
	}

	public function all_brands(){
		$query = 'SELECT * FROM matches_brand order by name';
		$data = $this->DB_CONN->query($query);
		$result = [];
		while($r = $data->fetch_array(MYSQLI_ASSOC)){
			$result[$r['id']] = $r;
		}
		return $result;
	}

	public function brand($id){
		$query = 'SELECT * FROM matches_brand WHERE id=?';
		$stmt = $this->DB_CONN->prepare($query);
		$stmt->bind_param('i', $id);
		$stmt->execute();
		return $stmt->get_result()->fetch_array(MYSQLI_ASSOC);
	}

	public function add_brand($name, $image_url=''){
		$query = 'INSERT INTO matches_brand (name, image_url, last_updated) VALUES (?, ?, now())';
		$stmt = $this->DB_CONN->prepare($query);
		$success = $stmt->bind_param('ss', $name, $image_url);
		if($success){
			$success = $stmt->execute();
		}
		return $success?mysqli_insert_id($this->DB_CONN):false;
	}

	public function all_titles(){
		$query = 'SELECT * FROM matches_title order by name';
		$data = $this->DB_CONN->query($query);
		$result = [];
		while($r = $data->fetch_array(MYSQLI_ASSOC)){
			$result[$r['id']] = $r;
		}
		return $result;
	}

	public function title($id){
		$query = 'SELECT * FROM matches_title WHERE id=?';
		$stmt = $this->DB_CONN->prepare($query);
		$stmt->bind_param('i', $id);
		$stmt->execute();
		return $stmt->get_result()->fetch_array(MYSQLI_ASSOC);
	}

	public function update_brand($id, $name, $image_url){
		$query = 'UPDATE matches_brand SET name=?, image_url=?, last_updated=NOW() WHERE id=?';
		$stmt = $this->DB_CONN->prepare($query);
		$success = $stmt->bind_param('ssi', $name, $image_url, $id);
		if($success){
			$success = $stmt->execute();
		}
		return $success;
	}

	public function add_title($name){
		$query = 'INSERT INTO matches_title (name, last_updated) VALUES (?, NOW())';
		$stmt = $this->DB_CONN->prepare($query);
		$success = $stmt->bind_param('s', $name);
		if($success){
			$success = $stmt->execute();
		}
		return $success;
	}

	public function update_title($id, $name){
		$query = 'UPDATE matches_title SET name=?, last_updated=NOW() WHERE id=?';
		$stmt = $this->DB_CONN->prepare($query);
		$success = $stmt->bind_param('si', $name, $id);
		if($success){
			$success = $stmt->execute();
		}
		return $success;
	}

	public function all_match_types(){
		$query = 'SELECT * FROM matches_match_type order by name';
		$data = $this->DB_CONN->query($query);
		$result = [];
		while($r = $data->fetch_array(MYSQLI_ASSOC)){
			$result[$r['id']] = $r;
		}
		return $result;
	}

	public function add_match_type($name){
		$query = 'INSERT INTO matches_match_type (name) VALUES (?)';
		$stmt = $this->DB_CONN->prepare($query);
		$success = $stmt->bind_param('s', $name);
		if($success){
			$success = $stmt->execute();
		}
		return $success;
	}

	public function update_match_type($id, $name){
		$query = 'UPDATE matches_match_type SET name=? WHERE id=?';
		$stmt = $this->DB_CONN->prepare($query);
		$success = $stmt->bind_param('si', $name, $id);
		if($success){
			$success = $stmt->execute();
		}
		return $success;
	}

	public function all_events(){
		$data = $this->DB_CONN->query('SELECT * FROM matches_event ORDER BY date_time DESC');
		$result = [];
		while($r = $data->fetch_array(MYSQLI_ASSOC)){
			$result[$r['id']] = $r;
		}
		return $result;
	}

	public function upcoming_events(){
		$data = $this->DB_CONN->query('SELECT * FROM matches_event WHERE date_time >= (NOW() - INTERVAL 1 DAY) ORDER BY date_time ASC');
		$result = [];
		while($r = $data->fetch_array(MYSQLI_ASSOC)){
			$result[$r['id']] = $r;
		}
		return $result;
	}

	public function add_event($datetime, $name, $ppv){
		$query = 'INSERT INTO matches_event (date_time, name, ppv) VALUES (?, ?, ?)';
		$stmt = $this->DB_CONN->prepare($query);
		$success = $stmt->bind_param('ssi', $datetime, $name, $ppv);
		if($success){
			$success = $stmt->execute();
		}
		return $success?mysqli_insert_id($this->DB_CONN):false;
	}

	public function update_event($id, $datetime, $name, $ppv){
		$query = 'UPDATE matches_event SET date_time=?, name=?, ppv=? WHERE id=?';
		$stmt = $this->DB_CONN->prepare($query);
		$success = $stmt->bind_param('ssii', $datetime, $name, $ppv, $id);
		if($success){
			$success = $stmt->execute();
		}
		return $success;
	}

	public function add_match($event_id, $title_id, $match_type_id, $match_note, $team_won, $winner_note, $bet_open, $user_id){
		$query = '
			INSERT INTO matches_match (event_id, title_id, match_type_id, match_note, team_won, winner_note, bet_open, last_updated_by, last_updated)
			VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())';
		$stmt = $this->DB_CONN->prepare($query);
		$success = $stmt->bind_param('iiisisii', $event_id, $title_id, $match_type_id, $match_note, $team_won, $winner_note, $bet_open, $user_id);
		if($success){
			$success = $stmt->execute();
		}
		return $success?mysqli_insert_id($this->DB_CONN):false;
	}

	public function update_match($id, $event_id, $title_id, $match_type_id, $match_note, $team_won, $winner_note, $bet_open, $user_id){
		$query = '
			UPDATE matches_match
			SET event_id=?, title_id=?, match_type_id=?, match_note=?, team_won=?, winner_note=?, bet_open=?, last_updated_by=?, last_updated=NOW()
			WHERE id=?';
		$stmt = $this->DB_CONN->prepare($query);
		$success = $stmt->bind_param('iiisisiii', $event_id, $title_id, $match_type_id, $match_note, $team_won, $winner_note, $bet_open, $user_id, $id);
		if($success){
			$success = $stmt->execute();
		}
		return $success;
	}

	public function recalculate_match($id){
		$query = '
			UPDATE matches_match
			SET last_updated=NOW()
			WHERE id=?';
		$stmt = $this->DB_CONN->prepare($query);
		$success = $stmt->bind_param('i', $id);
		if($success){
			$success = $stmt->execute();
		}
		return $success;
	}

	public function add_match_contestant($match_id, $superstar_id, $team, $bet_multiplier){
		$query = 'INSERT INTO matches_contestant (match_id, superstar_id, team, bet_multiplier) VALUES (?, ?, ?, ?)';
		$stmt = $this->DB_CONN->prepare($query);
		$success = $stmt->bind_param('iiii', $match_id, $superstar_id, $team, $bet_multiplier);
		if($success){
			$success = $stmt->execute();
		}
		return $success;
	}

	public function remove_all_match_contestants($match_id){
		$query = 'DELETE FROM matches_contestant WHERE match_id=?';
		$stmt = $this->DB_CONN->prepare($query);
		$success = $stmt->bind_param('i', $match_id);
		if($success){
			$success = $stmt->execute();
		}
		return $success;
	}

	public function update_favorite_superstar($user_id, $superstar_id){
		$query = 'CALL usp_matches_upd_favorite_superstar(?, ?)';
		$stmt = $this->DB_CONN->prepare($query);
		$success = $stmt->bind_param('ii', $user_id, $superstar_id);
		if($success){
			$success = $stmt->execute();
		}
		return $success;
	}

	public function add_user_bet($user_id, $match_id, $team, $points){
		$query = '
			INSERT INTO matches_bet (user_id, match_id, team, points, dt_placed)
			VALUES (?, ?, ?, ?, NOW())';
		$stmt = $this->DB_CONN->prepare($query);
		$success = $stmt->bind_param('iiii', $user_id, $match_id, $team, $points);
		if($success){
			$success = $stmt->execute();
		}
		return $success;
	}

	public function all_royalrumble_entries(){
		$data = $this->DB_CONN->query('SELECT * FROM uv_royalrumble');
		$result = [];
		while($r = $data->fetch_array(MYSQLI_ASSOC)){
			$result[$r['id']][] = $r;
		}
		return $result;
	}

	public function royalrumble($id){
		$query = 'SELECT * FROM royalrumble WHERE id=?';
		$stmt = $this->DB_CONN->prepare($query);
		$stmt->bind_param('i', $id);
		$stmt->execute();
		return $stmt->get_result()->fetch_array(MYSQLI_ASSOC);
	}

	public function royalrumble_entry($royalrumble_id, $username){
		$query = 'SELECT * FROM uv_royalrumble WHERE id=? AND username=?';
		$stmt = $this->DB_CONN->prepare($query);
		$stmt->bind_param('is', $royalrumble_id, $username);
		$stmt->execute();
		return $stmt->get_result()->fetch_array(MYSQLI_ASSOC);
	}

	public function add_royalrumble_entry($royalrumble_id, $user_id, $username, $comment){
		$rr_data = $this->royalrumble($royalrumble_id);
		if(!$rr_data || $rr_data['winner']){
			return false;
		}
		$max_entries = $rr_data['entries'];
		$set_entries = [];

		if($user_id!=0){
			$query = 'SELECT DISTINCT number FROM royalrumble_entry WHERE royalrumble_id=? AND user_id>0';
			$stmt = $this->DB_CONN->prepare($query);
			$stmt->bind_param('i', $royalrumble_id);
			$stmt->execute();
			$set_entries_data = $stmt->get_result();
			while($r = $set_entries_data->fetch_array(MYSQLI_ASSOC)){
				$set_entries[] = $r['number'];
			}
		}

		$available_numbers = array_diff(range(1, $max_entries), $set_entries);
		if(empty($available_numbers)){
			$available_numbers = range(1, $max_entries);
		}

		$rand_entry = $available_numbers[array_rand($available_numbers, 1)];
		if(!$rand_entry){
			return false;
		}

		$query = '
			INSERT INTO royalrumble_entry (royalrumble_id, username, user_id, number, comment, dt_entered)
			VALUES (?, ?, ?, ?, ?, NOW())';
		$stmt = $this->DB_CONN->prepare($query);
		$success = $stmt->bind_param('isiis', $royalrumble_id, $username, $user_id, $rand_entry, $comment);
		if($success){
			$success = $stmt->execute();
			$success = true;
		}
		if($success){
			$success = $rand_entry;
		}
		return $success;
	}

	// POLL

	public function all_polls(){
		$data = $this->DB_CONN->query('SELECT * FROM uv_poll_info ORDER BY created_dt DESC');
		$result = [];
		while($r = $data->fetch_array(MYSQLI_ASSOC)){
			$result[$r['id']] = $r;
		}
		return $result;
	}

	public function polls_most_active(){
		$data = $this->DB_CONN->query('SELECT * FROM uv_poll_active ORDER BY votes DESC');
		$result = [];
		while($r = $data->fetch_array(MYSQLI_ASSOC)){
			$result[$r['id']] = $r;
		}
		return $result;
	}

	public function polls_ending_soon(){
		$data = $this->DB_CONN->query('SELECT * FROM uv_poll_active ORDER BY expire_dt ASC');
		$result = [];
		while($r = $data->fetch_array(MYSQLI_ASSOC)){
			$result[$r['id']] = $r;
		}
		return $result;
	}

	public function polls_most_recent(){
		$data = $this->DB_CONN->query('SELECT * FROM uv_poll_active ORDER BY created_dt DESC');
		$result = [];
		while($r = $data->fetch_array(MYSQLI_ASSOC)){
			$result[$r['id']] = $r;
		}
		return $result;
	}

	public function polls_user_most_active($user_id){
		$query = 'SELECT * FROM uv_poll_active WHERE user_id=? ORDER BY votes DESC';
		$stmt = $this->DB_CONN->prepare($query);
		$stmt->bind_param('i', $user_id);
		$stmt->execute();
		$data = $stmt->get_result();
		$result = [];
		while($r = $data->fetch_array(MYSQLI_ASSOC)){
			$result[] = $r;
		}
		return $result;
	}

	public function polls_user_most_recent($user_id){
		$query = 'SELECT * FROM uv_poll_active WHERE user_id=? ORDER BY created_dt DESC';
		$stmt = $this->DB_CONN->prepare($query);
		$stmt->bind_param('i', $user_id);
		$stmt->execute();
		$data = $stmt->get_result();
		$result = [];
		while($r = $data->fetch_array(MYSQLI_ASSOC)){
			$result[] = $r;
		}
		return $result;
	}

	public function polls_user_expired($user_id){
		$query = 'SELECT * FROM uv_poll_expired WHERE user_id=? ORDER BY expire_dt DESC';
		$stmt = $this->DB_CONN->prepare($query);
		$stmt->bind_param('i', $user_id);
		$stmt->execute();
		$data = $stmt->get_result();
		$result = [];
		while($r = $data->fetch_array(MYSQLI_ASSOC)){
			$result[] = $r;
		}
		return $result;
	}

	public function polls_user_votes($user_id){
		$query = 'SELECT BIN_TO_UUID(topic_id) AS topic_id, BIN_TO_UUID(item_id) AS item_id, created_dt FROM poll_vote WHERE user_id=?';
		$stmt = $this->DB_CONN->prepare($query);
		$stmt->bind_param('i', $user_id);
		$stmt->execute();
		$data = $stmt->get_result();
		$result = [];
		while($r = $data->fetch_array(MYSQLI_ASSOC)){
			$result[] = $r;
		}
		return $result;
	}

	public function poll_info($topic_id){
		$query = 'SELECT * FROM uv_poll_info WHERE id=?';
		$stmt = $this->DB_CONN->prepare($query);
		$stmt->bind_param('s', $topic_id);
		$stmt->execute();
		return $stmt->get_result()->fetch_array(MYSQLI_ASSOC);
	}

	public function poll_topic($topic_id){
		$query = 'SELECT * FROM poll_topic WHERE id=UUID_TO_BIN(?)';
		$stmt = $this->DB_CONN->prepare($query);
		$stmt->bind_param('s', $topic_id);
		$stmt->execute();
		return $stmt->get_result()->fetch_array(MYSQLI_ASSOC);
	}

	public function poll_items($topic_id){
		$query = 'SELECT BIN_TO_UUID(id) AS id, BIN_TO_UUID(topic_id) AS topic_id, content, votes FROM poll_item WHERE topic_id=UUID_TO_BIN(?)';
		$stmt = $this->DB_CONN->prepare($query);
		$stmt->bind_param('s', $topic_id);
		$stmt->execute();
		$data = $stmt->get_result();
		$result = [];
		while($r = $data->fetch_array(MYSQLI_ASSOC)){
			$result[] = $r;
		}
		return $result;
	}

	public function add_poll_topic($content, $allow_multi, $hide_votes, $user_id, $expire_dt){
		$topic_id = $this->get_uuid();
		$query = '
			INSERT INTO poll_topic (id, content, allow_multi, hide_votes, user_id, expire_dt, created_dt)
			VALUES (UUID_TO_BIN(?), ?, ?, ?, ?, ?, NOW())';
		$stmt = $this->DB_CONN->prepare($query);
		$success = $stmt->bind_param('ssiiis', $topic_id, $content, $allow_multi, $hide_votes, $user_id, $expire_dt);
		if($success){
			$success = $stmt->execute();
		}
		return $success?$topic_id:false;
	}

	public function add_poll_item($topic_id, $content){
		$item_id = $this->get_uuid();
		$query = 'INSERT INTO poll_item (id, topic_id, content) VALUES (UUID_TO_BIN(?), UUID_TO_BIN(?), ?)';
		$stmt = $this->DB_CONN->prepare($query);
		$success = $stmt->bind_param('sss', $item_id, $topic_id, $content);
		if($success){
			$success = $stmt->execute();
		}
		return $success?$item_id:false;
	}

	public function add_poll_vote($topic_id, $item_id, $user_id){
		$vote_id = $this->get_uuid();
		$query = '
			INSERT INTO poll_vote (id, topic_id, item_id, user_id, created_dt)
			VALUES (UUID_TO_BIN(?), UUID_TO_BIN(?), UUID_TO_BIN(?), ?, NOW())';
		$stmt = $this->DB_CONN->prepare($query);
		$success = $stmt->bind_param('sssi', $vote_id, $topic_id, $item_id, $user_id);
		if($success){
			$success = $stmt->execute();
		}
		return $success?$vote_id:false;
	}

}
?>
