<?php 
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' ) {
	header('Content-type: application/json');
	$result = array();
    if (isset($_POST["mobile"])) {
    	$mobile = '09'.$_POST["mobile"];
		$pattern = "/^09[0-9]{9}$/m";
		$regex = preg_match($pattern, $mobile);
		if (!$regex) {
			$result['error'] = 'شماره موبایل نا معتبر است.';
		}


		date_default_timezone_set('Asia/Tehran');
		$now = date('Y-m-d H:i:s');
		$private = str_pad(time() - strtotime('today midnight'), 5, '0', STR_PAD_LEFT);
		$private = intval($private)*10 + rand(0,9);
		$bytes = random_bytes(20);
		$public_key = bin2hex($bytes).strval(microtime(true)*1000);

		$data = array(
			'mobile' 			=> $mobile,
			'private_key' 		=> $private,
			'public_key' 		=> $public_key,
			'key_generate_date' => $now
		);

		include(dirname(__FILE__).'/class.smsQueries.php');
		$smsQueriesClass = new smsQueries();
		$user = $smsQueriesClass->setUser($data);

		$result['user'] = $user;















		echo json_encode($result);
    }
} else {
	header('HTTP/1.0 403 Forbidden');
	echo 'You are forbidden! pls enable javascript and check later!';
}
exit();