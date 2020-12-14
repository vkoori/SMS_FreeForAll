<?php 
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' ) {
	header('Content-type: application/json');
	$result = array();
	
	include(dirname(__FILE__).'/class.smsQueries.php');
	$smsQueriesClass = new smsQueries();
	$setting = $smsQueriesClass->setting();

    if (isset($_POST["mobile"])) {
    	$mobile = '09'.$_POST["mobile"];
		$pattern = "/^09[0-9]{9}$/m";
		$regex = preg_match($pattern, $mobile);
		if (!$regex) {
			$result['error'] = 'شماره موبایل نا معتبر است.';
			echo json_encode($result);
			exit();
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

		$user = $smsQueriesClass->setUser($data);

		if (!isset($user->id)) {
			// send sms
			$data = array(
				'username' 	=> $setting->user_api,
				'password' 	=> $setting->pass_api,
				'to' 		=> $user['mobile'],
				'text' 		=> $user['private_key'],
				'from' 		=> '30004388511788',
				'api' 		=> '5'
			);

			// $sms = $smsQueriesClass->sendsms($data);
			// $result['sms'] = $sms;
		}

		$key_generate_date = (gettype($user) == "array") ? $user['key_generate_date'] : $user->key_generate_date ;
		$remind = 120 - (time()-strtotime($key_generate_date));
		$public_key = (gettype($user) == "array") ? $user['public_key'] : $user->public_key ;
		$result["title"] = 'رمز یکبار مصرف پیامک شده را وارد نمایید:';
		$result["inner-form"] = '
				<div class="free_for_all_row" dir="ltr">
					<input type="hidden" name="public" value="'.$public_key.'">
					<input class="d-ib free_for_all_input free_for_all_input_default" type="number" name="private" required="required">
				</div>
				<div class="free_for_all_row">
					<span id="count-down" data-remind="'.$remind.'"></span>
				</div>
				<div class="free_for_all_row">
					<button type="submit" class="free_for_all_btn free_for_all_color_graybtn">بعدی</button>
				</div>';
		$result["progress-bar"] = '<div id="progress-load" class="progress-bar-striped free_for_all_color_default" style="width: 25%;">25%</div>';

		echo json_encode($result);
    } elseif (isset($_POST["private"])) {
		$private = $_POST["private"];
		$public = $_POST["public"];

		$user = $smsQueriesClass->getUser('public_key', $public);
		
		if ($user->private_key != $private) {
			$result['error'] = 'احراز هویت صورت نپذیرفت.';
			echo json_encode($result);
			exit();
		}

		$expire = $smsQueriesClass->expireUser($user->mobile);
		$profile = $smsQueriesClass->getProfile($user->id);

		if (sizeof($profile) == 0) {
			$result["title"] = 'اطلاعات خود را وارد کنید:';
			$result["inner-form"] = '
				<div class="free_for_all_row">
					<label for="fname">نام</label>
					<input class="d-ib free_for_all_input free_for_all_input_default" type="text" name="fname" id="fname" required="required">
					<input type="hidden" name="userid" value="'.$user->id.'">
					<input type="hidden" name="public" value="'.$user->public_key.'">
				</div>
				<div class="free_for_all_row">
					<label for="lname">نام خانوادگی</label>
					<input class="d-ib free_for_all_input free_for_all_input_default" type="text" name="lname" id="lname" required="required">
				</div>
				<div class="free_for_all_row">
					<label for="sex">جنسیت</label>
					<select class="d-ib free_for_all_input free_for_all_input_default" name="sex" id="sex">
						<option value="1">مرد</option>
						<option value="0">زن</option>
					</select>
				</div>
				<div class="free_for_all_row">
					<button type="submit" class="free_for_all_btn free_for_all_color_graybtn">بعدی</button>
				</div>';
			$result["progress-bar"] = '<div id="progress-load" class="progress-bar-striped free_for_all_color_default" style="width: 50%;">50%</div>';
		} else {
			$subjects = $smsQueriesClass->get_subjects();

			$result["title"] = 'متن پیام را انتخاب کنید:';
			$result["inner-form"] = '
				<div class="free_for_all_row">
					<input type="hidden" name="public" value="'.$user->public_key.'">
					<label for="subject">موضوع</label>
					<select class="d-ib free_for_all_input free_for_all_input_default" name="subject" id="subject" onchange="getTexts(this.value);" required="required">
						<option value="">انتخاب کنید</option>';
						foreach ($subjects as $s) {
							$result["inner-form"] .= '<option value="'.$s->id.'">'.$s->subject.'</option>';
						}
					$result["inner-form"] .= '</select>
				</div>
				<div class="free_for_all_row">
					<label for="text">متن پیامک</label>
					<select class="d-ib free_for_all_input free_for_all_input_default" name="text" id="text" onchange="setVars(this.value);" required="required">
					</select>
				</div>
				<div class="free_for_all_row">
					<button type="submit" class="free_for_all_btn free_for_all_color_graybtn">بعدی</button>
				</div>';
			$result["progress-bar"] = '<div id="progress-load" class="progress-bar-striped free_for_all_color_default" style="width: 75%;">75%</div>';
		}
		
		echo json_encode($result);
	} elseif (isset($_POST["fname"])) {
		$user = $smsQueriesClass->getUser('public_key', $_POST["public"]);
		if ($user->key_generate_date != "2001-01-01 12:00:00") {
			$result['error'] = 'احراز هویت صورت نپذیرفت.';
			echo json_encode($result);
			exit();
		}

		$data = array(
			'first_name' 	=> $_POST['fname'],
			'last_name' 	=> $_POST['lname'],
			'sex' 			=> $_POST['sex'],
			'userid' 		=> $_POST['userid']
		);
		$profile = $smsQueriesClass->setProfile($data);

		$subjects = $smsQueriesClass->get_subjects();

		$result["title"] = 'متن پیام را انتخاب کنید:';
		$result["inner-form"] = '
			<div class="free_for_all_row">
				<input type="hidden" name="public" value="'.$_POST["public"].'">
				<label for="subject">موضوع</label>
				<select class="d-ib free_for_all_input free_for_all_input_default" name="subject" id="subject" onchange="getTexts(this.value);" required="required">
					<option value="">انتخاب کنید</option>';
					foreach ($subjects as $s) {
						$result["inner-form"] .= '<option value="'.$s->id.'">'.$s->subject.'</option>';
					}
				$result["inner-form"] .= '</select>
			</div>
			<div class="free_for_all_row">
				<label for="text">متن پیامک</label>
				<select class="d-ib free_for_all_input free_for_all_input_default" name="text" id="text" onchange="setVars(this.value);" required="required">
				</select>
			</div>
			<div class="free_for_all_row">
				<button type="submit" class="free_for_all_btn free_for_all_color_graybtn">بعدی</button>
			</div>';
		$result["progress-bar"] = '<div id="progress-load" class="progress-bar-striped free_for_all_color_default" style="width: 75%;">75%</div>';

		echo json_encode($result);
	} elseif(isset($_POST["text"])) {
		$id = $_POST["text"];
		$text = $smsQueriesClass->texts_with_id($id);
		$result["title"] = 'متن پیام را تکمیل کن:';
		$result["inner-form"] = '
			<div class="free_for_all_row">
				<input type="hidden" name="public" value="'.$_POST["public"].'">
				<label for="to">شماره موبایل گیرنده:</label>
				<input class="d-ib free_for_all_input free_for_all_input_default" type="text" name="to" id="to" required="required">
			</div>
			<div class="free_for_all_row">
				<input type="hidden" name="quoteid" value="'.$id.'">
				'.str_replace("%s", '-------', $text[0]->message).'
			</div>
			';
			$var_count = preg_match_all('/%s/i', $text[0]->message);
			for ($i=0; $i < $var_count; $i++) { 
				$result["inner-form"] .= '
				<div class="free_for_all_row">
					<input class="d-ib free_for_all_input free_for_all_input_default" type="text" name="variables[]" required="required">
				</div>';
			}
			$result["inner-form"] .= '
			<div class="free_for_all_row">
				<button type="submit" class="free_for_all_btn free_for_all_color_graybtn">ارسال</button>
			</div>';
		$result["progress-bar"] = '<div id="progress-load" class="progress-bar-striped free_for_all_color_default" style="width: 100%;">99%</div>';


		echo json_encode($result);
		// if (publicKey == publicKey AND date == 2001) then send message
	} elseif(isset($_POST["to"])) {
		$user = $smsQueriesClass->getUser('public_key', $_POST["public"]);
		if ($user->key_generate_date != "2001-01-01 12:00:00") {
			$result['error'] = 'احراز هویت صورت نپذیرفت.';
			echo json_encode($result);
			exit();
		}

		$userid = $user->id;
		if (is_null($setting->freeSmsTime)) {
			$smsCount = $smsQueriesClass->count_of_use_sms($userid);
		} else {
			date_default_timezone_set('Asia/Tehran');
			$date = date('Y-m-d H:i:s', strtotime('-'.intval($setting->freeSmsTime).' hours'));
			$smsCount = $smsQueriesClass->count_of_use_sms2($userid, $date);
		}

		if ($smsCount->count >= $setting->freeSmsCount) {
			$result['error'] = 'تعداد پیامک رایگان شما به پایان رسیده است.';
			echo json_encode($result);
			exit();
		}

		$to = $_POST["to"];
		$vars = $_POST["variables"];
		$quoteid = $_POST["quoteid"];
		$text = $smsQueriesClass->texts_with_id($quoteid);
		$quote = vsprintf($text[0]->message, $vars);

		$data = array(
			"userid" => $userid,
			"quote" => $quote,
			"to" => $to
		);
		$smsQueriesClass->insert_sms($data);
		
		$data = array(
			'username' 	=> $setting->user_api,
			'password' 	=> $setting->pass_api,
			'to' 		=> $to,
			'text' 		=> $quote,
			'from' 		=> $setting->phone_number,
			'api' 		=> intval($setting->api_number)
		);
		// $smsQueriesClass->sendsms($data);
		
		$result['refresh'] = true;
		echo json_encode($result);
	}
} else {
	header('HTTP/1.0 403 Forbidden');
	echo 'You are forbidden! pls enable javascript and check later!';
}
exit();