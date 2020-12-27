<?php 
function check_sms_count($smsQueriesClass, $setting, $public) {
	$result = array();
	$user = $smsQueriesClass->getUser('public_key', $public);
	if ($user->key_generate_date != "2001-01-01 12:00:00") {
		$result['error'] = 'احراز هویت صورت نپذیرفت.';
		echo json_encode($result);
		exit();
	}

	$userid = $user->id;
	if (is_null($setting->freeSmsTime)) {
		$smsCount = $smsQueriesClass->count_of_use_sms($userid);
	} else {
		$reset_time = explode("|", $setting->freeSmsTime);
		$reset_time = $reset_time[0]*$reset_time[1];
		date_default_timezone_set('Asia/Tehran');
		$date = date('Y-m-d H:i:s', strtotime('-'.$reset_time.' hours'));
		$smsCount = $smsQueriesClass->count_of_use_sms2($userid, $date);
	}

	if ($smsCount->count >= $setting->freeSmsCount) {
		$result['error'] = 'تعداد پیامک رایگان شما به پایان رسیده است.';
		echo json_encode($result);
		exit();
	}
}

if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' ) {
	header('Content-type: application/json');
	$result = array();
	
	include(dirname(__FILE__).'/class.smsQueries.php');
	$smsQueriesClass = new smsQueries();
	$setting = $smsQueriesClass->setting();

    if (isset($_POST["mobile"])) {
    	$mobile = $_POST["mobile"];
		$pattern = "/^09[0-9]{9}$/m";
		$regex = preg_match($pattern, $mobile);
		if (!$regex) {
			$result['error'] = 'شماره موبایل نا معتبر است.';
			echo json_encode($result);
			exit();
		}

		if (is_null($setting->freeSmsTime)) {
			$smsCount = $smsQueriesClass->count_of_use_sms_mobile($mobile);
		} else {
			$reset_time = explode("|", $setting->freeSmsTime);
			$reset_time = $reset_time[0]*$reset_time[1];
			date_default_timezone_set('Asia/Tehran');
			$date = date('Y-m-d H:i:s', strtotime('-'.$reset_time.' hours'));
			$smsCount = $smsQueriesClass->count_of_use_sms_mobile2($mobile, $date);
		}
		if ($smsCount->count >= $setting->freeSmsCount) {
			$result['error'] = 'تعداد پیامک رایگان شما به پایان رسیده است.';
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
				'text' 		=> sprintf(unserialize($setting->other_texts)["gift_text"], $user['private_key']),
				'from' 		=> $setting->phone_number,
				'api' 		=> intval($setting->api_number)
			);

			$sms = $smsQueriesClass->sendsms($data);
			// $result['sms'] = $sms;
		}

		$key_generate_date = (gettype($user) == "array") ? $user['key_generate_date'] : $user->key_generate_date ;
		$remind = 120 - (time()-strtotime($key_generate_date));
		$public_key = (gettype($user) == "array") ? $user['public_key'] : $user->public_key ;
		$result["title"] = 'رمز یکبار مصرف پیامک شده را وارد نمایید:';
		$result["inner-form"] = '
				<div class="free_for_all_row" dir="ltr">
					<input type="hidden" name="public" value="'.$public_key.'">
					<input id="free_sms_psw" class="d-ib free_for_all_input free_for_all_input_default" type="number" name="private" required="required">
				</div>
				<div class="free_for_all_row" align="center">
					<span id="count-down" data-remind="'.$remind.'"></span>
				</div>
				<div class="free_for_all_row">
					<button type="submit" class="swipe-overlay-out"><span>بعدی</span></button>
				</div>';
		$result["progress-bar"] = '33%';

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
		check_sms_count($smsQueriesClass, $setting, $public);

		$result["inner-form"] = '<input type="hidden" name="public" value="'.$user->public_key.'">';
		
		$profile_setting = unserialize($setting->other_texts)["profile"];
		if (sizeof($profile_setting) > 0) {
			$result["inner-form"] .= '<input type="hidden" name="userid" value="'.$user->id.'">';

			$profile = $smsQueriesClass->getProfile($user->id);
			if (in_array('fname', $profile_setting) AND (is_null($profile[0]->first_name) OR sizeof($profile)==0)) {
				$result["inner-form"] .= '
					<div class="free_for_all_row">
						<label for="fname">نام *</label>
						<input class="d-ib free_for_all_input free_for_all_input_default" type="text" name="fname" id="fname" required="required">
					</div>';
			}
			if (in_array('lname', $profile_setting) AND (is_null($profile[0]->last_name) OR sizeof($profile)==0)) {
				$result["inner-form"] .= '
					<div class="free_for_all_row">
						<label for="lname">نام خانوادگی *</label>
						<input class="d-ib free_for_all_input free_for_all_input_default" type="text" name="lname" id="lname" required="required">
					</div>';
			}
			if (in_array('sex', $profile_setting) AND (is_null($profile[0]->sex) OR sizeof($profile)==0)) {
				$result["inner-form"] .= '
					<div class="free_for_all_row">
						<label for="sex">جنسیت *</label>
						<select class="d-ib free_for_all_input free_for_all_input_default" name="sex" id="sex" required="required">
							<option value="">انتخاب کنید</option>
							<option value="1">مرد</option>
							<option value="0">زن</option>
						</select>
					</div>';
			}
		}

		$subjects = $smsQueriesClass->get_subjects();
		$result["inner-form"] .= '
			<div class="free_for_all_row">
				<label for="subject">موضوع *</label>
				<select class="d-ib free_for_all_input free_for_all_input_default" name="subject" id="subject" onchange="getTexts(this.value);" required="required">
					<option value="">انتخاب کنید</option>';
					foreach ($subjects as $s) {
						$result["inner-form"] .= '<option value="'.$s->id.'">'.$s->subject.'</option>';
					}
				$result["inner-form"] .= '</select>
			</div>
			<div class="free_for_all_row">
				<label for="text">متن پیامک *</label>
				<select class="d-ib free_for_all_input free_for_all_input_default" name="text" id="text" required="required">
				</select>
			</div>
			<div class="free_for_all_row">
				<button type="submit" class="swipe-overlay-out"><span>بعدی</span></button>
			</div>';

		$result["title"] = 'فرم زیر را تکمیل کنید:';
		$result["progress-bar"] = '66%';
		$result["script"] = '
			  var el1 = document.querySelector("#subject");
			  new Choices(el1);
			  var el2 = document.querySelector("#text");
			  choices2 = new Choices(el2);
		';
		
		echo json_encode($result);
	} elseif (isset($_POST["text"])) {
		$public = $_POST["public"];
		check_sms_count($smsQueriesClass, $setting, $public);

		if (isset($_POST["fname"]) OR isset($_POST["lname"]) OR isset($_POST["sex"])) {
			$profile = $smsQueriesClass->getProfile($_POST['userid']);
			
			$data = array(
				'userid' => $_POST['userid']
			);
			if (isset($_POST["fname"])) {
				$data['first_name'] = $_POST['fname'];
			}
			if (isset($_POST["lname"])) {
				$data['last_name'] = $_POST['lname'];
			}
			if (isset($_POST["sex"])) {
				$data['sex'] = $_POST['sex'];
			}
			$profile = $smsQueriesClass->getProfile($_POST['userid']);
			if (sizeof($profile) == 0)
				$profile = $smsQueriesClass->setProfile($data);
			else
				$profile = $smsQueriesClass->updateProfile($data, $_POST['userid']);
		}

		$id = $_POST["text"];
		$text = $smsQueriesClass->texts_with_id($id);
		$result["title"] = 'متن پیام را تکمیل کن:';
		$result["inner-form"] = '
			<div class="flex">
				<div class="free_for_all_col3">
					<input type="hidden" name="public" value="'.$_POST["public"].'">
					<label for="to">شماره موبایل گیرنده:</label>
					<input class="d-ib free_for_all_input free_for_all_input_default" type="number" name="to" id="to" required="required">
				</div>
				<div class="free_for_all_col3">
					<label for="to-name">نام گیرنده:</label>
					<input class="d-ib free_for_all_input free_for_all_input_default" type="text" name="to-name" id="to-name" required="required">
				</div>
				<div class="free_for_all_col3">
					<label for="to-family">نام خانوادگی گیرنده:</label>
					<input class="d-ib free_for_all_input free_for_all_input_default" type="text" name="to-family" id="to-family" required="required">
				</div>
			</div>
			<div class="free_for_all_row">
				<input type="hidden" name="quoteid" value="'.$id.'">
				'.str_replace("%s", '-------', $text[0]->message).'<br><br>
				'.unserialize($setting->other_texts)["signature"].'
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
				<button type="submit" class="swipe-overlay-out"><span>ارسال</span></button>
			</div>';
		$result["progress-bar"] = '99%';

		$result["back-btn"] = '<form id="free-sms-back" action="'.admin_url('admin-ajax.php').'" method="POST" accept-charset="utf-8" onsubmit="return nextStep(this);">
			<input type="hidden" name="back" value="back">
			<input type="hidden" name="public" value="'.$_POST["public"].'">
			<button type="submit" class="swipe-overlay-out"><span>تغییر متن</span></button>
		</form>';


		echo json_encode($result);
		
	} elseif(isset($_POST["back"])) {
		$public = $_POST["public"];
		$user = $smsQueriesClass->getUser('public_key', $public);

		check_sms_count($smsQueriesClass, $setting, $public);

		$result["inner-form"] = '<input type="hidden" name="public" value="'.$user->public_key.'">';
		
		$profile_setting = unserialize($setting->other_texts)["profile"];
		if (sizeof($profile_setting) > 0) {
			$result["inner-form"] .= '<input type="hidden" name="userid" value="'.$user->id.'">';

			$profile = $smsQueriesClass->getProfile($user->id);
			if (in_array('fname', $profile_setting) AND (is_null($profile[0]->first_name) OR sizeof($profile)==0)) {
				$result["inner-form"] .= '
					<div class="free_for_all_row">
						<label for="fname">نام</label>
						<input class="d-ib free_for_all_input free_for_all_input_default" type="text" name="fname" id="fname" required="required">
					</div>';
			}
			if (in_array('lname', $profile_setting) AND (is_null($profile[0]->last_name) OR sizeof($profile)==0)) {
				$result["inner-form"] .= '
					<div class="free_for_all_row">
						<label for="lname">نام خانوادگی</label>
						<input class="d-ib free_for_all_input free_for_all_input_default" type="text" name="lname" id="lname" required="required">
					</div>';
			}
			if (in_array('sex', $profile_setting) AND (is_null($profile[0]->sex) OR sizeof($profile)==0)) {
				$result["inner-form"] .= '
					<div class="free_for_all_row">
						<label for="sex">جنسیت</label>
						<select class="d-ib free_for_all_input free_for_all_input_default" name="sex" id="sex">
							<option value="1">مرد</option>
							<option value="0">زن</option>
						</select>
					</div>';
			}
		}

		$subjects = $smsQueriesClass->get_subjects();
		$result["inner-form"] .= '
			<div class="free_for_all_row">
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
				<select class="d-ib free_for_all_input free_for_all_input_default" name="text" id="text" required="required">
				</select>
			</div>
			<div class="free_for_all_row">
				<button type="submit" class="swipe-overlay-out"><span>بعدی</span></button>
			</div>';

		$result["title"] = 'فرم زیر را تکمیل کنید:';
		$result["progress-bar"] = '66%';
		$result["script"] = '
			  var el1 = document.querySelector("#subject");
			  new Choices(el1);
			  var el2 = document.querySelector("#text");
			  choices2 = new Choices(el2);
		';
		
		echo json_encode($result);
	} elseif(isset($_POST["to"])) {
		$public = $_POST["public"];
		check_sms_count($smsQueriesClass, $setting, $public);

		$user = $smsQueriesClass->getUser('public_key', $public);
		$to = $_POST["to"];
		$to_name = $_POST["to-name"];
		$to_family = $_POST["to-family"];
		$quoteid = $_POST["quoteid"];
		$text = $smsQueriesClass->texts_with_id($quoteid);
		if (isset($_POST["variables"])) {
			$vars = $_POST["variables"];
			$quote = vsprintf($text[0]->message, $vars);
		} else {
			$quote = $text[0]->message;
		}
		$quote .= "\n\n".unserialize($setting->other_texts)["signature"];

		$data = array(
			"userid" => $user->id,
			"quote" => $quote,
			"to" => $to,
			"to_name" => $to_name,
			"to_family" => $to_family
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
		$smsQueriesClass->sendsms($data);
		
		$result['refresh'] = true;
		echo json_encode($result);
	}
} else {
	header('HTTP/1.0 403 Forbidden');
	echo 'You are forbidden! pls enable javascript and check later!';
}
exit();
